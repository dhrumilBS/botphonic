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
</body>

</html>

