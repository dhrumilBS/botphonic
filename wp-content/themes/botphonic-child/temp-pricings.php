<?php
/**
 * Template Name: Pricing Plans
 * Botphonic pricing page (/pricings-plans/).
 * @package Botphonic
 */

defined('ABSPATH') || exit;

/* =============================================================================
 * 1. Page data — edit here, nowhere else
 * ========================================================================== */

$bp_signup_url = 'https://app.botphonic.ai/register/';
$bp_contact_url = 'https://botphonic.ai/contact/';
$bp_demo_url = 'https://calendly.com/contact-botphonic/product-discovery';
$bp_api_origin = 'https://api.botphonic.ai';

$bp_default_view = 'yearly'; // monthly | yearly | payg
$bp_default_currency = 'USD';    // USD | INR

/**
 * The API returns "Per Minute Credit" in USD even on INR wallet plans, so the
 * rupee rate has to be derived on the client. Kept here so it is changed once.
 */
$bp_usd_inr_rate = 95.5;

/** Add-on price shown under the pay-as-you-go grid, per currency. */
$bp_addon_number = array(
	'USD' => '$1.48',
	'INR' => '₹300',
);

$bp_notes = array(
	'monthly' => 'No subscription charge today. Billing starts after your 14-day free trial.',
	'yearly' => 'Billed once a year. No subscription charge today — billing starts after your 14-day free trial.',
	'payg' => 'Prepaid credit, no subscription and no renewal. Top up whenever you need more minutes.',
);

$bp_foot_trial = '$1 verification charge, refunded within 48 hours.';
$bp_foot_enterprise = 'White-label and reseller terms available for agencies.';
$bp_foot_wallet = 'Credit does not expire. No monthly subscription charge.';

/**
 * Subscription plans.
 *
 * monthly/yearly: price per month in USD. null = custom (Enterprise).
 * features: limited inline markup (<b>) is allowed and filtered on output.
 */
$bp_plans = array(
	array(
		'name' => 'Starter',
		'monthly' => 22,
		'yearly' => 20,
		'minutes' => 50,
		'popular' => false,
		'description' => 'For a single line: a small clinic, agency or practice replacing voicemail.',
		'inherit' => 'The foundation every plan builds on.',
		'cta' => 'Start 14-day free trial',
		'cta_url' => $bp_signup_url,
		'foot' => $bp_foot_trial,
		'features' => array(
			'<b>50 talk minutes</b> per month',
			'<b>5 concurrent calls</b>',
			'Unlimited AI agents, all 50+ languages',
			'Outbound batch campaigns',
			'Team member invitations',
			'REST API and all integrations',
		),
	),
	array(
		'name' => 'Pro',
		'monthly' => 60,
		'yearly' => 55,
		'minutes' => 120,
		'popular' => true,
		'description' => 'For growing teams running campaigns alongside inbound answering.',
		'inherit' => 'Everything in Starter, included.',
		'cta' => 'Start 14-day free trial',
		'cta_url' => $bp_signup_url,
		'foot' => $bp_foot_trial,
		'features' => array(
			'<b>120 talk minutes</b> per month',
			'<b>25 concurrent calls</b>',
			'5 custom workflows',
			'Unlimited AI agents, all 50+ languages',
			'Outbound batch campaigns',
			'Email and Google Meet support',
		),
	),
	array(
		'name' => 'Enterprise',
		'monthly' => null,
		'yearly' => null,
		'minutes' => null,
		'popular' => false,
		'description' => 'For call centers and BPOs with high-volume, regulated calling.',
		'inherit' => 'Everything in Pro, included.',
		'cta' => 'Talk to sales',
		'cta_url' => $bp_contact_url,
		'foot' => $bp_foot_enterprise,
		'features' => array(
			'Custom minute bundles',
			'<b>200+ concurrent calls</b>',
			'Unlimited custom workflows and integrations',
			'Boosted call queuing and rebilling',
			'Solution architect and onboarding',
		),
	),
);

/**
 * Pay-as-you-go credit wallets. `currency` decides which cards are visible in
 * the fallback; pricing.js swaps the whole grid once the API responds.
 */
$bp_wallets = array(
	array(
		'name' => 'Nexus pay as you go',
		'currency' => 'USD',
		'wallet' => '$250',
		'per_min' => '$0.40 per talk minute',
		'estimate' => 'Roughly 625 minutes of conversation.',
		'foot' => $bp_foot_wallet,
		'features' => array(
			'<b>25 concurrent calls</b>',
			'5 custom workflows and batch campaigns',
			'Unlimited AI agents, all 50+ languages',
			'5 white-label subaccounts',
			'Solution architect support',
		),
	),
	array(
		'name' => 'Starter pay as you go',
		'currency' => 'INR',
		'wallet' => '₹3,000',
		'per_min' => '₹11.46 per talk minute',
		'estimate' => 'Roughly 262 minutes of conversation.',
		'foot' => $bp_foot_wallet . ' Per-minute rate converted from USD at the prevailing rate.',
		'features' => array(
			'<b>5 concurrent calls</b>',
			'Unlimited AI agents, all 50+ languages',
			'Batch campaigns and real-time booking',
			'3 white-label subaccounts',
			'Email and chat support',
		),
	),
	array(
		'name' => 'Pro pay as you go',
		'currency' => 'INR',
		'wallet' => '₹5,000',
		'per_min' => '₹14.32 per talk minute',
		'estimate' => '',
		'foot' => $bp_foot_wallet . ' Per-minute rate converted from USD at the prevailing rate.',
		'features' => array(
			'<b>10 concurrent calls</b>',
			'5 custom workflows',
			'Unlimited AI agents, all 50+ languages',
			'3 white-label subaccounts',
			'Email, chat and Google Meet support',
		),
	),
);

