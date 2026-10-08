<?php

/**
 * Template Name: Cold Mail
 *
 * Cold Email landing page.
 *
 * Every section carries `bpl bpcm`: `.bpl` opts into the shared landing design
 * system (assets/css/common-landing.css — tokens, resets, .bpl-wrap, .bpl-sec),
 * and `.bpcm` is this page's hook in assets/css/cold-mail.css.
 *
 * The classes are put on each section rather than on one outer wrapper on
 * purpose. The three shortcode sections ([usa_integrations], [botphonic_faq],
 * [cold_mail_cta]) render their own markup with their own stylesheets, and the
 * `.bpl` reset is broad enough (`:where(p){margin:0}`,
 * `:where(ul,ol){list-style:none}`) to disturb them. Keeping them outside any
 * `.bpl` root leaves them exactly as they were.
 *
 * Copy, headings, statistics and benchmark figures are unchanged from the
 * previous version of this template.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

/** Inline SVG icons, so the page pulls in no icon font. */
$bpcm_icon = static function ($name) {
	$icons = array(
		'chevron' => '<path d="M6 9l6 6 6-6"/>',
		'check' => '<path d="M20 6L9 17l-5-5"/>',
		'copy' => '<path d="M8 8H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-3"/><rect x="8" y="3" width="13" height="13" rx="2"/>',
		'bulb' => '<path d="M9 18h6M10 22h4M12 2a6 6 0 0 0-3.5 10.9V15h7v-2.1A6 6 0 0 0 12 2z"/>',
	);

	if (!isset($icons[$name])) {
		return '';
	}

	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[$name] . '</svg>';
};
?>

<!-- ── Hero ──────────────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec bpcm-hero">
	<div class="bpl-wrap">
		<div class="bpcm-hero__inner">
			<p class="bpcm-eyebrow">Cold email automation</p>
			<h1 class="bpcm-hero__title">
				<span class="bpcm-accent">Automate Your Cold Emails</span> Seamlessly and Watch Your Sales Grow.
			</h1>
			<p class="bpcm-hero__lede">
				Unlock 5x more meetings and opportunities while closing deals faster with seamless email automation and smart integrations.
			</p>
			<div class="bpcm-hero__form">
				<?php echo do_shortcode('[contact-form-7 id="fdc94f2" title="Sign UP Form"]'); ?>
			</div>
		</div>
	</div>
</section>

