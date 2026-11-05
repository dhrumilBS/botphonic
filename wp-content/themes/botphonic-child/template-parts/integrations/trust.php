<?php
/**
 * Security and compliance band. The shared points follow
 * docs.botphonic.ai/docs/legal/security-compliance; pages can add their own.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_trust = isset($bpi_page['trust']) ? (array) $bpi_page['trust'] : array();

$bpi_items = array_merge(
	isset($bpi_trust['extra']) ? (array) $bpi_trust['extra'] : array(),
	array(
		array('title' => 'Encrypted in transit and at rest', 'text' => 'TLS 1.2 or 1.3 on every connection, AES-256 for recordings, transcripts and contact data.'),
		array('title' => 'HIPAA and GDPR', 'text' => 'HIPAA for healthcare on Enterprise with a signed BAA. GDPR for EU and EEA customers, with a DPA on request.'),
		array('title' => 'PCI DSS', 'text' => 'Card details are handled under PCI DSS controls.'),
		array('title' => 'Access you control', 'text' => 'Role-based access and MFA available to every user, plus SAML SSO and IP allowlisting on Enterprise.'),
	)
);

$bpi_check = botphonic_integration_icon('check', 14);
?>
<section id="security" class="bpi-section bpi-section--dark" aria-labelledby="bpi-trust-title">
	<div class="bpi-wrap bpi-grid bpi-grid--trust">
		<div class="bpi-stack">
			<p class="bpi-kicker bpi-kicker--dark">Security &amp; compliance</p>
			<h2 id="bpi-trust-title"><?php echo esc_html(!empty($bpi_trust['title']) ? $bpi_trust['title'] : 'Your customer data stays protected'); ?></h2>
			<p class="bpi-lede bpi-lede--inverse"><?php echo botphonic_integration_kses(!empty($bpi_trust['lede']) ? $bpi_trust['lede'] : 'Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<a class="bpi-text-link bpi-text-link--inverse" href="<?php echo esc_url(home_url('/security/')); ?>">Read the security overview</a>
		</div>
		<ul class="bpi-trust">
			<?php foreach ($bpi_items as $bpi_item) : ?>
			<li data-bpi-reveal>
				<span class="bpi-trust__icon" aria-hidden="true"><?php echo $bpi_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
				<span><strong><?php echo esc_html($bpi_item['title']); ?></strong><?php echo esc_html($bpi_item['text']); ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php
unset($bpi_page, $bpi_trust, $bpi_items, $bpi_item, $bpi_check);
