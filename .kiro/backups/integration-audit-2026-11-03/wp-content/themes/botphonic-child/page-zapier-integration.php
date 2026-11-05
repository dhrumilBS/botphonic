<?php
/**
 * Zapier integration page (/integrations/zapier-integration/).
 *
 * WordPress uses this file for the page with the slug "zapier-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * The connection uses Webhooks by Zapier with the Botphonic Public API,
 * webhooks, custom actions and workflow API steps
 * (docs.botphonic.ai/docs/developers/public-api, /docs/developers/webhooks,
 * /docs/developers/api-keys). Zapier facts: help.zapier.com (Webhooks by
 * Zapier triggers, actions and plan availability). Checked 2 Nov 2026.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_api_doc = 'https://docs.botphonic.ai/docs/developers/public-api';
$bpi_hooks_doc = 'https://docs.botphonic.ai/docs/developers/webhooks';

$bpi_page = botphonic_integration_boot(
	'zapier',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + Zapier',
			'title' => 'Zapier integration: start AI phone calls from any Zap',
			'lede' => 'Send a lead from any app Zapier connects to Botphonic, and an AI voice agent calls them. Then pass what happened on the call back to Zapier to update the rest of your tools. It all runs on Webhooks by Zapier and the Botphonic Public API.',
			'points' => array(
				'Trigger calls from forms, ads, spreadsheets and booking tools',
				'Built on Webhooks by Zapier, which Zapier offers on every plan',
				'Signed Botphonic webhooks when contacts join or leave segments',
			),
			'guide' => array('label' => 'Read the Public API reference', 'url' => $bpi_api_doc),
		),

		'visual' => array(
			'type' => 'flow',
			'caption' => 'Example: a new form lead gets a call',
			'status' => 'Webhooks',
			'events' => array(
				array('source' => 'partner', 'label' => 'Zapier trigger', 'time' => '11:05', 'title' => 'New form submission', 'detail' => 'Marco Diaz · +44 20 7946 0958'),
				array('source' => 'partner', 'label' => 'Webhooks by Zapier', 'time' => '11:05', 'title' => 'Contact added to a segment', 'detail' => 'POST /v1/segments/{id}/members'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '11:06', 'title' => 'Workflow calls the lead', 'detail' => 'Auto-enroll campaign · AI call step'),
				array('source' => 'partner', 'label' => 'Zapier Catch Hook', 'time' => '11:10', 'title' => 'Result passed on', 'detail' => 'Qualified: yes · Slack alert and a Google Sheets row'),
			),
			'outcomes_label' => 'What the Zaps did in this example',
			'outcomes' => array('Contact created', 'AI call placed', 'Team notified'),
			'footnote' => 'Illustrative example. Your Zaps decide the apps and fields.',
		),

		'facts' => array(
			'title' => 'Zapier integration at a glance',
			'items' => array(
				array('label' => 'Connection', 'value' => 'Webhooks by Zapier with the Botphonic Public API'),
				array('label' => 'Into Botphonic', 'value' => 'Create contacts and add them to segments, up to 1,000 per request'),
				array('label' => 'Out of Botphonic', 'value' => 'Segment webhooks, workflow API steps and custom actions'),
				array('label' => 'Authentication', 'value' => 'Scoped Bearer API keys and HMAC-signed webhooks'),
			),
		),

		'steps' => array(
			'title' => 'Two Zaps: one starts the call, one shares the result',
			'lede' => 'You need a Botphonic API key, a segment and a running workflow campaign. Zapier’s webhook steps do the rest.',
			'items' => array(
				array(
					'icon' => 'key',
					'title' => 'Create a scoped API key',
					'text' => 'In Botphonic, open Developers → API keys and create a key with only the scopes the Zap needs, such as <code>segments:write</code>. The full key is shown once, so paste it straight into Zapier.',
				),
				array(
					'icon' => 'zap',
					'title' => 'Send contacts from your trigger app',
					'text' => 'Add a Webhooks by Zapier Custom Request step that POSTs JSON to <code>/v1/segments/{id}/members</code> with <code>"action": "add"</code>, <code>"createMissing": true</code> and the contact’s name, email and phone in E.164 format.',
				),
				array(
					'icon' => 'flow',
					'title' => 'Let the campaign place the call',
					'text' => 'A workflow campaign that targets the segment with Auto-Enroll on starts a run for each new contact: the AI call, follow-up emails and any branches you designed.',
				),
				array(
					'icon' => 'webhook',
					'title' => 'Catch the result in a second Zap',
					'text' => 'Start a Zap with Catch Hook and send data to its URL from a workflow API step or a custom action. For segment changes, add the URL as a Botphonic webhook endpoint.',
				),
			),
			'more' => array('label' => 'Read the webhooks guide', 'url' => $bpi_hooks_doc),
		),

		'capabilities' => array(
			'title' => 'What you can build with Botphonic and Zapier',
			'lede' => 'A change made through the Botphonic Public API behaves exactly like one made in the app, so a contact added by a Zap starts the same campaigns as one added by hand.',
			'items' => array(
				array(
					'icon' => 'zap',
					'title' => 'Calls from any trigger',
					'text' => 'Form fills, ad leads, new spreadsheet rows, cancelled bookings: any Zapier trigger can add a contact to the segment your campaign watches.',
				),
				array(
					'icon' => 'copy',
					'title' => 'No duplicates on retries',
					'text' => 'Send an <code>Idempotency-Key</code> header and Botphonic applies a repeated request only once within 24 hours. Contacts are matched by externalId, phone and email first.',
				),
				array(
					'icon' => 'layers',
					'title' => 'Bulk-safe requests',
					'text' => 'Up to 1,000 contact records per request. A bad record is reported on its own and never fails the rest of the batch.',
				),
				array(
					'icon' => 'webhook',
					'title' => 'Segment events as triggers',
					'text' => 'Botphonic sends <code>segment.contact_joined</code> and <code>segment.contact_left</code> events, batched up to 500 contacts per delivery.',
				),
				array(
					'icon' => 'refresh',
					'title' => 'Data sent during the call',
					'text' => 'A custom action can POST to a Catch Hook while the caller is still talking, for example when they confirm an address or ask for a quote.',
				),
				array(
					'icon' => 'list-check',
					'title' => 'Structured call answers',
					'text' => 'Information Extractor turns answers into yes/no, single-choice or open values you can pass to Zapier and filter on in your Zaps.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'Popular Zaps for AI calling',
			'lede' => 'Each one starts with a trigger app your team already uses.',
			'tabs_label' => 'Choose a Zapier use case',
			'items' => array(
				array(
					'id' => 'zapier-lead-forms',
					'label' => 'Lead forms and ads',
					'title' => 'Call ad and form leads automatically',
					'text' => 'When Facebook Lead Ads, Typeform or your website form captures a lead, the Zap adds it to your “New leads” segment and Botphonic calls it within the calling window you set.',
					'points' => array(
						'Lead details mapped to Botphonic contact fields',
						'Qualifying questions asked on every call',
						'Qualified leads posted to Slack or your CRM by a second Zap',
						'Unanswered calls retried by the workflow',
					),
					'link' => array('label' => 'See the AI sales assistant', 'url' => home_url('/solution/ai-sales-assistant/')),
				),
				array(
					'id' => 'zapier-bookings',
					'label' => 'Cancelled bookings',
					'title' => 'Win back cancelled appointments',
					'text' => 'When an invitee cancels in Calendly or another scheduling tool, a Zap adds them to a rebooking segment and the agent calls to find a new time.',
					'points' => array(
						'Triggered by the cancellation itself',
						'New times offered from your connected calendar',
						'The new booking confirmed on the call',
						'Results passed to your CRM or a spreadsheet',
					),
					'link' => array('label' => 'Explore AI appointment booking', 'url' => home_url('/solution/ai-appointment-booking/')),
				),
				array(
					'id' => 'zapier-reminders',
					'label' => 'Reminders from a sheet',
					'title' => 'Turn a spreadsheet into a reminder campaign',
					'text' => 'Add a row to a Google Sheet for each payment, renewal or appointment reminder. The Zap sends the row to Botphonic and a campaign makes the calls, with DNC scrubbing and calling-hour limits for US numbers.',
					'points' => array(
						'One row per contact, phone in E.164 format',
						'Contacts matched by your own externalId',
						'Outcomes written back to the sheet by a second Zap',
						'Calling times set in the workflow',
					),
					'link' => array('label' => 'See financial services use cases', 'url' => home_url('/industry/finance/')),
				),
			),
		),

		'data_table' => array(
			'kicker' => 'Endpoints and events',
			'title' => 'The Botphonic endpoints and events your Zaps use',
			'lede' => 'Endpoints sit under <code>https://api.botphonic.ai/api/v1</code> and need a Bearer API key with the scope shown.',
			'caption' => 'Botphonic Public API endpoints and webhook events for Zapier',
			'columns' => array('Direction', 'Endpoint or event', 'Use it to'),
			'rows' => array(
				array('Zapier to Botphonic', '<code>POST /v1/segments/{id}/members</code>', 'Add or remove contacts in a segment, creating missing ones (<code>segments:write</code>)'),
				array('Zapier to Botphonic', '<code>POST /v1/contacts</code>', 'Create or update up to 1,000 contacts in one request (<code>contacts:write</code>)'),
				array('Zapier to Botphonic', '<code>POST /v1/segments/move</code>', 'Move contacts between segments without interrupting their runs (<code>segments:write</code>)'),
				array('Zapier to Botphonic', '<code>GET /v1/contacts/lookup</code>', 'Find a contact and the segments it’s in (<code>contacts:read</code>)'),
				array('Botphonic to Zapier', '<code>segment.contact_joined</code>, <code>segment.contact_left</code>', 'Start a Zap when contacts enter or leave a segment'),
				array('Botphonic to Zapier', 'Workflow API step or custom action', 'Send call details to a Catch Hook URL'),
			),
			'note' => 'Limits: 600 requests per minute per key and 60 bulk contact requests per minute. Over the limit, the API answers with a <code>Retry-After</code> header.',
		),

		'setup' => array(
			'title' => 'What you need to connect Zapier',
			'lede' => 'You connect with Webhooks by Zapier, Zapier’s built-in app for calling any API, so there’s nothing extra to install.',
			'needs' => array(
				'A Zapier account. Zapier lists Webhooks by Zapier on its Free, Professional, Team and Enterprise plans',
				'A Botphonic API key with the scopes your Zaps need. Creating keys needs the API Keys & Webhooks permission, which admins have by default',
				'A segment and a running workflow campaign with Auto-Enroll on (workflows are on Pro and Enterprise plans)',
				'Phone numbers in E.164 format, for example +14155550101',
				'For results, a second Zap that starts with Catch Hook',
			),
			'note' => 'Keep API keys inside Zapier. Never put them in browser code or public files.',
			'links' => array(
				array('label' => 'Read the Public API reference', 'url' => $bpi_api_doc),
				array('label' => 'Read the webhooks guide', 'url' => $bpi_hooks_doc),
			),
		),

		'trust' => array(
			'title' => 'Keys you scope, webhooks you can verify',
			'lede' => 'Every API key belongs to one workspace and can be revoked or rotated at any time. Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'Scoped, revocable API keys', 'text' => 'Give each key only the scopes it needs, set an expiry and revoke it instantly.'),
				array('title' => 'Signed webhook deliveries', 'text' => 'Each delivery carries an HMAC-SHA256 signature and a unique delivery ID, and failed deliveries are retried for about 30 minutes.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'See AI phone calls', 'url' => home_url('/product/ai-phone-call/')),
			),
		),

		'faq_title' => 'Zapier integration FAQ',
		'faqs' => array(
			array(
				'question' => 'How do I connect Botphonic to Zapier?',
				'answer' => 'With Webhooks by Zapier. Send contacts to the Botphonic Public API with a Custom Request or POST action, authenticated with a Botphonic API key as a Bearer token. To receive data from Botphonic, start a Zap with a Catch Hook trigger.',
			),
			array(
				'question' => 'Can a Zap start an AI phone call?',
				'answer' => 'Yes. The Zap adds the contact to a segment through the Public API, and a running workflow campaign with Auto-Enroll on that segment starts the contact’s journey, including the AI call.',
			),
			array(
				'question' => 'Which Zapier plan do I need?',
				'answer' => 'Zapier lists Webhooks by Zapier on its Free, Professional, Team and Enterprise plans, so check Zapier’s pricing for the task volume your Zaps need. On the Botphonic side, workflow campaigns are included on Pro and Enterprise plans.',
			),
			array(
				'question' => 'Will Zap retries create duplicate contacts?',
				'answer' => 'Not if you send an Idempotency-Key header. If the same key arrives again within 24 hours, Botphonic returns the first response and applies nothing twice. Records are also matched by externalId, phone and email before a new contact is created.',
			),
			array(
				'question' => 'How do I send call results to other apps?',
				'answer' => 'Create a Zap that starts with Catch Hook, then send data to its URL from a workflow API step or a custom action. For segment changes, add the URL as a Botphonic webhook endpoint for the segment.contact_joined or segment.contact_left events.',
			),
			array(
				'question' => 'Are Botphonic webhooks secure?',
				'answer' => 'Each delivery is signed with HMAC-SHA256 using your endpoint’s secret and carries a unique delivery ID you can use to ignore duplicates. Botphonic retries failed deliveries up to 8 times over about 30 minutes.',
			),
			array(
				'question' => 'What are the API limits?',
				'answer' => '600 requests per minute per API key, 60 bulk contact requests per minute and up to 1,000 records in each bulk request. Responses include rate-limit headers, and a Retry-After header when you go over.',
			),
		),

		'cta' => array(
			'title' => 'Build your first calling Zap today',
			'text' => 'Start a free trial, create an API key and point a Zap at your first segment. If you’d rather plan it together, book a demo and bring your trigger app.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_api_doc, $bpi_hooks_doc, $bpi_page);
get_footer();
