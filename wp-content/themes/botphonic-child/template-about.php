<?php
/**
 * Template Name: About Us
 * Template Post Type: page
 *
 * About Botphonic: who we are, why we exist, who leads us and why customers trust us.
 * FAQ content comes from botphonic_about_faq_items() in functions.php, which also feeds the schema.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bp_about_register = 'https://app.botphonic.ai/register/';
$bp_about_demo = 'https://calendly.com/contact-botphonic/product-discovery';

$bp_about_stats = array(
	array('value' => '500+', 'label' => 'businesses trust Botphonic with their calls'),
	array('value' => '<300 ms', 'label' => 'response time, so it feels like a real conversation'),
	array('value' => '50+', 'label' => 'languages, with mid-call switching'),
	array('value' => '99.99%', 'label' => 'platform uptime'),
);

$bp_about_pains = array(
	array(
		'icon' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/><path d="M16 2l6 6M22 2l-6 6"/>',
		'quote' => '“We miss calls when we’re busiest.”',
		'text' => 'Peak hours and after-hours are when callers need you most and when your team can least pick up. Botphonic answers on the first ring and takes several calls at once, so nobody waits on hold.',
	),
	array(
		'icon' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 15l2 2 4-4"/>',
		'quote' => '“Our staff spend all day on routine calls.”',
		'text' => 'Booking, rescheduling, reminders and the same ten questions eat your team’s day. Botphonic handles them end to end and hands over only the calls that need a person.',
	),
	array(
		'icon' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
		'quote' => '“Covering the phones means hiring.”',
		'text' => 'Answering services and call centers charge per agent or per hour. Botphonic plans start at $12 a month, and you pay for talk time, never for seats.',
	),
);

$bp_about_values = array(
	array('title' => 'Answer first, always', 'text' => 'A caller who waits is a caller who leaves. Speed is the first thing we engineer for, which is why our agents respond in under 300 ms.'),
	array('title' => 'AI for the routine, people for the moments that matter', 'text' => 'Our agents support your team rather than replace it. Frustrated, urgent or complex callers reach a person, with the full context of the call.'),
	array('title' => 'Trust is earned on every call', 'text' => 'Every conversation is recorded, transcribed and summarised, with compliance controls for healthcare, payments and outbound calling, so you always know what happened.'),
);

$bp_about_leaders = array(
	array(
		'name' => 'Bharat Godhani',
		'role' => 'Founder & CEO',
		'slug' => 'bharat-godhani',
		'initials' => 'BG',
		'bio' => 'Bharat founded Botphonic in 2025 after 13 years building Bigscal into a software company with more than 200 developers and over 1,000 projects delivered in 25 countries. He designed Botphonic’s end-to-end architecture, led its telephony integrations with Twilio and Intalk, and sets the product direction.',
		'linkedin' => 'https://www.linkedin.com/in/bharat-godhani/',
	),
	array(
		'name' => 'Tosif Saiyad',
		'role' => 'Chief Technology Officer',
		'slug' => 'tosif-saiyad',
		'initials' => 'TS',
		'bio' => 'Tosif has built software since 2014, with deep experience in backend systems, APIs, cloud infrastructure and real-time applications. He led the engineering that brought Botphonic’s response time down to about 300 ms, and oversees how agents remember context, update CRMs, book appointments and hand calls to people.',
		'linkedin' => 'https://www.linkedin.com/in/tosif-saiyad-30a184140/',
	),
);

$bp_about_milestones = array(
	array('when' => 'Before Botphonic', 'title' => '13 years building software', 'text' => '1,000+ projects for businesses in 25 countries at Bigscal.'),
	array('when' => '2025', 'title' => 'Botphonic founded', 'text' => 'An AI agent that does the work on the call, not just answers it.'),
	array('when' => 'Engineering', 'title' => '~300 ms response time', 'text' => 'Conversations flow without long pauses or talking over callers.'),
	array('when' => 'Today', 'title' => '500+ businesses', 'text' => 'Across healthcare, real estate, finance, contact centers and agencies.'),
);

$bp_about_differences = array(
	array(
		'icon' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>',
		'title' => 'It does the work, not just the talking',
		'text' => 'Books into your live calendar, looks up orders, captures PCI-compliant payments, qualifies leads and updates Salesforce, HubSpot or Zoho while the caller is still on the line.',
	),
	array(
		'icon' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
		'title' => 'One agent, every language, both directions',
		'text' => 'The same agent answers inbound calls and runs outbound campaigns in 50+ languages, switching mid-call when your caller does, with 65+ natural voices to match your brand.',
	),
	array(
		'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>',
		'title' => 'Every call on the record',
		'text' => 'Recordings, transcripts, summaries, sentiment and QA scoring for every conversation, so you always know what was said and what was done.',
	),
	array(
		'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'title' => 'Live in minutes, not weeks',
		'text' => 'Connect a number, add your FAQs, pick a voice. No code needed, and a voice-AI engineer is on hand if you want help tuning the agent.',
	),
	array(
		'icon' => '<path d="M4 7h16M4 12h16M4 17h10"/>',
		'title' => 'Keep your phone system',
		'text' => 'SIP trunking into Twilio, Telnyx, Plivo or your own telephony, plus 100+ tools via API or Zapier. Keep your current numbers.',
	),
	array(
		'icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/>',
		'title' => 'Honest, per-minute pricing',
		'text' => 'Plans from $12 a month, or pay as you go from a $75 wallet at $0.13 per talk minute. Unlimited AI agents and all 50+ languages included, with no seat licences.',
	),
);

$bp_about_audiences = array(
	'health' => array(
		'label' => 'Healthcare practices',
		'title' => 'Your front desk, freed from the phone',
		'pain' => 'Patients call when your front desk is busiest, and the ones left on hold rarely call back. Botphonic answers every patient call, day or night, and keeps your schedule full.',
		'cta' => 'Explore healthcare',
		'href' => home_url('/industry/healthcare/'),
		'points' => array(
			'Books, reschedules and confirms appointments in your practice software and EHR',
			'Sends reminders that cut no-shows',
			'Escalates urgent or complex calls to your staff instantly',
			'HIPAA-ready records of every call',
		),
	),
	'realestate' => array(
		'label' => 'Real estate',
		'title' => 'Every inquiry qualified before it goes cold',
		'pain' => 'Buyers call about a listing once. If nobody answers, they call the next agent. Botphonic answers listing inquiries 24/7 and puts qualified buyers on your calendar.',
		'cta' => 'Explore real estate',
		'href' => home_url('/industry/real-estate/'),
		'points' => array(
			'Asks about budget, location, timeline and financing',
			'Books showings straight into your agents’ calendars',
			'Scores every lead and pushes it to your CRM',
			'Follows up on web leads within seconds of a form fill',
		),
	),
	'finance' => array(
		'label' => 'Finance & insurance',
		'title' => 'Renewals, verification and follow-ups that run themselves',
		'pain' => 'Policy renewals, payment reminders and client checks eat hours of skilled time. Botphonic runs them on autopilot and hands only warm prospects to your licensed team.',
		'cta' => 'Explore financial services',
		'href' => home_url('/industry/finance/'),
		'points' => array(
			'Verifies clients before sharing account details',
			'Runs renewal and payment-reminder campaigns',
			'Captures card payments with PCI DSS-compliant controls',
			'Warm-transfers to a licensed agent to close',
		),
	),
	'bpo' => array(
		'label' => 'Contact centers & BPOs',
		'title' => 'Absorb the peaks without hiring for them',
		'pain' => 'Call spikes mean long queues or idle agents. Botphonic handles overflow, after-hours and tier-1 calls so your human team works the calls that need them.',
		'cta' => 'Explore contact center solutions',
		'href' => home_url('/industry/bpo-customer-service/'),
		'points' => array(
			'Custom concurrency on Enterprise, so callers don’t queue at peak',
			'SIP trunking into Twilio, Telnyx, Plivo or your own telephony',
			'Transcripts, sentiment and QA scoring on every call',
			'Visual workflow builder for custom call flows',
		),
	),
	'agency' => array(
		'label' => 'Agencies',
		'title' => 'White-label AI receptionists for your clients',
		'pain' => 'Your clients want AI calling without the build. Botphonic lets you offer it under your own brand, with volume pricing that leaves room for margin.',
		'cta' => 'Explore the agency program',
		'href' => home_url('/industry/agency/'),
		'points' => array(
			'White-label platform under your own brand',
			'Volume pricing for growing client portfolios',
			'Same agent, voices and integrations for every client',
			'Partner support from our voice-AI team',
		),
	),
);

$bp_about_testimonials = array(
	array('quote' => 'Botphonic helps us handle calls instantly and never miss a customer opportunity. The AI sounds natural, works 24/7, and saves our team valuable time.', 'name' => 'Alpesh Vaghasiya', 'role' => 'CEO, Superworks', 'initials' => 'AV'),
	array('quote' => 'Botphonic makes handling customer calls effortless. Its AI handles routine conversations 24/7, reducing our team’s workload while keeping every caller supported.', 'name' => 'Ketan Mangukiya', 'role' => 'Founder & CEO, Healthray', 'initials' => 'KM'),
	array('quote' => 'Botphonic feels like an extra team member. It handles calls, captures key details, and keeps our team focused on what matters.', 'name' => 'Dr. Alphonsa', 'role' => 'Executive Director, Ashok One Hospital', 'initials' => 'DA'),
);

$bp_about_ratings = array(
	array('score' => '5.0', 'site' => 'G2', 'url' => 'https://www.g2.com/products/botphonic-ai-call-assistant/reviews'),
	array('score' => '4.8', 'site' => 'Capterra'),
	array('score' => '4.8', 'site' => 'SoftwareSuggest'),
	array('score' => '4.5', 'site' => 'Trustpilot', 'url' => 'https://www.trustpilot.com/review/botphonic.ai'),
	array('score' => '4.2', 'site' => 'SaaSworthy'),
);

$bp_about_trust = array(
	array('title' => 'HIPAA-ready', 'text' => 'PHI handled under BAA-compatible controls, with audit logs.'),
	array('title' => 'PCI DSS payments', 'text' => 'Card details never touch recordings, transcripts or agents.'),
	array('title' => 'SOC 2 Type II ready', 'text' => 'Controls, continuous penetration testing and MFA.'),
	array('title' => 'GDPR-aligned', 'text' => 'Regional data residency, retention controls and a DPA on request.'),
	array('title' => 'TCPA controls', 'text' => 'Consent tracking, calling windows and opt-out handling.'),
	array('title' => 'Role-based access', 'text' => 'You decide who can hear recordings, read transcripts or change a script.'),
);

$bp_about_faqs = array();
if (function_exists('botphonic_about_faq_items')) {
	foreach (botphonic_about_faq_items() as $bp_about_faq) {
		$bp_about_faqs[] = array('question' => $bp_about_faq['q'], 'answer' => $bp_about_faq['a']);
	}
}

$bp_about_arrow = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$bp_about_check = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>';

get_header();
?>

<div id="bp-about" class="bp bp-about no-js">
	<main>
		<section id="top" class="bau-section bau-section--dark bau-hero" aria-labelledby="hero-title">
			<div class="bau-wrap">
				<nav class="bau-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<span aria-current="page">About us</span>
				</nav>
				<div class="bau-grid bau-grid--hero">
					<div class="bau-stack bau-stack--lg">
						<p class="bau-kicker bau-kicker--dark">About Botphonic</p>
						<h1 id="hero-title">We make sure every customer call gets answered</h1>
						<p class="bau-hero__lede">Botphonic builds AI voice agents for businesses. They pick up in under 300 ms, talk naturally in 50+ languages, book the appointment, update your CRM and send your team a summary, so a busy line or a closed office never costs you a customer.</p>
						<div class="bau-actions">
							<a class="bau-btn bau-btn--coral" href="<?php echo esc_url($bp_about_register); ?>">Start 14-day free trial<?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
							<a class="bau-btn bau-btn--ghost-dark" href="<?php echo esc_url($bp_about_demo); ?>">Book a 20-minute demo</a>
						</div>
						<ul class="bau-proof" aria-label="Botphonic ratings and customer count">
							<li><span class="bau-stars" aria-hidden="true">★★★★★</span> <strong>5.0</strong> on G2</li>
							<li><strong>4.8</strong> on Capterra</li>
							<li><strong>500+</strong> businesses</li>
						</ul>
					</div>

					<figure class="bau-call" aria-labelledby="example-call-title">
						<figcaption class="bau-call__head">
							<span class="bau-live-dot" aria-hidden="true"></span>
							<span class="bau-call__identity"><strong id="example-call-title">Incoming call · Brightside Dental</strong><span>Example call · AI receptionist · 8:42 PM</span></span>
							<span class="bau-call__speed">Response &lt; 300 ms</span>
						</figcaption>
						<div class="bau-call__messages">
							<p class="bau-message bau-message--caller"><span>Caller</span>Hi, I chipped a tooth tonight. Can I get in tomorrow?</p>
							<p class="bau-message bau-message--agent"><span>Botphonic agent</span>I’m sorry to hear that. Dr. Lee has openings tomorrow at 10:30 AM or 4:00 PM. Which works better?</p>
							<p class="bau-message bau-message--caller"><span>Caller</span>4 PM. Can you text me the address?</p>
							<p class="bau-message bau-message--agent"><span>Botphonic agent</span>Done. You’re booked for 4:00 PM tomorrow, and the address is on its way by text.</p>
						</div>
						<ul class="bau-outcomes" aria-label="Actions completed during the example call">
							<?php foreach (array('Appointment booked', 'Practice software updated', 'Text message sent', 'Summary emailed to the team') as $bp_about_outcome) : ?>
								<li><?php echo $bp_about_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><?php echo esc_html($bp_about_outcome); ?></li>
							<?php endforeach; ?>
						</ul>
					</figure>
				</div>
			</div>
		</section>

		<section class="bau-stats-section" aria-label="Botphonic in numbers">
			<div class="bau-wrap">
				<div class="bau-stats">
					<?php foreach ($bp_about_stats as $bp_about_stat) : ?>
						<div class="bau-stat" data-bau-reveal><strong><?php echo esc_html($bp_about_stat['value']); ?></strong><span><?php echo esc_html($bp_about_stat['label']); ?></span></div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="our-story" class="bau-section" aria-labelledby="story-title">
			<div class="bau-wrap bau-grid bau-grid--story">
				<div class="bau-story" data-bau-reveal>
					<p class="bau-kicker">Our story</p>
					<h2 id="story-title">Started by people who watched businesses lose customers to a ringing phone</h2>
					<p class="bau-lede">Most businesses don’t lose customers because their service is poor. They lose them at the first step: the phone rings at 8 AM, over lunch or after closing, and nobody picks up. The caller doesn’t leave a message. They call the next business on the list.</p>
					<p>Our founder, Bharat Godhani, saw this up close. In 13 years building Bigscal into a software company with more than 200 developers, he oversaw over 1,000 projects in 25 countries, and kept seeing how much business slipped away between the ring and the answer.</p>
					<p>He also learned that a useful AI agent starts with the right questions. Which calls should it handle? What does the caller actually need? Where does that information live? When should a person take over? Bharat founded Botphonic in 2025 to turn those answers into software that does the work on the call, not just answers it.</p>
					<figure class="bau-quote">
						<blockquote>“‘We need an AI agent to answer our calls’ is not a complete requirement.”</blockquote>
						<figcaption><strong>Bharat Godhani</strong>, Founder &amp; CEO</figcaption>
					</figure>
					<p>Today, with CTO Tosif Saiyad leading engineering, Botphonic handles real phone work for 500+ businesses, from patient scheduling to lead qualification, while their people focus on the conversations that truly need a human.</p>
				</div>

				<aside class="bau-facts" aria-labelledby="facts-title" data-bau-reveal>
					<h3 id="facts-title">Botphonic at a glance</h3>
					<dl>
						<div><dt>Founded</dt><dd>2025</dd></div>
						<div><dt>Founder &amp; CEO</dt><dd>Bharat Godhani</dd></div>
						<div><dt>CTO</dt><dd>Tosif Saiyad</dd></div>
						<div><dt>What we build</dt><dd>AI voice agents for inbound and outbound business calls</dd></div>
						<div><dt>Customers</dt><dd>500+ businesses</dd></div>
						<div><dt>Languages</dt><dd>50+, with mid-call switching</dd></div>
						<div><dt>Rated</dt><dd>5.0 on G2 · 4.8 on Capterra</dd></div>
					</dl>
					<a class="bau-text-link" href="<?php echo esc_url(home_url('/contact/')); ?>">Talk to our team</a>
				</aside>
			</div>
		</section>

		<section class="bau-section bau-section--tint" aria-labelledby="problem-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--center">
					<p class="bau-kicker">Why Botphonic exists</p>
					<h2 id="problem-title">If any of these sound familiar, we built Botphonic for you</h2>
				</header>
				<div class="bau-grid bau-grid--three">
					<?php foreach ($bp_about_pains as $bp_about_pain) : ?>
						<article class="bau-card bau-pain" data-bau-reveal>
							<span class="bau-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $bp_about_pain['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg></span>
							<h3><?php echo esc_html($bp_about_pain['quote']); ?></h3>
							<p><?php echo esc_html($bp_about_pain['text']); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="bau-section" aria-labelledby="mission-title">
			<div class="bau-wrap">
				<header class="bau-heading">
					<p class="bau-kicker">Mission &amp; values</p>
					<h2 id="mission-title">Our mission: make every business reachable, in every language, at every hour</h2>
					<p class="bau-lede">Three principles guide how we build Botphonic and how we work with you.</p>
				</header>
				<ol class="bau-grid bau-grid--three bau-values">
					<?php foreach ($bp_about_values as $bp_about_value) : ?>
						<li class="bau-value" data-bau-reveal>
							<h3><?php echo esc_html($bp_about_value['title']); ?></h3>
							<p><?php echo esc_html($bp_about_value['text']); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<section id="team" class="bau-section bau-section--tint" aria-labelledby="team-title">
			<div class="bau-wrap bau-stack bau-stack--section">
				<header class="bau-heading bau-heading--flush">
					<p class="bau-kicker">Leadership</p>
					<h2 id="team-title">Meet the people behind Botphonic</h2>
					<p class="bau-lede">Botphonic is led by engineers who have spent years building software for real businesses. You won’t be left alone with a dashboard: our voice-AI team helps you set up, tune scripts and connect your systems, and stays on hand as your call volume grows.</p>
				</header>

				<div class="bau-grid bau-grid--leaders">
					<?php foreach ($bp_about_leaders as $bp_about_leader) : ?>
						<?php
						$bp_about_user = get_user_by('slug', $bp_about_leader['slug']);
						$bp_about_photo = $bp_about_user ? get_avatar_url($bp_about_user->ID, array('size' => 192)) : '';
						?>
						<article class="bau-card bau-person" data-bau-reveal>
							<div class="bau-person__head">
								<?php if ($bp_about_photo) : ?>
									<img class="bau-avatar" src="<?php echo esc_url($bp_about_photo); ?>" alt="" width="72" height="72" loading="lazy" decoding="async">
								<?php else : ?>
									<span class="bau-avatar bau-avatar--initials" aria-hidden="true"><?php echo esc_html($bp_about_leader['initials']); ?></span>
								<?php endif; ?>
								<div>
									<h3><?php echo esc_html($bp_about_leader['name']); ?></h3>
									<span><?php echo esc_html($bp_about_leader['role']); ?></span>
								</div>
							</div>
							<p><?php echo esc_html($bp_about_leader['bio']); ?></p>
							<div class="bau-person__links">
								<a class="bau-text-link" href="<?php echo esc_url($bp_about_leader['linkedin']); ?>">LinkedIn<span class="screen-reader-text"> profile of <?php echo esc_html($bp_about_leader['name']); ?></span></a>
								<?php if ($bp_about_user) : ?>
									<a class="bau-text-link" href="<?php echo esc_url(get_author_posts_url($bp_about_user->ID)); ?>">Articles<span class="screen-reader-text"> by <?php echo esc_html($bp_about_leader['name']); ?></span></a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<ol class="bau-timeline" aria-label="Botphonic milestones">
					<?php foreach ($bp_about_milestones as $bp_about_milestone) : ?>
						<li data-bau-reveal>
							<span><?php echo esc_html($bp_about_milestone['when']); ?></span>
							<strong><?php echo esc_html($bp_about_milestone['title']); ?></strong>
							<p><?php echo esc_html($bp_about_milestone['text']); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<section id="different" class="bau-section bau-section--dark" aria-labelledby="diff-title">
			<div class="bau-wrap">
				<header class="bau-heading">
					<p class="bau-kicker bau-kicker--dark">What makes us different</p>
					<h2 id="diff-title">Not a call menu with a nicer voice</h2>
					<p class="bau-lede bau-lede--inverse">What you get with Botphonic that you won’t get from an IVR, a human answering service or a developer-only voice API.</p>
				</header>
				<div class="bau-grid bau-grid--three">
					<?php foreach ($bp_about_differences as $bp_about_difference) : ?>
						<article class="bau-dark-card" data-bau-reveal>
							<svg viewBox="0 0 24 24" aria-hidden="true"><?php echo $bp_about_difference['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg>
							<h3><?php echo esc_html($bp_about_difference['title']); ?></h3>
							<p><?php echo esc_html($bp_about_difference['text']); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="who-we-serve" class="bau-section bau-section--tint" aria-labelledby="serve-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--center">
					<p class="bau-kicker">Who we serve</p>
					<h2 id="serve-title">Built for industries where a missed call is a lost customer</h2>
				</header>
				<div class="bau-tabs" role="tablist" aria-label="Choose your industry" data-bau-tabs>
					<?php foreach ($bp_about_audiences as $bp_about_id => $bp_about_audience) : ?>
						<button class="bau-tab" type="button" role="tab" id="bau-tab-<?php echo esc_attr($bp_about_id); ?>" aria-controls="bau-panel-<?php echo esc_attr($bp_about_id); ?>" aria-selected="<?php echo 'health' === $bp_about_id ? 'true' : 'false'; ?>"<?php echo 'health' === $bp_about_id ? '' : ' tabindex="-1"'; ?>><?php echo esc_html($bp_about_audience['label']); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="bau-panels">
					<?php foreach ($bp_about_audiences as $bp_about_id => $bp_about_audience) : ?>
						<article class="bau-panel" id="bau-panel-<?php echo esc_attr($bp_about_id); ?>" role="tabpanel" aria-labelledby="bau-tab-<?php echo esc_attr($bp_about_id); ?>" data-bau-panel>
							<div class="bau-stack">
								<p class="bau-panel__label"><?php echo esc_html($bp_about_audience['label']); ?></p>
								<h3><?php echo esc_html($bp_about_audience['title']); ?></h3>
								<p><?php echo esc_html($bp_about_audience['pain']); ?></p>
								<a class="bau-btn bau-btn--ink" href="<?php echo esc_url($bp_about_audience['href']); ?>"><?php echo esc_html($bp_about_audience['cta']); ?><?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
							</div>
							<ul class="bau-check-list">
								<?php foreach ($bp_about_audience['points'] as $bp_about_point) : ?>
									<li><span aria-hidden="true"><?php echo $bp_about_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><?php echo esc_html($bp_about_point); ?></li>
								<?php endforeach; ?>
							</ul>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="proof" class="bau-section" aria-labelledby="proof-title">
			<div class="bau-wrap bau-stack bau-stack--section">
				<header class="bau-heading bau-heading--flush">
					<p class="bau-kicker">Proof, not promises</p>
					<h2 id="proof-title">Results from teams that moved their phones to Botphonic</h2>
				</header>
				<div class="bau-case" data-bau-reveal>
					<div class="bau-stack">
						<p class="bau-kicker bau-kicker--dark">Customer story · Serenity</p>
						<h3>150% ROI after switching call handling to Botphonic</h3>
						<p>Inbound answering, booking and follow-up moved to the AI agent. Staff kept the conversations that needed a person.</p>
						<a class="bau-btn bau-btn--white" href="<?php echo esc_url(home_url('/success-stories/ecommerce-ai-monetization-case-study/')); ?>">Read Serenity’s story<?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
					</div>
					<dl class="bau-case__metrics">
						<div><dt>higher conversion on inbound inquiries</dt><dd>25%</dd></div>
						<div><dt>shorter call handling time</dt><dd>50%</dd></div>
						<div><dt>fewer errors in bookings and records</dt><dd>20%</dd></div>
						<div><dt>higher agent satisfaction</dt><dd>15%</dd></div>
					</dl>
				</div>
				<div class="bau-grid bau-grid--three">
					<?php foreach ($bp_about_testimonials as $bp_about_testimonial) : ?>
						<figure class="bau-card bau-testimonial" data-bau-reveal>
							<span class="bau-stars" aria-hidden="true">★★★★★</span>
							<blockquote>“<?php echo esc_html($bp_about_testimonial['quote']); ?>”</blockquote>
							<figcaption><span aria-hidden="true"><?php echo esc_html($bp_about_testimonial['initials']); ?></span><span><strong><?php echo esc_html($bp_about_testimonial['name']); ?></strong><small><?php echo esc_html($bp_about_testimonial['role']); ?></small></span></figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
				<ul class="bau-ratings" aria-label="Third-party review ratings">
					<?php foreach ($bp_about_ratings as $bp_about_rating) : ?>
						<li>
							<strong><?php echo esc_html($bp_about_rating['score']); ?><small>/5</small></strong>
							<?php if (!empty($bp_about_rating['url'])) : ?>
								<a href="<?php echo esc_url($bp_about_rating['url']); ?>"><?php echo esc_html($bp_about_rating['site']); ?></a>
							<?php else : ?>
								<span><?php echo esc_html($bp_about_rating['site']); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<section id="security" class="bau-section bau-section--tint" aria-labelledby="trust-title">
			<div class="bau-wrap bau-grid bau-grid--trust">
				<div class="bau-stack">
					<p class="bau-kicker">Security &amp; compliance</p>
					<h2 id="trust-title">Built for regulated calls from day one</h2>
					<p class="bau-lede">Hospitals, lenders and contact centers trust Botphonic with sensitive conversations. Your data is encrypted in transit and at rest, and nothing moves without explicit permission.</p>
					<a class="bau-text-link" href="<?php echo esc_url(home_url('/security/')); ?>">Read the full security overview</a>
				</div>
				<ul class="bau-trust">
					<?php foreach ($bp_about_trust as $bp_about_item) : ?>
						<li data-bau-reveal><?php echo $bp_about_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><strong><?php echo esc_html($bp_about_item['title']); ?></strong><?php echo esc_html($bp_about_item['text']); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<section id="work-with-us" class="bau-section" aria-labelledby="work-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--center">
					<p class="bau-kicker">Work with us</p>
					<h2 id="work-title">However you want to work with Botphonic, there’s a way in</h2>
				</header>
				<div class="bau-grid bau-grid--three">
					<a class="bau-card bau-path" href="<?php echo esc_url($bp_about_demo); ?>" data-bau-reveal>
						<h3>See it on your calls</h3>
						<p>Book a 20-minute demo and hear the agent handle your own use case.</p>
						<span class="bau-path__cta">Book a demo<?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
					</a>
					<a class="bau-card bau-path" href="<?php echo esc_url(home_url('/become-a-partner/')); ?>" data-bau-reveal>
						<h3>Partner with us</h3>
						<p>Agencies and resellers can offer Botphonic under their own brand.</p>
						<span class="bau-path__cta">Become a partner<?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
					</a>
					<a class="bau-card bau-path" href="<?php echo esc_url(home_url('/contact/')); ?>" data-bau-reveal>
						<h3>Talk to our team</h3>
						<p>Questions about pricing, compliance or a large rollout? We’ll get back to you.</p>
						<span class="bau-path__cta">Contact us<?php echo $bp_about_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
					</a>
				</div>
			</div>
		</section>

		<?php if ($bp_about_faqs && function_exists('botphonic_render_faq_group')) : ?>
			<section id="faq" class="bau-section bau-section--tint" aria-labelledby="faq-title">
				<div class="bau-wrap bau-faq">
					<div class="bau-stack bau-faq__intro">
						<p class="bau-kicker">FAQ</p>
						<h2 id="faq-title">Questions people ask about Botphonic</h2>
						<p class="bau-lede">Still unsure? Hear the agent on your own use case.</p>
						<a class="bau-btn bau-btn--ink" href="<?php echo esc_url($bp_about_demo); ?>">Book a 20-minute demo</a>
					</div>
					<div class="bau-faq__list">
						<?php echo botphonic_render_faq_group($bp_about_faqs); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer. ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="bau-section bau-section--dark bau-final" aria-labelledby="cta-title">
			<div class="bau-final__inner">
				<h2 id="cta-title">Your next call could be answered by Botphonic this afternoon</h2>
				<p>Connect a number, add your FAQs, pick a voice. Your first call is answered in minutes, and you only pay for talk time.</p>
				<div class="bau-actions bau-actions--center">
					<a class="bau-btn bau-btn--coral" href="<?php echo esc_url($bp_about_register); ?>">Start 14-day free trial</a>
					<a class="bau-btn bau-btn--ghost-dark" href="<?php echo esc_url(home_url('/pricing/')); ?>">See pricing</a>
				</div>
				<small>14-day free trial · 20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime</small>
			</div>
		</section>
	</main>
</div>

<?php
unset(
	$bp_about_register, $bp_about_demo, $bp_about_stats, $bp_about_pains, $bp_about_values, $bp_about_leaders,
	$bp_about_milestones, $bp_about_differences, $bp_about_audiences, $bp_about_testimonials, $bp_about_ratings,
	$bp_about_trust, $bp_about_faqs, $bp_about_arrow, $bp_about_check
);
get_footer();
