document.addEventListener('DOMContentLoaded', async () => {
    const menu = document.getElementById('menu-header');
    const url = id => `/pages/produtos.php?categorias=${encodeURIComponent(id)}`;
    try {
        const categories = await Codigo42Auth.request('/categorias');
        menu.replaceChildren();
        categories.filter(c => !c.id_categoria_pai).forEach(parent => {
            const group = document.createElement('div'); group.className = 'categoria-menu';
            const link = document.createElement('a'); link.className = 'categoria-link';
            link.href = url(parent.id_categoria); link.textContent = parent.nome;
            const children = document.createElement('div'); children.className = 'subcategorias';
            categories.filter(c => Number(c.id_categoria_pai) === Number(parent.id_categoria)).forEach(child => {
                const a = document.createElement('a'); a.className = 'subcategoria-link';
                a.href = url(child.id_categoria); a.textContent = child.nome; children.append(a);
            });
            group.append(link, children); menu.append(group);
        });
    } catch { menu.textContent = 'Categorias indisponíveis no momento.'; }
});
