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
	<link rel="stylesheet" href="assets/style.css">
	<script src="assets/carousel.js" defer></script>
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
			<div class="header-actions">
				<nav class="socials">
					<a href="https://facebook.com" target="_blank" aria-label="Facebook">FB</a>
					<a href="https://instagram.com" target="_blank" aria-label="Instagram">IG</a>
					<a href="https://twitter.com" target="_blank" aria-label="Twitter">TW</a>
				</nav>
				<a class="btn secondary" href="donate.php">Doar</a>
				<a class="btn" href="admin/login.php">Área do Administrador</a>
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
								<img src="<?php echo htmlspecialchars($cat['image_path']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
							<?php else: ?>
								<img src="assets/placeholder.svg" alt="placeholder">
							<?php endif; ?>
						</div>
						<div class="meta">
							<div>
								<div class="name"><?php echo htmlspecialchars($cat['name']); ?></div>
								<div class="sub"><?php echo htmlspecialchars($cat['breed'] ?? 'Sem raça definida'); ?> • <?php echo htmlspecialchars($cat['age'] ?? 'idade desconhecida'); ?></div>
							</div>
							<div class="actions">
								<a class="btn" href="adopt.php?cat_id=<?php echo $cat['id']; ?>">Adotar</a>
							</div>
						</div>
						<p class="muted"><?php echo nl2br(htmlspecialchars(substr($cat['description'] ?? '', 0, 140))); ?></p>
					</article>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>

		<!-- Benefits Section -->
		<section class="benefits">
			<h2>Por que adotar um animal resgatado?</h2>
			<div class="grid benefits-grid">
				<div class="card">
					<h3>Salvar uma vida</h3>
					<p class="muted">Ao adotar, você dá uma segunda chance a um animal que foi abandonado ou resgatado.</p>
				</div>
				<div class="card">
					<h3>Adoção responsável</h3>
					<p class="muted">Animais resgatados geralmente já estão vacinados e avaliados por profissionais.</p>
				</div>
				<div class="card">
					<h3>Companhia e afeto</h3>
					<p class="muted">Ganho de um amigo para a vida — benefícios para saúde mental e bem-estar.</p>
				</div>
			</div>
		</section>

		<!-- News Carousel -->
		<section class="news">
			<h2>Notícias e Atualizações</h2>
			<div class="carousel" id="news-carousel" data-interval="5000">
				<div class="slides">
					<div class="slide">Proteção animal: novas leis em discussão que impactam adoções.</div>
					<div class="slide">Evento de adoção neste sábado no campus — traga sua família!</div>
					<div class="slide">Dicas de cuidados: como preparar sua casa para um novo gato.</div>
				</div>
				<div class="controls">
					<button class="prev">‹</button>
					<button class="next">›</button>
				</div>
			</div>
		</section>
	</main>

	<footer class="site-footer">
		<div class="container">
			<div class="grid footer-grid">
				<div>
					<h4>Contato</h4>
					<p class="muted">Email: contato@peludinhos.ufopa.br<br>Telefone: (94) 1234-5678</p>
				</div>
				<div>
					<h4>Endereço</h4>
					<p class="muted">Campus UFOPA — Santarém/PA<br>Bloco A, Sala 12</p>
				</div>
				<div>
					<h4>Redes</h4>
					<p><a href="https://instagram.com" target="_blank">Instagram</a> • <a href="https://facebook.com" target="_blank">Facebook</a></p>
				</div>
			</div>
			<div class="muted" style="margin-top:12px;font-size:0.9rem">© Peludinhos UFOPA — Projeto de adoção responsável</div>
		</div>
	</footer>
</body>
</html>
