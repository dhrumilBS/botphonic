<?php
/**
 * Integration directory on the hub: every integration as a card, with a
 * category filter and a search box (assets/js/integration-pages.js).
 *
 * Without JavaScript every card is shown and the filter controls are hidden.
 * A category can be linked directly with its key as the hash, e.g. #calling.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_dir = isset($bpi_page['directory']) ? (array) $bpi_page['directory'] : array();
$bpi_items = botphonic_integration_directory();
if (!$bpi_items) {
	return;
}

$bpi_categories = botphonic_integration_directory_categories();
$bpi_counts = array_count_values(array_map('strval', wp_list_pluck($bpi_items, 'category')));
$bpi_total = count($bpi_items);
$bpi_arrow = botphonic_integration_icon('arrow', 16);
?>
<section id="all-integrations" class="bpi-section bpi-directory" aria-labelledby="bpi-dir-title" data-bpi-directory>
	<div class="bpi-wrap">
		<header class="bpi-heading">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_dir['kicker']) ? $bpi_dir['kicker'] : 'Integration directory'); ?></p>
			<h2 id="bpi-dir-title"><?php echo esc_html(!empty($bpi_dir['title']) ? $bpi_dir['title'] : 'Browse every Botphonic integration'); ?></h2>
			<?php if (!empty($bpi_dir['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_dir['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<div class="bpi-dir__controls">
			<div class="bpi-dir__search">
				<label class="screen-reader-text" for="bpi-dir-search">Search integrations</label>
				<span class="bpi-dir__search-icon" aria-hidden="true"><?php echo botphonic_integration_icon('search', 18); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
				<input type="search" id="bpi-dir-search" class="bpi-dir__input" placeholder="Search by name, for example Twilio" autocomplete="off" spellcheck="false" data-bpi-search>
			</div>
			<div class="bpi-dir__filters" role="group" aria-label="Filter integrations by category">
				<button type="button" class="bpi-chip" aria-pressed="true" data-bpi-filter="all">All<span class="bpi-chip__count"><?php echo esc_html((string) $bpi_total); ?><span class="screen-reader-text"> integrations</span></span></button>
				<?php foreach ($bpi_categories as $bpi_key => $bpi_category) : ?>
					<?php if (empty($bpi_counts[$bpi_key])) { continue; } ?>
				<button type="button" class="bpi-chip" aria-pressed="false" data-bpi-filter="<?php echo esc_attr($bpi_key); ?>" data-bpi-label="<?php echo esc_attr($bpi_category['label']); ?>"><?php echo esc_html($bpi_category['label']); ?><span class="bpi-chip__count"><?php echo esc_html((string) $bpi_counts[$bpi_key]); ?><span class="screen-reader-text"> integrations</span></span></button>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="bpi-dir__status" role="status" data-bpi-status><?php echo esc_html(sprintf('Showing all %d integrations', $bpi_total)); ?></p>

		<ul class="bpi-dir__grid">
			<?php
			foreach ($bpi_items as $bpi_item) {
				get_template_part('template-parts/integrations/card', null, array('item' => $bpi_item));
			}
			?>
		</ul>

		<div class="bpi-dir__empty" data-bpi-empty hidden>
			<p>No integration matches your search.</p>
			<button type="button" class="bpi-btn bpi-btn--outline" data-bpi-reset>Show all integrations</button>
		</div>

		<?php if (!empty($bpi_dir['more'])) : ?>
		<aside class="bpi-dir__more" aria-labelledby="bpi-dir-more-title">
			<span class="bpi-icon" aria-hidden="true"><?php echo botphonic_integration_icon('plug'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			<div class="bpi-stack bpi-dir__more-copy">
				<h3 id="bpi-dir-more-title"><?php echo esc_html($bpi_dir['more']['title']); ?></h3>
				<p><?php echo botphonic_integration_kses($bpi_dir['more']['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			</div>
			<?php if (!empty($bpi_dir['more']['links'])) : ?>
			<div class="bpi-actions">
				<?php foreach ((array) $bpi_dir['more']['links'] as $bpi_index => $bpi_link) : ?>
				<a class="bpi-btn <?php echo 0 === $bpi_index ? 'bpi-btn--ink' : 'bpi-btn--outline'; ?>" href="<?php echo esc_url($bpi_link['url']); ?>"><?php echo esc_html($bpi_link['label']); ?><?php echo 0 === $bpi_index ? $bpi_arrow : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</aside>
		<?php endif; ?>
	</div>
</section>
<?php
unset($bpi_page, $bpi_dir, $bpi_items, $bpi_categories, $bpi_counts, $bpi_total, $bpi_arrow, $bpi_key, $bpi_category, $bpi_item, $bpi_index, $bpi_link);
