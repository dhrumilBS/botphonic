<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * Contact Botphonic: demo request form, the right route for each kind of
 * enquiry, what happens after sending, office details and FAQ.
 * FAQ content comes from botphonic_contact_faq_items() in functions.php, and
 * the shared FAQ renderer outputs the FAQPage schema.
 * Assets: assets/css/contact.css, assets/js/contact.js (functions.php §12).
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bp_contact_email = 'contact@botphonic.ai';
$bp_contact_demo = 'https://calendly.com/contact-botphonic/product-discovery';
$bp_contact_register = 'https://app.botphonic.ai/register/';
$bp_contact_login = 'https://app.botphonic.ai/login';
$bp_contact_docs = 'https://docs.botphonic.ai/';
$bp_contact_form = '[contact-form-7 id="1642357" title="Contact form"]';
// Keep in step with the "How quickly will I hear back?" FAQ answer in functions.php.
$bp_contact_reply = 'within one business day';

$bp_contact_promises = array(
	'A live demo built around your own calls',
	'A clear estimate of what you could automate',
	'Straight answers on pricing for your call volume',
);

$bp_contact_routes = array(
	array(
		'icon' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 15l2 2 4-4"/>',
		'label' => 'Sales & demos',
		'title' => 'Book a 20-minute call',
		'text' => 'Pick a time that suits you and hear the agent handle your use case live, with pricing for your volume.',
		'cta' => 'Choose a time',
		'href' => $bp_contact_demo,
		'external' => true,
	),
	array(
		'icon' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>',
		'label' => 'Try it yourself',
		'title' => 'Start a free trial',
		'text' => 'Set up an agent in minutes. 14 days and 20 free minutes, with no sales call needed.',
		'cta' => 'Start 14-day free trial',
		'href' => $bp_contact_register,
		'external' => true,
	),
	array(
		'icon' => '<circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3M12 17h.01"/>',
		'label' => 'Existing customers',
		'title' => 'Get help with your account',
		'text' => 'Guides for setup, integrations and call flows are in the docs. Log in to manage your agents and billing.',
		'cta' => 'Open the documentation',
		'href' => $bp_contact_docs,
		'external' => true,
		'secondary' => array('label' => 'Log in', 'href' => $bp_contact_login),
	),
	array(
		'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
		'label' => 'Agencies & resellers',
		'title' => 'Partner with us',
		'text' => 'Offer AI voice agents to your clients under your own brand, with volume pricing that leaves room for margin.',
		'cta' => 'Become a partner',
		'href' => home_url('/become-a-partner/'),
		'external' => false,
	),
);

$bp_contact_steps = array(
	array('title' => 'We read your request', 'text' => 'A member of our team looks at your use case, call volume and the systems you use, so the conversation starts from your situation, not a script.'),
	array('title' => 'We reply ' . $bp_contact_reply, 'text' => 'You hear back by email or phone to agree a demo time. No automated sales sequence, and no pressure.'),
	array('title' => 'You hear it on your calls', 'text' => 'In a live demo we run the agent on your scenario, show the integrations you need and give you pricing for your volume.'),
);

$bp_contact_stats = array(
	array('value' => '40M+', 'label' => 'client calls handled'),
	array('value' => '4.5M+', 'label' => 'work hours saved'),
	array('value' => '<300 ms', 'label' => 'response time on every turn'),
	array('value' => '80%', 'label' => 'of routine inquiries resolved without a person'),
);

$bp_contact_enterprise = array(
	'Custom concurrency so callers never queue at peak',
	'SIP trunking into Twilio, Telnyx, Plivo or your own telephony',
	'HIPAA-ready, PCI DSS payments and SOC 2 controls',
	'Volume pricing and a dedicated voice-AI engineer',
);

$bp_contact_socials = array(
	array('label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/botphonic/'),
	array('label' => 'X (Twitter)', 'url' => 'https://x.com/botphonic'),
	array('label' => 'Facebook', 'url' => 'https://facebook.com/botphonic'),
	array('label' => 'Instagram', 'url' => 'https://www.instagram.com/botphonicai/'),
	array('label' => 'YouTube', 'url' => 'https://www.youtube.com/@BotPhonic'),
);

$bp_contact_address = '1915, 447 Broadway, 2nd Floor, New York, NY 10013, United States';
$bp_contact_map = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('447 Broadway, New York, NY 10013');

$bp_contact_faqs = array();
if (function_exists('botphonic_contact_faq_items')) {
	foreach (botphonic_contact_faq_items() as $bp_contact_faq) {
		$bp_contact_faqs[] = array('question' => $bp_contact_faq['q'], 'answer' => $bp_contact_faq['a']);
	}
}

$bp_contact_arrow = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$bp_contact_check = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>';
$bp_contact_new_tab = '<span class="screen-reader-text"> (opens in a new tab)</span>';

get_header();
?>

