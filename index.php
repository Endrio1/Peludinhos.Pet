<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peludinhos da UFOPA - Adoção de Gatos</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <h1>🐾 Peludinhos da UFOPA</h1>
            <p>Encontre seu novo amigo de quatro patas!</p>
        </div>
    </header>

    <main class="container">
        <h2>Gatinhos esperando por um lar</h2>
        <div id="catalogo-gatos" class="catalogo">
            </div>
    </main>

    <footer class="main-footer">
        <p>Projeto de Extensão da Universidade Federal do Oeste do Pará - UFOPA</p>
    </footer>

    <div id="modal-adocao" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Formulário de Adoção</h2>
            <p>Você está prestes a iniciar o processo de adoção para <strong id="modal-nome-gato"></strong>!</p>
            <form id="form-adocao">
                <input type="hidden" id="gato_id" name="gato_id">
                <div class="form-group">
                    <label for="nome_interessado">Seu Nome Completo</label>
                    <input type="text" id="nome_interessado" name="nome_interessado" required>
                </div>
                <div class="form-group">
                    <label for="email_interessado">Seu E-mail</label>
                    <input type="email" id="email_interessado" name="email_interessado" required>
                </div>
                <div class="form-group">
                    <label for="telefone_interessado">Seu Telefone (WhatsApp)</label>
                    <input type="tel" id="telefone_interessado" name="telefone_interessado" required>
                </div>
                <div class="form-group">
                    <label for="mensagem">Por que você quer adotar este gatinho?</label>
                    <textarea id="mensagem" name="mensagem" rows="4"></textarea>
                </div>
                <button type="submit" class="btn-submit">Enviar Pedido</button>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>