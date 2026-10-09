<?php
/** * BOTPHONIC CHILD THEME — functions.php *
 * TABLE OF CONTENTS (priority order)
 * --------------------------------------------------------------------
 * 1. CONFIG + INCLUDES
 * 2. GLOBAL FONTS + SHORTCODES + ADMIN BAR
 * 3. SEO — OPEN GRAPH TYPE
 * 4. SEO / SCHEMA — VideoObject for posts with YouTube embeds
 * 5. SEO / SCHEMA — Page-specific nodes for page ID 23537 only
 * 6. SEO / SCHEMA — Page-specific nodes for page ID 2027 only
 * 7. MEDIA / ATTACHMENT HOOKS
 * 8. ADMIN UI
 * 9. HTTP 410 GONE HANDLING
 * 10. CF7 — DUPLICATE EMAIL GUARD (24 HOURS) */

/** * 1. CONFIG + INCLUDES */
if (!defined('CUSTOME_STORY_SLUG')) {
	define('CUSTOME_STORY_SLUG', 'success-stories');
}
if (!defined('BOTPHONIC_ALT_SLUG')) {
	define('BOTPHONIC_ALT_SLUG', 'alternatives');
}

$botphonic_includes = array(
	'function-blog.php',
	'function-author.php',
	'function-story.php',
	'function-alternative.php',
	'acf-alternatives.php',
	'function-solution-pages.php',
	'function-modern-templates.php',
	'function-enqueue.php',
	'function-widget.php',
	'function-cpt.php',
	'function-breadcrumb.php',
	'function-schema.php',
);

foreach ($botphonic_includes as $botphonic_include) {
	$botphonic_include_path = get_stylesheet_directory() . '/inc/' . $botphonic_include;
	if (is_readable($botphonic_include_path)) {
		require_once $botphonic_include_path;
	} elseif (defined('WP_DEBUG') && WP_DEBUG) {
		error_log('Botphonic child theme: missing include ' . $botphonic_include_path);
	}
}
unset($botphonic_includes, $botphonic_include, $botphonic_include_path);


/** * 2. GLOBAL FONTS + SHORTCODES + ADMIN BAR */
if (!defined('BOTPHONIC_FONT_HANDLE')) {
	define('BOTPHONIC_FONT_HANDLE', 'botphonic-manrope');
}

if (!defined('BOTPHONIC_FONT_SRC')) {
	define('BOTPHONIC_FONT_SRC', 'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap');
}

function botphonic_enqueue_global_fonts()
{
	wp_enqueue_style(BOTPHONIC_FONT_HANDLE, BOTPHONIC_FONT_SRC, array(), null);
}
add_action('wp_enqueue_scripts', 'botphonic_enqueue_global_fonts');
add_filter('wp_resource_hints', 'botphonic_font_resource_hints', 10, 2);
function botphonic_font_resource_hints($urls, $relation_type)
{
	if ('preconnect' !== $relation_type) {
		return $urls;
	}

	$urls[] = array('href' => 'https://fonts.googleapis.com');
	$urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous');

	return $urls;
}

add_action('login_enqueue_scripts', 'botphonic_enqueue_login_fonts');
function botphonic_enqueue_login_fonts()
{
	wp_enqueue_style(BOTPHONIC_FONT_HANDLE, BOTPHONIC_FONT_SRC, array(), null);
	wp_add_inline_style(
		BOTPHONIC_FONT_HANDLE,
		'body.login, body.login input, body.login button, body.login label, body.login a { font-family: "Manrope", ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; }'
	);
}

function botphonic_child_load_shortcodes()
{
	$shortcode_dirs = array(
		get_stylesheet_directory() . '/shortcodes/',
		get_stylesheet_directory() . '/shortcodes/usa/',
	);

	foreach ($shortcode_dirs as $shortcode_dir) {
		if (!is_dir($shortcode_dir)) {
			continue;
		}

		$files = glob($shortcode_dir . '*.php');
		if (empty($files)) {
			continue;
		}

		foreach ($files as $shortcode_file) {
			include_once $shortcode_file;
		}
	}
}
add_action('init', 'botphonic_child_load_shortcodes');