<div id="bp-contact" class="bp bp-contact no-js">
	<main>
		<section id="top" class="bct-section bct-section--dark bct-hero" aria-labelledby="hero-title">
			<div class="bct-wrap">
				<nav class="bct-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<span aria-current="page">Contact</span>
				</nav>

				<div class="bct-grid bct-grid--hero">
					<div class="bct-stack bct-stack--lg">
						<p class="bct-kicker bct-kicker--dark">Contact Botphonic</p>
						<h1 id="hero-title">Talk to our team about your calls</h1>
						<p class="bct-hero__lede">Contact Botphonic to see how an AI voice agent would answer, book and follow up on your calls. Tell us how your business handles calls today, then send the form for a free, personalised demo, or reach us directly below.</p>

						<ul class="bct-promises" aria-label="What you get from a demo">
							<?php foreach ($bp_contact_promises as $bp_contact_promise) : ?>
							<li><span aria-hidden="true"><?php echo $bp_contact_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><?php echo esc_html($bp_contact_promise); ?></li>
							<?php endforeach; ?>
						</ul>

						<ul class="bct-direct" aria-label="Other ways to reach us">
							<li>
								<span class="bct-direct__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/></svg></span>
								<span><small>Email us</small><a href="mailto:<?php echo esc_attr(antispambot($bp_contact_email)); ?>"><?php echo esc_html(antispambot($bp_contact_email)); ?></a></span>
							</li>
							<li>
								<span class="bct-direct__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
								<span><small>Prefer to talk now?</small><a href="<?php echo esc_url($bp_contact_demo); ?>" target="_blank" rel="noopener">Book a 20-minute call<?php echo $bp_contact_new_tab; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?></a></span>
							</li>
						</ul>

						<ul class="bct-proof" aria-label="Botphonic ratings and customer count">
							<li><span class="bct-stars" aria-hidden="true">★★★★★</span> <strong>5.0</strong> on G2</li>
							<li><strong>4.8</strong> on Capterra</li>
							<li><strong>500+</strong> businesses</li>
						</ul>
					</div>

					<div id="contact-form" class="bct-form-card" role="region" aria-labelledby="form-title">
						<div class="bct-form-card__head">
							<h2 id="form-title">Request your free demo</h2>
							<p>Takes about a minute. We reply <?php echo esc_html($bp_contact_reply); ?>.</p>
						</div>
						<?php echo do_shortcode($bp_contact_form); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 output. ?>
						<p class="bct-form-card__note">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
							<span>We use your details only to reply to this request. Read our <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">privacy policy</a>.</span>
						</p>
					</div>
				</div>
			</div>
		</section>

		<section class="bct-stats-section" aria-label="Botphonic in numbers">
			<div class="bct-wrap">
				<div class="bct-stats">
					<?php foreach ($bp_contact_stats as $bp_contact_stat) : ?>
					<div class="bct-stat" data-bct-reveal><strong><?php echo esc_html($bp_contact_stat['value']); ?></strong><span><?php echo esc_html($bp_contact_stat['label']); ?></span></div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="ways-to-reach-us" class="bct-section" aria-labelledby="routes-title">
			<div class="bct-wrap">
				<header class="bct-heading bct-heading--center">
					<p class="bct-kicker">Ways to reach us</p>
					<h2 id="routes-title">Pick the fastest route for what you need</h2>
					<p class="bct-lede">Not every question needs a form. Choose the option that fits and you’ll get to the right person sooner.</p>
				</header>
				<div class="bct-grid bct-grid--four">
					<?php foreach ($bp_contact_routes as $bp_contact_route) : ?>
					<article class="bct-card bct-route" data-bct-reveal>
						<span class="bct-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $bp_contact_route['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg></span>
						<p class="bct-route__label"><?php echo esc_html($bp_contact_route['label']); ?></p>
						<h3><?php echo esc_html($bp_contact_route['title']); ?></h3>
						<p><?php echo esc_html($bp_contact_route['text']); ?></p>
						<div class="bct-route__links">
							<a class="bct-route__cta" href="<?php echo esc_url($bp_contact_route['href']); ?>"<?php echo $bp_contact_route['external'] ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html($bp_contact_route['cta']); ?><?php echo $bp_contact_route['external'] ? $bp_contact_new_tab : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php echo $bp_contact_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
							<?php if (!empty($bp_contact_route['secondary'])) : ?>
							<a class="bct-text-link" href="<?php echo esc_url($bp_contact_route['secondary']['href']); ?>"><?php echo esc_html($bp_contact_route['secondary']['label']); ?></a>
							<?php endif; ?>
						</div>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="what-happens-next" class="bct-section bct-section--tint" aria-labelledby="steps-title">
			<div class="bct-wrap bct-grid bct-grid--steps">
				<div class="bct-stack">
					<p class="bct-kicker">What happens next</p>
					<h2 id="steps-title">From your message to a working demo in three steps</h2>
					<p class="bct-lede">The more you tell us about your calls, the more useful your demo will be. Helpful details: your monthly call volume, the calls you want to automate, the CRM or calendar you use, and any compliance needs.</p>
					<a class="bct-btn bct-btn--ink" href="#contact-form">Go to the form<?php echo $bp_contact_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
				</div>
				<ol class="bct-steps">
					<?php foreach ($bp_contact_steps as $bp_contact_step) : ?>
					<li data-bct-reveal>
						<h3><?php echo esc_html($bp_contact_step['title']); ?></h3>
						<p><?php echo esc_html($bp_contact_step['text']); ?></p>
					</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<section id="enterprise" class="bct-section" aria-labelledby="enterprise-title">
			<div class="bct-wrap">
				<div class="bct-enterprise" data-bct-reveal>
					<div class="bct-stack">
						<p class="bct-kicker bct-kicker--dark">Enterprise &amp; contact centers</p>
						<h2 id="enterprise-title">Handling more than 20,000 calls a month?</h2>
						<p>Talk to our sales team about rolling Botphonic out across teams, locations and languages. We’ll scope concurrency, telephony and compliance with you before you commit.</p>
						<div class="bct-actions">
							<a class="bct-btn bct-btn--coral" href="<?php echo esc_url($bp_contact_demo); ?>" target="_blank" rel="noopener">Talk to sales<?php echo $bp_contact_new_tab; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php echo $bp_contact_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
							<a class="bct-btn bct-btn--ghost-dark" href="<?php echo esc_url(home_url('/security/')); ?>">Security overview</a>
						</div>
					</div>
					<ul class="bct-enterprise__list" aria-label="What enterprise plans include">
						<?php foreach ($bp_contact_enterprise as $bp_contact_point) : ?>
						<li><span aria-hidden="true"><?php echo $bp_contact_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><?php echo esc_html($bp_contact_point); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>

		<section id="office" class="bct-section bct-section--tint" aria-labelledby="office-title">
			<div class="bct-wrap">
				<header class="bct-heading">
					<p class="bct-kicker">Company details</p>
					<h2 id="office-title">Where to find Botphonic</h2>
				</header>
				<div class="bct-grid bct-grid--three">
					<div class="bct-card bct-info" data-bct-reveal>
						<h3>Office</h3>
						<address><?php echo esc_html($bp_contact_address); ?></address>
						<a class="bct-text-link" href="<?php echo esc_url($bp_contact_map); ?>" target="_blank" rel="noopener">Open in Google Maps<?php echo $bp_contact_new_tab; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?></a>
					</div>
					<div class="bct-card bct-info" data-bct-reveal>
						<h3>Email</h3>
						<p>Sales, support, partnerships and press. Tell us which in your subject line and we’ll route it.</p>
						<a class="bct-text-link" href="mailto:<?php echo esc_attr(antispambot($bp_contact_email)); ?>"><?php echo esc_html(antispambot($bp_contact_email)); ?></a>
					</div>
					<div class="bct-card bct-info" data-bct-reveal>
						<h3>Follow us</h3>
						<p>Product updates, customer stories and voice-AI tips.</p>
						<ul class="bct-socials">
							<?php foreach ($bp_contact_socials as $bp_contact_social) : ?>
							<li><a href="<?php echo esc_url($bp_contact_social['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($bp_contact_social['label']); ?><?php echo $bp_contact_new_tab; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<?php if ($bp_contact_faqs && function_exists('botphonic_render_faq_group')) : ?>
		<section id="faq" class="bct-section" aria-labelledby="faq-title">
			<div class="bct-wrap bct-faq">
				<div class="bct-stack bct-faq__intro">
					<p class="bct-kicker">FAQ</p>
					<h2 id="faq-title">Questions about contacting Botphonic</h2>
					<p class="bct-lede">Can’t find your answer? Email us at <a class="bct-inline-link" href="mailto:<?php echo esc_attr(antispambot($bp_contact_email)); ?>"><?php echo esc_html(antispambot($bp_contact_email)); ?></a>.</p>
				</div>
				<div class="bct-faq__list">
					<?php echo botphonic_render_faq_group($bp_contact_faqs); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer. ?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<section class="bct-final" aria-labelledby="cta-title">
			<div class="bct-final__inner">
				<h2 id="cta-title">Rather hear it for yourself first?</h2>
				<p>Start a free trial and have an AI agent answering your number in minutes. You only pay for talk time, and you can talk to us whenever you’re ready.</p>
				<div class="bct-actions bct-actions--center">
					<a class="bct-btn bct-btn--coral" href="<?php echo esc_url($bp_contact_register); ?>">Start 14-day free trial<?php echo $bp_contact_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
					<a class="bct-btn bct-btn--outline" href="<?php echo esc_url(home_url('/pricing/')); ?>">See pricing</a>
				</div>
				<small>14-day free trial · 20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime</small>
			</div>
		</section>
	</main>
</div>

<?php
unset(
	$bp_contact_email, $bp_contact_demo, $bp_contact_register, $bp_contact_login, $bp_contact_docs, $bp_contact_form,
	$bp_contact_reply, $bp_contact_promises, $bp_contact_routes, $bp_contact_steps, $bp_contact_stats, $bp_contact_enterprise,
	$bp_contact_socials, $bp_contact_address, $bp_contact_map, $bp_contact_faqs, $bp_contact_arrow, $bp_contact_check, $bp_contact_new_tab
);
get_footer();
