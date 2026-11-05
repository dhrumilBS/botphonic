<?php
/**
 * Integrations hub (/integrations/).
 *
 * WordPress uses this file for the page with the slug "integrations". It lists
 * every integration from botphonic_integration_directory() and links to the
 * five integration pages beneath it. The copy lives here; sections are shared
 * in template-parts/integrations/ and wired up in
 * inc/function-integration-pages.php.
 *
 * Sources checked 2 Nov 2026: docs.botphonic.ai (integrations overview,
 * HubSpot, Cal.com, Calendly, Twilio, Telnyx, Plivo, Exotel, phone numbers,
 * email, actions, workflows, public API, webhooks, API keys, security) and the
 * plan features from api.botphonic.ai/api/plans.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_docs = 'https://docs.botphonic.ai';
$bpi_count = count(botphonic_integration_directory());

$bpi_page = botphonic_integration_boot(
	BOTPHONIC_INTEGRATION_HUB,
	array(
		'name' => 'Botphonic',
		'label' => 'Integrations',
		'sections' => array('hero', 'directory', 'steps', 'data-table', 'trust', 'faq', 'cta'),

		'hero' => array(
			'kicker' => 'Botphonic integrations',
			'title' => 'Botphonic integrations for your CRM, calendar and phone system',
			'lede' => 'Let your AI voice agent look up callers, book meetings and log call results in the tools your team already uses.',
			'guide' => array('label' => sprintf('Browse all %d integrations', $bpi_count), 'url' => '#all-integrations'),
		),

		'visual' => array(
			'type' => 'hub',
			'caption' => 'What Botphonic connects to',
			'status' => sprintf('%d integrations', $bpi_count),
			'groups' => array(
				array('label' => 'CRM', 'note' => 'Look up and update records', 'items' => array('hubspot', 'salesforce', 'zoho')),
				array('label' => 'Calendars', 'note' => 'Book meetings during the call', 'items' => array('calcom', 'calendly')),
				array('label' => 'Calling', 'note' => 'Calls on your own numbers', 'items' => array('twilio', 'telnyx', 'plivo')),
				array('label' => 'Automation & API', 'note' => 'Follow-ups and other apps', 'items' => array('zapier', 'whatsapp', 'webhooks')),
			),
			'footnote' => 'Exotel, email, custom actions and the REST API are in the directory below.',
		),

		'directory' => array(
			'kicker' => 'Integration directory',
			'title' => 'Browse every Botphonic integration',
			'lede' => 'Filter by category or search by name. Each card links to the integration’s page or to its setup guide in the Botphonic docs.',
			'more' => array(
				'title' => 'Don’t see your tool?',
				'text' => 'Any system with a REST API can be reached through a custom action, and Zapier links Botphonic to thousands of other apps. Tell us what you use and we’ll help you connect it.',
				'links' => array(
					array('label' => 'How custom actions work', 'url' => $bpi_docs . '/docs/build/actions/custom-http-action'),
					array('label' => 'Ask about an integration', 'url' => home_url('/contact/')),
				),
			),
		),

		'steps' => array(
			'tone' => 'tint',
			'title' => 'How Botphonic integrations work',
			'lede' => 'You set up each connection once, in the Integrations tab. After that, every call your assistant handles can use it.',
			'items' => array(
				array(
					'icon' => 'search',
					'title' => 'Pick a provider',
					'text' => 'Open the Integrations tab in Botphonic and choose a tool: your CRM, your calendar or the carrier behind your phone numbers.',
				),
				array(
					'icon' => 'key',
					'title' => 'Authorize the connection',
					'text' => 'Approve access on the provider’s own screen, or paste its API credentials, such as a Cal.com API key or your Twilio Account SID and API key. Botphonic stores credentials encrypted in a vault.',
				),
				array(
					'icon' => 'flow',
					'title' => 'Assign it to an assistant',
					'text' => 'Add the calendar to the assistant’s Real Time Booking action, give the assistant one of your numbers, or attach custom actions that run before, during or after the call.',
				),
				array(
					'icon' => 'refresh',
					'title' => 'Let every call use it',
					'text' => 'The agent checks availability, books the meeting and looks up or updates records during the call, then passes the outcome on when it ends.',
				),
			),
			'more' => array('label' => 'Read the integrations overview', 'url' => $bpi_docs . '/integrations/getting-started'),
		),

		'data_table' => array(
			'tone' => 'white',
			'kicker' => 'Integrations on a call',
			'title' => 'What integrations do before, during and after a call',
			'lede' => 'Each integration plugs into a specific moment of the conversation.',
			'caption' => 'Where Botphonic integrations act during a call',
			'columns' => array('When', 'What the agent can do', 'Works with'),
			'rows' => array(
				array('On every call', 'Answer and dial out on your own phone numbers', 'Twilio, Telnyx, Plivo, Exotel'),
				array('When a lead comes in', 'Send a WhatsApp message as soon as the lead submits a form, then run the follow-up workflow', 'WhatsApp'),
				array('Before the call', 'Look up the caller and load their details, so it can greet returning callers by name', 'Salesforce, Zoho CRM or any REST API, through a custom action'),
				array('During the call', 'Check live availability and book the time the caller picks', 'Cal.com, Calendly'),
				array('During the call', 'Create or update a record, or check an order or a case, while the caller waits', 'Custom actions pointed at your CRM or your own API'),
				array('After the call', 'Add a note with the summary, recording link and transcript to the contact', 'HubSpot natively, Salesforce and Zoho CRM through workflow API steps'),
				array('After the call', 'Pass the outcome and extracted answers to other apps, or send a follow-up email', 'Zapier, workflow API steps, email over SMTP'),
				array('Any time', 'Add contacts and move them between segments, which can start or stop campaigns', 'REST API, webhooks, HubSpot contact sync'),
			),
			'note' => 'Custom actions are included on every plan. Multi-step workflows are on Pro and Enterprise.',
		),

		'trust' => array(
			'title' => 'Integration credentials stay protected',
			'lede' => 'Botphonic connects with OAuth 2.0 where a tool supports it, or with API credentials that are encrypted and stored in a vault. It’s built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'OAuth or encrypted keys', 'text' => 'Approvals happen on the provider’s own screen, and pasted credentials are kept encrypted.'),
				array('title' => 'Scoped keys, signed webhooks', 'text' => 'Botphonic API keys carry only the scopes you choose, and webhook deliveries are signed with HMAC-SHA256.'),
			),
		),

		'faq_title' => 'Questions about Botphonic integrations',
		'faqs' => array(
			array(
				'question' => 'Which tools does Botphonic integrate with?',
				'answer' => 'Botphonic connects natively to HubSpot, to Cal.com and Calendly for booking, and to Twilio, Telnyx, Plivo and Exotel for your phone numbers. Salesforce and Zoho CRM connect through their REST APIs, Zapier through Webhooks by Zapier, and WhatsApp sends automated messages and follow-ups to new leads. Botphonic doesn’t make or answer calls over WhatsApp. Custom actions, webhooks and the Botphonic REST API cover other tools that have an API.',
			),
			array(
				'question' => 'Are integrations included in every plan?',
				'answer' => 'Yes. Every existing integration and the REST API are included on the Starter, Pro and Enterprise plans, and in the 14-day free trial. Multi-step workflows, which some setups use for follow-up, are on Pro and Enterprise. See <a href="' . esc_url(home_url('/pricing/')) . '">pricing</a> for the details of each plan.',
			),
			array(
				'question' => 'Can I keep my existing phone numbers?',
				'answer' => 'Yes. Connect your Twilio, Telnyx, Plivo or Exotel account and assign your numbers to an assistant for inbound and outbound calls. You can also buy a new number in the Botphonic dashboard, searching by country and area code.',
			),
			array(
				'question' => 'Do I need a developer to set up integrations?',
				'answer' => 'Not for HubSpot, Cal.com, Calendly or your phone carrier, which you connect from the Integrations tab with an approval screen or API credentials. Zapier, custom actions and the REST API involve API requests, so it helps to have someone comfortable with JSON, and we can walk through the setup with you on a demo call.',
			),
			array(
				'question' => 'How does real-time booking work?',
				'answer' => 'Connect Cal.com with an API key or Calendly with a personal access token, then add the calendar to your assistant’s Real Time Booking action. During the call the agent offers open times and books the one the caller picks. A slot that fills up in your calendar is no longer offered.',
			),
			array(
				'question' => 'Can Botphonic connect to a tool that isn’t listed?',
				'answer' => 'Usually, if the tool has an API. A custom action can call any REST API before or during a call, a workflow API step can send results after it, and Zapier links Botphonic to thousands of other apps. Webhooks notify your own systems when contacts join or leave a segment.',
			),
			array(
				'question' => 'How does Botphonic protect integration credentials?',
				'answer' => 'Connections use OAuth 2.0 where the tool supports it, or API credentials that are encrypted and stored in a vault. Data is encrypted in transit with TLS 1.2 or 1.3 and at rest with AES-256, Botphonic API keys can be scoped and revoked, and webhook deliveries are signed with HMAC-SHA256. See the <a href="' . esc_url(home_url('/security/')) . '">security overview</a>.',
			),
			array(
				'question' => 'Does Botphonic have a public API?',
				'answer' => 'Yes. The REST API lets your systems create, update and look up contacts and manage segment membership, which can start or stop workflow campaigns. Webhooks tell your systems when contacts join or leave a segment. You create scoped API keys under Developers → API keys.',
			),
		),

		'cta' => array(
			'title' => 'Connect your tools and hear the agent use them on a real call',
			'text' => 'Start a free trial and connect your CRM, calendar or phone numbers in minutes. Or book a demo and we’ll map the integrations your call flow needs.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_docs, $bpi_count, $bpi_page);
get_footer();