/*
 * Admin bar is hidden for everyone unless a logged-in user adds ?h=true.
 */
function botphonic_child_toggle_admin_bar()
{
	if (is_user_logged_in() && isset($_GET['h']) && $_GET['h'] === 'true') {
		add_filter('show_admin_bar', '__return_true');
	} else {
		add_filter('show_admin_bar', '__return_false');
	}
}
add_action('init', 'botphonic_child_toggle_admin_bar');


/** * 3. SEO — Open Graph type */

function botphonic_custom_og_type($og_type)
{

	$website_pages = array(
		2027,
	);

	if (is_page($website_pages)) {
		return 'website';
	}

	return $og_type;
}
add_filter('wpseo_opengraph_type', 'botphonic_custom_og_type');


/** * SCHEMA HELPER — does the graph already contain a node of this type? */
if (!function_exists('botphonic_graph_has_type')) {
	function botphonic_graph_has_type($graph, $type)
	{
		foreach ((array) $graph as $node) {
			$types = isset($node['@type']) ? (array) $node['@type'] : array();
			if (in_array($type, $types, true)) {
				return true;
			}
		}
		return false;
	}
}


/** * 4. SEO / SCHEMA — VideoObject for posts with YouTube embeds */
add_filter('wpseo_schema_graph', function ($data) {

	if (!is_singular())
		return $data;

	global $post;
	if (!$post)
		return $data;

	$content = $post->post_content;
	preg_match(
		'~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?(?:.*&)?v=|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~',
		$content,
		$matches
	);
	$video_id = $matches[1] ?? null;

	if (!$video_id)
		return $data;

	if (botphonic_graph_has_type($data, 'VideoObject')) {
		return $data;
	}

	$video_url = "https://www.youtube.com/watch?v={$video_id}";
	$embed_url = "https://www.youtube.com/embed/{$video_id}";
	$thumbnail = "https://i.ytimg.com/vi/{$video_id}/hqdefault.jpg";
	$post_title = get_the_title($post);
	$description = has_excerpt($post->ID)
		? wp_strip_all_tags(get_the_excerpt($post))
		: wp_trim_words(wp_strip_all_tags($post->post_content), 30);

	$data[] = [
		'@type' => 'VideoObject',
		'@id' => get_permalink($post) . '#video',
		'name' => $post_title,
		'description' => $description,
		'thumbnailUrl' => [$thumbnail],
		'uploadDate' => get_the_date('c', $post),
		'contentUrl' => $video_url,
		'embedUrl' => $embed_url,
		'mainEntityOfPage' => [
			'@id' => get_permalink($post) . '#webpage',
		],
		'publisher' => [
			'@id' => home_url('/#organization'),
		],
	];

	return $data;
}, 10, 1);