/** 14-day trial timeline. */
$bp_timeline = array(
	array(
		'when' => 'Today',
		'what' => '$1 card verification',
		'body' => 'A temporary $1 charge confirms your payment method is valid. No subscription charge is taken.',
	),
	array(
		'when' => 'Within 48 hours',
		'what' => '$1 refunded',
		'body' => 'The verification charge is returned automatically. Nothing else is billed.',
	),
	array(
		'when' => 'Days 1–14',
		'what' => '20 free minutes',
		'body' => 'Build agents, connect your number and run real calls with every feature of your chosen plan unlocked.',
	),
	array(
		'when' => 'Day 15',
		'what' => 'Your plan starts',
		'body' => 'Billing begins only if you stay. Cancel any time before day 15 and you pay nothing.',
	),
);

/** Cost-of-alternatives cards. */
$bp_cost_cards = array(
	array(
		'title' => 'Botphonic AI voice agent',
		'fig' => 'From $20/month',
		'body' => 'Unlimited simultaneous calls, 24/7 cover, books appointments and updates your CRM. Scales by adding minutes, not people.',
		'best' => true,
	),
	array(
		'title' => 'Human answering service',
		'fig' => '$1–$2 per minute',
		'body' => 'Monthly minimums, queues at peak, and most services take a message rather than completing the booking.',
		'best' => false,
	),
	array(
		'title' => 'In-house receptionist',
		'fig' => 'Full salary, one shift',
		'body' => 'Covers business hours only, one call at a time, plus cover for holidays and sickness.',
		'best' => false,
	),
);


$bp_matrix_heading = 'Compare Starter, Pro and pay as you go feature by feature';
$bp_matrix_lede = 'The table follows the billing period and currency you select. Enterprise is quoted individually &mdash; <a class="bpp-link" href="' . esc_url($bp_contact_url) . '">talk to sales</a> for those numbers.';
$bp_matrix_caption = 'Feature comparison of Botphonic AI voice agent plans.';
$bp_matrix_footnote = 'Need more minutes than a plan includes? Additional minutes are billed at your plan&rsquo;s overage rate, and you can upgrade mid-cycle paying only the difference.';
$bp_matrix_skip = array('New Phone Number');

$bp_matrix_labels = array(
	'Minutes Included' => 'Talk minutes included',
	'Per Minute Credit' => 'Per-minute rate',
	'Concurrent Calls' => 'Concurrent calls',
	'Boosted Queuing for Calls' => 'Boosted call queuing',
	'Conversational Voice Engine API' => 'Conversational voice engine API',
	'LLM Agent' => 'LLM agent',
	'Transcriber' => 'Transcriber',
	'Concurrent API Calls' => 'Concurrent API calls',
	'Concurrent Emails' => 'Concurrent emails',
	'Concurrent CRM Actions' => 'Concurrent CRM actions',
	'Unlimited Agents' => 'Unlimited AI agents',
	'Multi-language' => '50+ languages and 65+ voices',
	'Batch Campaigns' => 'Outbound batch campaigns',
	'Invite Team Members' => 'Team member invitations',
	'Rebilling' => 'Rebilling',
	'Custom Workflows Included' => 'Custom workflows',
	'Real-Time Booking' => 'Real-time appointment booking',
	'Send SMS' => 'Send SMS',
	'Call Transfer' => 'Call transfer',
	'Information Extractor' => 'Information extractor',
	'Custom Actions' => 'Custom actions',
	'Subaccounts' => 'White-label subaccounts',
	'Rest API' => 'REST API',
	'All Existing Integrations' => 'All existing integrations',
	'CRM Sync' => 'CRM sync',
	'Email Support' => 'Email support',
	'Desku Support' => 'Live chat support',
	'Google Meet Support' => 'Google Meet support',
	'Solution Architect' => 'Dedicated solution architect',
);

$bp_matrix_order = array(
	'Minutes Included',
	'Per Minute Credit',
	'Concurrent Calls',
	'Boosted Queuing for Calls',
	'Unlimited Agents',
	'Multi-language',
	'Batch Campaigns',
	'Real-Time Booking',
	'Custom Workflows Included',
	'Invite Team Members',
	'Subaccounts',
	'Rebilling',
	'Rest API',
	'All Existing Integrations',
	'CRM Sync',
	'Email Support',
	'Desku Support',
	'Google Meet Support',
	'Solution Architect',
);

$bp_matrix_absent = array(
	'Minutes Included' => 'Drawn from credit',
	'Per Minute Credit' => 'Included in plan',
);

$bp_matrix_nojs = 'The live feature comparison needs JavaScript. Every plan&rsquo;s limits and headline features are listed on the plan cards above.';

$bp_enterprise_points = array(
	'Volume rates that fall as your minutes rise',
	'BAA for HIPAA workloads, DPA for GDPR, PCI DSS payment capture',
	'Regional data residency and retention controls',
	'Migration from your existing IVR or answering service',
	'White-label for agencies reselling to their own clients',
);

