<?php
/**
 * The template for displaying archive pages
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div id="archive-wrapper">
	<section class="hero-section section-padded dark-bg--bg1">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col">
					<div class="heading text-center m-0">
						<span class="pre-title"> Customer Stories </span>
						<h1 class="title m-0"> Let’s Discover How Botphonic Impact on Different Businesses </h1>
						<?php if (get_the_archive_description()) {  ?><p class="mt-3 mb-0"><?= the_archive_description(); ?> </p> <?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<section class="client-section section-padded">
		<div class="container" id="content" tabindex="-1">
			<div class="row">
				<?php
				get_template_part('global-templates/left-sidebar-check');
				?>

				<main class="site-main" id="main">
					<div class="row row-cols-1 row-cols-sm-2">
						<?php
						if (have_posts()) {
							while (have_posts()) {
								the_post();
								echo "<div class='col mb-4'>";
								get_template_part('loop-templates/content', get_post_format());
								echo "</div>";
							}
						} else {
							echo "<div class='col-12 col-lg-6 text-center offset-lg-3' >";
							get_template_part('loop-templates/content', 'none');
							echo "</div>";
						}
						?>
					</div>
					<?php understrap_pagination(); ?>
				</main>
			</div><!-- .row -->
		</div><!-- #content -->
	</section>
</div><!-- #archive-wrapper -->

<?php
get_footer();
