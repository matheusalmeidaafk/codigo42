document.addEventListener('DOMContentLoaded', async () => {
    const auth = window.Codigo42Auth;
    if (!auth.current() || auth.current().tipo !== 'USUARIO') { location.replace('/pages/acesso.php'); return; }
    const form = document.getElementById('formPerfil');
    const alert = document.getElementById('perfilAlerta');
    function notify(msg, ok = false) { alert.className = `alert alert-${ok ? 'success' : 'danger'}`; alert.textContent = msg; }
    document.getElementById('sairConta').addEventListener('click', auth.logout);
    try {
        const user = await auth.request('/usuarios/me');
        for (const field of ['nome_completo', 'email', 'telefone']) form.elements[field].value = user[field] || '';
        document.getElementById('perfilCpf').value = user.cpf || '';
    } catch (err) { notify(err.message); }
    form.addEventListener('submit', async event => {
        event.preventDefault();
        try {
            await auth.request('/usuarios/me', { method: 'PUT', body: JSON.stringify(Object.fromEntries(new FormData(form))) });
            notify('Dados atualizados com sucesso.', true);
        } catch (err) { notify(err.message); }
    });
    function readArray(key) { try { const items = JSON.parse(localStorage.getItem(key)); return Array.isArray(items) ? items : []; } catch { return []; } }
    const cart = readArray('ecommerce_cart');
    const cartBox = document.getElementById('perfilCarrinho');
    const money = value => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    if (!cart.length) cartBox.textContent = 'Seu carrinho está vazio.';
    let total = 0;
    cart.forEach(item => {
        const quantity = Math.max(0, Number(item.quantity) || 0);
        const price = Math.max(0, Number(item.price) || 0);
        total += price * quantity;
        const row = document.createElement('div');
        row.className = 'd-flex justify-content-between border-bottom py-2 gap-2';
        const name = document.createElement('span');
        name.textContent = `${item.name || 'Produto'} × ${quantity}`;
        const cost = document.createElement('strong');
        cost.textContent = money(price * quantity);
        row.append(name, cost); cartBox.appendChild(row);
    });
    document.getElementById('perfilTotal').textContent = `Total: ${money(total)}`;
    const favorites = readArray('codigo42_favoritos');
    const favBox = document.getElementById('perfilFavoritos');
    function renderFavorites() {
        favBox.replaceChildren();
        if (!favorites.length) { favBox.textContent = 'Você ainda não salvou produtos.'; return; }
        favorites.forEach((item, index) => {
            const line = document.createElement('div');
            line.className = 'd-flex align-items-center justify-content-between gap-2 border-bottom py-2';
            const link = document.createElement('a');
            link.href = `/pages/produto.php?id=${encodeURIComponent(item.id)}`;
            link.textContent = item.name || 'Produto';
            const remove = document.createElement('button');
            remove.type = 'button'; remove.className = 'btn btn-sm btn-outline-danger'; remove.textContent = 'Remover';
            remove.addEventListener('click', () => {
                favorites.splice(index, 1);
                localStorage.setItem('codigo42_favoritos', JSON.stringify(favorites));
                renderFavorites();
            });
            line.append(link, remove); favBox.append(line);
        });
    }
    renderFavorites();
});