$bp_ratings = array(
	array(
		'name' => 'G2',
		'logo' => 'https://botphonic.ai/wp-content/uploads/2025/12/g2-logo.webp',
		'score' => '5.0',
		'url' => 'https://www.g2.com/products/botphonic-ai-call-assistant/reviews',
		'label' => 'Read reviews',
	),
	array(
		'name' => 'Capterra',
		'logo' => 'https://botphonic.ai/wp-content/uploads/2025/12/capterra-1.webp',
		'score' => '4.8',
		'url' => '',
		'label' => 'Capterra reviews',
	),
	array(
		'name' => 'SoftwareSuggest',
		'logo' => 'https://botphonic.ai/wp-content/uploads/2025/12/softwaresuggest-logo.webp',
		'score' => '4.8',
		'url' => '',
		'label' => 'SoftwareSuggest reviews',
	),
	array(
		'name' => 'Trustpilot',
		'logo' => 'https://botphonic.ai/wp-content/uploads/2025/12/trustpilot-1.webp',
		'score' => '4.5',
		'url' => 'https://www.trustpilot.com/review/botphonic.ai',
		'label' => 'Read reviews',
	),
	array(
		'name' => 'SaaSworthy',
		'logo' => 'https://botphonic.ai/wp-content/uploads/2025/12/saasworthy.webp',
		'score' => '4.2',
		'url' => '',
		'label' => 'SaaSworthy reviews',
	),
);

/** Pricing FAQs. Also the single source for the FAQPage structured data. */
$bp_faqs = array(
	array(
		'q' => 'How much does an AI voice agent cost?',
		'a' => 'Botphonic subscription plans start at $20 per month on Starter billed yearly, or $22 billed monthly, which includes 50 talk minutes and 5 concurrent calls. Pro is $55 per month billed yearly and includes 120 minutes and 25 concurrent calls. There is also a pay-as-you-go option: a $250 credit wallet billed at $0.40 per talk minute with no monthly commitment. Enterprise pricing is based on your minute volume, concurrency and compliance needs.',
	),
	array(
		'q' => 'Is there a free trial, and do I need a credit card?',
		'a' => 'Every plan starts with a 14-day free trial that includes 20 free minutes. A card is required for a $1 verification charge, which is automatically refunded within 48 hours. No subscription charge is taken until day 15, and you can cancel any time before the trial ends.',
	),
	array(
		'q' => 'What is pay as you go and how is it different from a plan?',
		'a' => 'Pay as you go is a prepaid credit wallet instead of a monthly subscription. You top up once, and talk minutes are drawn from the balance at a fixed per-minute rate. Nothing renews automatically and the credit does not expire at the end of a month, which suits seasonal or unpredictable call volume. Subscription plans work out cheaper if your monthly volume is steady.',
	),
	array(
		'q' => 'Should I choose a subscription or pay as you go?',
		'a' => 'Choose a monthly or yearly plan if your call volume is predictable, because included minutes and yearly billing lower your effective cost. Choose pay as you go if your volume is seasonal, if you are running a one-off campaign, or if you want to avoid a recurring charge entirely. You can move between them at any time.',
	),
	array(
		'q' => 'What happens if I use more minutes than my plan includes?',
		'a' => 'Additional minutes are billed at your plan\'s overage rate, and you can upgrade mid-cycle paying only the difference. Pay-as-you-go wallets simply draw down the credit you have topped up, so you top up again whenever you need more minutes.',
	),
	array(
		'q' => 'Can I change or cancel my plan later?',
		'a' => 'Yes. You can upgrade, downgrade or cancel from the dashboard at any time. Upgrades take effect immediately and are prorated; downgrades and cancellations take effect at the end of the current billing period, with no cancellation fee.',
	),
	array(
		'q' => 'Do you charge per seat or per user?',
		'a' => 'No. Botphonic is priced on talk minutes and concurrent calls, not on the number of people in your team. Unlimited assistants are included on every plan, and team member invitations are included from the Pro plan up.',
	),
	array(
		'q' => 'Can I pay in Indian rupees?',
		'a' => 'Yes. Prices can be displayed and billed in USD or INR. Indian customers are billed in rupees with GST applied where required.',
	),
	array(
		'q' => 'How does Botphonic pricing compare with a human answering service?',
		'a' => 'A staffed answering service typically costs $1 to $2 per minute with monthly minimums and limits on how many calls can be handled at once. Botphonic is a fraction of that per minute, handles unlimited simultaneous calls, and books appointments and updates your CRM rather than only taking messages.',
	),
	array(
		'q' => 'Are there setup fees or long-term contracts?',
		'a' => 'No setup fee and no long-term contract on Starter or Pro. Yearly billing is optional and simply costs less than monthly. Enterprise agreements are tailored and can include annual terms if you prefer.',
	),
);

/**
 * Largest yearly saving, so the toggle badge can never contradict the cards.
 * pricing.js recalculates the same figure from live data once it loads.
 */
$bp_max_saving = 0;
foreach ($bp_plans as $bp_plan) {
	if (!empty($bp_plan['monthly']) && !empty($bp_plan['yearly'])) {
		$bp_max_saving = max(
			$bp_max_saving,
			(int) floor((($bp_plan['monthly'] - $bp_plan['yearly']) / $bp_plan['monthly']) * 100)
		);
	}
}

/* =============================================================================
 * 2. Assets — registered on the correct hook, before get_header()
 * ========================================================================== */

