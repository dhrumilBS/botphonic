<?php

/**
 * Search result card — one card for every content type search covers.
 *
 * Built on the blog card (.bpg-card, blog.css § 14) so hover, media zoom and
 * focus-within match the listings; search.css adds the type badge, the
 * highlighted snippet and the per-type fallback artwork. The type-specific
 * archive cards (content-altcard / content-storycard) are scoped to their own
 * archive body classes, which is why search has a card of its own.
 *
 * Accepted $args:
 *   snippet  bool  Optional. Print the matched-text excerpt. Default true.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bps_show_snippet = !isset($args['snippet']) || $args['snippet'];

$bps_types = botphonic_search_types();
$bps_key = botphonic_search_type_of();
$bps_type = isset($bps_types[$bps_key]) ? $bps_types[$bps_key] : reset($bps_types);
$bps_permalink = get_permalink();
$bps_title = get_the_title();
$bps_snippet = $bps_show_snippet ? botphonic_search_snippet() : '';
$bps_category = ('articles' === $bps_key && function_exists('botphonic_blog_primary_category')) ? botphonic_blog_primary_category() : null;
?>

<article <?php post_class(array('bpg-card', 'bps-card', 'bps-card--' . $bps_key)); ?> id="post-<?php the_ID(); ?>">

	<a class="bpg-card__media<?php echo has_post_thumbnail() ? '' : ' bps-card__media--empty'; ?>" href="<?php echo esc_url($bps_permalink); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail(
				'medium_large',
				array(
					'loading' => 'lazy',
					'decoding' => 'async',
					'alt' => '',
				)
			);
		} else {
			echo '<span class="bps-card__glyph">' . botphonic_search_icon($bps_type['icon']) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
		}
		?>
	</a>

	<div class="bpg-card__body">

		<div class="bpg-card__top">
			<span class="bps-type bps-type--<?php echo esc_attr($bps_key); ?>">
				<?php echo botphonic_search_icon($bps_type['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
				<?php echo esc_html($bps_type['singular']); ?>
			</span>
			<?php if ($bps_category) : ?>
			<a class="bpg-chip" href="<?php echo esc_url(get_category_link($bps_category->term_id)); ?>"><?php echo esc_html($bps_category->name); ?></a>
			<?php endif; ?>
		</div>

		<h2 class="bpg-card__title">
			<a href="<?php echo esc_url($bps_permalink); ?>" rel="bookmark"><?php echo botphonic_search_highlight($bps_title); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside. ?></a>
		</h2>

		<?php if ($bps_snippet) : ?>
		<p class="bps-card__snippet"><?php echo $bps_snippet; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside. ?></p>
		<?php endif; ?>

		<div class="bpg-card__foot">
			<?php if ('pages' === $bps_key) : ?>
			<?php
			// Path relative to the site root, so a subdirectory install reads the same as production.
			$bps_home_path = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);
			$bps_path = '/' . ltrim(substr((string) wp_parse_url($bps_permalink, PHP_URL_PATH), strlen(untrailingslashit($bps_home_path))), '/');
			?>
			<span class="bps-card__path"><?php echo esc_html(untrailingslashit($bps_path) ?: '/'); ?></span>
			<?php else : ?>
			<time class="bps-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
			<?php if ('articles' === $bps_key && function_exists('botphonic_blog_reading_time')) : ?>
			<span class="bps-card__dot" aria-hidden="true"></span>
			<span><?php echo esc_html(botphonic_blog_reading_time()); ?></span>
			<?php endif; ?>
			<?php endif; ?>
			<span class="bps-card__more" aria-hidden="true">
				<?php echo esc_html($bps_type['cta']); ?>
				<?php echo botphonic_search_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
			</span>
		</div>

	</div>

</article>
