<?php
/**
 * Integration page layout: every section in order.
 *
 * Loaded by page-{slug}.php with $args['page'] from botphonic_integration_boot().
 * A section is skipped when the page has no data for it.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) && is_array($args['page']) ? $args['page'] : botphonic_integration_page_data();
if (!$bpi_page) {
	return;
}

$bpi_sections = array(
	'hero',
	'facts',
	'steps',
	'capabilities',
	'use-cases',
	'data-table',
	'setup',
	'trust',
	'related',
	'faq',
	'cta',
);
?>
<div id="bp-integration" class="bp bp-integration bp-integration--<?php echo esc_attr($bpi_page['key']); ?> no-js">
	<main id="main" class="bpi-main">
		<?php
		foreach ($bpi_sections as $bpi_section) {
			get_template_part('template-parts/integrations/' . $bpi_section, null, array('page' => $bpi_page));
		}
		?>
	</main>
</div>
<?php
unset($bpi_page, $bpi_sections, $bpi_section);