/** * 5. SEO / SCHEMA — Page-specific nodes for page ID 23537 only */
add_filter('wpseo_schema_graph', function ($graph, $context) {

	if (!is_page(23537)) {
		return $graph;
	}
	foreach ($graph as $node) {
		if (($node['@id'] ?? '') === home_url('/#software')) {
			return $graph;
		}
	}

	$pricing_url = get_permalink(2027);
	if (!$pricing_url) {
		$pricing_url = home_url('/pricing/');
	}

	$graph[] = [
		'@type' => 'SoftwareApplication',
		'@id' => home_url('/#software'),
		'name' => 'Botphonic AI Voice Agent',
		'applicationCategory' => 'BusinessApplication',
		'applicationSubCategory' => 'AI answering service, AI receptionist, AI call center software',
		'operatingSystem' => 'Web',
		'url' => home_url('/'),
		'image' => content_url('/uploads/2025/04/botPhonic-See-AI-in-action.webp'),
		'publisher' => [
			'@id' => home_url('/#organization'),
		],
		'offers' => [
			'@type' => 'AggregateOffer',
			'lowPrice' => '0.20',
			'highPrice' => '0.40',
			'priceCurrency' => 'USD',
			'url' => $pricing_url,
		],
		'featureList' => [
			'Inbound AI answering service with spam screening',
			'AI receptionist with live calendar booking',
			'Outbound AI phone agent and batch calling',
			'AI call center software with SIP trunking',
			'CRM sync: Salesforce, HubSpot, Zoho',
			'Call recording, transcripts, summaries and sentiment analysis',
			'50+ languages, under 300 ms latency',
		],
	];

	if (botphonic_graph_has_type($graph, 'FAQPage')) {
		return $graph;
	}

	$graph[] = [
		'@type' => 'FAQPage',
		'@id' => home_url('/#faq'),
		'mainEntity' => [
			[
				'@type' => 'Question',
				'name' => 'What is an AI voice agent?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => "An AI voice agent is software that holds a real phone conversation on your behalf. It answers or places calls, understands what the caller wants, takes an action such as booking an appointment or updating a CRM record, and hands your team a summary. Botphonic's agents respond in under 300 ms so the conversation feels natural.",
				],
			],
			[
				'@type' => 'Question',
				'name' => 'How is an AI answering service different from a human answering service?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'A human answering service takes messages and passes them on. An AI answering service like Botphonic answers instantly, 24/7, handles unlimited simultaneous calls, books directly into your calendar and updates your CRM, at a per-minute cost that is a fraction of staffed services.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Can the AI receptionist book appointments into my calendar?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Yes. The AI receptionist checks live availability in Google Calendar, Outlook or your scheduling tool, offers open slots to the caller, confirms the booking and sends a confirmation. It also updates the contact record in Salesforce, HubSpot or Zoho.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Can Botphonic make outbound calls as well as answer them?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Yes. The same AI phone agent runs outbound campaigns: lead follow-up, appointment reminders, payment reminders, surveys and re-engagement. Batch calling runs thousands of calls in parallel with branded caller ID and TCPA-compliant controls.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Does it work with my existing phone system?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Yes. Botphonic connects over SIP trunking to Twilio, Telnyx, Plivo and most VoIP providers, so you keep your current numbers. You can also buy new local or toll-free numbers inside the platform.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Which languages does the AI voice agent speak?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Botphonic speaks 50+ languages and switches language mid-call when the caller does. This includes English variants, Spanish, French, German, Arabic, Hindi, Gujarati, Marathi, Tamil and other Indian languages.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Is Botphonic HIPAA and PCI DSS compliant?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Botphonic is built for regulated calls: HIPAA-ready workflows for healthcare, PCI DSS-compliant payment capture, GDPR-aligned data handling, SOC 2 controls, continuous penetration testing, encryption in transit and at rest, and role-based access to recordings and transcripts.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'How much does an AI call center cost?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Botphonic is priced per minute of talk time, from $0.20 to $0.40 per minute depending on volume, with no seat licences and no minimum call volume. Every plan starts with a 14-day free trial.',
				],
			],
		],
	];

	return $graph;
}, 20, 2);


