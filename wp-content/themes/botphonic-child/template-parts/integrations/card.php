<?php
/**
 * One integration card, used by the hub directory and by "More integrations"
 * on each integration page.
 *
 * $args['item'] is an entry of botphonic_integration_directory(). The whole
 * card is clickable through its link, which CSS stretches over the card.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_item = isset($args['item']) ? (array) $args['item'] : array();
if (empty($bpi_item['name']) || empty($bpi_item['url'])) {
	return;
}

$bpi_categories = botphonic_integration_directory_categories();
$bpi_category = isset($bpi_categories[$bpi_item['category']]) ? $bpi_categories[$bpi_item['category']]['card'] : '';
$bpi_logo_kind = !empty($bpi_item['mark']) ? 'mark' : (!empty($bpi_item['logo']) ? 'image' : 'icon');
$bpi_tone = in_array($bpi_item['tone'] ?? '', array('native', 'api', 'dev'), true) ? $bpi_item['tone'] : 'api';
$bpi_external = !empty($bpi_item['external']);
$bpi_host = $bpi_external ? (string) wp_parse_url($bpi_item['url'], PHP_URL_HOST) : '';
$bpi_search = implode(' ', array($bpi_item['name'], $bpi_category, $bpi_item['badge'] ?? '', $bpi_item['keywords'] ?? ''));

// Keep the last word and the arrow together when the label wraps.
$bpi_cta = trim((string) ($bpi_item['cta'] ?? ''));
$bpi_cut = strrpos($bpi_cta, ' ');
$bpi_cta_lead = false === $bpi_cut ? '' : substr($bpi_cta, 0, $bpi_cut + 1);
$bpi_cta_last = false === $bpi_cut ? $bpi_cta : substr($bpi_cta, $bpi_cut + 1);
?>
<li class="bpi-card bpi-int-card" data-bpi-item data-category="<?php echo esc_attr($bpi_item['category']); ?>" data-search="<?php echo esc_attr(strtolower($bpi_search)); ?>">
	<div class="bpi-int-card__head">
		<span class="bpi-int-card__logo bpi-int-card__logo--<?php echo esc_attr($bpi_logo_kind); ?>"><?php echo botphonic_integration_item_logo($bpi_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?></span>
		<div class="bpi-int-card__titles">
			<h3 class="bpi-int-card__title"><?php echo esc_html($bpi_item['name']); ?></h3>
			<p class="bpi-int-card__meta">
				<?php if ('' !== $bpi_category) : ?>
				<span class="bpi-int-card__cat"><?php echo esc_html($bpi_category); ?></span>
				<?php endif; ?>
				<?php if (!empty($bpi_item['badge'])) : ?>
				<span class="bpi-badge bpi-badge--<?php echo esc_attr($bpi_tone); ?>"><?php echo esc_html($bpi_item['badge']); ?></span>
				<?php endif; ?>
			</p>
		</div>
	</div>
	<?php if (!empty($bpi_item['summary'])) : ?>
	<p class="bpi-int-card__text"><?php echo esc_html($bpi_item['summary']); ?></p>
	<?php endif; ?>
	<a class="bpi-int-card__link" href="<?php echo esc_url($bpi_item['url']); ?>"><?php echo esc_html($bpi_cta_lead); ?><span class="bpi-int-card__nowrap"><?php echo esc_html($bpi_cta_last); ?><?php if ('' !== $bpi_host) : ?><span class="screen-reader-text"> on <?php echo esc_html($bpi_host); ?></span><?php endif; ?><?php echo botphonic_integration_icon($bpi_external ? 'external' : 'arrow', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span></a>
</li>
<?php
unset($bpi_item, $bpi_categories, $bpi_category, $bpi_logo_kind, $bpi_tone, $bpi_external, $bpi_host, $bpi_search, $bpi_cta, $bpi_cut, $bpi_cta_lead, $bpi_cta_last);
