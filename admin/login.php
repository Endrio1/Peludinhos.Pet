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
  header('Location: dashboard.php');
      exit;
    } else {
      $error = 'Credenciais inválidas.';
    }
  }
}
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#0b57d0"><title>Login Admin</title>
<link rel="stylesheet" href="../assets/style.css"></head><body class="page">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C10 2 6 4 6 8v5c0 4 4 7 6 7s6-3 6-7V8c0-4-4-6-6-6z" fill="#fff"/></svg></div><h1>Área Administrativa</h1></div>
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
  <p class="muted">Se não houver administrador, crie um em <a href="register.php">Registrar Admin</a></p>
      </div>
    </div>
  </main>
</body></html>
