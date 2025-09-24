<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';

$payload = admin_protect();
if (!$payload) { header('Location: login.php'); exit; }

?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#0b57d0"><title>Dashboard Admin</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C10 2 6 4 6 8v5c0 4 4 7 6 7s6-3 6-7V8c0-4-4-6-6-6z" fill="#fff"/></svg></div><h1>Painel do Administrador</h1></div>
  <div><a class="btn" href="logout.php">Sair</a></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <?php echo flash_render(); ?>
      <h2>Bem-vindo, <?php echo htmlspecialchars($payload['name'] ?? $payload['email']); ?></h2>
      <div class="grid" style="margin-top:16px">
        <a class="card" href="cats.php"><h3>Gerenciar Gatos</h3><p class="muted">Adicionar, editar e remover</p></a>
        <a class="card" href="requests.php"><h3>Pedidos de Adoção</h3><p class="muted">Processar solicitações</p></a>
        <a class="card" href="profile.php"><h3>Meu Perfil</h3><p class="muted">Atualizar dados e senha</p></a>
      </div>
    </div>
  </main>
</body></html>
