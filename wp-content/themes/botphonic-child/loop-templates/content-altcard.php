<?php

defined('ABSPATH') || exit;

$alt_variant = isset($args['variant']) ? $args['variant'] : 'default';
$alt_flag = isset($args['flag']) ? $args['flag'] : '';
$alt_featured = ('featured' === $alt_variant);

$alt_permalink = get_permalink();
$alt_title = get_the_title();
$alt_image_size = $alt_featured ? 'large' : 'medium_large';
$alt_classes = array('bpg-card', 'bpg-alt-card');
if ($alt_featured) {
	$alt_classes[] = 'bpg-card--featured';
}
?>

<article <?php post_class($alt_classes); ?> id="post-<?php the_ID(); ?>">

	<?php if ($alt_flag) : ?>
		<span class="bpg-card__flag"><?php echo esc_html($alt_flag); ?></span>
	<?php endif; ?>

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

		<h2 class="bpg-card__title">
			<a href="<?php echo esc_url($alt_permalink); ?>" rel="bookmark"><?php echo esc_html($alt_title); ?></a>
		</h2>
		<div class="bpg-card__foot">
			<time class="bpg-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo esc_html(get_the_date('M j, Y')); ?>
			</time>
			<a class="bpg-alt-card__more" href="<?php echo esc_url($alt_permalink); ?>" tabindex="-1" aria-hidden="true">
				<?php esc_html_e('View comparison', 'botphonic'); ?>
				<?php echo botphonic_alt_icon('arrow-right'); ?>
			</a>
		</div>

	</div>

</article>