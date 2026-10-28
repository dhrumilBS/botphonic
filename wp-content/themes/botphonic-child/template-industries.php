<?php
/**
 * Template Name: Industries Hub
 * Template Post Type: page
 *
 * Lists every published child page of the page it is assigned to (the Industry
 * parent page). Known industries get a curated icon, line and tasks; any new
 * child page still appears, using its Yoast meta description.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_register = 'https://app.botphonic.ai/register/';
$bpi_demo = 'https://calendly.com/contact-botphonic/product-discovery';

$bpi_groups = array(
	'health' => 'Health & education',
	'sales' => 'Sales & property',
	'finance' => 'Finance & insurance',
	'service' => 'Service & operations',
);

// Curated card content by page slug, in display order. Copy comes from each page's own description.
$bpi_known = array(
	'healthcare' => array('group' => 'health', 'icon' => 'heart', 'line' => 'Answer every patient call, day or night.', 'tasks' => array('Books appointments', 'Verifies insurance', 'HIPAA-ready')),
	'real-estate' => array('group' => 'sales', 'icon' => 'home', 'line' => 'Never miss a buyer or seller inquiry.', 'tasks' => array('Answers listing calls', 'Qualifies buyers', 'Books showings')),
	'finance' => array('group' => 'finance', 'icon' => 'bank', 'line' => 'Client calls handled with compliance built in.', 'tasks' => array('Handles client calls', 'Flags fraud', 'PCI DSS workflows')),
	'insurance' => array('group' => 'finance', 'icon' => 'shield', 'line' => 'Faster claims and policy answers, 24/7.', 'tasks' => array('Speeds up claims', 'Detects fraud', 'Answers policy questions')),
	'bpo-customer-service' => array('group' => 'service', 'icon' => 'headset', 'line' => 'Absorb call peaks without adding seats.', 'tasks' => array('Handles routine inquiries', 'Routes complex calls', 'Qualifies leads')),
	'home-services' => array('group' => 'service', 'icon' => 'wrench', 'line' => 'Book jobs while your crew is on site.', 'tasks' => array('Answers 24/7', 'Books jobs instantly', 'Routes to the right technician')),
	'car-dealerships' => array('group' => 'sales', 'icon' => 'car', 'line' => 'Turn every inquiry into a showroom visit.', 'tasks' => array('Handles sales inquiries', 'Schedules test drives', 'Follows up with customers')),
	'solar' => array('group' => 'sales', 'icon' => 'sun', 'line' => 'Qualify homeowners while interest is high.', 'tasks' => array('Qualifies leads', 'Schedules site visits', 'Calculates ROI on the call')),
	'travel-and-hospitality' => array('group' => 'service', 'icon' => 'plane', 'line' => 'Guest calls answered in their language.', 'tasks' => array('Handles bookings', 'Manages cancellations', '20+ languages')),
	'education' => array('group' => 'health', 'icon' => 'cap', 'line' => 'Take admin calls off your staff’s desks.', 'tasks' => array('Answers school calls', 'Counsels students', 'FERPA-ready')),
	'recruitment' => array('group' => 'sales', 'icon' => 'users', 'line' => 'Screen candidates without the phone tag.', 'tasks' => array('Screens candidates', 'Books interviews', 'Cuts hiring time')),
	'agency' => array('group' => 'sales', 'icon' => 'briefcase', 'line' => 'Offer AI calling to every client you serve.', 'tasks' => array('Books discovery calls', 'Qualifies leads', 'Handles client inquiries')),
	'logistic' => array('group' => 'service', 'icon' => 'truck', 'label' => 'Logistics', 'line' => 'Keep shippers and drivers updated on every call.', 'tasks' => array('Shares status updates', 'Streamlines operations', 'Keeps clients informed')),
);

$bpi_icons = array(
	'heart' => '<path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7z"/><path d="M3.2 12h4.3l1.5-3 3 6 1.5-3h4.3"/>',
	'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/>',
	'bank' => '<path d="M3 21h18"/><path d="M3 10h18"/><path d="m12 3 9 7H3z"/><path d="M6 10v8M10 10v8M14 10v8M18 10v8"/>',
	'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
	'headset' => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
	'wrench' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9l-3.8 3.8z"/>',
	'car' => '<path d="M5 17h14v-5l-2-5H7l-2 5z"/><path d="M3 12h18"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
	'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
	'plane' => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
	'cap' => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
	'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
	'briefcase' => '<rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
	'truck' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62L18.3 8.38A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
	'building' => '<rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
);

$bpi_features = array(
	array('icon' => 'headset', 'title' => 'Answers every call', 'text' => 'Picks up on the first ring in under 300 ms, day or night, and takes several calls at once.'),
	array('icon' => 'users', 'title' => 'Hands off to your team', 'text' => 'Urgent, complex or frustrated callers reach a person, with the full context of the call.'),
	array('icon' => 'briefcase', 'title' => 'Works in your tools', 'text' => 'Books into your calendar and updates Salesforce, HubSpot, Zoho and 100+ tools via API or Zapier.'),
	array('icon' => 'shield', 'title' => 'Built for regulated calls', 'text' => 'HIPAA-ready, PCI DSS payments, SOC 2 Type II ready controls and TCPA outbound rules.'),
);

$bpi_icon = static function ($name, $size = 24) use ($bpi_icons) {
	$paths = isset($bpi_icons[$name]) ? $bpi_icons[$name] : $bpi_icons['building'];
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths . '</svg>';
};
$bpi_arrow = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';

// Published child pages, curated ones first in the order above, then any new ones by menu order.
$bpi_children = get_pages(array(
	'parent' => get_the_ID(),
	'hierarchical' => false,
	'post_status' => 'publish',
	'sort_column' => 'menu_order,post_title',
));
$bpi_order = array_flip(array_keys($bpi_known));
usort($bpi_children, static function ($a, $b) use ($bpi_order) {
	$ia = isset($bpi_order[$a->post_name]) ? $bpi_order[$a->post_name] : PHP_INT_MAX;
	$ib = isset($bpi_order[$b->post_name]) ? $bpi_order[$b->post_name] : PHP_INT_MAX;
	return $ia === $ib ? $a->menu_order <=> $b->menu_order : $ia <=> $ib;
});

$bpi_cards = array();
foreach ($bpi_children as $bpi_page) {
	$bpi_data = isset($bpi_known[$bpi_page->post_name]) ? $bpi_known[$bpi_page->post_name] : array();
	$bpi_line = !empty($bpi_data['line']) ? $bpi_data['line'] : (string) get_post_meta($bpi_page->ID, '_yoast_wpseo_metadesc', true);
	if ('' === $bpi_line) {
		$bpi_line = wp_trim_words(wp_strip_all_tags(get_the_excerpt($bpi_page)), 18);
	}
	$bpi_cards[] = array(
		'url' => get_permalink($bpi_page),
		'title' => !empty($bpi_data['label']) ? $bpi_data['label'] : get_the_title($bpi_page),
		'group' => !empty($bpi_data['group']) ? $bpi_data['group'] : '',
		'icon' => !empty($bpi_data['icon']) ? $bpi_data['icon'] : 'building',
		'line' => $bpi_line,
		'tasks' => !empty($bpi_data['tasks']) ? $bpi_data['tasks'] : array(),
	);
}
$bpi_used_groups = array_unique(array_filter(wp_list_pluck($bpi_cards, 'group')));

get_header();
?>

<div id="bp-industries" class="bpi no-js">
	<main>
		<section class="bpi-hero" aria-labelledby="bpi-title">
			<div class="bpi-wrap">
				<nav class="bpi-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<span aria-current="page">Industries</span>
				</nav>
				<p class="bpi-kicker bpi-kicker--dark">Industries</p>
				<h1 id="bpi-title">AI voice agents built for the way your industry takes calls</h1>
				<p class="bpi-lede">Botphonic answers, books and follows up on calls for <?php echo esc_html(number_format_i18n(count($bpi_cards))); ?> industries, from clinics and dealerships to contact centers. Pick yours to see what the agent does on a real call.</p>
				<div class="bpi-actions">
					<a class="bpi-btn bpi-btn--coral" href="<?php echo esc_url($bpi_register); ?>">Start 14-day free trial<?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
					<a class="bpi-btn bpi-btn--ghost" href="<?php echo esc_url($bpi_demo); ?>">Book a 20-minute demo</a>
				</div>
				<ul class="bpi-proof" aria-label="Botphonic at a glance">
					<li><strong>500+</strong> businesses</li>
					<li><strong>50+</strong> languages</li>
					<li><strong>&lt;300 ms</strong> response time</li>
					<li><strong>24/7</strong> answering</li>
				</ul>
			</div>
		</section>

		<section id="all-industries" class="bpi-section bpi-list" aria-labelledby="bpi-list-title">
			<div class="bpi-wrap">
				<div class="bpi-list__head">
					<div>
						<p class="bpi-kicker">All industries</p>
						<h2 id="bpi-list-title">Choose your industry</h2>
					</div>
					<?php if (count($bpi_used_groups) > 1) : ?>
						<div class="bpi-filters" role="group" aria-label="Filter industries" data-bpi-filters>
							<button type="button" class="bpi-filter" data-bpi-filter="all" aria-pressed="true">All</button>
							<?php foreach ($bpi_groups as $bpi_group_id => $bpi_group_label) : ?>
								<?php if (in_array($bpi_group_id, $bpi_used_groups, true)) : ?>
									<button type="button" class="bpi-filter" data-bpi-filter="<?php echo esc_attr($bpi_group_id); ?>" aria-pressed="false"><?php echo esc_html($bpi_group_label); ?></button>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<p class="bpi-count" aria-live="polite" data-bpi-count><?php echo esc_html(sprintf(_n('%s industry', '%s industries', count($bpi_cards), 'botphonic'), number_format_i18n(count($bpi_cards)))); ?></p>

				<ul class="bpi-grid">
					<?php foreach ($bpi_cards as $bpi_card) : ?>
						<li class="bpi-grid__item" data-bpi-group="<?php echo esc_attr($bpi_card['group']); ?>">
							<a class="bpi-card" href="<?php echo esc_url($bpi_card['url']); ?>">
								<span class="bpi-card__icon"><?php echo $bpi_icon($bpi_card['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
								<h3 class="bpi-card__title"><?php echo esc_html($bpi_card['title']); ?></h3>
								<p class="bpi-card__line"><?php echo esc_html($bpi_card['line']); ?></p>
								<?php if ($bpi_card['tasks']) : ?>
									<ul class="bpi-card__tasks">
										<?php foreach ($bpi_card['tasks'] as $bpi_task) : ?>
											<li><?php echo esc_html($bpi_task); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
								<span class="bpi-card__cta">Explore <?php echo esc_html($bpi_card['title']); ?><?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<section class="bpi-section bpi-section--tint" aria-labelledby="bpi-common-title">
			<div class="bpi-wrap">
				<header class="bpi-heading">
					<p class="bpi-kicker">In every industry</p>
					<h2 id="bpi-common-title">One agent, tuned to your calls</h2>
					<p class="bpi-heading__lede">Whatever your industry, every Botphonic agent comes with the same foundations.</p>
				</header>
				<ul class="bpi-features">
					<?php foreach ($bpi_features as $bpi_feature) : ?>
						<li class="bpi-feature">
							<span class="bpi-feature__icon"><?php echo $bpi_icon($bpi_feature['icon'], 22); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
							<h3><?php echo esc_html($bpi_feature['title']); ?></h3>
							<p><?php echo esc_html($bpi_feature['text']); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<section class="bpi-section" aria-labelledby="bpi-cta-title">
			<div class="bpi-wrap">
				<div class="bpi-cta">
					<div>
						<h2 id="bpi-cta-title">Don’t see your industry?</h2>
						<p>Botphonic works for any business that takes calls. Tell us how yours come in, and we’ll show you the agent on your own use case.</p>
					</div>
					<div class="bpi-actions">
						<a class="bpi-btn bpi-btn--coral" href="<?php echo esc_url($bpi_demo); ?>">Book a demo<?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
						<a class="bpi-btn bpi-btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">Contact us</a>
					</div>
				</div>
			</div>
		</section>
	</main>
</div>

<?php
unset($bpi_register, $bpi_demo, $bpi_groups, $bpi_known, $bpi_icons, $bpi_features, $bpi_icon, $bpi_arrow, $bpi_children, $bpi_order, $bpi_cards, $bpi_used_groups);
get_footer();
