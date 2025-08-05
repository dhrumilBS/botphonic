<?php get_header(); ?>
<style>
	.error404-section .container { max-width: 900px; width: 100%; height: 90vh; display: flex; gap: 30px; }
	.error404-section .image { flex: 1 1 300px; display: flex; align-items: center; justify-content: center; }
	.error404-section .image img { max-width: 100%; height: auto; }
	.error404-section .content { flex: 1 1 300px; display: flex; flex-direction: column; justify-content: center; text-align: left; }
	.error404-section .content h1 { margin-bottom: 20px; }
	.error404-section .content .headline { font-size: 18px; margin-bottom: 30px; }
	@media (max-width: 768px) {
		.error404-section  .container { flex-direction: column; text-align: center; }
		.error404-section  .content { text-align: center; }
	}
</style>
<section class="error404-section">
	<div class="container">
		<div class="image">
			<img src="https://botphonic.ai/wp-content/uploads/2025/05/Botphonic-AI-404.webp" alt="AI Call Assistant Bot">
		</div>
		<div class="content">
			<h1>404 – Call Disconnected!</h1>
			<p class="headline">Oops! That page doesn’t exist. But Botphonic AI never misses a real call.</p>
			<a class="elementor-button" href="https://botphonic.ai/" >
				<span class="elementor-button-text">Go To Homepage</span>
			</a>
		</div>
	</div>
</section>
<?php get_footer(); ?>