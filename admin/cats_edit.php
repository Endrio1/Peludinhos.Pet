<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';

$payload = admin_protect();
if (!$payload) { header('Location: /testes/admin/login.php'); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: /testes/admin/cats.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM cats WHERE id = :id');
$stmt->execute([':id' => $id]);
$cat = $stmt->fetch();
if (!$cat) { header('Location: /testes/admin/cats.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check()) { $errors[] = 'Token CSRF inválido.'; }
    if (!empty($_POST['action']) && $_POST['action'] === 'delete') {
        // remover imagem
        if (!empty($cat['image_path']) && file_exists(__DIR__ . '/../' . $cat['image_path'])) {
            @unlink(__DIR__ . '/../' . $cat['image_path']);
        }
        $pdo->prepare('DELETE FROM cats WHERE id = :id')->execute([':id' => $id]);
        header('Location: /testes/admin/cats.php'); exit;
    }

    // update
    $name = trim($_POST['name'] ?? '');
    $breed = trim($_POST['breed'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $status = ($_POST['status'] ?? 'available') === 'adopted' ? 'adopted' : 'available';

    if (!$name) $errors[] = 'Nome é obrigatório.';

    // tratar upload de nova imagem
    if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['image'];
        if ($f['error'] !== UPLOAD_ERR_OK) { $errors[] = 'Erro no upload da imagem.'; }
        else {
            $maxBytes = 2 * 1024 * 1024;
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
                if (!move_uploaded_file($f['tmp_name'], $dest)) { $errors[] = 'Falha ao mover o arquivo.'; }
                else {
                    // apagar imagem antiga
                    if (!empty($cat['image_path']) && file_exists(__DIR__ . '/../' . $cat['image_path'])) {
                        @unlink(__DIR__ . '/../' . $cat['image_path']);
                    }
                    $cat['image_path'] = 'uploads/cats/' . $filename;
                }
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE cats SET name = :name, age = :age, breed = :breed, description = :desc, image_path = :image_path, status = :status WHERE id = :id');
        $stmt->execute([':name'=>$name,':age'=>$age,':breed'=>$breed,':desc'=>$desc,':image_path'=>$cat['image_path'],':status'=>$status,':id'=>$id]);
        header('Location: /testes/admin/cats.php'); exit;
    }
}

?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Editar Gato</title>
<link rel="stylesheet" href="/testes/assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Editar Gato</h1></div>
      <div><a class="btn" href="/testes/admin/cats.php">Voltar</a></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <?php if ($errors): ?><div class="card"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
      <div class="form-wrap card">
        <form method="post" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <div><label>Nome<br><input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" required></label></div>
          <div><label>Raça<br><input type="text" name="breed" value="<?php echo htmlspecialchars($cat['breed']); ?>"></label></div>
          <div><label>Idade<br><input type="text" name="age" value="<?php echo htmlspecialchars($cat['age']); ?>"></label></div>
          <div><label>Status<br>
            <select name="status"><option value="available" <?php echo $cat['status']=='available'?'selected':''; ?>>available</option><option value="adopted" <?php echo $cat['status']=='adopted'?'selected':''; ?>>adopted</option></select>
          </label></div>
          <div>
            <p>Imagem atual:</p>
            <?php if (!empty($cat['image_path']) && file_exists(__DIR__ . '/../' . $cat['image_path'])): ?>
              <img src="/<?php echo ltrim($cat['image_path'], '/'); ?>" style="max-width:200px;display:block">
            <?php else: ?>
              <p class="muted">Sem imagem</p>
            <?php endif; ?>
            <label>Trocar imagem (opcional)<br><input type="file" name="image" accept="image/*"></label>
          </div>
          <div><label>Descrição<br><textarea name="description"><?php echo htmlspecialchars($cat['description']); ?></textarea></label></div>
          <div style="margin-top:12px"><button class="btn">Salvar</button></div>
        </form>

        <form method="post" onsubmit="return confirm('Confirma exclusão deste gato?');" style="margin-top:12px">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="delete">
          <button class="btn" style="background:#d33">Excluir Gato</button>
        </form>
      </div>
    </div>
  </main>
</body></html>