<?php
$strategySteps = array(
	array(
		'title' => 'Step 1: Research & Segmentation',
		'text' => 'Understand your target audience, segment leads effectively, and tailor your message to specific pain points.',
		'tip' => 'Pro Tip: Personalization Matters',
		'tipContent' => 'Emails with personalized subject lines see 26% higher open rates.*',
	),
	array(
		'title' => 'Step 2: Craft Engaging Emails',
		'text' => 'Write concise, value-driven emails with clear CTAs. Use AI personalization to increase response rates.',
		'tip' => 'Pro Tip: Keep it Short',
		'tipContent' => 'Emails under 150 words have significantly higher reply rates.*',
	),
	array(
		'title' => 'Step 3: Follow-Up Strategy',
		'text' => 'Use automated, timed follow-ups to maximize engagement without overwhelming your prospects.',
		'tip' => 'Pro Tip: Timing is Key',
		'tipContent' => 'Send follow-ups 2–3 days after the initial email for best results.*',
	),
);
?>
<!-- ── Strategy ──────────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec" id="strategy" aria-labelledby="bpcm-strategy-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-strategy-title">Cold Email Strategy &amp; Best Practices</h2>
			<p class="bpcm-head__lede">Learn actionable techniques to craft high-performing cold emails and maximize your outreach success.</p>
		</div>

		<div class="bpcm-steps">
			<?php foreach ($strategySteps as $i => $step) : ?>
			<article class="bpcm-card bpcm-step">
				<span class="bpcm-step__n" aria-hidden="true"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
				<h3 class="bpcm-step__title"><?php echo esc_html($step['title']); ?></h3>
				<p class="bpcm-step__text"><?php echo esc_html($step['text']); ?></p>

				<?php
				// Native <details>: keyboard operable and self-announcing. This was
				// a <div> with a click handler, which was neither.
				?>
				<details class="bpcm-tip">
					<summary class="bpcm-tip__summary">
						<?php echo $bpcm_icon('bulb'); ?>
						<span><?php echo esc_html($step['tip']); ?></span>
						<?php echo $bpcm_icon('chevron'); ?>
					</summary>
					<p class="bpcm-tip__body"><?php echo esc_html($step['tipContent']); ?></p>
				</details>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$bfSteps = array(
	array(
		'title' => 'Turn Outreach Into Revenue',
		'description' => 'Craft automated cold emails combined with LinkedIn actions and conditional logic, in minutes, using a smart drag-and-drop editor.',
		'image' => 'https://botphonic.ai/wp-content/uploads/2025/12/Turn-Outreach-Into-Revenue-Botphonic.webp',
	),
	array(
		'title' => 'Streamline Outreach with Verified Lead Emails',
		'description' => 'Identify and access accurate work emails for your leads and focus on engagement instead of guessing contact info.',
		'image' => 'https://botphonic.ai/wp-content/uploads/2025/12/Streamline-Outreach-with-Verified-Lead-Emails-Botphonic.webp',
	),
	array(
		'title' => 'Stand Out with Hyper-Personalized Outreach',
		'description' => 'Personalize each email using variables and send emails that resonate, engage, and drive higher response rates.',
		'image' => 'https://botphonic.ai/wp-content/uploads/2025/12/Stand-Out-with-Hyper-Personalized-Outreach-Botphonic.webp',
	),
	array(
		'title' => 'Advanced Analytics for Smarter Email Campaigns',
		'description' => 'Track email deliverability, engagement, and response rates with precision, giving you actionable insight to increase conversions and ROI.',
		'image' => 'https://botphonic.ai/wp-content/uploads/2025/12/Advanced-Analytics-for-Smarter-Email-Campaigns-Botphonic.webp',
	),
	array(
		'title' => 'Centralize Your Email Outreach',
		'description' => 'Track, respond, and manage emails from multiple accounts in one centralized hub, so you can respond faster and stay organized without juggling inboxes.',
		'image' => 'https://botphonic.ai/wp-content/uploads/2025/12/Centralize-Your-Email-Outreach-Botphonic.webp',
	),
);
?>
<!-- ── Feature walkthrough ───────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec bpl-sec--tint" aria-labelledby="bpcm-walk-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-walk-title">Elevate Your Lead Generation with Smarter Email Automation</h2>
			<p class="bpcm-head__lede">Take control of your outreach with automated email sequences, craft personalized cold emails, and watch your sales pipeline grow.</p>
		</div>

		<div class="bpcm-walk" data-bpcm-walk>
			<ul class="bpcm-walk__list">
				<?php foreach ($bfSteps as $index => $step) : ?>
				<li class="bpcm-walk__item<?php echo 0 === $index ? ' is-active' : ''; ?>">
					<?php
					// The image URL lives only here. assets/js/cold-mail.js reads it
					// off the button, so there is no second list to keep in step.
					?>
					<button
						class="bpcm-walk__btn"
						type="button"
						aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						data-bpcm-img="<?php echo esc_url($step['image']); ?>"
						data-bpcm-alt="<?php echo esc_attr($step['title']); ?>">
						<img class="bpcm-walk__thumb" src="<?php echo esc_url($step['image']); ?>" alt="" loading="lazy" decoding="async" width="640" height="400">
						<span class="bpcm-walk__title"><?php echo esc_html($step['title']); ?></span>
						<span class="bpcm-walk__text"><?php echo esc_html($step['description']); ?></span>
					</button>
				</li>
				<?php endforeach; ?>
			</ul>

			<figure class="bpcm-walk__media">
				<img
					data-bpcm-preview
					src="<?php echo esc_url($bfSteps[0]['image']); ?>"
					alt="<?php echo esc_attr($bfSteps[0]['title']); ?>"
					width="640" height="400" decoding="async">
			</figure>
		</div>
	</div>
</section>

