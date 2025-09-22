<?php
$require_line = '';
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';
require __DIR__ . '/../includes/csrf.php';
$payload = admin_protect();
if (!$payload) { header('Location: /testes/admin/login.php'); exit; }

// criar novo gato com upload seguro
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
  if (!csrf_check()) { $errors[] = 'Token CSRF inválido.'; }
  $name = trim($_POST['name'] ?? '');
  $breed = trim($_POST['breed'] ?? '');
  $age = trim($_POST['age'] ?? '');
  $desc = trim($_POST['description'] ?? '');

  if (!$name) $errors[] = 'Nome é obrigatório.';

  $image_path = null;
  if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['image'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Erro no upload da imagem.';
    } else {
      // validações básicas: tamanho e mime
      $maxBytes = 2 * 1024 * 1024; // 2MB
      if ($f['size'] > $maxBytes) { $errors[] = 'Imagem muito grande (máx 2MB).'; }
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $f['tmp_name']);
      finfo_close($finfo);
      $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
      if (!isset($allowed[$mime])) { $errors[] = 'Tipo de imagem não permitido.'; }
      if (empty($errors)) {
        $ext = $allowed[$mime];
        $nameHash = bin2hex(random_bytes(8));
        $targetDir = __DIR__ . '/../uploads/cats';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $filename = sprintf('%s.%s', $nameHash, $ext);
        $dest = $targetDir . '/' . $filename;
        if (!move_uploaded_file($f['tmp_name'], $dest)) {
          $errors[] = 'Falha ao mover o arquivo.';
        } else {
          // salvar caminho relativo em DB
          $image_path = 'uploads/cats/' . $filename;
        }
      }
    }
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare('INSERT INTO cats (name, age, breed, description, image_path) VALUES (:name,:age,:breed,:desc,:image)');
    $stmt->execute([':name'=>$name,':age'=>$age,':breed'=>$breed,':desc'=>$desc,':image'=>$image_path]);
    flash_set('Gato criado com sucesso.');
    header('Location: /testes/admin/cats.php'); exit;
  }
}

$cats = $pdo->query('SELECT * FROM cats ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Gerenciar Gatos</title>
<link rel="stylesheet" href="/testes/assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Gerenciar Gatos</h1></div>
      <div><a class="btn" href="/testes/admin/dashboard.php">Voltar</a></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <div class="form-wrap card">
        <h3>Cadastrar novo gato</h3>
        <?php echo flash_render(); ?>
        <?php if ($errors): ?><div class="card"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="create">
          <?php echo csrf_field(); ?>
          <div><label>Nome<br><input type="text" name="name" required></label></div>
          <div><label>Raça<br><input type="text" name="breed"></label></div>
          <div><label>Idade<br><input type="text" name="age"></label></div>
          <div><label>Imagem (envie um arquivo)<br><input type="file" name="image" accept="image/*"></label></div>
          <div><label>Descrição<br><textarea name="description"></textarea></label></div>
          <div style="margin-top:12px"><button class="btn">Criar</button></div>
        </form>
      </div>

      <section style="margin-top:18px">
        <h3>Lista de gatos</h3>
        <div class="grid">
          <?php foreach($cats as $c): ?>
            <a class="card" href="/testes/admin/cats_edit.php?id=<?php echo $c['id']; ?>">
              <h4><?php echo htmlspecialchars($c['name']); ?> <small class="muted">(<?php echo htmlspecialchars($c['status']); ?>)</small></h4>
              <p class="muted"><?php echo htmlspecialchars($c['breed'] ?? ''); ?> • <?php echo htmlspecialchars($c['age'] ?? ''); ?></p>
              <p><?php echo nl2br(htmlspecialchars(substr($c['description'] ?? '',0,120))); ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </main>
</body></html>
