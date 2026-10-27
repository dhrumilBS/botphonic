<?php
/**
 * Template Name: About Us
 * Template Post Type: page
 *
 * About Botphonic landing page.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bp_about_stats = array(
	array('value' => '500+', 'label' => 'businesses trust Botphonic with their calls'),
	array('value' => '<300 ms', 'label' => 'response time, so it feels like a real conversation'),
	array('value' => '50+', 'label' => 'languages, with mid-call switching'),
	array('value' => '99.99%', 'label' => 'platform uptime'),
	array('value' => '100+', 'label' => 'integrations via API, SIP and Zapier'),
);

$bp_about_languages = array(
	'North America and Europe' => array(
		array('name' => 'English', 'detail' => 'US · UK · AU'),
		array('name' => 'Spanish'),
		array('name' => 'Portuguese'),
		array('name' => 'French'),
		array('name' => 'German'),
		array('name' => 'Italian'),
		array('name' => 'Dutch'),
		array('name' => 'Polish'),
		array('name' => 'Russian'),
		array('name' => 'Turkish'),
	),
	'Middle East and Asia-Pacific' => array(
		array('name' => 'Arabic'),
		array('name' => 'Mandarin'),
		array('name' => 'Japanese'),
		array('name' => 'Korean'),
		array('name' => 'Indonesian'),
		array('name' => 'Vietnamese'),
		array('name' => 'Thai'),
		array('name' => 'Filipino'),
	),
	'South Asian languages' => array(
		array('name' => 'Hindi'),
		array('name' => 'Gujarati'),
		array('name' => 'Marathi'),
		array('name' => 'Tamil'),
		array('name' => 'Telugu'),
		array('name' => 'Kannada'),
		array('name' => 'Bengali'),
		array('name' => 'Malayalam'),
		array('name' => 'Punjabi'),
		array('name' => 'Urdu'),
		array('name' => 'Indian English'),
		array('name' => '+ 20 more', 'more' => true),
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
			'200+ concurrent calls on Enterprise, so callers don’t queue',
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
		'href' => home_url('/agency/'),
		'points' => array(
			'White-label platform under your own brand',
			'Volume pricing for growing client portfolios',
			'Same agent, voices and integrations for every client',
			'Partner support from our voice-AI team',
		),
	),
);

$bp_about_faqs = array(
	array(
		'question' => 'What is Botphonic?',
		'answer' => 'Botphonic is an AI voice agent platform for businesses. Its agents answer inbound calls, place outbound calls, book appointments, update your CRM and send your team a summary, 24/7 and in 50+ languages, with a response time under 300 milliseconds.',
	),
	array(
		'question' => 'Who founded Botphonic?',
		'answer' => 'Botphonic was founded in 2025 by Bharat Godhani, its founder and CEO. Before Botphonic, Bharat spent 13 years building Bigscal, a software company with more than 200 developers that has delivered over 1,000 projects in 25 countries. Tosif Saiyad is Botphonic’s CTO and leads its engineering.',
	),
	array(
		'question' => 'Who uses Botphonic?',
		'answer' => 'More than 500 businesses, including healthcare practices and hospitals, real estate teams, lenders, insurers, contact centers and agencies. Customers include Healthray, Superworks and Ashok One Hospital.',
	),
	array(
		'question' => 'Will an AI voice agent replace my staff?',
		'answer' => 'No. Botphonic takes over routine calls like booking, reminders and common questions, and hands frustrated, urgent or complex callers to a person with full context. Your team keeps the conversations that need a human.',
	),
	array(
		'question' => 'How is Botphonic different from an IVR or answering service?',
		'answer' => 'An IVR makes callers press buttons, and most answering services just take a message. Botphonic holds a natural conversation and completes the task on the call: it books into your live calendar, updates your CRM and follows up automatically.',
	),
	array(
		'question' => 'Is Botphonic secure and compliant?',
		'answer' => 'Botphonic is built for regulated calls, with HIPAA-ready healthcare workflows and a BAA for HIPAA workloads, PCI DSS-compliant payment capture, GDPR-aligned data handling, SOC 2 controls, TCPA outbound controls, encryption in transit and at rest, and role-based access.',
	),
	array(
		'question' => 'How much does Botphonic cost?',
		'answer' => 'Plans start at $20 a month billed yearly ($22 billed monthly) for 50 talk minutes and 5 concurrent calls. Pro is $55 a month billed yearly for 120 minutes and 25 concurrent calls. You can also pay as you go from a prepaid credit wallet at $0.12 per talk minute, with no subscription, and Enterprise pricing is based on your volume and compliance needs. There are no per-seat fees and no setup fees.',
	),
	array(
		'question' => 'Is there a free trial?',
		'answer' => 'Yes. Every plan starts with a 14-day free trial that includes 20 free minutes. A $1 card verification charge is refunded within 48 hours, nothing else is billed until day 15, and you can cancel anytime before then.',
	),
	array(
		'question' => 'How quickly can I go live?',
		'answer' => 'Most businesses go live in minutes: connect a number, add your FAQs and pick a voice. A voice-AI engineer is available if you want help tuning the agent or connecting your systems.',
	),
);

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
						<h1 id="hero-title">We build AI voice agents so no customer call goes unanswered</h1>
						<p class="bau-hero__lede">Botphonic gives growing businesses a phone agent that picks up in under 300 ms, speaks your caller’s language, books the appointment and hands your team a clean summary. Here’s who we are, why we built it, and how we earn your trust on every call.</p>
						<div class="bau-actions">
							<a class="bau-btn bau-btn--coral" href="<?php echo esc_url('https://app.botphonic.ai/register/'); ?>">Start 14-day free trial
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
							</a>
							<a class="bau-btn bau-btn--ghost-dark" href="<?php echo esc_url('https://calendly.com/contact-botphonic/product-discovery'); ?>">Book a 20-minute demo</a>
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
							<?php foreach (array('Appointment booked', 'Practice software updated', 'Text message sent', 'Summary emailed to the team') as $outcome): ?>
							<li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5L20 7"></path></svg><?php echo esc_html($outcome); ?></li>
							<?php endforeach; ?>
						</ul>
					</figure>
				</div>
			</div>
		</section>

		<section class="bau-stats-section" aria-label="Botphonic in numbers">
			<div class="bau-wrap bau-stats">
				<?php foreach ($bp_about_stats as $stat): ?>
				<div class="bau-stat"><strong><?php echo esc_html($stat['value']); ?></strong><span><?php echo esc_html($stat['label']); ?></span></div>
				<?php endforeach; ?>
			</div>
		</section>

		<section id="who-we-are" class="bau-section" aria-labelledby="who-title">
			<div class="bau-wrap bau-grid bau-grid--split bau-align-start">
				<div class="bau-stack">
					<p class="bau-kicker">Who we are</p>
					<h2 id="who-title">An AI voice agent company built around one belief: every call deserves an answer</h2>
					<div class="bau-highlight"><p><strong>Botphonic is an AI voice agent platform for businesses, founded in 2025 by Bharat Godhani.</strong> Our agents answer inbound calls, place outbound calls, book appointments, update your CRM and send your team a summary, 24/7 and in 50+ languages. Healthcare practices, real estate teams, lenders, insurers, contact centers and agencies use Botphonic to stop losing customers to missed calls.</p></div>
					<div class="bau-facts">
						<h3>Botphonic at a glance</h3>
						<dl>
							<dt>Founded</dt><dd>2025</dd>
							<dt>Founder &amp; CEO</dt><dd>Bharat Godhani</dd>
							<dt>CTO</dt><dd>Tosif Saiyad</dd>
							<dt>What we build</dt><dd>AI voice agents for inbound and outbound business calls</dd>
							<dt>Customers</dt><dd>500+ businesses</dd>
							<dt>Languages</dt><dd>50+, with mid-call switching</dd>
						</dl>
					</div>
				</div>
				<div class="bau-story">
					<h3>Our story</h3>
					<p>Most businesses don’t lose customers because their service is poor. They lose them at the very first step, when the phone rings at 8 AM, over lunch or after closing, and nobody picks up. Many callers who reach voicemail never leave a message. They simply call the next business on the list.</p>
					<p>Our founder, Bharat Godhani, saw this up close. In 13 years building Bigscal into a software company with more than 200 developers, he oversaw over 1,000 projects in 25 countries. That work gave him a practical view of how different businesses handle their calls, and where automation can genuinely help.</p>
					<p>He also learned that a useful AI agent starts with the right questions. Which calls should it handle? What does the caller actually need? Where does that information live? When should a person take over? Bharat founded Botphonic in 2025 to turn those answers into working software: an AI agent that does the work on the call, not just answers it.</p>
					<figure class="bau-founder-quote"><blockquote>“‘We need an AI agent to answer our calls’ is not a complete requirement.”</blockquote><figcaption><strong>Bharat Godhani</strong>, Founder &amp; CEO</figcaption></figure>
					<p>Today, with CTO Tosif Saiyad leading engineering, Botphonic handles real phone work for 500+ businesses, from patient scheduling to lead qualification, while their people focus on the conversations that truly need a human.</p>
				</div>
			</div>
		</section>

		<section class="bau-section bau-section--tint" aria-labelledby="problem-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--center">
					<p class="bau-kicker">Why Botphonic exists</p>
					<h2 id="problem-title">If any of these sound familiar, we built Botphonic for you</h2>
				</header>
				<div class="bau-grid bau-grid--three">
					<article class="bau-card bau-icon-card">
						<span class="bau-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path><path d="M16 2l6 6M22 2l-6 6"></path></svg></span>
						<h3>“We miss calls when we’re busiest.”</h3>
						<p>Peak hours and after-hours are when callers need you most and when your team can least pick up. Botphonic answers on the first ring and handles many calls at once, so nobody waits on hold.</p>
					</article>
					<article class="bau-card bau-icon-card">
						<span class="bau-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18M9 15l2 2 4-4"></path></svg></span>
						<h3>“Our staff spend all day on routine calls.”</h3>
						<p>Booking, rescheduling, reminders and the same ten questions eat up your team’s day. Botphonic handles them end to end and only hands over the calls that need a person.</p>
					</article>
					<article class="bau-card bau-icon-card">
						<span class="bau-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
						<h3>“Scaling the phones means hiring.”</h3>
						<p>Answering services and call centers charge per agent or per hour. Botphonic plans start at $20 a month, and you pay for talk time, never for seats.</p>
					</article>
				</div>
			</div>
		</section>

		<section class="bau-section" aria-labelledby="beliefs-title">
			<div class="bau-wrap">
				<header class="bau-heading">
					<p class="bau-kicker">Mission &amp; values</p>
					<h2 id="beliefs-title">Our mission is to make every business reachable, in every language, at every hour</h2>
					<p class="bau-lede">Three principles guide how we build Botphonic and how we work with you.</p>
				</header>
				<div class="bau-grid bau-grid--three">
					<div class="bau-principle"><span>01</span><h3>Answer first, always</h3><p>A caller who waits is a caller who leaves. Speed is the first thing we engineer for, which is why our agents respond in under 300 ms.</p></div>
					<div class="bau-principle"><span>02</span><h3>AI for the routine, people for the moments that matter</h3><p>We build agents that support your team, not replace it. Frustrated, urgent or complex callers are handed to a human with full context.</p></div>
					<div class="bau-principle"><span>03</span><h3>Trust is earned on every call</h3><p>Recordings, transcripts and summaries for every conversation, plus compliance controls for healthcare, payments and outbound calling, so you can see exactly what happened.</p></div>
				</div>
			</div>
		</section>

		<section id="team" class="bau-section bau-section--tint" aria-labelledby="team-title">
			<div class="bau-wrap bau-stack bau-stack--section">
				<header class="bau-heading bau-heading--compact">
					<p class="bau-kicker">Leadership</p>
					<h2 id="team-title">Meet the people behind Botphonic</h2>
					<p class="bau-lede bau-lede--small">Botphonic is led by engineers who have spent years building software for real businesses. You won’t be left alone with a dashboard: our voice-AI team helps you set up, tune scripts and connect your systems, and stays on hand as your call volume grows.</p>
				</header>
				<div class="bau-grid bau-grid--leaders">
					<article class="bau-card bau-person">
						<div class="bau-person__head"><span class="bau-avatar bau-avatar--ink" aria-hidden="true">BG</span><div><h3>Bharat Godhani</h3><span>Founder &amp; CEO</span></div></div>
						<p>Bharat founded Botphonic in 2025 after 13 years building Bigscal into a software company with more than 200 developers and over 1,000 projects delivered in 25 countries. He designed Botphonic’s end-to-end architecture, led its telephony integrations with Twilio and Intalk, and today sets the product direction.</p>
						<a class="bau-text-link" href="<?php echo esc_url('https://www.linkedin.com/in/bharat-godhani/'); ?>">Connect with Bharat on LinkedIn</a>
					</article>
					<article class="bau-card bau-person">
						<div class="bau-person__head"><span class="bau-avatar bau-avatar--coral" aria-hidden="true">TS</span><div><h3>Tosif Saiyad</h3><span>Chief Technology Officer</span></div></div>
						<p>Tosif has worked in software development since 2014, with deep experience in backend systems, APIs, cloud infrastructure and real-time applications. He led the engineering work that brought Botphonic’s response time down to about 300 milliseconds, and he oversees how agents remember context, update CRMs, book appointments and hand calls to people.</p>
						<a class="bau-text-link" href="<?php echo esc_url('https://www.linkedin.com/in/tosif-saiyad-30a184140/'); ?>">Connect with Tosif on LinkedIn</a>
					</article>
				</div>
				<div class="bau-grid bau-grid--timeline">
					<div class="bau-timeline"><span>Before Botphonic</span><strong>13 years of building software</strong><p>1,000+ projects for businesses in 25 countries at Bigscal.</p></div>
					<div class="bau-timeline"><span>2025</span><strong>Botphonic founded</strong><p>Bharat Godhani launches an AI agent that does the work on the call.</p></div>
					<div class="bau-timeline"><span>Engineering</span><strong>~300 ms response time</strong><p>Conversations flow naturally, without long pauses or talking over callers.</p></div>
					<div class="bau-timeline bau-timeline--coral"><span>Today</span><strong>500+ businesses</strong><p>Across healthcare, real estate, finance, insurance, contact centers and agencies.</p></div>
				</div>
				<div class="bau-actions">
					<a class="bau-btn bau-btn--ghost" href="<?php echo esc_url(home_url('/become-a-partner/')); ?>">Become a partner</a>
					<a class="bau-btn bau-btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">Talk to our team</a>
				</div>
			</div>
		</section>

		<section id="different" class="bau-section bau-section--dark" aria-labelledby="diff-title">
			<div class="bau-wrap">
				<header class="bau-heading">
					<p class="bau-kicker bau-kicker--dark">What makes us different</p>
					<h2 id="diff-title">Not a call menu with a nicer voice</h2>
					<p class="bau-lede bau-lede--inverse">Here’s what you get with Botphonic that you won’t get from an IVR, a human answering service or a developer-only voice API.</p>
				</header>
				<div class="bau-grid bau-grid--different">
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg><h3>It does the work, not just the talking</h3><p>Books into your live calendar, looks up orders, captures PCI-compliant payments, qualifies leads and updates Salesforce, HubSpot or Zoho while the caller is still on the line.</p></article>
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6M8 13h8M8 17h5"></path></svg><h3>Every call on the record</h3><p>Recordings, transcripts, summaries, sentiment and QA scoring for every conversation, so you always know what was said and what was done.</p></article>
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg><h3>Live in minutes, not weeks</h3><p>Connect a number, paste your FAQs, pick a voice. No code needed, and a voice-AI engineer is on hand if you want help tuning your agent.</p></article>
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"></path></svg><h3>Keep your phone system</h3><p>SIP trunking into Twilio, Telnyx, Plivo or your own telephony. Keep your current numbers, with nothing to port.</p></article>
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3v18h18"></path><path d="M7 15l4-4 3 3 5-6"></path></svg><h3>One agent, inbound and outbound</h3><p>The same voice, knowledge base and rules answer your calls, run reminder and renewal campaigns, and follow up on new leads within seconds.</p></article>
					<article class="bau-dark-card"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle></svg><h3>Honest, per-minute pricing</h3><p>Plans from $20 a month, or pay as you go at $0.12 per talk minute. Unlimited AI agents and all 50+ languages included, with no seat licenses and no setup fee.</p></article>
				</div>
			</div>
		</section>

		<section id="languages" class="bau-section" aria-labelledby="lang-title">
			<div class="bau-wrap bau-grid bau-grid--split bau-align-start">
				<div class="bau-stack bau-stack--lg">
					<p class="bau-kicker">Multilingual by design</p>
					<h2 id="lang-title">Speaks 50+ languages, and switches the moment your caller does</h2>
					<p class="bau-lede bau-lede--small">One multilingual AI voice agent covers every market you sell into. It detects the caller’s language in the first sentence, replies in kind, and can change languages mid-call without a transfer. Your script, knowledge base and rules stay the same in every language.</p>
					<div class="bau-language-stats">
						<div><strong>50+</strong><span>Languages and regional variants</span></div>
						<div><strong>65+</strong><span>Natural male and female voices, by accent</span></div>
						<div><strong>Mid-call</strong><span>Language switching, no handoff</span></div>
						<div><strong>1 agent</strong><span>Same script, knowledge base and rules in every language</span></div>
					</div>
					<a class="bau-text-link" href="<?php echo esc_url(home_url('/multilingual-voice-ai-agents/')); ?>">See the full language and voice list</a>
				</div>
				<div class="bau-language-groups">
					<?php foreach ($bp_about_languages as $group_name => $languages): ?>
					<div class="bau-language-group">
						<h3><?php echo esc_html($group_name); ?></h3>
						<ul>
							<?php foreach ($languages as $language): ?>
							<li<?php echo !empty($language['more']) ? ' class="is-more"' : ''; ?>><?php echo esc_html($language['name']); ?><?php if (!empty($language['detail'])): ?> <span><?php echo esc_html($language['detail']); ?></span><?php endif; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section id="who-we-serve" class="bau-section bau-section--tint" aria-labelledby="serve-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--compact">
					<p class="bau-kicker">Who we serve</p>
					<h2 id="serve-title">Built for industries where a missed call is a lost customer</h2>
				</header>
				<div class="bau-choice-group" role="group" aria-label="Choose your industry" data-bau-audience-controls>
					<?php foreach ($bp_about_audiences as $audience_id => $audience): ?>
					<button class="bau-choice<?php echo 'health' === $audience_id ? ' is-active' : ''; ?>" type="button" data-bau-audience="<?php echo esc_attr($audience_id); ?>" aria-controls="bau-audience-<?php echo esc_attr($audience_id); ?>" aria-pressed="<?php echo 'health' === $audience_id ? 'true' : 'false'; ?>"><?php echo esc_html($audience['label']); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="bau-audience-output" aria-live="polite">
					<?php foreach ($bp_about_audiences as $audience_id => $audience): ?>
					<article id="bau-audience-<?php echo esc_attr($audience_id); ?>" class="bau-audience-panel<?php echo 'health' === $audience_id ? ' is-active' : ''; ?>" data-bau-audience-panel="<?php echo esc_attr($audience_id); ?>">
						<div class="bau-stack">
							<h3><?php echo esc_html($audience['title']); ?></h3>
							<p><?php echo esc_html($audience['pain']); ?></p>
							<a class="bau-btn bau-btn--ink" href="<?php echo esc_url($audience['href']); ?>"><?php echo esc_html($audience['cta']); ?><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></a>
						</div>
						<ul class="bau-check-list">
							<?php foreach ($audience['points'] as $point): ?>
							<li><span aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"></path></svg></span><?php echo esc_html($point); ?></li>
							<?php endforeach; ?>
						</ul>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="bau-section" aria-labelledby="how-title">
			<div class="bau-wrap">
				<header class="bau-heading bau-heading--center">
					<p class="bau-kicker">How Botphonic works</p>
					<h2 id="how-title">From the first ring to the CRM note, you stay in control</h2>
				</header>
				<ol class="bau-grid bau-grid--steps">
					<li class="bau-step"><span aria-hidden="true">1</span><h3>Answer or dial</h3><p>The agent picks up instantly or calls from a list or CRM trigger, screening spam before a word is spoken.</p></li>
					<li class="bau-step"><span aria-hidden="true">2</span><h3>Understand the caller</h3><p>A natural conversation that follows your knowledge base and rules, reading intent and sentiment as it goes.</p></li>
					<li class="bau-step"><span aria-hidden="true">3</span><h3>Take the action</h3><p>Books the slot, takes the payment, qualifies the lead, or transfers to the right person using your live data.</p></li>
					<li class="bau-step"><span aria-hidden="true">4</span><h3>Hand over cleanly</h3><p>Recording, transcript and summary land in your CRM and inbox, with follow-ups already scheduled.</p></li>
				</ol>
			</div>
		</section>

		<section class="bau-section bau-section--tint" aria-labelledby="proof-title">
			<div class="bau-wrap bau-stack bau-stack--section">
				<header class="bau-heading bau-heading--compact">
					<p class="bau-kicker">Proof, not promises</p>
					<h2 id="proof-title">Results from teams that moved their phones to Botphonic</h2>
				</header>
				<div class="bau-case-study">
					<div class="bau-stack">
						<p class="bau-kicker bau-kicker--dark">Success story · Serenity</p>
						<h3>150% ROI after switching call handling to Botphonic</h3>
						<p>Inbound answering, booking and follow-up moved to the AI agent. Staff kept the conversations that needed a person.</p>
						<a class="bau-text-link bau-text-link--inverse" href="<?php echo esc_url(home_url('/success-stories/')); ?>">Read the success story</a>
					</div>
					<div class="bau-case-metrics">
						<div><strong>25%</strong><span>higher conversion on inbound inquiries</span></div>
						<div><strong>50%</strong><span>shorter call handling time</span></div>
						<div><strong>20%</strong><span>fewer errors in bookings and records</span></div>
						<div><strong>15%</strong><span>higher agent satisfaction</span></div>
					</div>
				</div>
				<div class="bau-grid bau-grid--three">
					<figure class="bau-card bau-testimonial"><blockquote>“Botphonic helps us handle calls instantly and never miss a customer opportunity. The AI sounds natural, works 24/7, and saves our team valuable time.”</blockquote><figcaption><span aria-hidden="true">AV</span><span><strong>Alpesh Vaghasiya</strong><small>CEO, Superworks</small></span></figcaption></figure>
					<figure class="bau-card bau-testimonial"><blockquote>“Botphonic makes handling customer calls effortless. Its AI handles routine conversations 24/7, reducing our team’s workload while keeping every caller supported.”</blockquote><figcaption><span aria-hidden="true">KM</span><span><strong>Ketan Mangukiya</strong><small>Founder &amp; CEO, Healthray</small></span></figcaption></figure>
					<figure class="bau-card bau-testimonial"><blockquote>“Botphonic feels like an extra team member. It handles calls, captures key details, and keeps our team focused on what matters.”</blockquote><figcaption><span aria-hidden="true">DA</span><span><strong>Dr. Alphonsa</strong><small>Executive Director, Ashok One Hospital</small></span></figcaption></figure>
				</div>
				<ul class="bau-ratings" aria-label="Third-party review ratings">
					<li><strong>5.0<small>/5</small></strong><a href="<?php echo esc_url('https://www.g2.com/products/botphonic-ai-call-assistant/reviews'); ?>">G2 reviews</a></li>
					<li><strong>4.8<small>/5</small></strong><span>Capterra</span></li>
					<li><strong>4.8<small>/5</small></strong><span>SoftwareSuggest</span></li>
					<li><strong>4.5<small>/5</small></strong><a href="<?php echo esc_url('https://www.trustpilot.com/review/botphonic.ai'); ?>">Trustpilot</a></li>
					<li><strong>4.2<small>/5</small></strong><span>SaaSworthy</span></li>
				</ul>
			</div>
		</section>

		<section class="bau-section" aria-labelledby="trust-title">
			<div class="bau-wrap bau-grid bau-grid--trust bau-align-start">
				<div class="bau-stack">
					<p class="bau-kicker">Security &amp; compliance</p>
					<h2 id="trust-title">Built for regulated calls from day one</h2>
					<p class="bau-lede bau-lede--small">Hospitals, lenders and contact centers trust Botphonic with sensitive conversations. Your data is encrypted in transit and at rest, and you decide who can hear recordings, read transcripts or change a script.</p>
					<a class="bau-text-link" href="<?php echo esc_url(home_url('/security/')); ?>">Read the full security overview</a>
				</div>
				<ul class="bau-trust-grid">
					<li><strong>HIPAA-ready</strong><span>PHI handled under BAA-compatible controls, with audit logs.</span></li>
					<li><strong>PCI DSS payments</strong><span>Card details never touch recordings, transcripts or agents.</span></li>
					<li><strong>SOC 2 Type II ready</strong><span>Controls, continuous penetration testing and MFA.</span></li>
					<li><strong>GDPR-aligned</strong><span>Regional data residency, retention controls and a DPA on request.</span></li>
					<li><strong>TCPA controls</strong><span>Consent tracking, calling windows and opt-out handling.</span></li>
					<li><strong>Role-based access</strong><span>Nothing moves without explicit permission.</span></li>
				</ul>
			</div>
		</section>

		<section id="estimate" class="bau-section bau-section--tint" aria-labelledby="est-title">
			<div class="bau-wrap bau-grid bau-grid--estimate">
				<div class="bau-stack">
					<p class="bau-kicker">Why we price by the minute</p>
					<h2 id="est-title">See what answering every call could cost you</h2>
					<p class="bau-lede bau-lede--small">We believe you should pay for conversations, not seats. Pick your monthly call minutes to compare Botphonic with a typical human answering service.</p>
					<div class="bau-choice-group" role="group" aria-label="Choose your monthly talk minutes" data-bau-estimator-controls>
						<?php foreach (array(250, 500, 1000, 2500) as $minutes): ?>
						<button class="bau-choice<?php echo 500 === $minutes ? ' is-active' : ''; ?>" type="button" data-bau-minutes="<?php echo esc_attr((string) $minutes); ?>" aria-pressed="<?php echo 500 === $minutes ? 'true' : 'false'; ?>"><?php echo esc_html(number_format_i18n($minutes, 0)); ?> min</button>
						<?php endforeach; ?>
					</div>
					<a class="bau-text-link" href="<?php echo esc_url(home_url('/pricing/')); ?>">Compare all plans and pricing</a>
				</div>
				<div class="bau-estimate" aria-live="polite">
					<span class="bau-estimate__label">Estimated monthly cost for <strong data-bau-minutes-label>500</strong> talk minutes</span>
					<div class="bau-estimate__cards">
						<div class="bau-estimate-card bau-estimate-card--dark"><span>Botphonic</span><strong data-bau-bot-cost>$60</strong><p>Pay as you go at $0.12 per minute, with up to 25 concurrent calls. Books appointments and updates your CRM.</p></div>
						<div class="bau-estimate-card"><span>Human answering service</span><strong data-bau-human-cost>$500–$1,000</strong><p>At $1–$2 per minute, usually with monthly minimums, and most only take a message.</p></div>
					</div>
					<p class="bau-savings">You could save <strong data-bau-saving>$440–$940</strong> a month.</p>
					<p class="bau-estimate__fine">Estimates use Botphonic’s pay-as-you-go rate and typical answering service rates. Subscription plans start at $20 a month for 50 minutes. Costs vary by market and provider.</p>
				</div>
			</div>
		</section>

		<section id="faq" class="bau-section" aria-labelledby="faq-title">
			<div class="bau-wrap bau-faq-layout">
				<div class="bau-stack bau-faq-intro">
					<p class="bau-kicker">FAQ</p>
					<h2 id="faq-title">Questions people ask about Botphonic</h2>
					<p>Still unsure? Hear the agent on your own use case.</p>
					<a class="bau-btn bau-btn--ink" href="<?php echo esc_url('https://calendly.com/contact-botphonic/product-discovery'); ?>">Book a 20-minute demo</a>
				</div>
				<div class="bau-faq-list">
					<?php
					if (function_exists('botphonic_render_faq_group')) {
						echo botphonic_render_faq_group($bp_about_faqs); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer.
					}
					?>
				</div>
			</div>
		</section>

		<section class="bau-section bau-section--dark bau-final" aria-labelledby="cta-title">
			<div class="bau-final__inner">
				<h2 id="cta-title">Give your phones to an AI agent this afternoon</h2>
				<p>Connect a number, paste your FAQs, pick a voice. Your first call is answered in minutes, and you only pay for talk time.</p>
				<div class="bau-actions bau-actions--center">
					<a class="bau-btn bau-btn--coral" href="<?php echo esc_url('https://app.botphonic.ai/register/'); ?>">Start 14-day free trial</a>
					<a class="bau-btn bau-btn--ghost-dark" href="<?php echo esc_url(home_url('/pricing/')); ?>">See pricing</a>
				</div>
				<small>14-day free trial · 20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime</small>
			</div>
		</section>
	</main>
</div>

<?php
unset($bp_about_stats, $bp_about_languages, $bp_about_audiences, $bp_about_faqs);
get_footer();
