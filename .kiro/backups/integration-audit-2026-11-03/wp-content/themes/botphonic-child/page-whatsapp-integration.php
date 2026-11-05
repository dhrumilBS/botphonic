<?php
/**
 * WhatsApp integration page (/integrations/whatsapp-integration/).
 *
 * WordPress uses this file for the page with the slug "whatsapp-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * Scope confirmed by the Botphonic team (2 Nov 2026): the WhatsApp integration
 * sends automated WhatsApp messages and runs follow-up workflows, for example
 * when someone submits a form with their mobile number. Botphonic does not
 * provide inbound or outbound WhatsApp calling, so don't describe calls here.
 *
 * WhatsApp rules follow Meta's WhatsApp Business Messaging Policy
 * (whatsappbusiness.com/policy) and message template guidelines
 * (developers.facebook.com/docs/whatsapp/message-templates/guidelines/,
 * updated 21 May 2026). Checked 2 Nov 2026.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_policy = 'https://whatsappbusiness.com/policy/';
$bpi_templates = 'https://developers.facebook.com/docs/whatsapp/message-templates/guidelines/';

$bpi_page = botphonic_integration_boot(
	'whatsapp',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + WhatsApp',
			'title' => 'WhatsApp integration: automated messages for every new lead',
			'lede' => 'When someone submits a form with their mobile number, Botphonic sends them a WhatsApp message automatically and runs the follow-up workflow you set up. Nobody has to copy numbers or remember to reply.',
			'points' => array(
				'Messages each new lead on WhatsApp automatically',
				'Starts the moment a form with a mobile number comes in',
				'Hands every lead to your follow-up workflow',
			),
			'guide' => array('label' => 'See what you need to start', 'url' => '#setup'),
		),

		'visual' => array(
			'type' => 'flow',
			'caption' => 'Example: a new lead gets a WhatsApp message',
			'status' => 'Connected',
			'events' => array(
				array('source' => 'caller', 'label' => 'Website form', 'time' => '10:14', 'title' => 'Form submitted', 'detail' => 'Aisha Khan · +44 7700 900123 · Asked about pricing'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => '10:14', 'title' => 'Workflow started', 'detail' => 'New lead added to your follow-up workflow'),
				array('source' => 'partner', 'label' => 'WhatsApp', 'time' => '10:14', 'title' => 'Message sent', 'detail' => '“Hi Aisha, thanks for your enquiry. Here’s our price list and a link to book a call.”'),
				array('source' => 'botphonic', 'label' => 'Botphonic', 'time' => 'Day 2', 'title' => 'Follow-up runs', 'detail' => 'The next step in your workflow, such as a reminder message'),
			),
			'outcomes_label' => 'What ran automatically in this example',
			'outcomes' => array('Message sent', 'Lead added', 'Follow-up scheduled'),
			'footnote' => 'Illustrative example. Your workflow sets the messages and timing.',
		),

		'facts' => array(
			'title' => 'WhatsApp integration at a glance',
			'items' => array(
				array('label' => 'What it does', 'value' => 'Sends automated WhatsApp messages and runs your follow-up workflow'),
				array('label' => 'Starts when', 'value' => 'Someone submits a form with their mobile number'),
				array('label' => 'WhatsApp’s rules', 'value' => 'Opt-in from each contact, and approved templates for first messages'),
				array('label' => 'Voice calls', 'value' => 'Not over WhatsApp. Botphonic’s AI calls use your phone numbers'),
			),
		),

		'steps' => array(
			'title' => 'From form submission to WhatsApp follow-up, automatically',
			'lede' => 'Set the workflow up once. After that, every new lead with a mobile number gets the same fast follow-up.',
			'items' => array(
				array(
					'icon' => 'link',
					'title' => 'Connect WhatsApp',
					'text' => 'Connect your WhatsApp Business number to Botphonic, so your workflows can send messages from it.',
				),
				array(
					'icon' => 'list-check',
					'title' => 'Connect your form',
					'text' => 'Send new form submissions to Botphonic, for example through Zapier. Each lead needs a mobile number and their consent to hear from you on WhatsApp.',
				),
				array(
					'icon' => 'edit',
					'title' => 'Write the messages',
					'text' => 'Set the first message and the follow-ups. WhatsApp only lets a business start a conversation with a message template it has approved.',
				),
				array(
					'icon' => 'flow',
					'title' => 'Let the workflow run',
					'text' => 'Botphonic sends the first message as soon as a form comes in, then runs the follow-up steps on the schedule you set.',
				),
			),
			'more' => array('label' => 'Read WhatsApp’s business messaging policy', 'url' => $bpi_policy),
		),

		'capabilities' => array(
			'title' => 'What the WhatsApp integration does',
			'lede' => 'You build the workflow in Botphonic: what starts it, the messages it sends and when it follows up.',
			'items' => array(
				array(
					'icon' => 'clock',
					'title' => 'Messages new leads straight away',
					'text' => 'The first WhatsApp message goes out as soon as the form comes in, not when someone on your team gets to it.',
				),
				array(
					'icon' => 'link',
					'title' => 'Starts from your forms',
					'text' => 'Website, landing page and ad forms can start the workflow once their submissions reach Botphonic, for example through Zapier.',
				),
				array(
					'icon' => 'refresh',
					'title' => 'Follows up on a schedule',
					'text' => 'Add follow-up messages with the timing you choose, so every lead gets the same follow-up without anyone keeping a list.',
				),
				array(
					'icon' => 'message',
					'title' => 'Sends from your business number',
					'text' => 'Messages come from your own WhatsApp Business number and profile, so leads know who is writing to them.',
				),
				array(
					'icon' => 'database',
					'title' => 'Keeps every lead in Botphonic',
					'text' => 'Each lead is saved as a Botphonic contact, ready for segments and other campaigns.',
				),
				array(
					'icon' => 'transfer',
					'title' => 'Part of your wider follow-up',
					'text' => 'Botphonic also makes AI phone calls and sends email, so WhatsApp can be one of several ways you follow up with a lead. See <a href="' . esc_url(home_url('/product/ai-phone-call/')) . '">AI phone calls</a>.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'Where automated WhatsApp messages help most',
			'lede' => 'Anywhere a new lead should hear from you in minutes, not hours.',
			'tabs_label' => 'Choose a WhatsApp use case',
			'items' => array(
				array(
					'id' => 'whatsapp-enquiries',
					'label' => 'Quote and enquiry forms',
					'title' => 'Reach every enquiry while it’s still fresh',
					'text' => 'A visitor asks for a quote or a callback. Botphonic sends them a WhatsApp message straight away, so they know you have their request, and the workflow follows up on the schedule you set.',
					'points' => array(
						'First message sent as soon as the form comes in',
						'The lead saved as a Botphonic contact',
						'Follow-up steps on your schedule',
						'No numbers copied by hand',
					),
					'link' => array('label' => 'See the AI sales assistant', 'url' => home_url('/solution/ai-sales-assistant/')),
				),
				array(
					'id' => 'whatsapp-real-estate',
					'label' => 'Property enquiries',
					'title' => 'Message property leads before another agent does',
					'text' => 'Buyers and renters fill in a form on your website or a listing page. Botphonic messages them on WhatsApp with the next steps, and the workflow follows up a day later.',
					'points' => array(
						'A WhatsApp message moments after the enquiry',
						'Listing links or next steps in the message',
						'A follow-up the next day, set in the workflow',
						'Every enquiry kept in Botphonic',
					),
					'link' => array('label' => 'See real estate use cases', 'url' => home_url('/industry/real-estate/')),
				),
				array(
					'id' => 'whatsapp-services',
					'label' => 'Service requests',
					'title' => 'Confirm service requests the moment they arrive',
					'text' => 'Customers book a visit or ask for a quote online. Botphonic confirms the request on WhatsApp, and the workflow sends the follow-up messages you set.',
					'points' => array(
						'Instant confirmation on WhatsApp',
						'Follow-up messages on your schedule',
						'Your business name on every message',
						'Requests saved as Botphonic contacts',
					),
					'link' => array('label' => 'See home services use cases', 'url' => home_url('/industry/home-services/')),
				),
			),
		),

		'data_table' => array(
			'kicker' => 'Who does what',
			'title' => 'What WhatsApp controls and what Botphonic does',
			'lede' => 'WhatsApp sets the rules for business messages. Botphonic sends them at the right moment and runs the follow-up.',
			'caption' => 'Responsibilities for WhatsApp messages sent from Botphonic workflows',
			'columns' => array('Area', 'Set by WhatsApp (Meta)', 'Handled by Botphonic'),
			'rows' => array(
				array('Who you can message', 'People who gave you their number and opted in to your messages', 'Messages the mobile number from the form'),
				array('The first message', 'An approved message template, because your business starts the conversation', 'Sends it as soon as the form comes in'),
				array('Follow-ups', 'Templates again once 24 hours pass without a message from the contact', 'Runs the next steps of your workflow on your schedule'),
				array('Sender identity', 'Your business profile, display name and verification', 'Sends from the WhatsApp number you connect'),
			),
			'note' => 'WhatsApp updates these rules. Check the <a href="' . esc_url($bpi_policy) . '">WhatsApp Business Messaging Policy</a> for the current version.',
		),

		'setup' => array(
			'title' => 'What you need to send WhatsApp messages from Botphonic',
			'lede' => 'Most of this is a one-time setup in your WhatsApp Business account. We can go through it with you on a demo call.',
			'needs' => array(
				'A WhatsApp Business number to send from, connected to Botphonic',
				'A form that collects each lead’s mobile number, connected to Botphonic, for example through Zapier',
				'Opt-in from each contact to receive WhatsApp messages from you, as WhatsApp’s policy requires',
				'Approved message templates for the messages your business sends first',
				'A Botphonic workflow with the messages and follow-up steps you want',
			),
			'note' => 'Meta reviews new message templates before you can use them, which can take up to 24 hours.',
			'links' => array(
				array('label' => 'Book a demo to set it up', 'url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery'),
				array('label' => 'Read Meta’s template guidelines', 'url' => $bpi_templates),
			),
		),

		'trust' => array(
			'title' => 'Lead details stay protected',
			'lede' => 'Contact details from your forms are encrypted in transit and at rest. Botphonic is built for regulated industries, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'Opt-in first', 'text' => 'WhatsApp’s policy only allows business messages to people who opted in, and every opt-out request must be honoured.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'See AI phone calls', 'url' => home_url('/product/ai-phone-call/')),
			),
		),

		'faq_title' => 'WhatsApp integration FAQ',
		'faqs' => array(
			array(
				'question' => 'What does the Botphonic WhatsApp integration do?',
				'answer' => 'It sends automated WhatsApp messages and runs follow-up workflows. For example, when someone submits a form with their mobile number, Botphonic sends them a WhatsApp message straight away and then runs the follow-up steps you set.',
			),
			array(
				'question' => 'Can Botphonic make or answer calls over WhatsApp?',
				'answer' => 'No. Botphonic doesn’t provide inbound or outbound WhatsApp calling. The WhatsApp integration covers messages and follow-up workflows. Botphonic’s AI voice agent makes and answers calls on regular phone numbers, such as your Twilio, Telnyx, Plivo or Exotel numbers or a number bought in Botphonic.',
			),
			array(
				'question' => 'What starts a WhatsApp message?',
				'answer' => 'A form submission that includes a mobile number. Once your form’s submissions reach Botphonic, for example through Zapier, each new one starts the workflow and the first message goes out automatically.',
			),
			array(
				'question' => 'Do contacts have to opt in to WhatsApp messages?',
				'answer' => 'Yes. WhatsApp’s Business Messaging Policy only allows a business to message people who gave it their number and agreed to receive its messages. Add a clear WhatsApp consent checkbox to your form, and honour every opt-out request.',
			),
			array(
				'question' => 'Why does WhatsApp need message templates?',
				'answer' => 'WhatsApp only lets a business start a conversation with a message template it has approved, and requires one again once 24 hours have passed since the contact last wrote to you. Meta reviews new templates before you can send them, which can take up to 24 hours.',
			),
			array(
				'question' => 'Which number do the messages come from?',
				'answer' => 'From the WhatsApp Business number you connect, so leads see your business name and profile on every message.',
			),
		),

		'cta' => array(
			'title' => 'Send every new lead a WhatsApp message, automatically',
			'text' => 'Book a demo and we’ll help you connect your form, set up the messages and build the follow-up workflow. Or start a free trial and explore Botphonic first.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_policy, $bpi_templates, $bpi_page);
get_footer();
