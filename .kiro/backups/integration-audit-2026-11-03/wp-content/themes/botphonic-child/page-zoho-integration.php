<?php
/**
 * Zoho CRM integration page (/integrations/zoho-integration/).
 *
 * WordPress uses this file for the page with the slug "zoho-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * Botphonic reaches Zoho CRM through the Zoho CRM REST API (v8, OAuth 2.0)
 * from custom actions and workflow API steps; Zoho workflow rules can call the
 * Botphonic Public API with a webhook. Zoho facts: zoho.com/crm/developer/docs/api/v8
 * (insert, upsert, Calls module fields) and Zoho CRM help (workflow webhooks).
 * Checked 2 Nov 2026.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_actions_doc = 'https://docs.botphonic.ai/docs/build/actions/custom-http-action';

$bpi_page = botphonic_integration_boot(
	'zoho',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + Zoho CRM',
			'title' => 'Zoho integration: AI calls that keep Zoho CRM up to date',
			'lede' => 'Botphonic works with Zoho CRM through its REST API. The agent can recognise the caller before it answers, create or update the lead from the conversation, and log the call in the Calls module. Zoho CRM workflow rules can start calls with a webhook.',
			'points' => array(
				'Caller lookup in Zoho CRM before the greeting',
				'Leads created or updated without duplicates',
				'Calls logged in the Calls module with type, time and notes',
			),
			'guide' => array('label' => 'How custom actions work', 'url' => $bpi_actions_doc),
		),

		'visual' => array(
			'type' => 'flow',
			'caption' => 'Example: a new web lead in Zoho CRM gets a call',
			'status' => 'REST API',
			'events' => array(
				array('source' => 'partner', 'label' => 'Zoho CRM', 'time' => '10:12', 'title' => 'Lead created', 'detail' => 'Ananya Rao · Lead source: Web form'),
				array('source' => 'partner', 'label' => 'Zoho CRM', 'time' => '10:12', 'title' => 'Workflow rule calls a webhook', 'detail' => 'Lead added to the Botphonic segment “New leads”'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '10:13', 'title' => 'AI call completed', 'detail' => 'Interested · wants a demo next week'),
				array('source' => 'partner', 'label' => 'Zoho CRM', 'time' => '10:18', 'title' => 'Call logged, lead updated', 'detail' => 'Calls module · Outbound · Lead status: Contacted'),
			),
			'outcomes_label' => 'Records changed in Zoho CRM in this example',
			'outcomes' => array('Call record', 'Lead status', 'Follow-up details'),
			'footnote' => 'Illustrative example. Your workflow rules and field mapping decide what changes.',
		),

		'facts' => array(
			'title' => 'Zoho CRM integration at a glance',
			'items' => array(
				array('label' => 'Connection', 'value' => 'Zoho CRM REST API with OAuth 2.0'),
				array('label' => 'Logs calls to', 'value' => 'The Calls module, as inbound, outbound or missed'),
				array('label' => 'Starts calls from', 'value' => 'Workflow rule webhooks or a Zap'),
				array('label' => 'Data centres', 'value' => 'All of them: use your account’s API domain, such as zohoapis.com, .eu or .in'),
			),
		),

		'steps' => array(
			'title' => 'How Botphonic works with your Zoho CRM data',
			'lede' => 'Each step is a Botphonic action or workflow step pointed at the Zoho CRM API, so you control which modules and fields it touches.',
			'items' => array(
				array(
					'icon' => 'key',
					'title' => 'Authorise API access',
					'text' => 'Register a client in the Zoho API Console and grant only the scopes you need, for example for the Leads, Contacts and Calls modules.',
				),
				array(
					'icon' => 'search',
					'title' => 'Recognise the caller',
					'text' => 'A before-the-call custom action searches Leads or Contacts by phone, so the agent can greet returning callers by name and see their status.',
				),
				array(
					'icon' => 'edit',
					'title' => 'Capture and upsert',
					'text' => 'Information Extractor turns the conversation into clean values. A custom action sends them to Zoho’s Upsert API, which updates the matching lead or creates a new one.',
				),
				array(
					'icon' => 'note',
					'title' => 'Log the call',
					'text' => 'A workflow API step, or a webhook to your API layer, inserts a Calls record with the call type, start time, subject and notes, linked to the lead or contact.',
				),
			),
			'more' => array('label' => 'Read the custom actions guide', 'url' => $bpi_actions_doc),
		),

		'capabilities' => array(
			'title' => 'What the agent can do with Zoho CRM',
			'lede' => 'It works with Zoho CRM’s own modules and API names, so your layouts and custom fields keep working.',
			'items' => array(
				array(
					'icon' => 'search',
					'title' => 'Knows who’s calling',
					'text' => 'Look up the caller’s number in Leads or Contacts before the greeting, and use their name, owner or lead status in the conversation.',
				),
				array(
					'icon' => 'copy',
					'title' => 'Upserts instead of duplicating',
					'text' => 'Zoho’s Upsert API matches existing records on duplicate-check fields such as email or phone, so a returning caller updates their lead rather than creating a second one.',
				),
				array(
					'icon' => 'note',
					'title' => 'Logs to the Calls module',
					'text' => 'Each call becomes a Calls record, typed as inbound, outbound or missed, with the start time, subject and notes.',
				),
				array(
					'icon' => 'zap',
					'title' => 'Calls from workflow rules',
					'text' => 'A workflow rule’s webhook action can send a new lead to the Botphonic Public API, and an auto-enroll campaign places the call.',
				),
				array(
					'icon' => 'list-check',
					'title' => 'Picklist-ready answers',
					'text' => 'Single-choice extractors return only the values you define, so answers drop straight into Zoho picklists such as Lead Status.',
				),
				array(
					'icon' => 'calendar',
					'title' => 'Meetings on the call',
					'text' => 'With Cal.com or Calendly connected, the agent books a time while the caller is on the line, and an API step can add it to Zoho’s Meetings module.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'Where Botphonic fits your Zoho CRM process',
			'lede' => 'Pick the calls your team can’t get to fast enough.',
			'tabs_label' => 'Choose a Zoho CRM use case',
			'items' => array(
				array(
					'id' => 'zoho-inbound',
					'label' => 'Inbound sales line',
					'title' => 'Answer every sales call with the record in view',
					'text' => 'The agent recognises existing leads and contacts, answers questions from your knowledge base, and creates a new lead when a first-time caller is interested.',
					'points' => array(
						'Phone lookup in Leads and Contacts',
						'New leads upserted from the call',
						'Warm transfer to your sales team',
						'Every call logged in the Calls module',
					),
					'link' => array('label' => 'See the AI receptionist', 'url' => home_url('/solution/ai-receptionist/')),
				),
				array(
					'id' => 'zoho-callbacks',
					'label' => 'New lead callbacks',
					'title' => 'Call new Zoho leads while they’re interested',
					'text' => 'A workflow rule on lead creation sends each new lead to Botphonic. The agent calls, qualifies and books a meeting, and the result is logged back on the lead.',
					'points' => array(
						'Triggered by a Zoho CRM workflow rule',
						'Qualifying answers saved to lead fields',
						'Meetings booked during the call',
						'Unanswered calls retried by the workflow',
					),
					'link' => array('label' => 'Explore the AI appointment setter', 'url' => home_url('/solution/ai-appointment-setter/')),
				),
				array(
					'id' => 'zoho-follow-up',
					'label' => 'Service follow-ups',
					'title' => 'Follow up after a job or a closed deal',
					'text' => 'Send customers to a follow-up segment when a deal closes or a service visit ends. The agent checks they’re happy, collects feedback and logs the answers in Zoho CRM.',
					'points' => array(
						'Started from a workflow rule or a Zap',
						'Feedback captured as structured answers',
						'Unhappy customers routed to your team by a workflow branch',
						'Notes saved on the contact’s Calls record',
					),
					'link' => array('label' => 'See home services use cases', 'url' => home_url('/industry/home-services/')),
				),
			),
		),

		'data_table' => array(
			'kicker' => 'Field mapping',
			'title' => 'Example mapping between a call and Zoho CRM',
			'lede' => 'Field names are Zoho CRM API names. Your layouts and custom fields may differ.',
			'caption' => 'Example Zoho CRM mapping for a Botphonic call',
			'columns' => array('Botphonic data', 'Zoho CRM module', 'Typical fields'),
			'rows' => array(
				array('Caller’s phone number', 'Leads or Contacts (search)', '<code>Phone</code>, <code>Mobile</code>'),
				array('Qualification answers', 'Leads (upsert)', '<code>Lead_Status</code>, <code>Lead_Source</code>, your custom fields'),
				array('A first-time caller', 'Leads (insert)', '<code>Last_Name</code> (required), <code>Phone</code>, <code>Email</code>'),
				array('The call itself', 'Calls', '<code>Subject</code>, <code>Call_Type</code>, <code>Call_Start_Time</code>, <code>Call_Duration</code>, <code>Description</code>, <code>Who_Id</code>'),
				array('A booked meeting', 'Meetings (API name Events)', '<code>Event_Title</code>, <code>Start_DateTime</code>, <code>End_DateTime</code>'),
			),
			'note' => 'Use the API domain for your data centre, for example www.zohoapis.com, www.zohoapis.eu or www.zohoapis.in. Each request uses Zoho CRM API credits.',
		),

		'setup' => array(
			'title' => 'What you need to connect Zoho CRM',
			'lede' => 'Decide with your Zoho admin which calls create or change records, then build the actions in Botphonic.',
			'needs' => array(
				'A Zoho CRM account with API access, and a client registered in the Zoho API Console',
				'OAuth scopes limited to the modules you map, such as Leads, Contacts, Calls and Events',
				'For call triggers, a Zoho CRM workflow rule with a webhook action, created by a user who can manage workflows',
				'Botphonic custom actions, on every plan, and workflows for multi-step follow-up, on Pro and Enterprise',
				'Your Zoho data centre’s API domain in each action URL',
			),
			'note' => 'Zoho CRM API calls count against your daily API credits, which depend on your edition and number of user licenses.',
			'links' => array(
				array('label' => 'Read the custom actions guide', 'url' => $bpi_actions_doc),
				array('label' => 'Talk to our team', 'url' => home_url('/contact/')),
			),
		),

		'trust' => array(
			'title' => 'Zoho access limited to the modules you choose',
			'lede' => 'OAuth scopes decide which modules Botphonic can reach, and you can revoke access from your Zoho account at any time. Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'Scoped OAuth access', 'text' => 'Botphonic only reaches the Zoho CRM modules your OAuth scopes allow.'),
				array('title' => 'TCPA controls for US calls', 'text' => 'DNC scrubbing before campaigns, calling-hour limits and instant opt-outs.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'Read the CRM integration buying guide', 'url' => home_url('/blog/ai-call-center-crm-integration/')),
			),
		),

		'faq_title' => 'Zoho CRM integration FAQ',
		'faqs' => array(
			array(
				'question' => 'How does Botphonic connect to Zoho CRM?',
				'answer' => 'Through the Zoho CRM REST API with OAuth 2.0. Botphonic custom actions read and write records before or during a call, and workflow API steps log results afterwards. Zoho CRM workflow rules, or a Zap, can start calls through the Botphonic Public API.',
			),
			array(
				'question' => 'Can Botphonic log calls in the Zoho CRM Calls module?',
				'answer' => 'Yes. Zoho’s Insert Records API creates a Calls record with the call type (Inbound, Outbound or Missed), the start time, a subject and your notes, linked to the lead or contact.',
			),
			array(
				'question' => 'Can a Zoho CRM workflow rule start an AI call?',
				'answer' => 'Yes. Add a webhook action that sends the lead’s details as JSON to the Botphonic Public API, with your API key in the Authorization header. The lead joins a segment, and a workflow campaign with Auto-Enroll on calls them. If you’d rather not build the request in Zoho, use a Zap with the Zoho CRM trigger and Webhooks by Zapier.',
			),
			array(
				'question' => 'Which Zoho data centres does it work with?',
				'answer' => 'All of them. Put the API domain for your account’s data centre, such as www.zohoapis.com, www.zohoapis.eu or www.zohoapis.in, in your custom action URLs.',
			),
			array(
				'question' => 'How do I avoid duplicate leads?',
				'answer' => 'Use Zoho’s Upsert API. It matches existing records on duplicate-check fields such as email or phone, updates the match and only creates a lead when none exists.',
			),
			array(
				'question' => 'Does it work with Zoho Desk or other Zoho apps?',
				'answer' => 'Custom actions can call any REST API, so other Zoho apps with an API, such as Zoho Desk, can be connected the same way. This page covers Zoho CRM.',
			),
			array(
				'question' => 'Will this use up my Zoho API limits?',
				'answer' => 'Each lookup, upsert and call log counts against your Zoho CRM API credits. The daily allowance depends on your edition and number of user licenses, so check it before a large campaign.',
			),
		),

		'cta' => array(
			'title' => 'Plan your Zoho CRM call flow with us',
			'text' => 'Book a demo and we’ll map the lookup, the upsert and the call logging for your modules. Or start a free trial and connect your first custom action.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_actions_doc, $bpi_page);
get_footer();
