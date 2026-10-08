<?php

/**
 * Alternatives comparison card — used by archive-alternatives.php.
 *
 * Built on the blog card (.bpg-card, blog.css § 14) so hover, media zoom,
 * focus-within and the featured grid-span all behave identically; the
 * comparison-specific parts are the rating line and the "View comparison"
 * affordance, styled in alternatives.css § 10.
 *
 * Accepted $args:
 *   variant  'default' | 'featured'  Featured spans the grid at >=768px.
 *   flag     string                  Optional ribbon label, e.g. "Just updated".
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$alt_variant = isset($args['variant']) ? $args['variant'] : 'default';
$alt_flag = isset($args['flag']) ? $args['flag'] : '';
$alt_featured = ('featured' === $alt_variant);

$alt_permalink = get_permalink();
$alt_title = get_the_title();
$alt_data = botphonic_alt_data();
$alt_image_size = $alt_featured ? 'large' : 'medium_large';
$alt_first = !empty($alt_data['profiles']) ? $alt_data['profiles'][0] : array();
$alt_rating = isset($alt_first['rating_value']) ? trim((string) $alt_first['rating_value']) : '';
$alt_rating_source = isset($alt_first['rating_source']) ? trim((string) $alt_first['rating_source']) : '';
$alt_rating_source = '' !== $alt_rating_source ? $alt_rating_source : 'G2';
$alt_classes = array('bpg-card', 'bpg-alt-card');
if ($alt_featured) {
	$alt_classes[] = 'bpg-card--featured';
}
?>

<article <?php post_class($alt_classes); ?> id="post-<?php the_ID(); ?>">

	<?php if ($alt_flag) : ?>
	<span class="bpg-card__flag"><?php echo esc_html($alt_flag); ?></span>
	<?php endif; ?>

	<?php
	// Decorative duplicate of the title link: taken out of the tab order and
	// hidden from assistive tech, so the card exposes exactly one link to the
	// guide (the heading) plus the "View comparison" affordance.
	?>
	<a class="bpg-card__media<?php echo has_post_thumbnail() ? '' : ' bpg-alt-card__media--fallback'; ?>" href="<?php echo esc_url($alt_permalink); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail(
				$alt_image_size,
				array(
					'loading' => $alt_featured ? 'eager' : 'lazy',
					'decoding' => 'async',
					'alt' => esc_attr($alt_title),
				)
			);
		} else {
			$alt_logo_id = (int) get_theme_mod('custom_logo');

			if ($alt_logo_id) {
				echo wp_get_attachment_image(
					$alt_logo_id,
					'medium',
					false,
					array(
						'class' => 'bpg-alt-card__logo',
						'alt' => '',
						'loading' => 'lazy',
						'decoding' => 'async',
					)
				);
			}
		}
		?>
	</a>

	<div class="bpg-card__body">

		<div class="bpg-card__top">
			<?php if ('' !== $alt_rating) : ?>
			<span class="bpg-alt-card__rating">
				<?php echo botphonic_alt_stars((float) $alt_rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_stars(). ?>
				<span>
					<?php
					printf(
						/* translators: 1: rating out of five, 2: review source, e.g. G2. */
						esc_html__('%1$s/5 on %2$s', 'botphonic'),
						esc_html($alt_rating),
						esc_html($alt_rating_source)
					);
					?>
				</span>
			</span>
			<?php else : ?>
			<span class="bpg-chip"><?php esc_html_e('Comparison', 'botphonic'); ?></span>
			<?php endif; ?>
			<span class="bpg-card__read"><?php esc_html_e('Comparison guide', 'botphonic'); ?></span>
		</div>

		<h2 class="bpg-card__title">
			<a href="<?php echo esc_url($alt_permalink); ?>" rel="bookmark"><?php echo esc_html($alt_title); ?></a>
		</h2>
		<div class="bpg-card__foot">
			<time class="bpg-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo esc_html(get_the_date('M j, Y')); ?>
			</time>
			<a class="bpg-alt-card__more" href="<?php echo esc_url($alt_permalink); ?>" tabindex="-1" aria-hidden="true">
				<?php esc_html_e('View comparison', 'botphonic'); ?>
				<?php echo botphonic_alt_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
			</a>
		</div>

	</div>

</article>