add_action(
	'wp_enqueue_scripts',
	function () use ($bp_signup_url, $bp_contact_url, $bp_api_origin, $bp_default_view, $bp_default_currency, $bp_usd_inr_rate, $bp_addon_number, $bp_notes, $bp_plans, $bp_foot_trial, $bp_foot_enterprise, $bp_foot_wallet, $bp_matrix_skip, $bp_matrix_labels, $bp_matrix_order, $bp_matrix_absent) {
		if (!wp_script_is('pricing', 'registered') && current_user_can('manage_options')) {
			error_log('Botphonic: the "pricing" script handle is not registered. Check the priority of enqueue_child_theme_style() in inc/function-enqueue.php — this template must hook later than it.');
		}

		wp_enqueue_style('pricing');
		wp_enqueue_script('pricing');

		$bp_descriptions = array();
		$bp_inherit = array();
		foreach ($bp_plans as $plan) {
			$bp_descriptions[$plan['name']] = $plan['description'];
			$bp_inherit[$plan['name']] = $plan['inherit'];
		}

		wp_localize_script(
			'pricing',
			'BP_PRICING',
			array(
				'apiUrl' => trailingslashit($bp_api_origin) . 'api/plans',
				'signupUrl' => $bp_signup_url,
				'contactUrl' => $bp_contact_url,
				'defaultView' => $bp_default_view,
				'defaultCurrency' => $bp_default_currency,
				'usdInrRate' => $bp_usd_inr_rate,
				'addon' => $bp_addon_number,
				'notes' => $bp_notes,
				'descriptions' => $bp_descriptions,
				'inherit' => $bp_inherit,
				'matrixSkip' => array_values($bp_matrix_skip),
				'matrixLabels' => $bp_matrix_labels,
				'matrixOrder' => array_values($bp_matrix_order),
				'matrixAbsent' => $bp_matrix_absent,
				'i18n' => array(
					/* Card chrome */
					'mostPopular' => 'Most popular',
					'startTrial' => 'Start 14-day free trial',
					'talkToSales' => 'Talk to sales',
					'topUp' => 'Top up and start',
					'custom' => 'Custom',
					'volumePricing' => 'volume pricing',
					'volumeScope' => 'Priced on volume and compliance scope',
					'perMonthSlash' => '/month',
					'minutes' => 'minutes',
					'includedBilled' => 'included · billed',
					'creditWallet' => 'credit wallet',
					'paygFor' => 'Prepaid credit, drawn down per talk minute.',
					'perTalkMinute' => 'per talk minute',
					'roughlyMinutes' => 'Roughly %d minutes of conversation.',
					'saveUpTo' => 'save up to %d%',
					'footTrial' => $bp_foot_trial,
					'footEnterprise' => $bp_foot_enterprise,
					'footWallet' => $bp_foot_wallet,
					'footConverted' => 'Per-minute rate converted from USD at the prevailing rate.',
					'unavailable' => 'Live pricing for this selection is temporarily unavailable.',
					/* Feature comparison table */
					'matrixFeature' => 'Feature',
					'matrixPriceRow' => 'Price',
					'matrixBillingRow' => 'Billing',
					'matrixWalletSuffix' => ' wallet',
					'matrixPrepaid' => 'Prepaid, no renewal',
					'matrixBilled' => 'Billed %s',
					'cycleMonthly' => 'monthly',
					'cycleYearly' => 'yearly',
					'included' => 'Included',
					'notIncluded' => 'Not included',
					'matrixCaption' => 'Feature comparison of Botphonic AI voice agent plans, %s.',
					'inDollars' => 'in US dollars',
					'inRupees' => 'in Indian rupees',
					'matrixLoading' => 'Loading the feature comparison…',
					'matrixEmpty' => 'The feature comparison is not published for this selection yet.',
					'matrixError' => 'The feature comparison could not be loaded. Plan limits and headline features are listed on the plan cards above.',
					/* Feature lines */
					'talkMinutes' => 'talk minutes',
					'perMonth' => 'per month',
					'minuteBundle' => 'minute bundle',
					'concurrentCalls' => 'concurrent calls',
					'unlimitedAgents' => 'Unlimited AI agents, all 50+ languages',
					'batchCampaigns' => 'Outbound batch campaigns',
					'customWorkflows' => 'custom workflows',
					'unlimitedWorkflows' => 'Unlimited custom workflows',
					'inviteTeam' => 'Team member invitations',
					'subaccounts' => 'white-label subaccounts',
					'customSubaccounts' => 'Custom white-label subaccounts',
					'rebilling' => 'Rebilling',
					'boostedQueue' => 'Boosted call queuing',
					/* Support channels */
					'chEmail' => 'Email',
					'chChat' => 'chat',
					'chMeet' => 'Google Meet',
					'and' => 'and',
					'support' => 'support',
					'plusArchitect' => ', plus a solution architect',
					'architect' => 'Dedicated solution architect',
				),
			)
		);
	},
	30 // must be > 25, the priority of enqueue_child_theme_style()
);

add_action(
	'wp_head',
	function () use ($bp_api_origin) {
		printf(
			'<link rel="preconnect" href="%1$s" crossorigin><link rel="dns-prefetch" href="%1$s">' . "\n",
			esc_url($bp_api_origin)
		);
	},
	1
);

/* =============================================================================
 * 3. Structured data
 *
 * Yoast already emits WebPage / BreadcrumbList / Organization for this URL, so
 * only the pricing-specific nodes are added here: a Product carrying an
 * AggregateOffer, and the FAQPage built from $bp_faqs.
 * ========================================================================== */

