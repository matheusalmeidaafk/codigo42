document.addEventListener('DOMContentLoaded', () => {
    const auth = window.Codigo42Auth;
    if (auth.current()?.tipo !== 'ADM') { location.replace('/pages/admin-login.php'); return; }
    const fields = {
        produtos: { id: 'id_produto', label: 'Produtos', inputs: [
            ['nome', 'Nome', 'text', true], ['descricao', 'Descrição', 'textarea', true],
            ['imagem_url', 'URL da imagem', 'url', true], ['preco', 'Preço (R$)', 'number', true],
            ['is_autoral', 'Autoral', 'checkbox'], ['ativo', 'Ativo', 'checkbox']
        ] },
        categorias: { id: 'id_categoria', label: 'Categorias', inputs: [
            ['nome', 'Nome da categoria', 'text', true], ['id_categoria_pai', 'Categoria principal', 'select']
        ] },
        banners: { id: 'id_banner', label: 'Banners', inputs: [
            ['banner_url', 'URL da imagem', 'url', true], ['link_url', 'Link de destino', 'url'], ['ativo', 'Ativo', 'checkbox']
        ] },
        usuarios: { id: 'id_usuario', label: 'Usuários', inputs: [
            ['nome_completo', 'Nome completo', 'text', true], ['email', 'E-mail', 'email', true],
            ['telefone', 'Telefone', 'text', true], ['cpf', 'CPF', 'text', true],
            ['imagem_url', 'URL da imagem', 'url'], ['ativo', 'Ativo', 'checkbox'],
            ['senha', 'Senha (somente cadastro)', 'password']
        ] }
    };
    const modal = new bootstrap.Modal(document.getElementById('adminModal'));
    const tableHead = document.getElementById('adminTabelaCabecalho');
    const tableBody = document.getElementById('adminTabelaCorpo');
    const form = document.getElementById('adminForm');
    const formFields = document.getElementById('adminFormCampos');
    const notification = document.getElementById('adminAlerta');
    let resource = 'produtos', rows = [], editing = null;
    let categorias = [];
    document.getElementById('adminSair').addEventListener('click', auth.logout);
    function notify(message, success = false) {
        notification.className = `alert alert-${success ? 'success' : 'danger'}`;
        notification.textContent = message;
        const inside = document.getElementById('adminFormAlerta');
        inside.className = notification.className; inside.textContent = message;
    }
    async function refresh() {
        tableBody.replaceChildren();
        try {
            rows = await auth.request(`/admin/${resource}`);
            const config = fields[resource];
            const cols = config.inputs.filter(([name]) => name !== 'senha').slice(0, 5);
            const headings = document.createElement('tr');
            for (const name of ['ID', ...cols.map(([, label]) => label), 'Ações']) {
                const cell = document.createElement('th'); cell.textContent = name; headings.append(cell);
            }
            tableHead.replaceChildren(headings);
            if (!rows.length) {
                const tr = document.createElement('tr'), td = document.createElement('td');
                td.colSpan = cols.length + 2; td.textContent = 'Nenhum registro cadastrado.';
                tr.append(td); tableBody.append(tr); return;
            }
            rows.forEach(row => {
                const tr = document.createElement('tr');
                [row[config.id], ...cols.map(([key]) => row[key])].forEach(value => {
                    const td = document.createElement('td'); td.textContent = value == null ? '—' : String(value);
                    td.className = 'text-truncate'; td.style.maxWidth = '180px'; tr.append(td);
                });
                const actions = document.createElement('td');
                const edit = document.createElement('button'); edit.type = 'button'; edit.className = 'btn btn-sm btn-outline-primary me-2'; edit.textContent = 'Editar';
                edit.addEventListener('click', () => openForm(row));
                const del = document.createElement('button'); del.type = 'button'; del.className = 'btn btn-sm btn-outline-danger'; del.textContent = 'Excluir';
                del.addEventListener('click', () => remove(row));
                actions.append(edit, del); tr.append(actions); tableBody.append(tr);
            });
        } catch (error) { notify(error.message); }
    }
    async function carregarCategorias() {
        categorias = await auth.request('/admin/categorias');
        categorias.sort((a, b) => String(a.nome).localeCompare(String(b.nome), 'pt-BR'));
    }
    function montarCategoriasProduto(row) {
        const selecionadas = new Set(String(row?.categoria_ids || '').split(',').filter(Boolean).map(Number));
        const section = document.createElement('section');
        section.className = 'col-12 border rounded-3 p-3 bg-light';
        section.dataset.adminCategorias = '1';
        section.getSelectedCategories = () => [...selecionadas];
        const heading = document.createElement('div');
        heading.className = 'd-flex justify-content-between align-items-center mb-2';
        const title = document.createElement('strong'); title.textContent = 'Categorias do produto';
        const hint = document.createElement('small'); hint.className = 'text-muted'; hint.textContent = 'Selecione a categoria pai e/ou suas filhas';
        heading.append(title, hint); section.append(heading);
        const search = document.createElement('input');
        search.type = 'search'; search.className = 'form-control mb-2'; search.placeholder = 'Buscar categoria...';
        section.append(search);
        const filterParent = document.createElement('select'); filterParent.className = 'form-select mb-2';
        filterParent.setAttribute('aria-label', 'Filtrar por categoria pai');
        const renderFilter = () => {
            const previous = filterParent.value;
            filterParent.replaceChildren(new Option('Todas as categorias pai e filhas', ''));
            categorias.filter(c => !c.id_categoria_pai).forEach(c => filterParent.add(new Option(c.nome, c.id_categoria)));
            filterParent.value = previous;
        };
        renderFilter(); filterParent.addEventListener('change', () => render()); section.append(filterParent);
        const list = document.createElement('div');
        list.className = 'd-flex flex-wrap gap-2 mb-3';
        list.style.maxHeight = '160px'; list.style.overflowY = 'auto';
        function render() {
            list.replaceChildren();
            const term = search.value.trim().toLocaleLowerCase('pt-BR');
            categorias.filter(c => (!filterParent.value || String(c.id_categoria) === filterParent.value || String(c.id_categoria_pai) === filterParent.value) && String(c.nome).toLocaleLowerCase('pt-BR').includes(term)).forEach(c => {
                const id = Number(c.id_categoria);
                const label = document.createElement('label');
                label.className = 'btn btn-sm ' + (selecionadas.has(id) ? 'btn-dark' : 'btn-outline-secondary');
                const parentCategory = categorias.find(p => Number(p.id_categoria) === Number(c.id_categoria_pai));
                const check = document.createElement('input');
                check.type = 'checkbox'; check.className = 'admin-category-checkbox visually-hidden';
                check.value = String(id); check.checked = selecionadas.has(id);
                check.addEventListener('change', () => {
                    if (check.checked) selecionadas.add(id); else selecionadas.delete(id);
                    label.className = 'btn btn-sm ' + (check.checked ? 'btn-dark' : 'btn-outline-secondary');
                });
                label.append(check, document.createTextNode(parentCategory ? `${parentCategory.nome} / ${c.nome}` : `📁 ${c.nome}`)); list.append(label);
            });
            if (!list.children.length) list.textContent = 'Nenhuma categoria encontrada.';
        }
        search.addEventListener('input', render); render(); section.append(list);
        const smallTitle = document.createElement('label');
        smallTitle.className = 'form-label fw-semibold'; smallTitle.textContent = 'Criar categoria principal ou subcategoria agora';
        section.append(smallTitle);
        const quick = document.createElement('div'); quick.className = 'row g-2';
        const nameWrap = document.createElement('div'); nameWrap.className = 'col-md-5';
        const name = document.createElement('input'); name.className = 'form-control'; name.placeholder = 'Nome da categoria'; name.maxLength = 100;
        nameWrap.append(name);
        const parentWrap = document.createElement('div'); parentWrap.className = 'col-md-4';
        const parent = document.createElement('select'); parent.className = 'form-select';
        function renderParents() {
            parent.replaceChildren(new Option('Nova categoria principal (sem pai)', ''));
            categorias.filter(c => !c.id_categoria_pai).forEach(c => parent.add(new Option(c.nome, c.id_categoria)));
        }
        renderParents(); parentWrap.append(parent);
        const btnWrap = document.createElement('div'); btnWrap.className = 'col-md-3';
        const create = document.createElement('button'); create.type = 'button'; create.className = 'btn btn-outline-dark w-100'; create.textContent = '+ Criar';
        btnWrap.append(create); quick.append(nameWrap, parentWrap, btnWrap); section.append(quick);
        const status = document.createElement('small'); status.className = 'd-block mt-2 text-muted'; section.append(status);
        create.addEventListener('click', async () => {
            const nome = name.value.trim();
            if (!nome) { status.textContent = 'Digite o nome da categoria.'; return; }
            create.disabled = true; status.textContent = 'Criando categoria...';
            try {
                const result = await auth.request('/admin/categorias', {
                    method: 'POST', body: JSON.stringify({ nome, id_categoria_pai: parent.value || null })
                });
                await carregarCategorias(); selecionadas.add(Number(result.id));
                name.value = ''; search.value = ''; renderParents(); renderFilter(); render();
                status.textContent = 'Categoria criada e selecionada!';
            } catch (error) { status.textContent = error.message; }
            finally { create.disabled = false; }
        });
        return section;
    }
    function montarVariacoesProduto(row) {
        const section = document.createElement('section');
        section.className = 'col-12 border rounded-3 p-3';
        section.dataset.adminVariacoes = '1';
        const title = document.createElement('h3'); title.className = 'h6'; title.textContent = 'Tamanhos, cores e estoque';
        const help = document.createElement('p'); help.className = 'small text-muted';
        help.textContent = 'Escolha os tamanhos e as cores. Gere combinações e ajuste o estoque de cada opção.';
        section.append(title, help);
        const presets = document.createElement('div'); presets.className = 'd-flex flex-wrap gap-2 mb-2';
        const sizes = document.createElement('input'); sizes.className = 'form-control'; sizes.placeholder = 'Tamanhos separados por vírgula: P, M, G';
        const colors = document.createElement('input'); colors.className = 'form-control'; colors.placeholder = 'Cores separadas por vírgula: Preta, Branca';
        const stored = (row?.variacoes || []).filter(v => Number(v.ativo));
        sizes.value = [...new Set(stored.map(v => v.tamanho).filter(Boolean))].join(', ');
        colors.value = [...new Set(stored.map(v => v.cor).filter(Boolean))].join(', ');
        for (const [label, value] of [['Camisetas P–GG', 'P, M, G, GG'], ['Tamanho único', 'Único']]) {
            const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-sm btn-outline-dark';
            button.textContent = label; button.addEventListener('click', () => { sizes.value = value; generate(); }); presets.append(button);
        }
        section.append(presets);
        const fieldsRow = document.createElement('div'); fieldsRow.className = 'row g-2 mb-2';
        for (const element of [sizes, colors]) {
            const col = document.createElement('div'); col.className = 'col-md-6'; col.append(element); fieldsRow.append(col);
        }
        section.append(fieldsRow);
        const colorPresets = document.createElement('div'); colorPresets.className = 'd-flex flex-wrap gap-2 mb-3';
        for (const color of ['Preta', 'Branca', 'Azul', 'Vermelha']) {
            const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-sm btn-outline-secondary';
            button.textContent = '+ ' + color;
            button.addEventListener('click', () => {
                const values = colors.value.split(',').map(v => v.trim()).filter(Boolean);
                if (!values.some(v => v.toLocaleLowerCase('pt-BR') === color.toLocaleLowerCase('pt-BR'))) values.push(color);
                colors.value = values.join(', '); generate();
            });
            colorPresets.append(button);
        }
        section.append(colorPresets);
        const generateButton = document.createElement('button'); generateButton.type = 'button';
        generateButton.className = 'btn btn-outline-primary btn-sm mb-3'; generateButton.textContent = 'Gerar combinações';
        section.append(generateButton);
        const table = document.createElement('div'); table.className = 'vstack gap-2'; section.append(table);
        let items = stored.map(v => ({ id_variacao: Number(v.id_variacao), tamanho: v.tamanho || '', cor: v.cor || '', estoque: Number(v.estoque) || 0 }));
        if (!items.length) items = [{ tamanho: 'Único', cor: '', estoque: 0 }];
        function readRows() {
            return [...table.querySelectorAll('[data-variacao]')].map(line => ({
                id_variacao: Number(line.dataset.id || 0),
                tamanho: line.querySelector('[data-tamanho]').value.trim(),
                cor: line.querySelector('[data-cor]').value.trim(),
                estoque: Number(line.querySelector('[data-estoque]').value)
            }));
        }
        function render() {
            table.replaceChildren();
            items.forEach(item => {
                const line = document.createElement('div'); line.className = 'row g-2 align-items-end'; line.dataset.variacao = '1';
                line.dataset.id = item.id_variacao || '';
                for (const [key, label, value] of [['tamanho', 'Tamanho', item.tamanho], ['cor', 'Cor', item.cor], ['estoque', 'Estoque', item.estoque]]) {
                    const col = document.createElement('div'); col.className = key === 'estoque' ? 'col-5 col-md-2' : 'col-5 col-md-4';
                    const fieldLabel = document.createElement('label'); fieldLabel.className = 'form-label small mb-0'; fieldLabel.textContent = label;
                    const input = document.createElement('input'); input.className = 'form-control form-control-sm'; input.dataset[key] = '1';
                    input.value = value; if (key === 'estoque') { input.type = 'number'; input.min = '0'; input.step = '1'; }
                    fieldLabel.append(input); col.append(fieldLabel); line.append(col);
                }
                const removeCol = document.createElement('div'); removeCol.className = 'col-2';
                const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'btn btn-sm btn-outline-danger'; remove.textContent = '×';
                remove.setAttribute('aria-label', 'Remover variação');
                remove.addEventListener('click', () => { items = readRows().filter((_, index) => index !== [...table.children].indexOf(line)); render(); });
                removeCol.append(remove); line.append(removeCol); table.append(line);
            });
            if (!items.length) table.textContent = 'Nenhuma variação. Use os campos acima para gerar opções.';
        }
        function split(value) { return [...new Set(value.split(',').map(v => v.trim()).filter(Boolean))]; }
        function generate() {
            const old = readRows();
            const selectedSizes = split(sizes.value);
            const selectedColors = split(colors.value);
            items = (selectedSizes.length ? selectedSizes : ['Único']).flatMap(size =>
                (selectedColors.length ? selectedColors : ['']).map(color => {
                    const previous = old.find(v => v.tamanho === size && v.cor === color);
                    return previous || { tamanho: size, cor: color, estoque: 0 };
                }));
            render();
        }
        generateButton.addEventListener('click', generate);
        section.getVariacoes = readRows; render();
        return section;
    }
    function montarDescontoProduto(row) {
        const wrap = document.createElement('div'); wrap.className = 'col-md-6';
        const label = document.createElement('label'); label.className = 'form-label w-100'; label.textContent = 'Desconto promocional (%)';
        const input = document.createElement('input'); input.type = 'number'; input.name = 'desconto_percentual';
        input.className = 'form-control mt-1'; input.min = '0'; input.max = '100'; input.step = '0.01'; input.value = row?.desconto_percentual || '0';
        const preview = document.createElement('small'); preview.className = 'text-success d-block mt-1';
        function update() {
            const price = Number(form.querySelector('[name="preco"]')?.value || 0);
            const percent = Number(input.value || 0);
            const finalPrice = price * (1 - percent / 100);
            preview.textContent = `Preço na loja: ${finalPrice.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
        }
        input.addEventListener('input', update); form.querySelector('[name="preco"]')?.addEventListener('input', update);
        label.append(input, preview); wrap.append(label); update(); return wrap;
    }
    function montarUploadProduto() {
        const wrap = document.createElement('div'); wrap.className = 'col-12';
        const label = document.createElement('label'); label.className = 'form-label'; label.textContent = 'Ou enviar imagem do computador (JPG, PNG, WebP — até 5 MB)';
        const input = document.createElement('input'); input.type = 'file'; input.accept = 'image/jpeg,image/png,image/webp'; input.className = 'form-control';
        const status = document.createElement('small'); status.className = 'text-muted';
        input.addEventListener('change', async () => {
            const file = input.files?.[0]; if (!file) return;
            if (file.size > 5 * 1024 * 1024) { status.textContent = 'Imagem maior que 5 MB.'; return; }
            status.textContent = 'Enviando imagem...'; input.disabled = true;
            const save = form.querySelector('[type=submit]'); save.disabled = true;
            try {
                const body = new FormData(); body.append('imagem', file);
                const session = auth.current();
                const base = window.CODIGO42_API_URL || `${location.protocol}//${location.hostname}:8080`;
                const res = await fetch(`${base}/admin/upload-imagem`, {
                    method: 'POST', headers: { Authorization: `Bearer ${session.token}` }, body
                });
                const data = await res.json(); if (!res.ok) throw new Error(data.erro || 'Erro ao enviar imagem.');
                const urlInput = form.querySelector('[name="imagem_url"], [name="banner_url"]');
                urlInput.value = data.url; urlInput.dispatchEvent(new Event('input'));
                status.textContent = 'Imagem enviada com sucesso!';
            } catch (error) { status.textContent = error.message; }
            finally { input.disabled = false; save.disabled = false; }
        });
        wrap.append(label, input, status); return wrap;
    }
    async function openForm(row = null) {
        editing = row ? row[fields[resource].id] : null;
        formFields.replaceChildren();
        document.getElementById('adminFormAlerta').className = 'alert d-none';
        document.getElementById('adminModalTitulo').textContent = `${editing ? 'Editar' : 'Novo'} — ${fields[resource].label}`;
        if (resource === 'produtos' || resource === 'categorias') {
            try { await carregarCategorias(); }
            catch (error) { notify('Não foi possível carregar as categorias: ' + error.message); return; }
        }
        fields[resource].inputs.forEach(([name, label, type, required]) => {
            if (editing && name === 'senha') return;
            const wrapper = document.createElement('div'); wrapper.className = type === 'textarea' ? 'col-12' : 'col-md-6';
            const labelEl = document.createElement('label'); labelEl.className = 'form-label w-100'; labelEl.textContent = label;
            const input = type === 'textarea' ? document.createElement('textarea')
                : type === 'select' ? document.createElement('select') : document.createElement('input');
            if (type !== 'textarea' && type !== 'select') input.type = ['imagem_url', 'banner_url', 'link_url'].includes(name) ? 'text' : type;
            input.name = name; input.className = type === 'checkbox' ? 'form-check-input ms-2' : 'form-control mt-1';
            if (type === 'select' && resource === 'categorias') {
                input.className = 'form-select mt-1';
                input.add(new Option('Nenhuma — criar categoria principal', ''));
                categorias.filter(c => !c.id_categoria_pai && Number(c.id_categoria) !== Number(row?.id_categoria)).forEach(c => {
                    input.add(new Option(c.nome, c.id_categoria));
                });
            }
            if (resource === 'produtos') {
                const dicas = {
                    nome: 'Ex.: Camiseta Código 42', descricao: 'Descreva material, estilo e detalhes do produto',
                    imagem_url: 'https://exemplo.com/imagem.jpg', preco: 'Ex.: 49.90'
                };
                input.placeholder = dicas[name] || '';
            }
            input.required = !!required || (name === 'senha' && !editing);
            if (name === 'senha') input.minLength = 8;
            if (type === 'number') { input.step = name === 'preco' ? '0.01' : '1'; input.min = name === 'preco' ? '0' : '1'; }
            if (row) { if (type === 'checkbox') input.checked = !!Number(row[name]); else input.value = row[name] ?? ''; }
            else if (type === 'checkbox' && name === 'ativo') input.checked = true;
            labelEl.append(input); wrapper.append(labelEl); formFields.append(wrapper);
            if (resource === 'categorias' && name === 'id_categoria_pai') {
                const hint = document.createElement('small'); hint.className = 'text-muted d-block mt-2';
                hint.textContent = 'Sem categoria principal: aparece no menu principal. Com categoria principal: aparece como subcategoria.';
                wrapper.append(hint);
            }
            if (resource === 'produtos' && name === 'nome') formFields.append(montarCategoriasProduto(row));
            if (['imagem_url', 'banner_url'].includes(name) && resource !== 'usuarios') {
                const preview = document.createElement('img');
                preview.alt = 'Prévia da imagem'; preview.className = 'img-thumbnail mt-2 d-none';
                preview.style.maxHeight = '130px'; preview.style.maxWidth = '100%';
                input.addEventListener('input', () => {
                    preview.classList.toggle('d-none', !input.value.trim());
                    preview.src = input.value.trim();
                });
                if (input.value.trim()) { preview.src = input.value.trim(); preview.classList.remove('d-none'); }
                wrapper.append(preview);
                formFields.append(montarUploadProduto());
            }
        });
        if (resource === 'produtos') {
            formFields.append(montarDescontoProduto(row), montarVariacoesProduto(row));
        }
        modal.show();
    }
    async function remove(row) {
        const id = row[fields[resource].id];
        if (!confirm(`Confirma a exclusão/desativação do registro #${id}?`)) return;
        try { await auth.request(`/admin/${resource}/${id}`, { method: 'DELETE' }); notify('Registro excluído/desativado.', true); await refresh(); }
        catch (error) { notify(error.message); }
    }
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const payload = {};
        for (const input of form.querySelectorAll('[name]')) {
            payload[input.name] = input.type === 'checkbox' ? input.checked : input.value;
        }
        if (resource === 'produtos') {
            payload.categoria_ids = form.querySelector('[data-admin-categorias]')?.getSelectedCategories() || [];
            if (!payload.categoria_ids.length) { notify('Selecione pelo menos uma categoria para o produto.'); return; }
            payload.variacoes = form.querySelector('[data-admin-variacoes]')?.getVariacoes() || [];
            if (payload.variacoes.some(v => !Number.isInteger(v.estoque) || v.estoque < 0)) {
                notify('Informe quantidades de estoque válidas.'); return;
            }
        }
        const submit = form.querySelector('[type=submit]');
        submit.disabled = true;
        try {
            await auth.request(`/admin/${resource}${editing ? `/${editing}` : ''}`, {
                method: editing ? 'PUT' : 'POST', body: JSON.stringify(payload)
            });
            modal.hide(); notify('Registro salvo com sucesso.', true); await refresh();
        } catch (error) { notify(error.message); }
        finally { submit.disabled = false; }
    });
    document.getElementById('adminNovo').addEventListener('click', () => openForm());
    document.querySelectorAll('[data-resource]').forEach(button => button.addEventListener('click', () => {
        resource = button.dataset.resource;
        document.querySelectorAll('[data-resource]').forEach(item => item.classList.toggle('active', item === button));
        document.getElementById('adminRecursoTitulo').textContent = fields[resource].label.toLowerCase();
        notification.classList.add('d-none'); refresh();
    }));
    refresh();
});
