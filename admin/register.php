<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/flash.php';
require __DIR__ . '/../includes/csrf.php';

$stmt = $pdo->query('SELECT COUNT(*) as c FROM admins');
$count = $stmt->fetchColumn();
if ($count > 0) {
  echo 'Administrador já existe. <a href="login.php">Entrar</a>'; exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check()) { $errors[] = 'Token CSRF inválido.'; }
  else {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
        $errors[] = 'Nome, email válido e senha (>=6) são obrigatórios.';
    }
    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (name, email, password_hash) VALUES (:name, :email, :hash)');
        $stmt->execute([':name'=>$name,':email'=>$email,':hash'=>$hash]);
    flash_set('Administrador criado com sucesso. Faça login.');
  header('Location: login.php'); exit;
    }
  }
}
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Registrar Admin</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Área Administrativa</h1></div>
      <div></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <div class="form-wrap card">
        <h2>Registrar Administrador</h2>
        <?php echo flash_render(); ?>
        <?php if (!empty($errors)): ?><div class="card"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post">
          <?php echo csrf_field(); ?>
          <div><label>Nome<br><input type="text" name="name" required></label></div>
          <div><label>Email<br><input type="email" name="email" required></label></div>
          <div><label>Senha<br><input type="password" name="password" required></label></div>
          <div style="margin-top:12px"><button class="btn">Criar administrador</button></div>
        </form>
      </div>
    </div>
  </main>
</body></html>
