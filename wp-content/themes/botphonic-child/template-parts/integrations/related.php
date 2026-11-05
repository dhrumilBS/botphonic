<?php
/**
 * Links to the other integration pages and related guides, as the same cards
 * the hub directory uses (template-parts/integrations/card.php).
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_related = isset($bpi_page['related']) ? (array) $bpi_page['related'] : array();
$bpi_others = array();

foreach (botphonic_integration_directory() as $bpi_item) {
	if (!empty($bpi_item['page']) && $bpi_item['page'] !== $bpi_page['key']) {
		$bpi_others[] = $bpi_item;
	}
}

if (!$bpi_others) {
	return;
}
?>
<section id="more-integrations" class="bpi-section" aria-labelledby="bpi-related-title">
	<div class="bpi-wrap">
		<header class="bpi-heading">
			<p class="bpi-kicker">More integrations</p>
			<h2 id="bpi-related-title"><?php echo esc_html(!empty($bpi_related['title']) ? $bpi_related['title'] : 'Connect Botphonic to the rest of your stack'); ?></h2>
			<?php if (!empty($bpi_related['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_related['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<ul class="bpi-related">
			<?php
			foreach ($bpi_others as $bpi_item) {
				get_template_part('template-parts/integrations/card', null, array('item' => $bpi_item));
			}
			?>
		</ul>

		<p class="bpi-more">
			<a class="bpi-text-link" href="<?php echo esc_url($bpi_page['hub_url']); ?>">See all integrations</a>
			<?php foreach ((array) ($bpi_related['links'] ?? array()) as $bpi_link) : ?>
			<a class="bpi-text-link" href="<?php echo esc_url($bpi_link['url']); ?>"><?php echo esc_html($bpi_link['label']); ?></a>
			<?php endforeach; ?>
		</p>
	</div>
</section>
<?php
unset($bpi_page, $bpi_related, $bpi_others, $bpi_item, $bpi_link);
