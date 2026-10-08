<?php

/**
 * Table of contents — the one card every long-form single view uses.
 *
 * single.php, single-success-stories.php and single-alternatives.php each used
 * to carry their own copy of this markup, and the comparison guide carried it
 * twice: once for the sidebar and once for a second, phone-only card. Four
 * copies of the same card, drifting apart with each edit, is what this replaces.
 *
 * The card is styled by assets/css/blog.css §§ 5.1 / 17 and driven by
 * assets/js/toc.js, both shared by the same three views. Nothing here is
 * view-specific: a caller passes its entries, its wording and its ids.
 *
 * Accepted $args:
 *   'items'       array  Entries, each array('id' => string, 'label' => string,
 *                        'level' => int). 'title' is accepted in place of
 *                        'label', so botphonic_story_sections() can be passed
 *                        straight in. 'level' is only read when 'sub_level' is
 *                        set.
 *   'html'        string Pre-rendered list markup, for a view whose entries are
 *                        not a PHP array — the blog's, which only exist as
 *                        lwptoc plugin output. Ignored when 'items' is given.
 *   'title'       string Toggle label. Default 'On this page'.
 *   'aria_label'  string Accessible name for the <nav>. Defaults to 'title'.
 *   'close_label' string Accessible name for the unpin button.
 *   'nav_id'      string id of the scrolling region, for aria-controls.
 *   'class'       string Extra class on the card.
 *   'sub_level'   int    Entries at this level get the indented modifier.
 *                        0 (default) indents nothing.
 *
 * The three icons come from botphonic_blog_icon(). That is not a blog
 * dependency in disguise: botphonic_story_icon() and botphonic_alt_icon() both
 * fall through to it for any name they do not define themselves, and neither
 * defines list, chevron-down or close — so all three views were already drawing
 * these exact three glyphs from that one function.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpg_toc = wp_parse_args(
	(isset($args) && is_array($args)) ? $args : array(),
	array(
		'items' => array(),
		'html' => '',
		'title' => __('On this page', 'botphonic'),
		'aria_label' => '',
		'close_label' => __('Unpin the table of contents', 'botphonic'),
		'nav_id' => 'bpg-toc-nav',
		'class' => '',
		'sub_level' => 0,
	)
);

$bpg_toc_items = is_array($bpg_toc['items']) ? $bpg_toc['items'] : array();
$bpg_toc_html = trim((string) $bpg_toc['html']);
$bpg_toc_sub = (int) $bpg_toc['sub_level'];

/*
 * Nothing to link to: print nothing rather than an empty card. The callers with
 * an entries array guard on their own count as well, because "one section" is a
 * per-view policy question rather than an empty list. assets/js/toc.js repeats
 * this check client-side for the blog, whose entries cannot be counted until the
 * plugin has rendered them.
 */
if (!$bpg_toc_items && '' === $bpg_toc_html) {
	return;
}

$bpg_toc_aria = ('' !== $bpg_toc['aria_label']) ? $bpg_toc['aria_label'] : $bpg_toc['title'];
?>

<nav class="<?php echo esc_attr(trim('bpg-toc ' . $bpg_toc['class'])); ?>" data-bpg-toc aria-label="<?php echo esc_attr($bpg_toc_aria); ?>">

	<button class="bpg-toc__toggle" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr($bpg_toc['nav_id']); ?>">
		<?php echo botphonic_blog_icon('list'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
		<span><?php echo esc_html($bpg_toc['title']); ?></span>
		<span class="bpg-toc__chev"><?php echo botphonic_blog_icon('chevron-down'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
	</button>

	<?php
	/*
	 * Unpins the phone card, dropping it back into the flow. Hidden until
	 * assets/js/toc.js marks the card ready, so it is never shown as a dead
	 * control, and hidden above 768px where the card is not pinned to begin
	 * with (blog.css § 17).
	 */
	?>
	<button class="bpg-toc__close" type="button" data-bpg-toc-close aria-label="<?php echo esc_attr($bpg_toc['close_label']); ?>">
		<?php echo botphonic_blog_icon('close'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
	</button>

	<div class="bpg-toc__nav" id="<?php echo esc_attr($bpg_toc['nav_id']); ?>">
		<?php if ($bpg_toc_items) : ?>
		<ul class="bpg-toc__list">
			<?php
			foreach ($bpg_toc_items as $bpg_toc_item) :
				$bpg_toc_id = isset($bpg_toc_item['id']) ? (string) $bpg_toc_item['id'] : '';

				if ('' === $bpg_toc_id) {
					continue;
				}

				// 'label' is the contract; 'title' is what the story blueprint calls it.
				if (isset($bpg_toc_item['label'])) {
					$bpg_toc_label = (string) $bpg_toc_item['label'];
				} elseif (isset($bpg_toc_item['title'])) {
					$bpg_toc_label = (string) $bpg_toc_item['title'];
				} else {
					$bpg_toc_label = '';
				}

				$bpg_toc_level = isset($bpg_toc_item['level']) ? (int) $bpg_toc_item['level'] : 0;
				$bpg_toc_is_sub = ($bpg_toc_sub && $bpg_toc_sub === $bpg_toc_level);
			?>
			<li>
				<a class="bpg-toc__link<?php echo $bpg_toc_is_sub ? ' bpg-toc__link--sub' : ''; ?>" href="#<?php echo esc_attr($bpg_toc_id); ?>">
					<?php echo esc_html($bpg_toc_label); ?>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php else : ?>
		<?php echo $bpg_toc_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output. ?>
		<?php endif; ?>
	</div>

</nav>
