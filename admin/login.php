<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';
require __DIR__ . '/../includes/csrf.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check()) { $error = 'Token CSRF inválido.'; }
  else {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $token = admin_login($pdo, $email, $password);
    if ($token) {
      admin_set_cookie($token);
      flash_set('Login efetuado com sucesso.');
      header('Location: /testes/admin/dashboard.php');
      exit;
    } else {
      $error = 'Credenciais inválidas.';
    }
  }
}
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><title>Login Admin</title>
<link rel="stylesheet" href="/testes/assets/style.css"></head><body class="page">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Área Administrativa</h1></div>
      <div></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <div class="form-wrap card">
        <h2>Login Administrador</h2>
        <?php echo flash_render(); ?>
        <?php if ($error): ?><div class="card"><strong><?php echo htmlspecialchars($error); ?></strong></div><?php endif; ?>
        <form method="post">
          <?php echo csrf_field(); ?>
          <div><label>Email<br><input type="email" name="email" required></label></div>
          <div><label>Senha<br><input type="password" name="password" required></label></div>
          <div style="margin-top:12px"><button class="btn">Entrar</button></div>
        </form>
        <p class="muted">Se não houver administrador, crie um em <a href="/testes/admin/register.php">Registrar Admin</a></p>
      </div>
    </div>
  </main>
</body></html>
