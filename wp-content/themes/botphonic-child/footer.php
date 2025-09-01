<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<footer class="bg-light border-top py-4">
	<div class="container px-4">
		<div class="row align-items-center text-center text-md-start">
			<div class="col-md-6 mb-3 mb-md-0 d-flex flex-column flex-md-row gap-2 gap-md-4 justify-content-center justify-content-md-start">
				<a href="/privacy-policy/" class="text-decoration-none hover-link">Privacy Policy</a>
				<a href="/terms-condition/" class="text-decoration-none hover-link">Terms &amp; Condition</a>
				<a href="/disclaimer/" class="text-decoration-none hover-link">Disclaimer</a>
			</div>
			<div class="col-md-6 text-muted text-center text-md-end">
				© 2025 Botphonic AI. All rights reserved.
			</div>
		</div>
	</div>
</footer>

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const phoneInput = document.getElementById('your-number');
		if (phoneInput) {
			phoneInput.addEventListener('input', function () {
				let cleaned = this.value.replace(/[^\d()+\s]/g, '');
				this.value = cleaned.slice(0, 15);
			});
		}
	});
</script>
<?php if(!(is_user_logged_in())) { ?>
<script type="text/javascript">
	window.onload = () => {
		window.lc_id = '431699510458';
		window.lc_dc = 'botphonic';
		let v = localStorage.getItem("dmVyc2lvbg==") || Date.now().toString();
		if (!document.querySelector('app-chat-box')) {
			var chatWidget = document.createElement('app-chat-box');
			chatWidget.setAttribute('id', "widget");
			document.body.insertAdjacentElement('beforeend', chatWidget);
		}
		var deskuInstall = document.createElement('script');
		deskuInstall.src = `https://widget.desku.io/chat-widget.js?v=${v}`;
		deskuInstall.setAttribute('defer', true);
		document.body.insertAdjacentElement('beforeend', deskuInstall);
	}
</script>
<?php } ?>
</body>

</html>

