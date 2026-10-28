document.addEventListener('DOMContentLoaded', () => {
    if (Codigo42Auth.current()?.tipo === 'ADM') { location.replace('/pages/admin.php'); return; }
    document.getElementById('adminLoginForm').addEventListener('submit', async event => {
        event.preventDefault();
        const error = document.getElementById('adminLoginErro');
        const button = event.currentTarget.querySelector('button');
        button.disabled = true;
        error.classList.add('d-none');
        try {
            const credentials = Object.fromEntries(new FormData(event.currentTarget));
            const result = await Codigo42Auth.request('/loginadm', { method: 'POST', body: JSON.stringify(credentials) });
            Codigo42Auth.save(result.token);
            if (Codigo42Auth.current()?.tipo !== 'ADM') throw new Error('Acesso não autorizado.');
            location.replace('/pages/admin.php');
        } catch (err) { error.textContent = err.message; error.classList.remove('d-none'); }
        finally { button.disabled = false; }
    });
});
