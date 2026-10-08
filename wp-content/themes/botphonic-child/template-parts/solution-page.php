<?php
/**
 * Shared renderer for the Industry and Use Case page templates.
 *
 * Loaded through get_template_part() with `$args['family']` set by
 * page-industry.php / page-use-case.php.
 *
 * @package BotphonicChild
 */

defined('ABSPATH') || exit;

$botphonic_solution_family = isset($args['family']) ? (string) $args['family'] : botphonic_solution_family();
if (!in_array($botphonic_solution_family, array('industry', 'use-case'), true)) {
	$botphonic_solution_family = 'industry';
}

get_header();
?>

<div class="main-wrapper bps-page bps-page--<?php echo esc_attr($botphonic_solution_family); ?>" id="page-wrapper">
	<main class="site-main bps-page__main" id="main">
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('bps-page__article'); ?>>
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__('Page', 'botphonic') . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</article>
		<?php endwhile; ?>
	</main>
</div><!-- #page-wrapper -->

<?php get_footer(); ?>
