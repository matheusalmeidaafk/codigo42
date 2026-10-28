document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('salvarFavorito');
    if (!button) return;
    const key = 'codigo42_favoritos';
    const id = Number(button.dataset.id);
    function items() {
        try { const saved = JSON.parse(localStorage.getItem(key)); return Array.isArray(saved) ? saved : []; }
        catch { return []; }
    }
    function update() { button.textContent = items().some(item => Number(item.id) === id) ? '♥ Salvo nos favoritos' : '♡ Salvar nos favoritos'; }
    button.addEventListener('click', () => {
        const saved = items();
        const index = saved.findIndex(item => Number(item.id) === id);
        if (index >= 0) saved.splice(index, 1);
        else saved.push({ id, name: button.dataset.nome });
        localStorage.setItem(key, JSON.stringify(saved)); update();
    });
    update();
});
