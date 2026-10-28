(() => {
    const base = window.CODIGO42_API_URL || `${location.protocol}//${location.hostname}:8080`;
    const key = 'codigo42_auth';
    function current() {
        try {
            const data = JSON.parse(sessionStorage.getItem(key));
            if (!data?.token) return null;
            const payload = JSON.parse(atob(data.token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')));
            if (payload.exp * 1000 <= Date.now()) { sessionStorage.removeItem(key); return null; }
            return { ...data, tipo: payload.tipo };
        } catch { sessionStorage.removeItem(key); return null; }
    }
    async function request(path, options = {}) {
        const session = current();
        const res = await fetch(`${base}${path}`, {
            ...options,
            headers: { 'Content-Type': 'application/json', ...(session ? { Authorization: `Bearer ${session.token}` } : {}), ...options.headers }
        });
        const body = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(body.erro || `Erro HTTP ${res.status}`);
        return body;
    }
    function updateHeader() {
        const link = document.getElementById('linkConta');
        if (!link) return;
        const session = current();
        if (!session) link.href = '/pages/acesso.php?retorno=' + encodeURIComponent(returnUrl(location.pathname + location.search));
        if (session) {
            link.href = session.tipo === 'ADM' ? '/pages/admin.php' : '/pages/perfil.php';
            link.innerHTML = '<span aria-hidden="true">👤</span> <span class="visually-hidden">Meu perfil</span>';
            link.setAttribute('aria-label', 'Minha conta');
        }
    }
    function returnUrl(value) {
        try {
            const url = new URL(value || '/pages/home.php', location.origin);
            if (url.origin !== location.origin || !/^\/pages\/(home|produtos|produto|carrinho|perfil|sobre)\.php$/.test(url.pathname)) return '/pages/home.php';
            return url.pathname + url.search + url.hash;
        } catch { return '/pages/home.php'; }
    }
    window.Codigo42Auth = {
        current, request, returnUrl,
        save(token) { sessionStorage.setItem(key, JSON.stringify({ token })); updateHeader(); },
        logout() { sessionStorage.removeItem(key); location.href = '/pages/acesso.php'; }
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', updateHeader);
    else updateHeader();
})();
