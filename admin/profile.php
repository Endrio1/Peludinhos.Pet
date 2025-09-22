<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';
require __DIR__ . '/../includes/csrf.php';
$payload = admin_protect();
if (!$payload) { header('Location: login.php'); exit; }

$adminId = $payload['sub'];
$stmt = $pdo->prepare('SELECT id, name, email FROM admins WHERE id = :id');
$stmt->execute([':id'=>$adminId]);
$admin = $stmt->fetch();

$msgs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['action']) && $_POST['action'] === 'update') {
    if (!csrf_check()) { $msgs[] = 'Token CSRF inválido.'; }
    else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if ($name && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $pdo->prepare('UPDATE admins SET name = :name, email = :email WHERE id = :id');
            $stmt->execute([':name'=>$name,':email'=>$email,':id'=>$adminId]);
      $msgs[] = 'Dados atualizados.';
      flash_set('Dados atualizados com sucesso.');
        } else { $msgs[] = 'Nome e email válido são necessários.'; }
    }
    }
  if (!empty($_POST['action']) && $_POST['action'] === 'pass') {
    if (!csrf_check()) { $msgs[] = 'Token CSRF inválido.'; }
    else {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $stmt2 = $pdo->prepare('SELECT password_hash FROM admins WHERE id = :id');
    $stmt2->execute([':id' => $adminId]);
    $hash = $stmt2->fetchColumn();
    if (!password_verify($old, $hash)) {
      $msgs[] = 'Senha antiga incorreta.';
    } elseif (strlen($new) < 6) {
      $msgs[] = 'Nova senha precisa ter ao menos 6 caracteres.';
    } else {
      $newh = password_hash($new, PASSWORD_DEFAULT);
      $pdo->prepare('UPDATE admins SET password_hash = :h WHERE id = :id')->execute([':h' => $newh, ':id' => $adminId]);
      $msgs[] = 'Senha alterada.';
      flash_set('Senha alterada com sucesso.');
    }
    }
  }
}

?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Meu Perfil</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Meu Perfil</h1></div>
  <div><a class="btn" href="dashboard.php">Voltar</a></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <?php echo flash_render(); ?>
      <?php foreach($msgs as $m): ?><div class="card"><?php echo htmlspecialchars($m); ?></div><?php endforeach; ?>

      <div class="form-wrap card">
        <h3>Dados</h3>
        <form method="post">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="update">
          <div><label>Nome<br><input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>"></label></div>
          <div><label>Email<br><input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>"></label></div>
          <div style="margin-top:12px"><button class="btn">Salvar</button></div>
        </form>
      </div>

      <div class="form-wrap card" style="margin-top:12px">
        <h3>Alterar senha</h3>
        <form method="post">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="pass">
          <div><label>Senha atual<br><input type="password" name="old_password"></label></div>
          <div><label>Nova senha<br><input type="password" name="new_password"></label></div>
          <div style="margin-top:12px"><button class="btn">Alterar senha</button></div>
        </form>
      </div>
    </div>
  </main>
</body></html>
