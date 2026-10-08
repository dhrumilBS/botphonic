<?php

/**
 * Blog card used by the listing (index.php), archive.php and category.php.
 *
 * Kept as its own template part so loop-templates/content.php stays available
 * (and unchanged) for any other caller.
 *
 * Accepted $args:
 *   variant  'default' | 'featured'  Featured spans the grid on >=768px.
 *   flag     string                  Optional ribbon label, e.g. "Latest".
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpg_variant = isset($args['variant']) ? $args['variant'] : 'default';
$bpg_flag = isset($args['flag']) ? $args['flag'] : '';

$bpg_permalink = get_permalink();
$bpg_title = get_the_title();
$bpg_category = botphonic_blog_primary_category();
$bpg_author_id = (int) get_post_field('post_author', get_the_ID());
$bpg_author = get_the_author_meta('display_name', $bpg_author_id);
$bpg_image_size = ('featured' === $bpg_variant) ? 'large' : 'medium_large';

$bpg_classes = array('bpg-card');
if ('featured' === $bpg_variant) {
	$bpg_classes[] = 'bpg-card--featured';
}
?>

<article <?php post_class($bpg_classes); ?> id="post-<?php the_ID(); ?>">

	<?php if ($bpg_flag) : ?>
	<span class="bpg-card__flag"><?php echo esc_html($bpg_flag); ?></span>
	<?php endif; ?>

	<a class="bpg-card__media<?php echo has_post_thumbnail() ? '' : ' bpg-card__media--empty'; ?>" href="<?php echo esc_url($bpg_permalink); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail(
				$bpg_image_size,
				array(
					'loading' => 'lazy',
					'decoding' => 'async',
					'alt' => esc_attr($bpg_title),
				)
			);
		}
		?>
	</a>

	<div class="bpg-card__body">

		<div class="bpg-card__top">
			<?php if ($bpg_category) : ?>
			<a class="bpg-chip" href="<?php echo esc_url(get_category_link($bpg_category->term_id)); ?>">
				<?php echo esc_html($bpg_category->name); ?>
			</a>
			<?php endif; ?>
			<span class="bpg-card__read"><?php echo esc_html(botphonic_blog_reading_time()); ?></span>
		</div>

		<h2 class="bpg-card__title">
			<a href="<?php echo esc_url($bpg_permalink); ?>" rel="bookmark"><?php echo esc_html($bpg_title); ?></a>
		</h2>

		<div class="bpg-card__foot">
			<?php echo get_avatar($bpg_author_id, 56, '', esc_attr($bpg_author), array('loading' => 'lazy')); ?>
			<span class="bpg-card__author"><?php echo esc_html($bpg_author); ?></span>
			<time class="bpg-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo esc_html(get_the_date('M j, Y')); ?>
			</time>
		</div>

	</div>

</article>