add_action(
	'wp_head',
	function () use ($bp_plans, $bp_wallets, $bp_faqs, $bp_signup_url, $bp_contact_url) {

		$permalink = get_permalink();
		$offers = array();
		$prices = array();

		foreach ($bp_plans as $plan) {
			if (empty($plan['yearly'])) {
				$offers[] = array(
					'@type' => 'Offer',
					'@id' => $permalink . '#offer-' . sanitize_title($plan['name']),
					'name' => $plan['name'],
					'priceCurrency' => 'USD',
					'url' => $bp_contact_url,
					'availability' => 'https://schema.org/InStock',
					'description' => 'Custom pricing based on minute volume, concurrency and compliance requirements.',
				);
				continue;
			}

			$monthly_equivalent = number_format((float) $plan['yearly'], 2, '.', '');
			$prices[] = (float) $plan['yearly'];

			$offers[] = array(
				'@type' => 'Offer',
				'@id' => $permalink . '#offer-' . sanitize_title($plan['name']),
				'name' => $plan['name'],
				'price' => $monthly_equivalent,
				'priceCurrency' => 'USD',
				'url' => $bp_signup_url,
				'availability' => 'https://schema.org/InStock',
				'priceSpecification' => array(
					'@type' => 'UnitPriceSpecification',
					'price' => $monthly_equivalent,
					'priceCurrency' => 'USD',
					'billingIncrement' => 1,
					'unitText' => 'month',
					'billingDuration' => 12,
				),
			);
		}

		foreach ($bp_wallets as $wallet) {
			if ('USD' !== $wallet['currency']) {
				continue; // schema.org offers are published in a single currency
			}

			$wallet_price = (float) preg_replace('/[^0-9.]/', '', $wallet['wallet']);
			$per_minute = (float) preg_replace('/[^0-9.]/', '', $wallet['per_min']);

			if ($wallet_price <= 0) {
				continue;
			}

			$prices[] = $wallet_price;

			$offers[] = array(
				'@type' => 'Offer',
				'@id' => $permalink . '#offer-' . sanitize_title($wallet['name']),
				'name' => $wallet['name'],
				'price' => number_format($wallet_price, 2, '.', ''),
				'priceCurrency' => 'USD',
				'url' => $bp_signup_url,
				'availability' => 'https://schema.org/InStock',
				'description' => sprintf(
					'Prepaid credit wallet billed at $%s per talk minute with no monthly subscription.',
					number_format($per_minute, 2, '.', '')
				),
				'priceSpecification' => array(
					'@type' => 'UnitPriceSpecification',
					'price' => number_format($per_minute, 2, '.', ''),
					'priceCurrency' => 'USD',
					'unitText' => 'talk minute',
				),
			);
		}

		$faq_entities = array();
		foreach ($bp_faqs as $faq) {
			$faq_entities[] = array(
				'@type' => 'Question',
				'name' => $faq['q'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text' => $faq['a'],
				),
			);
		}

		$graph = array(
			array(
				'@type' => 'Product',
				'@id' => $permalink . '#product',
				'name' => 'Botphonic AI Voice Agent',
				'description' => 'AI voice agent platform for inbound answering, AI reception, outbound calling and call center workloads. Available on monthly or yearly subscription plans, or as a prepaid pay-as-you-go credit wallet.',
				'brand' => array(
					'@type' => 'Brand',
					'name' => 'Botphonic',
				),
				'url' => $permalink,
				'offers' => array(
					'@type' => 'AggregateOffer',
					'priceCurrency' => 'USD',
					'lowPrice' => number_format($prices ? min($prices) : 20, 2, '.', ''),
					'highPrice' => number_format($prices ? max($prices) : 250, 2, '.', ''),
					'offerCount' => (string) count($offers),
					'offers' => $offers,
				),
			),
			array(
				'@type' => 'FAQPage',
				'@id' => $permalink . '#faq',
				'mainEntity' => $faq_entities,
			),
		);

		echo '<script type="application/ld+json">'
			. wp_json_encode(
				array(
					'@context' => 'https://schema.org',
					'@graph' => $graph,
				),
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
			)
			. '</script>' . "\n";
	},
	20
);

/* =============================================================================
 * 4. Render helpers — keeps the markup below readable
 * ========================================================================== */

if (!function_exists('bp_pricing_tick')) {
	/**
	 * Inline tick used in feature bullets and matrix cells.
	 *
	 * @param string $class Optional extra class.
	 * @param string $width Stroke width.
	 * @return string
	 */
	function bp_pricing_tick($class = '', $width = '2.2')
	{
		return sprintf(
			'<svg%1$s viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="%2$s" aria-hidden="true" focusable="false"><path d="M4 10.5l4 4 8-9"/></svg>',
			$class ? ' class="' . esc_attr($class) . '"' : '',
			esc_attr($width)
		);
	}
}

if (!function_exists('bp_pricing_feature_html')) {
	/**
	 * Feature copy allows <b>/<strong> only.
	 *
	 * @param string $text Raw feature copy.
	 * @return string
	 */
	function bp_pricing_feature_html($text)
	{
		return wp_kses(
			$text,
			array(
				'b' => array(),
				'strong' => array(),
			)
		);
	}
}

