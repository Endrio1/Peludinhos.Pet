<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/flash.php';

// Buscar gatos disponíveis
$stmt = $pdo->prepare("SELECT id, name, age, breed, sex, description, image_path FROM cats WHERE status = 'available' ORDER BY created_at DESC");
$stmt->execute();
$cats = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Peludinhos UFOPA - Adoção de Gatos</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/testes/assets/style.css">
</head>
<body>
	<header class="site-header">
		<div class="container topbar">
			<div class="brand">
				<div class="logo"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C10 2 6 4 6 8v5c0 4 4 7 6 7s6-3 6-7V8c0-4-4-6-6-6z" fill="#fff"/></svg></div>
				<div>
					<h1>Peludinhos UFOPA</h1>
					<div class="sub">Adoção responsável de gatos</div>
				</div>
			</div>
			<div>
				<a class="btn" href="/testes/admin/login.php">Área do Administrador</a>
			</div>
		</div>
	</header>

	<main class="container">
		<section class="hero">
			<p class="muted">Conheça os gatinhos disponíveis para adoção. Clique em "Adotar" para enviar um pedido.</p>
		</section>

		<?php echo flash_render(); ?>

		<section class="card-area">
			<?php if (empty($cats)): ?>
				<div class="card">Nenhum gato disponível no momento.</div>
			<?php else: ?>
				<div class="grid">
				<?php foreach($cats as $cat): ?>
					<article class="card">
						<div class="thumb">
							<?php if (!empty($cat['image_path']) && file_exists(__DIR__ . '/' . $cat['image_path'])): ?>
								<img src="/<?php echo ltrim($cat['image_path'], '/'); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
							<?php else: ?>
								<img src="/testes/assets/placeholder.svg" alt="placeholder">
							<?php endif; ?>
						</div>
						<div class="meta">
							<div>
								<div class="name"><?php echo htmlspecialchars($cat['name']); ?></div>
								<div class="sub"><?php echo htmlspecialchars($cat['breed'] ?? 'Sem raça definida'); ?> • <?php echo htmlspecialchars($cat['age'] ?? 'idade desconhecida'); ?></div>
							</div>
							<div class="actions">
								<a class="btn" href="/testes/adopt.php?cat_id=<?php echo $cat['id']; ?>">Adotar</a>
							</div>
						</div>
						<p class="muted"><?php echo nl2br(htmlspecialchars(substr($cat['description'] ?? '', 0, 140))); ?></p>
					</article>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	</main>
</body>
</html>
