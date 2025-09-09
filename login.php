<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Admin Peludinhos</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <form id="login-form">
            <h2>Login do Administrador</h2>
            <div id="error-message" class="error-message"></div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <script>
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;
            const errorMessage = document.getElementById('error-message');

            try {
                const response = await fetch('api/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, senha })
                });

                const result = await response.json();

                if (response.ok && result.token) {
                    localStorage.setItem('jwt_token', result.token);
                    window.location.href = 'admin/dashboard.php';
                } else {
                    errorMessage.textContent = result.message || 'Erro ao fazer login.';
                    errorMessage.style.display = 'block';
                }
            } catch (error) {
                 errorMessage.textContent = 'Erro de conexão com o servidor.';
                 errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>