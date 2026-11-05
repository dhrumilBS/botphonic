<?php
/**
 * HubSpot integration page (/integrations/hubspot-integration/).
 *
 * WordPress uses this file for the page with the slug "hubspot-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * Sources checked 2 Nov 2026:
 * - Botphonic: docs.botphonic.ai/integrations/hubspot, /docs/build/workflow,
 *   /docs/build/configuration/actions, plan features from api.botphonic.ai/api/plans.
 * - HubSpot: knowledge.hubspot.com (app install permissions, lifecycle stages, lead status).
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_docs = botphonic_integration_docs_url('/integrations/hubspot');

$bpi_page = botphonic_integration_boot(
	'hubspot',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + HubSpot',
			'title' => 'HubSpot integration: call new contacts and log every outcome',
			'lede' => 'Botphonic’s AI voice agent calls new HubSpot contacts automatically, then writes the lead status, lifecycle stage and a call note back to the record.',
			'guide' => array('label' => 'Read the HubSpot setup guide', 'url' => $bpi_docs),
		),

		'visual' => array(
			'type' => 'flow',
			'caption' => 'Example: a new HubSpot contact gets a call',
			'status' => 'Connected',
			'events' => array(
				array('source' => 'partner', 'label' => 'HubSpot', 'time' => '09:02', 'title' => 'Contact created', 'detail' => 'Priya Shah · Lifecycle stage: Lead'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '09:03', 'title' => 'AI call placed', 'detail' => 'Outbound assistant calls the number on the record'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '09:07', 'title' => 'Outcome detected', 'detail' => '“Interested, asked about pricing”'),
				array('source' => 'partner', 'label' => 'HubSpot', 'time' => '09:07', 'title' => 'Contact updated', 'detail' => 'Lead status: Open · Note with summary, recording link and transcript'),
			),
			'outcomes_label' => 'Fields updated in HubSpot in this example',
			'outcomes' => array('Lead status', 'Lifecycle stage', 'Call note'),
			'footnote' => 'Illustrative example. Your outcome rules decide the values.',
		),

		'facts' => array(
			'title' => 'HubSpot integration at a glance',
			'items' => array(
				array('label' => 'Connection', 'value' => 'HubSpot app approval (OAuth), started from Integrations → HubSpot'),
				array('label' => 'Starts calls on', 'value' => 'New contacts and lifecycle stage changes'),
				array('label' => 'Writes back', 'value' => 'Lead status, lifecycle stage and call notes'),
				array('label' => 'Who can connect', 'value' => 'A HubSpot Super Admin or a user with App Marketplace access'),
			),
		),

		'steps' => array(
			'title' => 'From new contact to updated record, without anyone touching a list',
			'lede' => 'You set every rule inside Botphonic’s HubSpot settings. On the HubSpot side, you only approve the connection.',
			'items' => array(
				array(
					'icon' => 'link',
					'title' => 'Connect your HubSpot account',
					'text' => 'In Botphonic, open Integrations → HubSpot and click Connect. Approve the permissions on HubSpot’s screen, and the integration shows as Connected & Active.',
				),
				array(
					'icon' => 'flow',
					'title' => 'Choose what starts a call',
					'text' => 'Call every new contact, or enroll it in a Botphonic workflow. Stage triggers can also start a call or a workflow when a contact reaches a lifecycle stage.',
				),
				array(
					'icon' => 'transfer',
					'title' => 'The agent holds the conversation',
					'text' => 'Your assistant follows your prompt and knowledge base and qualifies the lead. With Cal.com or Calendly connected, it books the meeting before the call ends.',
				),
				array(
					'icon' => 'refresh',
					'title' => 'HubSpot updates itself',
					'text' => 'Botphonic matches the call to the outcomes you described, sets the lead status and lifecycle stage you mapped and adds a note. Unanswered calls follow their own mapping.',
				),
			),
			'more' => array('label' => 'Read the full HubSpot setup guide', 'url' => $bpi_docs),
		),

		'capabilities' => array(
			'title' => 'What Botphonic does with your HubSpot contacts',
			'lede' => 'Each option is a setting in Botphonic, so you can start with calls to new contacts and add stage triggers or workflows later.',
			'items' => array(
				array(
					'icon' => 'user-plus',
					'title' => 'Calls new contacts automatically',
					'text' => 'Choose “Make outbound call” and each new HubSpot contact gets a call from your AI agent. Nobody exports a list or dials by hand.',
				),
				array(
					'icon' => 'trending',
					'title' => 'Acts on lifecycle stages',
					'text' => 'Stage triggers start a workflow or a call when a contact enters a lifecycle stage you pick, such as Marketing Qualified Lead.',
				),
				array(
					'icon' => 'tag',
					'title' => 'Maps outcomes to HubSpot fields',
					'text' => 'Describe each outcome in plain words, like “interested and asked about pricing”. The AI matches the call to it and sets the lead status and lifecycle stage you assigned.',
				),
				array(
					'icon' => 'phone-off',
					'title' => 'Handles unanswered calls',
					'text' => 'A separate mapping covers calls nobody picks up, for example setting the lead status to Attempted to Contact, so the record shows you tried.',
				),
				array(
					'icon' => 'note',
					'title' => 'Adds a note after every call',
					'text' => 'Choose what goes into the note on the contact: call summary, recording link, call duration, extracted information and the full transcript.',
				),
				array(
					'icon' => 'database',
					'title' => 'Brings contacts into Botphonic',
					'text' => 'Turn on “Save contacts to Botphonic” and HubSpot contacts are created or updated in Botphonic with a HubSpot tag, ready for segments and auto-enroll campaigns.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'How sales and marketing teams use it',
			'lede' => 'Start where a slow or missing call costs you the most.',
			'tabs_label' => 'Choose a HubSpot use case',
			'items' => array(
				array(
					'id' => 'hubspot-new-leads',
					'label' => 'New lead follow-up',
					'title' => 'Call web leads while they still remember you',
					'text' => 'When a form fill creates a HubSpot contact, Botphonic calls it automatically instead of waiting for a rep to free up. The agent asks your qualifying questions and records the answers.',
					'points' => array(
						'New contacts trigger the call, with no list to export',
						'The same qualifying questions on every call',
						'Interested leads move to the lead status you mapped',
						'Meetings booked into Cal.com or Calendly during the call',
					),
					'link' => array('label' => 'See the AI sales assistant', 'url' => home_url('/solution/ai-sales-assistant/')),
				),
				array(
					'id' => 'hubspot-mql',
					'label' => 'MQL handoff',
					'title' => 'Qualify marketing leads before they reach sales',
					'text' => 'Add a stage trigger for Marketing Qualified Lead. Botphonic calls the contact, confirms need and timing, and sets the lifecycle stage and lead status that match the outcome, so reps start with conversations worth having.',
					'points' => array(
						'Triggered when a contact reaches the stage you choose',
						'Outcome rules written in plain language',
						'Lifecycle stage and lead status updated together',
						'Optional stop of any workflow already running',
					),
					'link' => array('label' => 'Explore the AI appointment setter', 'url' => home_url('/solution/ai-appointment-setter/')),
				),
				array(
					'id' => 'hubspot-nurture',
					'label' => 'Nurture workflows',
					'title' => 'Mix calls and emails in one journey',
					'text' => 'Enroll new contacts in a Botphonic workflow that combines AI calls, emails, waits and condition branches. If a call isn’t answered, the workflow can retry or send a follow-up email instead.',
					'points' => array(
						'Visual canvas with AI call, email, condition and API steps',
						'Retry or email fallback for unanswered calls',
						'Every run visible in the campaign’s execution logs',
						'Workflows are included on Pro and Enterprise plans',
					),
					'link' => array('label' => 'Compare plans', 'url' => home_url('/pricing/')),
				),
			),
		),

		'data_table' => array(
			'title' => 'What moves between HubSpot and Botphonic',
			'lede' => 'The integration works in both directions. These are the updates it makes, and when.',
			'caption' => 'Data the Botphonic HubSpot integration reads and writes',
			'columns' => array('Direction', 'When', 'What happens'),
			'rows' => array(
				array('HubSpot to Botphonic', 'A contact is created in HubSpot', 'Botphonic places an outbound call or enrolls the contact in the workflow you chose'),
				array('HubSpot to Botphonic', 'A contact reaches a lifecycle stage with a stage trigger', 'Botphonic runs the workflow or places the call set for that stage'),
				array('HubSpot to Botphonic', 'Contacts are created or updated, with contact sync on', 'The contact is created or updated in Botphonic and tagged, ready for segments'),
				array('Botphonic to HubSpot', 'A call ends with an outcome you defined', 'The lead status and lifecycle stage change to the values you mapped'),
				array('Botphonic to HubSpot', 'A call isn’t answered', 'Lead status and lifecycle stage follow your “call not answered” mapping'),
				array('Botphonic to HubSpot', 'A call completes, with notes on', 'A note is added to the contact with the details you selected'),
			),
			'note' => 'Disconnecting HubSpot stops all of these: workflow enrollments, outbound call triggers and CRM updates.',
		),

		'setup' => array(
			'title' => 'What you need to connect HubSpot',
			'lede' => 'The connection takes a few clicks. Most of the work is deciding your rules: which contacts get a call, and what each outcome should change in HubSpot.',
			'needs' => array(
				'A HubSpot user who is a Super Admin or has the App Marketplace access permission, which HubSpot requires to install apps',
				'A Botphonic account. The HubSpot integration is on every plan, including the free trial',
				'An outbound assistant and a number to call from: buy one in Botphonic or bring your own from Twilio, Telnyx, Plivo or Exotel',
				'For “Enroll in workflow” and stage-triggered workflows, a Botphonic workflow (Pro and Enterprise plans)',
				'To book meetings on the call, Cal.com or Calendly connected to Botphonic',
			),
			'note' => 'Tip: store phone numbers in HubSpot with the country code, for example +1&nbsp;415&nbsp;555&nbsp;0101, so the agent can dial them.',
			'links' => array(
				array('label' => 'Open the setup guide', 'url' => $bpi_docs),
				array('label' => 'Compare plans', 'url' => home_url('/pricing/')),
			),
		),

		'trust' => array(
			'title' => 'HubSpot access you can review and revoke',
			'lede' => 'You approve the connection on HubSpot’s own screen and can disconnect it from Botphonic at any time. Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'OAuth sign-in', 'text' => 'Botphonic connects through HubSpot’s OAuth 2.0 authorization, so it never needs a HubSpot password.'),
				array('title' => 'TCPA controls for US calls', 'text' => 'DNC scrubbing before campaigns, calling-hour limits and instant opt-outs.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'Read the CRM integration buying guide', 'url' => home_url('/blog/ai-call-center-crm-integration/')),
			),
		),

		'faq_title' => 'HubSpot integration FAQ',
		'faqs' => array(
			array(
				'question' => 'How does the Botphonic HubSpot integration work?',
				'answer' => 'You connect HubSpot from Integrations → HubSpot in Botphonic and approve access on HubSpot’s screen. Botphonic then acts on new contacts and on the lifecycle stage changes you choose: it places an AI call or starts a workflow, and writes the result back to HubSpot as a lead status, a lifecycle stage and a note.',
			),
			array(
				'question' => 'Which HubSpot permissions do I need?',
				'answer' => 'HubSpot only lets Super Admins, or users with the App Marketplace access permission, install apps. That user connects the integration and reviews the permissions Botphonic requests before approving them.',
			),
			array(
				'question' => 'What does Botphonic write back to HubSpot after a call?',
				'answer' => 'The lead status and lifecycle stage you mapped to the outcome the AI detected and, if you turn notes on, a note on the contact. You choose what the note includes: call summary, recording link, call duration, extracted information and the full transcript.',
			),
			array(
				'question' => 'Can Botphonic call contacts when they reach a lifecycle stage?',
				'answer' => 'Yes. A stage trigger runs a Botphonic workflow or places an outbound call when a contact enters the lifecycle stage you pick. You can also stop a workflow that’s already running for that contact before the new one starts.',
			),
			array(
				'question' => 'What happens when a contact doesn’t answer?',
				'answer' => 'You set a separate mapping for unanswered calls, so the lead status and lifecycle stage show the contact wasn’t reached. Inside a Botphonic workflow you can also retry the call or send a follow-up email.',
			),
			array(
				'question' => 'Do I have to build HubSpot workflows to trigger calls?',
				'answer' => 'No. You choose what starts a call (new contacts and lifecycle stage changes) in Botphonic’s HubSpot settings, not in HubSpot.',
			),
			array(
				'question' => 'How do I disconnect HubSpot?',
				'answer' => 'Open Integrations → HubSpot in Botphonic, click Disconnect and confirm. That stops every automation between the two: workflow enrollments, outbound call triggers and CRM updates.',
			),
			array(
				'question' => 'Is my HubSpot data secure?',
				'answer' => 'Botphonic connects through HubSpot’s OAuth 2.0 authorization, encrypts data in transit with TLS 1.2 or 1.3 and at rest with AES-256, and limits access with roles and MFA. See the <a href="' . esc_url(home_url('/security/')) . '">security overview</a> for compliance details.',
			),
		),

		'cta' => array(
			'title' => 'Hear the agent call a HubSpot lead before you commit',
			'text' => 'Start a free trial, connect HubSpot and test the flow on a contact of your own. Or book a demo and we’ll map your lifecycle stages and outcomes with you.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_docs, $bpi_page);
get_footer();