/** * 6. SEO / SCHEMA — Page-specific nodes for page ID 2027 only */
add_filter('wpseo_schema_graph', function ($graph, $context) {

	if (!is_page(2027)) {
		return $graph;
	}

	$pricing_url = get_permalink(2027);
	if (!$pricing_url) {
		$pricing_url = home_url('/pricing/');
	}
	$product_id = trailingslashit($pricing_url) . '#product';
	$faq_id = trailingslashit($pricing_url) . '#faq';

	foreach ($graph as $node) {
		if (($node['@id'] ?? '') === $product_id) {
			return $graph;
		}
	}

	$graph[] = [
		'@type' => 'Product',
		'@id' => $product_id,
		'name' => 'Botphonic AI Voice Agent',
		'description' => 'AI voice agent platform for inbound answering, AI reception, outbound calling and call center workloads. Available on monthly or yearly subscription plans, or as a prepaid pay-as-you-go credit wallet. Enterprise plans use custom pricing.',
		'brand' => [
			'@type' => 'Brand',
			'name' => 'Botphonic',
		],
		'url' => $pricing_url,
		'offers' => [
			'@type' => 'AggregateOffer',
			'priceCurrency' => 'USD',
			'lowPrice' => '20.00',
			'highPrice' => '250.00',
			'offerCount' => '3',
			'offers' => [
				[
					'@type' => 'Offer',
					'name' => 'Starter (billed yearly)',
					'price' => '20.00',
					'priceCurrency' => 'USD',
					'url' => 'https://app.botphonic.ai/register/',
					'availability' => 'https://schema.org/InStock',
					'priceSpecification' => [
						'@type' => 'UnitPriceSpecification',
						'price' => '20.00',
						'priceCurrency' => 'USD',
						'billingIncrement' => 1,
						'unitText' => 'month',
						'billingDuration' => 12,
					],
				],
				[
					'@type' => 'Offer',
					'name' => 'Pro (billed yearly)',
					'price' => '55.00',
					'priceCurrency' => 'USD',
					'url' => 'https://app.botphonic.ai/register/',
					'availability' => 'https://schema.org/InStock',
					'priceSpecification' => [
						'@type' => 'UnitPriceSpecification',
						'price' => '55.00',
						'priceCurrency' => 'USD',
						'billingIncrement' => 1,
						'unitText' => 'month',
						'billingDuration' => 12,
					],
				],
				[
					'@type' => 'Offer',
					'name' => 'Pay as you go credit wallet',
					'price' => '250.00',
					'priceCurrency' => 'USD',
					'url' => 'https://app.botphonic.ai/register/',
					'availability' => 'https://schema.org/InStock',
					'description' => 'Prepaid credit wallet billed at $0.40 per talk minute with no monthly subscription.',
				],
			],
		],
	];

	if (botphonic_graph_has_type($graph, 'FAQPage')) {
		return $graph;
	}

	$graph[] = [
		'@type' => 'FAQPage',
		'@id' => $faq_id,
		'mainEntity' => [
			[
				'@type' => 'Question',
				'name' => 'How much does an AI voice agent cost?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Botphonic subscription plans start at $20 per month on Starter billed yearly, or $22 billed monthly, which includes 50 talk minutes and 5 concurrent calls. Pro is $55 per month billed yearly and includes 120 minutes and 25 concurrent calls. There is also a pay-as-you-go option: a $250 credit wallet billed at $0.40 per talk minute with no monthly commitment. Enterprise pricing is based on your minute volume, concurrency and compliance needs.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Is there a free trial, and do I need a credit card?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Every plan starts with a 14-day free trial that includes 20 free minutes. A card is required for a $1 verification charge, which is automatically refunded within 48 hours. No subscription charge is taken until day 15, and you can cancel any time before the trial ends. Pay-as-you-go wallets have no trial period; you simply top up and start calling.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'What is pay as you go and how is it different from a plan?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Pay as you go is a prepaid credit wallet instead of a monthly subscription. You top up once, and talk minutes are drawn from the balance at a fixed per-minute rate. Nothing renews automatically and the credit does not expire at the end of a month, which suits seasonal or unpredictable call volume. Subscription plans work out cheaper if your monthly volume is steady.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Should I choose a subscription or pay as you go?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Choose a monthly or yearly plan if your call volume is predictable, because included minutes and yearly billing lower your effective cost. Choose pay as you go if your volume is seasonal, if you are running a one-off campaign, or if you want to avoid a recurring charge entirely. You can move between them at any time.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Can I change or cancel my plan later?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Yes. You can upgrade, downgrade or cancel from the dashboard at any time. Upgrades take effect immediately and are prorated; downgrades and cancellations take effect at the end of the current billing period, with no cancellation fee.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Do you charge per seat or per user?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'No. Botphonic is priced on talk minutes and concurrent calls, not on the number of people in your team. Unlimited assistants are included on every plan, and team member invitations are included from the Pro plan up.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Can I pay in Indian rupees?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'Yes. Prices can be displayed and billed in USD or INR. Indian customers are billed in rupees with GST applied where required.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'How does Botphonic pricing compare with a human answering service?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'A staffed answering service typically costs $1 to $2 per minute with monthly minimums and limits on how many calls can be handled at once. Botphonic is a fraction of that per minute, handles unlimited simultaneous calls, and books appointments and updates your CRM rather than only taking messages.',
				],
			],
			[
				'@type' => 'Question',
				'name' => 'Are there setup fees or long-term contracts?',
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => 'No setup fee and no long-term contract on Starter or Pro. Yearly billing is optional and simply costs less than monthly. Enterprise agreements are tailored and can include annual terms if you prefer.',
				],
			],
		],
	];

	return $graph;
}, 20, 2);


