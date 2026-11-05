<?php
/**
 * Key facts band that overlaps the bottom of the hero.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_facts = isset($bpi_page['facts']) ? (array) $bpi_page['facts'] : array();
if (empty($bpi_facts['items'])) {
	return;
}

$bpi_title = !empty($bpi_facts['title']) ? $bpi_facts['title'] : sprintf('%s at a glance', $bpi_page['label']);
?>
<section class="bpi-facts-section" aria-labelledby="bpi-facts-title">
	<div class="bpi-wrap">
		<h2 id="bpi-facts-title" class="screen-reader-text"><?php echo esc_html($bpi_title); ?></h2>
		<dl class="bpi-facts">
			<?php foreach ((array) $bpi_facts['items'] as $bpi_fact) : ?>
			<div class="bpi-fact" data-bpi-reveal>
				<dt><?php echo esc_html($bpi_fact['label']); ?></dt>
				<dd><?php echo esc_html($bpi_fact['value']); ?></dd>
			</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
<?php
unset($bpi_page, $bpi_facts, $bpi_title, $bpi_fact);