if (!function_exists('bp_pricing_controls')) {
	/**
	 * Billing period + currency selector.
	 *
	 * Rendered once per section that needs it (plan cards and feature matrix).
	 * Every group carries the same data-bpp-view / data-bpp-currency hooks, so
	 * pricing.js binds and syncs all of them from a single shared state — one
	 * page-wide selection, no second source of truth and no duplicated JS.
	 *
	 * @param array $args {
	 *     @type string $context     Slug used on data-bpp-controls, for debugging.
	 *     @type string $label       Human label folded into the group aria-labels.
	 *     @type string $view        Default view: monthly | yearly | payg.
	 *     @type string $currency    Default currency: USD | INR.
	 *     @type int    $max_saving  Yearly saving badge, 0 hides it.
	 * }
	 * @return void
	 */
	function bp_pricing_controls(array $args)
	{
		$context = isset($args['context']) ? $args['context'] : 'plans';
		$label = isset($args['label']) ? $args['label'] : 'plans';
		$view = isset($args['view']) ? $args['view'] : 'yearly';
		$currency = isset($args['currency']) ? $args['currency'] : 'USD';
		$max_saving = isset($args['max_saving']) ? (int) $args['max_saving'] : 0;

		$views = array(
			'monthly' => 'Monthly',
			'yearly' => 'Yearly',
			'payg' => 'Pay as you go',
		);
		$currencies = array(
			'USD' => 'USD $',
			'INR' => 'INR ₹',
		);
		?>
		<div class="bpp-controls" data-bpp-controls="<?php echo esc_attr($context); ?>">
			<div class="bpp-seg" role="group" aria-label="<?php echo esc_attr('Billing option for ' . $label); ?>">
				<?php foreach ($views as $bp_key => $bp_text): ?>
					<button type="button" data-bpp-view="<?php echo esc_attr($bp_key); ?>" aria-pressed="<?php echo $bp_key === $view ? 'true' : 'false'; ?>">
						<?php echo esc_html($bp_text); ?>
						<?php if ('monthly' !== $bp_key): ?>
							<?php $bp_show = 'yearly' === $bp_key && $max_saving > 0; ?>
							<span class="bpp-save" data-bpp-save="<?php echo esc_attr($bp_key); ?>" <?php echo $bp_show ? '' : ' hidden'; ?>><?php echo $bp_show ? esc_html(sprintf('save up to %d%%', $max_saving)) : ''; ?></span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="bpp-seg" role="group" aria-label="<?php echo esc_attr('Currency for ' . $label); ?>">
				<?php foreach ($currencies as $bp_key => $bp_text): ?>
					<button type="button" data-bpp-currency="<?php echo esc_attr($bp_key); ?>" aria-pressed="<?php echo $bp_key === $currency ? 'true' : 'false'; ?>"><?php echo esc_html($bp_text); ?></button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

if (!function_exists('bp_pricing_bullets')) {
	/**
	 * Renders a plan feature list.
	 *
	 * @param array $features Feature copy.
	 * @return void
	 */
	function bp_pricing_bullets(array $features)
	{
		echo '<ul>';
		foreach ($features as $feature) {
			echo '<li>' . bp_pricing_tick() . '<span>' . bp_pricing_feature_html($feature) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		}
		echo '</ul>';
	}
}

get_header();
?>

<main id="primary" class="bpp" data-bpp-root>

	<!-- ══════════════════════ HERO ══════════════════════ -->
	<section class="bpp-hero" aria-labelledby="bpp-h1">
		<div class="bpp-wrap">
			<h1 id="bpp-h1">AI voice agent pricing that<br class="bpp-br"> scales with your call volume</h1>
			<p class="bpp-lede">Pay for talk minutes and concurrency, never for seats. Every plan includes unlimited AI assistants, all 50+ languages and a 14-day free trial with 20 free minutes.</p>
			<ul class="bpp-pills">
				<?php
				$bp_trial_pills = array(
					'20 free minutes',
					'No charge during the trial',
					'Cancel any time',
					'$1 card check, refunded in 48 h',
				);
				foreach ($bp_trial_pills as $bp_pill) {
					echo '<li>' . bp_pricing_tick('bpp-tick') . esc_html($bp_pill) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
				}
				?>
			</ul>
		</div>
	</section>

	<!-- PLANS - Prices below are a complete server-rendered fallback, so the page is
		 correct for crawlers and if the API is unreachable. On load,
		 assets/js/pricing.js re-renders both grids from the live API. -->
	<section class="bpp-sec bpp-sec--flush" id="plans" aria-labelledby="bpp-h-plans">
		<div class="bpp-wrap">
			<h2 id="bpp-h-plans" class="bpp-sr">Botphonic plans and pricing</h2>

			<?php
			bp_pricing_controls(
				array(
					'context' => 'plans',
					'label' => 'plans',
					'view' => $bp_default_view,
					'currency' => $bp_default_currency,
					'max_saving' => $bp_max_saving,
				)
			);
			?>

			<p class="bpp-note" data-bpp-note><?php echo esc_html($bp_notes[$bp_default_view]); ?></p>

			<!-- Subscription pane -->
			<div class="bpp-view<?php echo 'payg' === $bp_default_view ? ' bpp-off' : ''; ?>" data-bpp-pane="sub" <?php echo 'payg' === $bp_default_view ? ' hidden' : ''; ?>>
				<div class="bpp-plans" data-bpp-grid="sub" aria-live="polite">
					<?php
					foreach ($bp_plans as $bp_plan):
						$bp_is_custom = empty($bp_plan['yearly']);
						$bp_price = 'monthly' === $bp_default_view ? $bp_plan['monthly'] : $bp_plan['yearly'];
						$bp_cycle = 'monthly' === $bp_default_view ? 'monthly' : 'yearly';
						?>
						<article class="bpp-plan<?php echo $bp_plan['popular'] ? ' bpp-plan--best' : ''; ?>">
							<?php if ($bp_plan['popular']): ?>
								<span class="bpp-flag">Most popular</span>
							<?php endif; ?>

							<h3><?php echo esc_html($bp_plan['name']); ?></h3>
							<p class="bpp-for"><?php echo esc_html($bp_plan['description']); ?></p>

							<?php if ($bp_is_custom): ?>
								<div class="bpp-amount bpp-amount--custom">
									<b>Custom</b><span>volume pricing</span>
								</div>
								<p class="bpp-rate">Priced on volume and compliance scope</p>
							<?php else: ?>
								<div class="bpp-amount">
									<b>$<?php echo esc_html($bp_price); ?></b><span>/month</span>
									<?php if ('yearly' === $bp_default_view): ?>
										<span class="bpp-was">$<?php echo esc_html($bp_plan['monthly']); ?></span>
									<?php endif; ?>
								</div>
								<p class="bpp-rate"><b><?php echo esc_html($bp_plan['minutes']); ?> minutes</b> included &middot; billed <?php echo esc_html($bp_cycle); ?></p>
							<?php endif; ?>

							<a class="bpp-btn <?php echo $bp_plan['popular'] ? 'bpp-btn--coral' : ($bp_is_custom ? 'bpp-btn--ghost' : 'bpp-btn--ink'); ?>" href="<?php echo esc_url($bp_plan['cta_url']); ?>" <?php echo $bp_is_custom ? '' : ' target="_blank" rel="noopener"'; ?>><?php echo esc_html($bp_plan['cta']); ?></a>

							<?php bp_pricing_bullets($bp_plan['features']); ?>

							<p class="bpp-inherit"><?php echo esc_html($bp_plan['inherit']); ?></p>
							<p class="bpp-foot"><?php echo esc_html($bp_plan['foot']); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Pay-as-you-go pane. Live data: /api/plans?currency=USD|INR&isWalletPlan=true -->
			<div class="bpp-view<?php echo 'payg' === $bp_default_view ? '' : ' bpp-off'; ?>" data-bpp-pane="payg" <?php echo 'payg' === $bp_default_view ? '' : ' hidden'; ?>>
				<h3 class="bpp-pane-h">Top up a credit wallet and pay per talk minute</h3>
				<p class="bpp-pane-p">No monthly commitment and nothing renews automatically. Credit does not expire at the end of the month, which suits seasonal or unpredictable call volume.</p>

				<div class="bpp-plans" data-bpp-grid="payg" aria-live="polite">
					<?php foreach ($bp_wallets as $bp_wallet): ?>
						<article class="bpp-plan" data-bpp-cur="<?php echo esc_attr($bp_wallet['currency']); ?>" <?php echo $bp_wallet['currency'] === $bp_default_currency ? '' : ' hidden'; ?>>
							<h3><?php echo esc_html($bp_wallet['name']); ?></h3>
							<p class="bpp-for">Prepaid credit, drawn down per talk minute.</p>
							<div class="bpp-amount">
								<b><?php echo esc_html($bp_wallet['wallet']); ?></b><span>credit wallet</span>
							</div>
							<p class="bpp-rate"><span class="bpp-permin"><?php echo esc_html($bp_wallet['per_min']); ?></span></p>
							<a class="bpp-btn bpp-btn--ink" href="<?php echo esc_url($bp_signup_url); ?>" target="_blank" rel="noopener">Top up and start</a>

							<?php bp_pricing_bullets($bp_wallet['features']); ?>

							<p class="bpp-inherit"><?php echo $bp_wallet['estimate'] ? esc_html($bp_wallet['estimate']) : '&nbsp;'; ?></p>
							<p class="bpp-foot"><?php echo esc_html($bp_wallet['foot']); ?></p>
						</article>
					<?php endforeach; ?>
				</div>

				<ul class="bpp-addons" aria-label="Add-ons">
					<li><b data-bpp-addon><?php echo esc_html($bp_addon_number[$bp_default_currency]); ?></b> per new phone number, per month</li>
					<li><b>10</b> concurrent API calls, emails and CRM actions on every plan</li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ══════════════════════ TRIAL TIMELINE ══════════════════════ -->
	<section class="bpp-sec bpp-sec--tint" aria-labelledby="bpp-h-trial">
		<div class="bpp-wrap">
			<div class="bpp-head">
				<h2 id="bpp-h-trial">What happens during your 14-day free trial</h2>
				<p class="bpp-lede">No subscription charge until day 15. The only thing that touches your card before then is a $1 check that comes straight back.</p>
			</div>
			<ol class="bpp-tl">
				<?php foreach ($bp_timeline as $bp_step): ?>
					<li>
						<small><?php echo esc_html($bp_step['when']); ?></small>
						<b><?php echo esc_html($bp_step['what']); ?></b>
						<p><?php echo esc_html($bp_step['body']); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- ══════════════════════ COST COMPARISON ══════════════════════ -->
	<section class="bpp-sec" aria-labelledby="bpp-h-cost">
		<div class="bpp-wrap">
			<div class="bpp-head">
				<h2 id="bpp-h-cost">What an AI answering service costs compared with the alternatives</h2>
				<p class="bpp-lede">The honest comparison for a business handling roughly 500 calls a month.</p>
			</div>
			<ul class="bpp-cost">
				<?php foreach ($bp_cost_cards as $bp_card): ?>
					<li<?php echo $bp_card['best'] ? ' class="bpp-best"' : ''; ?>>
						<h3><?php echo esc_html($bp_card['title']); ?></h3>
						<p class="bpp-fig"><?php echo esc_html($bp_card['fig']); ?></p>
						<p><?php echo esc_html($bp_card['body']); ?></p>
						</li>
					<?php endforeach; ?>
			</ul>
			<p class="bpp-fine">Costs vary by market and provider. See the full <a class="bpp-link" href="https://botphonic.ai/ai-answering-service/">AI answering service</a> and <a class="bpp-link" href="https://botphonic.ai/ai-receptionist/">AI receptionist</a> breakdowns.</p>
		</div>
	</section>
	<section class="bpp-sec bpp-sec--tint" id="compare" aria-labelledby="bpp-h-matrix">
		<div class="bpp-wrap">
			<div class="bpp-head bpp-head--center">
				<h2 id="bpp-h-matrix"><?php echo esc_html($bp_matrix_heading); ?></h2>
				<p class="bpp-lede">
					<?php
					echo wp_kses(
						$bp_matrix_lede,
						array(
							'a' => array(
								'href' => array(),
								'class' => array(),
							),
						)
					);
					?>
				</p>
			</div>

			<?php
			// Same control component as the plan cards. Both groups share one
			// state in pricing.js, so a change here moves the cards too.
			bp_pricing_controls(
				array(
					'context' => 'matrix',
					'label' => 'the feature comparison',
					'view' => $bp_default_view,
					'currency' => $bp_default_currency,
					'max_saving' => $bp_max_saving,
				)
			);
			?>

			<div data-bpp-matrix>
				<p class="bpp-matrix-state" data-bpp-matrix-state role="status"><?php echo wp_kses($bp_matrix_nojs, array()); ?></p>

				<div class="bpp-tbl" data-bpp-matrix-scroll role="region" aria-labelledby="bpp-h-matrix" aria-live="polite" tabindex="0" hidden>
					<table class="bpp-matrix">
						<caption data-bpp-matrix-caption><?php echo esc_html($bp_matrix_caption); ?></caption>
						<thead data-bpp-matrix-head></thead>
						<tbody data-bpp-matrix-body></tbody>
					</table>
				</div>
			</div>

			<p class="bpp-fine"><?php echo wp_kses($bp_matrix_footnote, array()); ?></p>
		</div>
	</section>

	<!-- ══════════════════════ ENTERPRISE ══════════════════════ -->
	<section class="bpp-sec bpp-sec--ink" aria-labelledby="bpp-h-ent">
		<div class="bpp-wrap bpp-ent">
			<div>
				<h2 id="bpp-h-ent">High call volume or regulated calls? Talk to us before you pick a plan</h2>
				<p class="bpp-lede">If you run a contact center, a hospital group or a lender, the right answer is rarely a self-serve plan. Enterprise pricing is built around your minute volume and your compliance obligations.</p>
				<div class="bpp-cta-row" style="margin-top:28px">
					<a class="bpp-btn bpp-btn--coral" href="<?php echo esc_url($bp_contact_url); ?>">Talk to sales</a>
					<a class="bpp-btn bpp-btn--ghost" href="<?php echo esc_url($bp_demo_url); ?>" target="_blank" rel="noopener">Book a 20-minute demo</a>
				</div>
			</div>
			<ul>
				<?php foreach ($bp_enterprise_points as $bp_point): ?>
					<li><?php echo bp_pricing_tick(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><span><?php echo esc_html($bp_point); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- ══════════════════════ RATINGS ══════════════════════ -->
	<section class="bpp-sec" aria-labelledby="bpp-h-rate">
		<div class="bpp-wrap">
			<div class="bpp-head bpp-head--center">
				<h2 id="bpp-h-rate">Rated across the major software review platforms</h2>
			</div>
			<ul class="bpp-ratings">
				<?php foreach ($bp_ratings as $bp_rating): ?>
					<li>
						<img src="<?php echo esc_url($bp_rating['logo']); ?>" alt="<?php echo esc_attr($bp_rating['name']); ?>" width="80" height="28" loading="lazy" decoding="async">
						<div class="bpp-score"><?php echo esc_html($bp_rating['score']); ?><small>/5</small></div>
						<?php if ($bp_rating['url']): ?>
							<a href="<?php echo esc_url($bp_rating['url']); ?>" target="_blank" rel="noopener nofollow"><?php echo esc_html($bp_rating['label']); ?> <span class="bpp-sr">on <?php echo esc_html($bp_rating['name']); ?></span></a>
						<?php else: ?>
							<span><?php echo esc_html($bp_rating['label']); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- ══════════════════════ FAQ ══════════════════════ -->
	<section class="bpp-sec bpp-sec--tint" aria-labelledby="bpp-h-faq">
		<div class="bpp-wrap bpp-faq">
			<div>
				<h2 id="bpp-h-faq">Pricing questions, answered</h2>
				<p class="bpp-lede">Something not covered? <a class="bpp-link" href="<?php echo esc_url($bp_contact_url); ?>">Ask our team</a> or <a class="bpp-link" href="<?php echo esc_url($bp_demo_url); ?>" target="_blank" rel="noopener">book a 20-minute demo</a>.</p>
			</div>
			<?php
			$bp_faq_rows = array_map(
				function ($bp_faq) {
					return array(
						'question' => $bp_faq['q'],
						'answer' => $bp_faq['a'],
					);
				},
				$bp_faqs
			);

			if (function_exists('botphonic_render_faq_group')) {
				echo botphonic_render_faq_group($bp_faq_rows, array('schema' => false)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer.
			}
			unset($bp_faq_rows);
			?>
		</div>
	</section>

	<!-- ══════════════════════ FINAL CTA ══════════════════════ -->
	<section class="bpp-sec bpp-sec--ink bpp-final" aria-labelledby="bpp-h-final">
		<div class="bpp-wrap">
			<h2 id="bpp-h-final">Start free, pay only when it&rsquo;s earning its keep</h2>
			<p class="bpp-lede">Connect a number, build your agent and run 20 minutes of real calls before anything is charged.</p>
			<div class="bpp-cta-row bpp-cta-row--center">
				<a class="bpp-btn bpp-btn--coral" href="<?php echo esc_url($bp_signup_url); ?>" target="_blank" rel="noopener">Start 14-day free trial</a>
				<a class="bpp-btn bpp-btn--ghost" href="<?php echo esc_url($bp_contact_url); ?>">Talk to sales</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();