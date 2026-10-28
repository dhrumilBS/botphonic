<?php

/**
 * The search form.
 *
 * Used by the blog listings' hero and by search.php. Keeps the Understrap
 * class names (.search-form / .input-group / .search-field / .search-submit)
 * that blog.css § 15 styles, and adds the hooks assets/js/search.js enhances
 * into a live-suggestion combobox. Without JavaScript it is a plain GET form.
 *
 * Accepted $args:
 *   aria_label   string  Optional landmark label.
 *   type         string  Optional search type tab to keep (search.php only).
 *   orderby      string  Optional sort to keep (search.php only).
 *   placeholder  string  Optional placeholder override.
 *   autofocus    bool    Optional. Focus the field on load.
 *
 * @package Botphonic
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$uid         = wp_unique_id('s-'); // The search form specific unique ID for the input.
$type        = isset($args['type']) ? sanitize_key($args['type']) : '';
$orderby     = isset($args['orderby']) && 'date' === $args['orderby'] ? 'date' : '';
$placeholder = ! empty($args['placeholder']) ? $args['placeholder'] : 'Search articles, comparisons, customer stories…';
$autofocus   = ! empty($args['autofocus']);
$has_icons   = function_exists('botphonic_search_icon');

$aria_label = '';
if (isset($args['aria_label']) && ! empty($args['aria_label'])) {
	$aria_label = 'aria-label="' . esc_attr($args['aria_label']) . '"';
}
?>

<form role="search" class="search-form bps-form" method="get" action="<?php echo esc_url(home_url('/')); ?>" data-bps-suggest <?php echo $aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. 
																																?>>
	<label class="screen-reader-text" for="<?php echo esc_attr($uid); ?>"><?php echo esc_html_x('Search for:', 'label', 'understrap'); ?></label>
	<div class="input-group">
		<?php if ($has_icons) : ?>
			<span class="bps-form__icon"><?php echo botphonic_search_icon('search'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
											?></span>
		<?php endif; ?>
		<input
			type="search"
			class="field search-field form-control"
			id="<?php echo esc_attr($uid); ?>"
			name="s"
			value="<?php the_search_query(); ?>"
			placeholder="<?php echo esc_attr($placeholder); ?>"
			autocomplete="off"
			spellcheck="false"
			enterkeyhint="search"
			role="combobox"
			aria-autocomplete="list"
			aria-expanded="false"
			aria-controls="<?php echo esc_attr($uid); ?>-list"
			<?php echo $autofocus ? 'autofocus' : ''; ?>>
		<?php if ($has_icons) : ?>
			<button type="button" class="bps-form__clear" data-bps-clear hidden aria-label="Clear search">
				<?php echo botphonic_search_icon('close'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
				?>
			</button>
			<kbd class="bps-form__kbd" aria-hidden="true">/</kbd>
		<?php endif; ?>
		<span class="input-group-append">
			<input type="submit" class="submit search-submit btn btn-primary" value="<?php echo esc_attr_x('Search', 'submit button', 'understrap'); ?>">
		</span>
	</div>
	<?php if ($type) : ?>
		<input type="hidden" name="type" value="<?php echo esc_attr($type); ?>">
	<?php endif; ?>
	<?php if ($orderby) : ?>
		<input type="hidden" name="orderby" value="<?php echo esc_attr($orderby); ?>">
	<?php endif; ?>
	<div class="bps-suggest" id="<?php echo esc_attr($uid); ?>-list" role="listbox" aria-label="Suggestions" hidden></div>
	<p class="screen-reader-text" aria-live="polite" data-bps-status></p>
</form>