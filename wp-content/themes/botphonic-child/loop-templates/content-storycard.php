<?php

/**
 * Customer story card — used by archive-success-stories.php and by the
 * "More customer stories" rail on single-success-stories.php.
 *
 * Built on the blog card (.bpg-card, blog.css § 14) so hover, media zoom,
 * focus-within and the featured grid-span all behave identically; the
 * story-specific part is the row of result chips, styled in
 * success-stories.css § 9.
 *
 * Deliberately terse: the headline plus the numbers carry the card, so there is
 * no sub-heading and no summary paragraph. The story itself is one click away.
 *
 * Accepted $args:
 *   variant  'default' | 'featured'  Featured spans the grid on >=768px.
 *   flag     string                  Optional ribbon label, e.g. "Latest".
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$story_variant = isset($args['variant']) ? $args['variant'] : 'default';
$story_flag = isset($args['flag']) ? $args['flag'] : '';
$story_featured = ('featured' === $story_variant);

$story_permalink = get_permalink();
$story_title = get_the_title();
$story_badge = botphonic_story_text('badge_label');
$story_stats = botphonic_story_stats(null, $story_featured ? 3 : 2);
$story_image_size = $story_featured ? 'large' : 'medium_large';

$story_classes = array('bpg-card', 'bpg-story-card');
if ($story_featured) {
	$story_classes[] = 'bpg-card--featured';
}
?>

<article <?php post_class($story_classes); ?> id="post-<?php the_ID(); ?>">

	<?php if ($story_flag) : ?>
	<span class="bpg-card__flag"><?php echo esc_html($story_flag); ?></span>
	<?php endif; ?>

	<?php
	// Decorative duplicate of the title link: taken out of the tab order and
	// hidden from assistive tech, so the card exposes exactly one link to its
	// story (the heading) plus the "Read story" affordance.
	?>
	<a class="bpg-card__media<?php echo has_post_thumbnail() ? '' : ' bpg-card__media--empty'; ?>" href="<?php echo esc_url($story_permalink); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail(
				$story_image_size,
				array(
					'loading' => $story_featured ? 'eager' : 'lazy',
					'decoding' => 'async',
					'alt' => esc_attr($story_title),
				)
			);
		}
		?>
	</a>

	<div class="bpg-card__body">

		<div class="bpg-card__top">
			<?php if ($story_badge) : ?>
			<span class="bpg-chip"><?php echo esc_html($story_badge); ?></span>
			<?php endif; ?>
			<span class="bpg-card__read">Customer story</span>
		</div>

		<h2 class="bpg-card__title">
			<a href="<?php echo esc_url($story_permalink); ?>" rel="bookmark"><?php echo esc_html($story_title); ?></a>
		</h2>

		<?php if ($story_stats) : ?>
		<ul class="bpg-story-card__stats">
			<?php foreach ($story_stats as $story_stat) : ?>
			<li class="bpg-story-card__stat">
				<?php
				echo esc_html($story_stat['prefix'] . $story_stat['value'] . $story_stat['suffix']);

				if ($story_stat['label']) {
					echo ' <span>' . esc_html($story_stat['label']) . '</span>';
				}
				?>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<div class="bpg-card__foot">
			<time class="bpg-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo esc_html(get_the_date('M j, Y')); ?>
			</time>
			<a class="bpg-story-card__more" href="<?php echo esc_url($story_permalink); ?>" tabindex="-1" aria-hidden="true">
				Read story
				<?php echo botphonic_story_icon('arrow-right'); ?>
			</a>
		</div>

	</div>

</article>
