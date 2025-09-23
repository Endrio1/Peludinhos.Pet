<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';

$payload = admin_protect();
if (!$payload) { header('Location: login.php'); exit; }

?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Dashboard Admin</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Painel do Administrador</h1></div>
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
