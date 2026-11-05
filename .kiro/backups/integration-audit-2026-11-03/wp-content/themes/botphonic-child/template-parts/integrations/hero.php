<?php
/**
 * Integration page hero: H1, sign-up form and the example visual.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_hero = isset($bpi_page['hero']) ? (array) $bpi_page['hero'] : array();
if (empty($bpi_hero['title'])) {
	return;
}

$bpi_check = botphonic_integration_icon('check', 14);
$bpi_arrow = botphonic_integration_icon('arrow', 16);
$bpi_form = shortcode_exists('contact-form-7') ? do_shortcode(BOTPHONIC_SIGNUP_FORM) : '';
$bpi_visual = isset($bpi_page['visual']['type']) ? sanitize_key($bpi_page['visual']['type']) : 'flow';

$bpi_points_label = !empty($bpi_hero['points_label']) ? $bpi_hero['points_label'] : sprintf('What the %s does', $bpi_page['label']);

// Extra bottom padding only when the facts card (facts.php) overlaps the hero.
$bpi_overlap = !empty($bpi_page['facts']['items']) && (empty($bpi_page['sections']) || in_array('facts', (array) $bpi_page['sections'], true));
?>
<section id="top" class="bpi-section bpi-section--dark bpi-hero<?php echo $bpi_overlap ? ' bpi-hero--overlap' : ''; ?>" aria-labelledby="bpi-hero-title">
	<div class="bpi-wrap">
		<div class="bpi-grid bpi-grid--hero">
			<div class="bpi-stack bpi-stack--lg bpi-hero__copy">
				<?php if (!empty($bpi_hero['kicker'])) : ?>
				<p class="bpi-kicker bpi-kicker--dark"><?php echo esc_html($bpi_hero['kicker']); ?></p>
				<?php endif; ?>

				<h1 id="bpi-hero-title"><?php echo esc_html($bpi_hero['title']); ?></h1>

				<?php if (!empty($bpi_hero['lede'])) : ?>
				<p class="bpi-hero__lede"><?php echo botphonic_integration_kses($bpi_hero['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
				<?php endif; ?>

				<?php if (!empty($bpi_hero['points'])) : ?>
				<ul class="bpi-points" aria-label="<?php echo esc_attr($bpi_points_label); ?>">
					<?php foreach ((array) $bpi_hero['points'] as $bpi_point) : ?>
					<li><span aria-hidden="true"><?php echo $bpi_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><?php echo esc_html($bpi_point); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<div id="start" class="bpi-signup" role="region" aria-labelledby="bpi-signup-title">
					<p id="bpi-signup-title" class="bpi-signup__title">Start your 14-day free trial</p>
					<?php if ('' !== trim($bpi_form)) : ?>
						<?php echo $bpi_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 output. ?>
					<?php else : ?>
					<a class="bpi-btn bpi-btn--coral" href="<?php echo esc_url($bpi_page['register_url']); ?>">Start 14-day free trial<?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
					<?php endif; ?>
					<p class="bpi-signup__note">20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime</p>
				</div>

				<p class="bpi-hero__links">
					<a class="bpi-text-link bpi-text-link--inverse" href="<?php echo esc_url($bpi_page['demo_url']); ?>">Book a 20-minute demo</a>
					<?php if (!empty($bpi_hero['guide']['url'])) : ?>
					<a class="bpi-text-link bpi-text-link--inverse" href="<?php echo esc_url($bpi_hero['guide']['url']); ?>"><?php echo esc_html($bpi_hero['guide']['label']); ?></a>
					<?php endif; ?>
				</p>

				<ul class="bpi-proof" aria-label="Botphonic ratings and customer count">
					<li><span class="bpi-stars" aria-hidden="true">★★★★★</span> <strong>5.0</strong> on G2</li>
					<li><strong>4.8</strong> on Capterra</li>
					<li><strong>500+</strong> businesses</li>
				</ul>
			</div>

			<?php get_template_part('template-parts/integrations/visual', $bpi_visual, array('page' => $bpi_page)); ?>
		</div>
	</div>
</section>
<?php
unset($bpi_page, $bpi_hero, $bpi_check, $bpi_arrow, $bpi_form, $bpi_visual, $bpi_points_label, $bpi_overlap, $bpi_point);