/** * 7. MEDIA / ATTACHMENT HOOKS */

add_action('add_attachment', 'botphonic_update_image_meta_upon_image_upload');
function botphonic_update_image_meta_upon_image_upload($post_ID)
{
	if (!wp_attachment_is_image($post_ID)) {
		return;
	}

	$attachment = get_post($post_ID);
	if (!$attachment) {
		return;
	}

	$raw_title = $attachment->post_title;
	$my_image_title = ucwords(str_replace(['-', '_'], ' ', $raw_title));
	$acronym_map = [
		'Ai ' => 'AI ',
		'Api ' => 'API ',
		'Crm ' => 'CRM ',
		'Seo ' => 'SEO ',
		'Usa ' => 'USA ',
		'Uk ' => 'UK ',
		'Roi ' => 'ROI ',
		'Saas ' => 'SaaS ',
	];
	$my_image_title = trim(str_replace(array_keys($acronym_map), array_values($acronym_map), $my_image_title . ' '));

	$existing_alt = get_post_meta($post_ID, '_wp_attachment_image_alt', true);
	if ($existing_alt === '' || $existing_alt === false) {
		update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_title);
	}

	wp_update_post(['ID' => $post_ID, 'post_title' => $my_image_title]);
}

add_filter('get_avatar', 'botphonic_force_gravatar_alt_to_author', 10, 2);
function botphonic_force_gravatar_alt_to_author($avatar, $id_or_email)
{
	if (empty($avatar) || !is_string($avatar)) {
		return $avatar;
	}

	$user = false;

	if (is_numeric($id_or_email)) {
		$user = get_user_by('id', (int) $id_or_email);
	} elseif ($id_or_email instanceof WP_User) {
		$user = $id_or_email;
	} elseif ($id_or_email instanceof WP_Post) {
		$user = get_user_by('id', (int) $id_or_email->post_author);
	} elseif ($id_or_email instanceof WP_Comment) {
		if (!empty($id_or_email->user_id)) {
			$user = get_user_by('id', (int) $id_or_email->user_id);
		} elseif (!empty($id_or_email->comment_author_email)) {
			$user = get_user_by('email', $id_or_email->comment_author_email);
		}
	} elseif (is_string($id_or_email) && is_email($id_or_email)) {
		$user = get_user_by('email', $id_or_email);
	}

	$name = ($user && !empty($user->display_name)) ? $user->display_name : 'Author';
	$updated = preg_replace('/alt=(["\']).*?\1/', 'alt="' . esc_attr($name) . '"', $avatar);

	return (null === $updated) ? $avatar : $updated;
}


/** * 8. ADMIN UI */
add_filter('manage_page_posts_columns', function ($columns) {
	return array_merge($columns, ['thumb-img' => __('Image', 'botphonic')]);
});

add_action('manage_page_posts_custom_column', function ($column_key, $post_id) {
	if ($column_key === 'thumb-img') {
		$feat_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		if ($feat_image) {
			echo '<img src="' . esc_url($feat_image) . '" width="80" height="45" alt="' . esc_attr(get_the_title($post_id)) . '" />';
		} else {
			echo 'Not Set';
		}
	}
}, 10, 2);


/** * 9. HTTP 410 GONE HANDLING */
$botphonic_410_module = get_stylesheet_directory() . '/inc/function-410-gone.php';
if (is_readable($botphonic_410_module)) {
	require_once $botphonic_410_module;
}
unset($botphonic_410_module);


/** * 10. CF7 — DUPLICATE EMAIL GUARD (24 HOURS) * Based on the Healthray duplicate-submission guard.
 * Add form IDs to botphonic_dup_form_ids() to protect more forms.
 * An empty array means all forms.
 */