<?php
$emailStats = array(
	array('value' => '3,600+', 'text' => 'Emails Sent Every Month'),
	array('value' => '98.5%', 'text' => 'Delivery Rate'),
	array('value' => '35%', 'text' => 'Open Rate'),
	array('value' => '11x', 'text' => 'More Efficient Outreach'),
	array('value' => '21%', 'text' => 'Conversation-to-Conversion Rate'),
	array('value' => '150%', 'text' => 'Improved ROI with Smarter Automation'),
);
?>
<!-- ── Result metrics ────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec" aria-labelledby="bpcm-stats-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-stats-title">Turn Cold Emails Into Warm Conversations</h2>
			<p class="bpcm-head__lede">Results our customers have seen using smart sequences and AI-powered personalization:*</p>
		</div>

		<ul class="bpcm-stats">
			<?php foreach ($emailStats as $stat) : ?>
			<li class="bpcm-card bpcm-stat">
				<p class="bpcm-stat__value"><?php echo esc_html($stat['value']); ?></p>
				<p class="bpcm-stat__label"><?php echo esc_html($stat['text']); ?></p>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- ── A/B testing ───────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec" aria-labelledby="bpcm-ab-title">
	<div class="bpl-wrap">
		<div class="bpcm-ab">
			<div class="bpcm-ab__visual" aria-hidden="true">
				<div class="bpcm-mail bpcm-mail--a">
					<p class="bpcm-mail__name">Email A</p>
					<p class="bpcm-mail__metric">Open rate <b>31%</b></p>
					<p class="bpcm-mail__metric">Reply rate <b>18%</b></p>
					<ul class="bpcm-mail__lines">
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>

				<div class="bpcm-mail bpcm-mail--b">
					<span class="bpcm-mail__win"><?php echo $bpcm_icon('check'); ?></span>
					<p class="bpcm-mail__name">Email B</p>
					<p class="bpcm-mail__metric">Open rate <b>44%</b></p>
					<p class="bpcm-mail__metric">Reply rate <b>27%</b></p>
					<ul class="bpcm-mail__lines">
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
			</div>

			<div class="bpcm-ab__copy">
				<h2 class="bpcm-head__title" id="bpcm-ab-title">Find What Works: Smart Email A/B Testing</h2>
				<p class="bpcm-head__lede" style="margin-left:0;text-align:left">Test different versions of your emails with different variables and see which messages drive the highest open and reply rates.</p>
				<ul class="bpcm-checks">
					<li>Choose the winning version yourself, or let Botphonic decide automatically</li>
					<li>Boost your ROI with smart split testing</li>
					<li>Enhance every variant with hyper-personalization</li>
					<li>Experiment, optimize, and win</li>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php
$coldTemplates = array(
	array(
		'title' => 'Intro Email Template',
		'body' => 'Hi [First Name], I noticed [Pain Point]. Our solution [Solution] can help you achieve [Benefit]. Would you like a quick call?',
	),
	array(
		'title' => 'Follow-Up Template',
		'body' => 'Hi [First Name], just checking in! Did you have a chance to review my last email? Let me know if I can clarify anything.',
	),
	array(
		'title' => 'Demo Request Template',
		'body' => 'Hello [First Name], I\'d love to show you how [Product] can help [Benefit]. Are you available for a 15-minute demo this week?',
	),
);
?>
<!-- ── Templates ─────────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec bpl-sec--tint" id="templates" aria-labelledby="bpcm-tpl-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-tpl-title">Cold Email Templates &amp; Examples</h2>
			<p class="bpcm-head__lede">Use ready-made, high-converting email templates to save time and improve your response rates.</p>
		</div>

		<div class="bpcm-tpls">
			<?php foreach ($coldTemplates as $tpl) : ?>
			<article class="bpcm-card bpcm-tpl">
				<h3 class="bpcm-tpl__title"><?php echo esc_html($tpl['title']); ?></h3>
				<p class="bpcm-tpl__body"><?php echo esc_html($tpl['body']); ?></p>
				<?php
				// No data-text duplicate of the copy: the script reads the visible
				// paragraph, so the wording can never drift from what is copied.
				?>
				<button class="bpcm-copy" type="button" data-bpcm-copy>
					<?php echo $bpcm_icon('copy'); ?>
					<span data-bpcm-copy-label>Copy</span>
				</button>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
// Shortcode sections stay outside any .bpl root so their own stylesheets keep
// full control of their markup.
?>
<section>
	<?php echo do_shortcode('[usa_integrations]'); ?>
</section>

