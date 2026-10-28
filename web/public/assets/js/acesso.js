document.addEventListener('DOMContentLoaded', () => {
    const auth = window.Codigo42Auth;
    const login = document.getElementById('formEntrar');
    const register = document.getElementById('formCadastro');
    const alert = document.getElementById('alertaAcesso');
    const enterTab = document.getElementById('abaEntrar');
    const registerTab = document.getElementById('abaCadastro');
    const destination = auth.returnUrl(new URLSearchParams(location.search).get('retorno'));
    if (auth.current()) { location.replace(destination); return; }
    function showRegister(value) {
        login.classList.toggle('d-none', value); register.classList.toggle('d-none', !value);
        document.getElementById('acessoShell').classList.toggle('cadastro-ativo', value);
        alert.classList.add('d-none');
    }
    function message(text, success = false) {
        alert.textContent = text; alert.className = `alert alert-${success ? 'success' : 'danger'}`;
    }
    enterTab.addEventListener('click', () => showRegister(false));
    registerTab.addEventListener('click', () => showRegister(true));
    showRegister(new URLSearchParams(location.search).get('modo') === 'cadastro');
    document.querySelectorAll('.ver-senha').forEach(button => button.addEventListener('click', () => {
        const input = button.previousElementSibling;
        input.type = input.type === 'password' ? 'text' : 'password';
        button.setAttribute('aria-label', input.type === 'password' ? 'Mostrar senha' : 'Ocultar senha');
    }));
    async function authenticate(email, senha) {
        const result = await auth.request('/login', { method: 'POST', body: JSON.stringify({ email, senha }) });
        if (!result.token) throw new Error('Não foi possível iniciar a sessão. Tente entrar novamente.');
        auth.save(result.token);
        location.replace(destination);
    }
    for (const form of [login, register]) form.addEventListener('submit', async event => {
        event.preventDefault();
        const button = form.querySelector('[type=submit]'); button.disabled = true;
        const data = Object.fromEntries(new FormData(form)); data.email = data.email.trim();
        let created = false;
        try {
            if (form === register) {
                if (data.senha !== data.confirmarSenha) throw new Error('As senhas não coincidem.');
                delete data.confirmarSenha;
                await auth.request('/cadastro', { method: 'POST', body: JSON.stringify(data) }); created = true;
            }
            await authenticate(data.email, data.senha);
        } catch (error) {
            if (created) {
                showRegister(false); login.elements.email.value = data.email;
                message('Conta criada! Entre com seu e-mail e senha para continuar.', true);
            } else message(error.message);
        } finally { button.disabled = false; }
    });
});