if (!defined('BOTPHONIC_DUP_MESSAGE')) {
	define('BOTPHONIC_DUP_MESSAGE', 'You have already signed up with this email. Please try again later or use a different email.');
}

function botphonic_dup_form_ids()
{
	return array(3104);
}

if (!function_exists('cf7_normalize_email')) {
	function cf7_normalize_email($email_raw)
	{
		return strtolower(trim((string) $email_raw));
	}
}

/*
 * Plugin-independent memory: each accepted sign-up email is remembered as a
 * hashed 24-hour transient (no raw emails stored), so the guard works even
 * if no CF7 entries table exists.
 */
function botphonic_email_transient_key($email)
{
	return 'botphonic_dup_' . md5(cf7_normalize_email($email));
}

add_action('wpcf7_before_send_mail', function ($contact_form) {
	$ids = botphonic_dup_form_ids();
	if (!empty($ids) && !in_array((int) $contact_form->id(), $ids, true)) {
		return;
	}

	if (!class_exists('WPCF7_Submission')) {
		return;
	}

	$submission = WPCF7_Submission::get_instance();
	if (!$submission) {
		return;
	}

	$data = $submission->get_posted_data();
	$email = (isset($data['your-email']) && is_string($data['your-email']))
		? cf7_normalize_email($data['your-email'])
		: '';

	if ($email !== '' && is_email($email)) {
		set_transient(botphonic_email_transient_key($email), 1, DAY_IN_SECONDS);
	}
});

add_filter('wpcf7_validate_email*', 'botphonic_validate_duplicate_email', 25, 2);
add_filter('wpcf7_validate_email', 'botphonic_validate_duplicate_email', 25, 2);

function botphonic_validate_duplicate_email($result, $tag)
{
	global $botphonic_duplicate_email_flag;

	if (!class_exists('WPCF7_ContactForm') || $tag->name !== 'your-email') {
		return $result;
	}

	$ids = botphonic_dup_form_ids();
	if (!empty($ids)) {
		$form = WPCF7_ContactForm::get_current();
		if (!$form || !in_array((int) $form->id(), $ids, true)) {
			return $result;
		}
	}

	$invalid = method_exists($result, 'get_invalid_fields') ? $result->get_invalid_fields() : array();
	if (isset($invalid[$tag->name])) {
		return $result;
	}

	$email = cf7_normalize_email(isset($_POST['your-email']) ? wp_unslash($_POST['your-email']) : '');
	if ($email === '' || !is_email($email)) {
		return $result;
	}

	if (get_transient(botphonic_email_transient_key($email)) || cf7_email_exists_last_24_hours($email)) {
		$result->invalidate($tag, BOTPHONIC_DUP_MESSAGE);
		$botphonic_duplicate_email_flag = true;
	}

	return $result;
}

if (!function_exists('cf7_email_exists_last_24_hours')) {
	function cf7_email_exists_last_24_hours($email)
	{
		global $wpdb;
		$table = $wpdb->prefix . 'cf7_data_entry';

		if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) !== $table) {
			return false;
		}

		$email = cf7_normalize_email($email);
		$since = date('Y-m-d H:i:s', current_time('timestamp') - DAY_IN_SECONDS);

		$recent_ids = $wpdb->get_col($wpdb->prepare(
			"SELECT data_id FROM {$table} WHERE name = 'submit_time' AND value >= %s",
			$since
		));

		if (empty($recent_ids)) {
			return false;
		}

		$placeholders = implode(',', array_fill(0, count($recent_ids), '%d'));
		$emails = $wpdb->get_col($wpdb->prepare(
			"SELECT value FROM {$table} WHERE name = 'your-email' AND data_id IN ($placeholders)",
			$recent_ids
		));

		foreach ($emails as $stored) {
			if (cf7_normalize_email($stored) === $email) {
				return true;
			}
		}

		return false;
	}
}

add_filter('wpcf7_feedback_response', function ($response, $result) {
	global $botphonic_duplicate_email_flag;

	if (($response['status'] ?? '') === 'validation_failed' && !empty($botphonic_duplicate_email_flag)) {
		$response['message'] = BOTPHONIC_DUP_MESSAGE;
	}

	return $response;
}, 10, 2);