<!-- ── Sales features ────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec" aria-labelledby="bpcm-sales-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-sales-title">Explore Every Feature That Drives Sales</h2>
			<p class="bpcm-head__lede">Generate a steady flow of qualified leads while saving time with smart email outreach.</p>
		</div>

		<div class="bpcm-bento">
			<article class="bpcm-bento__card bpcm-bento__card--wide">
				<h3 class="bpcm-bento__title">Automate Your Cold Email Outreach</h3>
				<p class="bpcm-bento__text">Advanced email automation, smart follow-ups, and powerful integrations to maintain a continuous stream of ready-to-convert leads.</p>
				<ul class="bpcm-pills">
					<li>Enrich Leads in Real Time</li>
					<li>Hyper-Personalization</li>
					<li>Unified Inbox</li>
					<li>Seamless Team Collaboration</li>
					<li>In-Depth Analytics</li>
					<li>A/B Testing to Find What Works</li>
					<li>Lead Qualification Automation</li>
					<li>Integrate with Your Favorite Tools</li>
				</ul>
			</article>

			<article class="bpcm-bento__card bpcm-bento__card--find">
				<h3 class="bpcm-bento__title">Discover Verified Emails</h3>
				<p class="bpcm-bento__text">Access accurate corporate email addresses for your prospects, ensuring you reach the right people with precision.</p>
				<p class="bpcm-scan">Searching verified emails&hellip;</p>
			</article>

			<article class="bpcm-bento__card bpcm-bento__card--team">
				<h3 class="bpcm-bento__title">Seamless Team Management</h3>
				<p class="bpcm-bento__text">Coordinate efforts, share insights, track progress, and scale your outreach to reach more leads and close deals faster.</p>
				<ul class="bpcm-team">
					<li>Campaign Manager <span>Active</span></li>
					<li>Sales Lead <span>Active</span></li>
				</ul>
			</article>
		</div>
	</div>
</section>

<?php
$benchmarks = array(
	array('metric' => 'Open Rate', 'b2b' => '20–30%', 'sales' => '25–35%', 'marketing' => '18–28%'),
	array('metric' => 'Reply Rate', 'b2b' => '5–10%', 'sales' => '8–12%', 'marketing' => '3–7%'),
	array('metric' => 'Click Rate', 'b2b' => '2–5%', 'sales' => '3–6%', 'marketing' => '1–4%'),
	array('metric' => 'Conversion Rate', 'b2b' => '1–3%', 'sales' => '2–4%', 'marketing' => '1–2%'),
);
?>
<!-- ── Benchmarks ────────────────────────────────────────────────── -->
<section class="bpl bpcm bpl-sec bpl-sec--tint" aria-labelledby="bpcm-bench-title">
	<div class="bpl-wrap">
		<div class="bpcm-head">
			<h2 class="bpcm-head__title" id="bpcm-bench-title">Cold Email Benchmarks &amp; Results</h2>
			<p class="bpcm-head__lede">See industry benchmarks to measure your cold email performance and set realistic expectations.</p>
		</div>

		<div class="bpcm-tablewrap" tabindex="0" role="region" aria-label="Benchmark comparison table">
			<table class="bpcm-table">
				<thead>
					<tr>
						<th scope="col">Metric</th>
						<th scope="col">B2B SaaS</th>
						<th scope="col">Sales Outreach</th>
						<th scope="col">Marketing Campaigns</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($benchmarks as $row) : ?>
					<tr>
						<th scope="row"><?php echo esc_html($row['metric']); ?></th>
						<td><?php echo esc_html($row['b2b']); ?></td>
						<td><?php echo esc_html($row['sales']); ?></td>
						<td><?php echo esc_html($row['marketing']); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<section>
	<?php echo do_shortcode('[botphonic_faq]'); ?>
</section>

<?php
/*
 * `text`, not `subtitle`. The shortcode in shortcodes/cold_mail_cta.php accepts
 * title, text, btn_text and btn_link — the old call passed subtitle="…", which
 * shortcode_atts() discarded, so this block silently rendered its default
 * paragraph instead of the copy written for it.
 */
?>
<section>
	<?php echo do_shortcode('[cold_mail_cta title="See Your First Leads in Less Than 48 Hours" text="Get your cold email campaigns running quickly and see measurable results fast with intelligent email automation. Start your free trial today."]'); ?>
</section>

<?php get_footer(); ?>
