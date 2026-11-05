<?php
/**
 * Salesforce integration page (/integrations/salesforce-integration/).
 *
 * WordPress uses this file for the page with the slug "salesforce-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * Botphonic reaches Salesforce through the Salesforce REST API from custom
 * actions and workflow API steps (docs.botphonic.ai/docs/build/actions/custom-http-action,
 * /docs/build/workflow). Inbound triggers use the Botphonic Public API.
 * Salesforce facts: developer.salesforce.com (API editions, Task call fields),
 * Flow HTTP callouts with Named and External Credentials. Checked 2 Nov 2026.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_actions_doc = 'https://docs.botphonic.ai/docs/build/actions/custom-http-action';

$bpi_page = botphonic_integration_boot(
	'salesforce',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + Salesforce',
			'title' => 'Salesforce integration: AI calls that know the record and log the result',
			'lede' => 'Botphonic works with Salesforce through the Salesforce REST API. Before the agent says hello it can look up the lead or contact, during the call it can create or update records, and afterwards it can log the outcome as a completed task your reps can see.',
			'points' => array(
				'Looks up leads and contacts by phone before the greeting',
				'Creates and updates records while the caller is on the line',
				'Logs calls as Salesforce tasks with the outcome',
			),
			'guide' => array('label' => 'How custom actions work', 'url' => $bpi_actions_doc),
		),

		'visual' => array(
			'type' => 'flow',
			'caption' => 'Example: a returning lead calls your sales line',
			'status' => 'REST API',
			'events' => array(
				array('source' => 'caller', 'label' => 'Inbound call', 'time' => '14:20', 'title' => 'AI agent answers', 'detail' => '+1 415 555 0132'),
				array('source' => 'partner', 'label' => 'Salesforce', 'time' => '14:20', 'title' => 'Lead found before the greeting', 'detail' => 'Daniel Ortiz · Status: Working - Contacted'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '14:24', 'title' => 'Demo booked on the call', 'detail' => 'Thursday, 10:30 AM · Calendly'),
				array('source' => 'partner', 'label' => 'Salesforce', 'time' => '14:25', 'title' => 'Task logged, lead updated', 'detail' => 'Call · Inbound · 4 min · Budget field filled'),
			),
			'outcomes_label' => 'Records changed in Salesforce in this example',
			'outcomes' => array('Lead status', 'Completed call task', 'Custom fields'),
			'footnote' => 'Illustrative example. You choose which objects and fields Botphonic reads and writes.',
		),

		'facts' => array(
			'title' => 'Salesforce integration at a glance',
			'items' => array(
				array('label' => 'Connection', 'value' => 'Salesforce REST API, called from Botphonic custom actions and workflow API steps'),
				array('label' => 'Salesforce editions', 'value' => 'Enterprise, Unlimited, Performance and Developer, or Professional with the API add-on'),
				array('label' => 'Start calls from Salesforce', 'value' => 'A Flow HTTP callout to the Botphonic Public API'),
				array('label' => 'Botphonic plans', 'value' => 'Custom actions on every plan, workflows on Pro and Enterprise'),
			),
		),

		'steps' => array(
			'title' => 'How Botphonic reads and writes Salesforce data',
			'lede' => 'You decide which objects and fields the agent can touch. Each step is a Botphonic action or workflow step pointed at your Salesforce org.',
			'items' => array(
				array(
					'icon' => 'key',
					'title' => 'Set up API access',
					'text' => 'Create a Salesforce integration user that can reach only the objects you’ll map, plus the OAuth credentials Botphonic’s requests will use. Requests can go to Salesforce directly or through your own API layer.',
				),
				array(
					'icon' => 'search',
					'title' => 'Look up the caller first',
					'text' => 'A before-the-call custom action queries Salesforce by phone number. The fields it returns, such as name, status and owner, become variables the agent uses in the conversation.',
				),
				array(
					'icon' => 'edit',
					'title' => 'Act during the conversation',
					'text' => 'During-the-call custom actions create a lead, update a field or check an open case while the caller waits. Keep each response under two to three seconds, or set a delay message.',
				),
				array(
					'icon' => 'note',
					'title' => 'Log the outcome',
					'text' => 'Information Extractor turns the caller’s answers into clean values. A workflow API step, or a webhook to your API layer, then creates a completed call task and updates the record with them.',
				),
			),
			'more' => array('label' => 'Read the custom actions guide', 'url' => $bpi_actions_doc),
		),

		'capabilities' => array(
			'title' => 'What the agent can do with Salesforce',
			'lede' => 'Everything runs through standard Salesforce objects and the REST API, so your custom fields work too.',
			'items' => array(
				array(
					'icon' => 'search',
					'title' => 'Caller lookup before hello',
					'text' => 'Match the incoming number to a lead or contact and greet returning callers by name, with their status in context.',
				),
				array(
					'icon' => 'edit',
					'title' => 'Record updates mid-call',
					'text' => 'Create leads, update fields or open a case while the caller is still talking, using during-the-call custom actions.',
				),
				array(
					'icon' => 'list-check',
					'title' => 'Clean, structured fields',
					'text' => 'Yes/no, single-choice and open-question extractors map straight to checkboxes, picklists and text fields, instead of leaving facts buried in a transcript.',
				),
				array(
					'icon' => 'zap',
					'title' => 'Calls started from Flow',
					'text' => 'A record-triggered Flow can add a new lead to a Botphonic segment with an HTTP callout. A workflow campaign set to auto-enroll that segment then calls them.',
				),
				array(
					'icon' => 'calendar',
					'title' => 'Meetings booked on the call',
					'text' => 'With Cal.com or Calendly connected, the agent offers open times and books the meeting before the call ends.',
				),
				array(
					'icon' => 'transfer',
					'title' => 'Warm transfer to your team',
					'text' => 'When a caller asks for a person, the agent briefs your rep before handing over, so nobody has to repeat themselves.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'Where Botphonic fits your Salesforce process',
			'lede' => 'Start with one flow, check the results in your Salesforce reports, then add the next.',
			'tabs_label' => 'Choose a Salesforce use case',
			'items' => array(
				array(
					'id' => 'salesforce-speed-to-lead',
					'label' => 'Speed to lead',
					'title' => 'Call new web leads automatically',
					'text' => 'A record-triggered Flow sends each new lead to Botphonic. The agent calls, asks your qualifying questions, books a meeting if there’s interest and logs the result on the lead.',
					'points' => array(
						'Started from Salesforce with a Flow HTTP callout',
						'Answers saved to the lead fields you choose',
						'Meetings booked through Cal.com or Calendly',
						'A completed call task on every lead',
					),
					'link' => array('label' => 'See the AI sales assistant', 'url' => home_url('/solution/ai-sales-assistant/')),
				),
				array(
					'id' => 'salesforce-inbound',
					'label' => 'Inbound sales and support',
					'title' => 'Answer every call with the record in hand',
					'text' => 'The agent recognises existing contacts before it speaks, answers common questions from your knowledge base, and creates a lead or a case when the caller needs follow-up.',
					'points' => array(
						'Caller lookup by phone number before the greeting',
						'New leads or cases created during the call',
						'Warm transfer to your team when needed',
						'Calls logged as tasks on the matching record',
					),
					'link' => array('label' => 'Explore AI customer service', 'url' => home_url('/solution/ai-customer-service/')),
				),
				array(
					'id' => 'salesforce-renewals',
					'label' => 'Renewals and reminders',
					'title' => 'Run reminder and renewal calls at scale',
					'text' => 'Send the contacts due for a renewal, payment or appointment reminder to a Botphonic segment through the Public API. A workflow campaign calls each one and logs the outcome back to Salesforce.',
					'points' => array(
						'Contacts added from Salesforce with the Public API',
						'AI call, retry and follow-up email steps in one workflow',
						'DNC scrubbing and US calling-hour limits on outbound calls',
						'Outcomes written back from a workflow API step',
					),
					'link' => array('label' => 'See insurance use cases', 'url' => home_url('/industry/insurance/')),
				),
			),
		),

		'data_table' => array(
			'kicker' => 'Field mapping',
			'title' => 'Example mapping between a call and Salesforce',
			'lede' => 'Standard objects and fields are shown. Swap in your custom fields, the ones ending in <code>__c</code>, wherever your org uses them.',
			'caption' => 'Example Salesforce mapping for a Botphonic call',
			'columns' => array('Botphonic data', 'Salesforce object', 'Typical fields'),
			'rows' => array(
				array('Caller’s phone number', 'Lead or Contact (lookup)', '<code>Phone</code>, <code>MobilePhone</code>'),
				array('Qualification answers', 'Lead', '<code>Status</code>, <code>Rating</code>, your custom fields'),
				array('The call itself', 'Task with the Call subtype', '<code>Subject</code>, <code>CallType</code>, <code>CallDurationInSeconds</code>, <code>CallDisposition</code>, <code>Description</code>, <code>WhoId</code>'),
				array('A booked meeting', 'Event', '<code>StartDateTime</code>, <code>EndDateTime</code>, <code>WhoId</code>'),
				array('A support request', 'Case', '<code>Subject</code>, <code>Description</code>, <code>ContactId</code>'),
			),
			'note' => '<code>CallDisposition</code> appears as Call Result in Salesforce. Check field-level security for your integration user before you go live.',
		),

		'setup' => array(
			'title' => 'What you need to connect Salesforce',
			'lede' => 'Plan the mapping with your Salesforce admin first: which calls create records, which fields change, and who owns the follow-up.',
			'needs' => array(
				'A Salesforce edition with API access: Enterprise, Unlimited, Performance or Developer, or Professional with the API add-on',
				'An integration user whose profile or permission sets cover only the objects and fields you map',
				'For Flow-triggered calls, a Named Credential and External Credential that hold your Botphonic API key',
				'Botphonic custom actions, on every plan, and workflows for multi-step follow-up, on Pro and Enterprise',
				'Optional: Cal.com or Calendly for booking meetings on the call',
			),
			'note' => 'On Enterprise plans, a solution architect helps you design and test the mapping.',
			'links' => array(
				array('label' => 'Read the custom actions guide', 'url' => $bpi_actions_doc),
				array('label' => 'Talk to our team', 'url' => home_url('/contact/')),
			),
		),

		'trust' => array(
			'title' => 'Least-privilege access to your Salesforce org',
			'lede' => 'Botphonic only reaches the objects your integration user can see, and every request travels over TLS. Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'Scoped to your integration user', 'text' => 'Salesforce permissions, not Botphonic, decide which records and fields the agent can read or change.'),
				array('title' => 'TCPA controls for US calls', 'text' => 'DNC scrubbing before campaigns, calling-hour limits and instant opt-outs.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'Read the CRM integration buying guide', 'url' => home_url('/blog/ai-call-center-crm-integration/')),
			),
		),

		'faq_title' => 'Salesforce integration FAQ',
		'faqs' => array(
			array(
				'question' => 'How does Botphonic connect to Salesforce?',
				'answer' => 'Through the Salesforce REST API. Botphonic custom actions call it before or during a call to read and write records, and workflow API steps sync results after the call. To start calls from Salesforce, a Flow HTTP callout sends contacts to the Botphonic Public API.',
			),
			array(
				'question' => 'Which Salesforce editions does it work with?',
				'answer' => 'Any edition with API access. Salesforce turns API access on by default in Enterprise, Unlimited, Performance and Developer editions, and Professional Edition can add it as an add-on.',
			),
			array(
				'question' => 'Can Botphonic log calls as Salesforce activities?',
				'answer' => 'Yes. A completed Task with the Call subtype shows in the record’s activity timeline, with standard call fields such as call type, duration and call result, plus your notes.',
			),
			array(
				'question' => 'Can a Salesforce Flow trigger an AI call?',
				'answer' => 'Yes. A record-triggered Flow uses an HTTP callout to add the lead to a Botphonic segment through the Public API. A workflow campaign with Auto-Enroll on that segment then calls the lead.',
			),
			array(
				'question' => 'What does the agent know about the caller?',
				'answer' => 'Whatever your before-the-call action returns, for example the name, lead status, account owner or open cases. Those values become variables the agent can use in the conversation.',
			),
			array(
				'question' => 'Will Salesforce lookups slow the call down?',
				'answer' => 'Not if your endpoint answers quickly. Botphonic recommends responses under two to three seconds, and you can set a timeout and a delay message such as “One moment while I check that” so the caller never sits in silence.',
			),
			array(
				'question' => 'Who sets this up?',
				'answer' => 'Usually your Salesforce admin, working with our team. We can review your objects and mapping on a demo call, and Enterprise plans include a solution architect.',
			),
		),

		'cta' => array(
			'title' => 'Map your first Salesforce call flow with us',
			'text' => 'Book a demo and we’ll sketch the lookup, the fields and the task logging for your org. Or start a free trial and build your first custom action today.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_actions_doc, $bpi_page);
get_footer();
