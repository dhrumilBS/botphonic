<?php
/**
 * Links to the other integration pages and related guides.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_related = isset($bpi_page['related']) ? (array) $bpi_page['related'] : array();
$bpi_others = array();

foreach (botphonic_integration_registry() as $bpi_key => $bpi_item) {
	if ($bpi_key !== $bpi_page['key'] && !empty($bpi_item['slug'])) {
		$bpi_others[$bpi_key] = $bpi_item;
	}
}

if (!$bpi_others) {
	return;
}

$bpi_arrow = botphonic_integration_icon('arrow', 16);
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
			<?php foreach ($bpi_others as $bpi_key => $bpi_item) : ?>
			<li class="bpi-card bpi-related__card" data-bpi-reveal>
				<span class="bpi-related__logo"><?php echo botphonic_integration_logo($bpi_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?></span>
				<h3><a class="bpi-related__link" href="<?php echo esc_url(home_url('/integrations/' . $bpi_item['slug'] . '/')); ?>"><?php echo esc_html($bpi_item['label']); ?></a></h3>
				<p><?php echo esc_html($bpi_item['summary']); ?></p>
				<span class="bpi-related__cta" aria-hidden="true">Learn more<?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			</li>
			<?php endforeach; ?>
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
unset($bpi_page, $bpi_related, $bpi_others, $bpi_key, $bpi_item, $bpi_arrow, $bpi_link);
