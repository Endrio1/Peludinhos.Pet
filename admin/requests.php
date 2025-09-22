<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/flash.php';
require __DIR__ . '/../includes/csrf.php';
$payload = admin_protect();
if (!$payload) { header('Location: /testes/admin/login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action']) && !empty($_POST['id'])) {
  if (!csrf_check()) { flash_set('Token CSRF inválido.', 'error'); header('Location: /testes/admin/requests.php'); exit; }
    $id = (int)$_POST['id'];
    if ($_POST['action'] === 'approve') {
        // marcar request como approved e marcar cat como adopted
        $pdo->beginTransaction();
        $pdo->prepare('UPDATE adoption_requests SET status = "approved" WHERE id = :id')->execute([':id'=>$id]);
        $cat_id = $pdo->query('SELECT cat_id FROM adoption_requests WHERE id = '. $id)->fetchColumn();
        if ($cat_id) $pdo->prepare('UPDATE cats SET status = "adopted" WHERE id = :id')->execute([':id'=>$cat_id]);
    $pdo->commit();
    flash_set('Pedido aprovado com sucesso.');
    } elseif ($_POST['action'] === 'reject') {
        $pdo->prepare('UPDATE adoption_requests SET status = "rejected" WHERE id = :id')->execute([':id'=>$id]);
    flash_set('Pedido rejeitado.');
    }
    header('Location: /testes/admin/requests.php'); exit;
}

$requests = $pdo->query('SELECT r.*, c.name as cat_name FROM adoption_requests r JOIN cats c ON r.cat_id = c.id ORDER BY r.created_at DESC')->fetchAll();
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><title>Gerenciar Pedidos</title>
<link rel="stylesheet" href="/testes/assets/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet"></head><body class="page">
  <header class="site-header">
    <div class="container topbar">
      <div class="brand"><div class="logo"></div><h1>Pedidos de Adoção</h1></div>
      <div><a class="btn" href="/testes/admin/dashboard.php">Voltar</a></div>
    </div>
  </header>
  <main class="main">
    <div class="panel container">
      <?php echo flash_render(); ?>
      <?php if (empty($requests)): ?>
        <div class="card">Nenhum pedido registrado.</div>
      <?php else: ?>
        <div class="grid">
        <?php foreach($requests as $r): ?>
          <div class="card">
            <strong><?php echo htmlspecialchars($r['applicant_name']); ?></strong> - <?php echo htmlspecialchars($r['applicant_email']); ?>
            <p class="muted">Gato: <?php echo htmlspecialchars($r['cat_name']); ?> • Status: <?php echo htmlspecialchars($r['status']); ?> • Em: <?php echo $r['created_at']; ?></p>
            <p><?php echo nl2br(htmlspecialchars($r['message'] ?? '')); ?></p>
            <div style="margin-top:10px">
              <form method="post" style="display:inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <button class="btn" name="action" value="approve">Aprovar</button>
              </form>
              <form method="post" style="display:inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <button class="btn" name="action" value="reject" style="background:#d33">Rejeitar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body></html>
