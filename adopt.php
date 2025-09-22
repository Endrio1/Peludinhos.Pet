<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/flash.php';
require __DIR__ . '/includes/csrf.php';

$cat = null;
if (!empty($_GET['cat_id'])) {
    $stmt = $pdo->prepare('SELECT id, name FROM cats WHERE id = :id AND status = "available"');
    $stmt->execute([':id' => $_GET['cat_id']]);
    $cat = $stmt->fetch();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check()) { $errors[] = 'Token CSRF inválido.'; }
  $cat_id = $_POST['cat_id'] ?? null;
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (!$cat_id || !$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Preencha o nome, e-mail válido e selecione um gato.';
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare('INSERT INTO adoption_requests (cat_id, applicant_name, applicant_email, applicant_phone, message) VALUES (:cat_id, :name, :email, :phone, :message)');
    $stmt->execute([
      ':cat_id' => $cat_id,
      ':name' => $name,
      ':email' => $email,
      ':phone' => $phone,
      ':message' => $message,
    ]);

    flash_set('Pedido de adoção enviado com sucesso. Obrigado!');
    header('Location: /testes/index.php');
    exit;
  }
}

?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pedido de Adoção - Peludinhos UFOPA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/testes/assets/style.css">
</head>
<body>
  <div class="container">
    <a href="/testes/index.php">&larr; Voltar</a>
    <h2>Pedido de Adoção</h2>
    <?php if ($cat): ?>
      <p>Você está pedindo adoção para: <strong><?php echo htmlspecialchars($cat['name']); ?></strong></p>
    <?php else: ?>
      <p class="muted">Selecione um gato na landing page.</p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="card">
        <strong>Erros:</strong>
        <ul>
          <?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="cat_id" value="<?php echo htmlspecialchars($cat['id'] ?? ''); ?>">
      <div>
        <label>Seu nome<br><input type="text" name="name" required></label>
      </div>
      <div>
        <label>Seu e-mail<br><input type="email" name="email" required></label>
      </div>
      <div>
        <label>Telefone<br><input type="text" name="phone"></label>
      </div>
      <div>
        <label>Mensagem<br><textarea name="message"></textarea></label>
      </div>
      <div style="margin-top:12px"><button class="btn" type="submit">Enviar pedido</button></div>
    </form>
  </div>
</body>
</html>
