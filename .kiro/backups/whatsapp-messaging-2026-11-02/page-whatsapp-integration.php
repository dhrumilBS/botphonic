<?php
/**
 * WhatsApp integration page (/integrations/whatsapp-integration/).
 *
 * WordPress uses this file for the page with the slug "whatsapp-integration".
 * The copy lives here; sections are shared in template-parts/integrations/ and
 * wired up in inc/function-integration-pages.php.
 *
 * WhatsApp facts follow Meta's WhatsApp Business Calling API documentation
 * (developers.facebook.com/docs/whatsapp/cloud-api/calling/, updated 26 Jun 2026;
 * business-initiated calls, updated 29 Oct 2026). Botphonic's docs don't cover
 * WhatsApp yet, so the agent features named here are the general assistant
 * features (actions, extraction, summaries). Confirm the WhatsApp connection
 * details with the product team before changing claims. Checked 2 Nov 2026.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_meta_doc = 'https://developers.facebook.com/docs/whatsapp/cloud-api/calling/';

$bpi_page = botphonic_integration_boot(
	'whatsapp',
	array(
		'hero' => array(
			'kicker' => 'Botphonic + WhatsApp Business',
			'title' => 'WhatsApp integration: an AI voice agent that answers WhatsApp Business calls',
			'lede' => 'Your customers already message you on WhatsApp. When they tap the call button instead, Botphonic’s AI voice agent can pick up, sort out routine requests, book appointments and save a summary of the call, at any hour.',
			'points' => array(
				'Answers calls placed from your WhatsApp chat and business profile',
				'Books, reschedules and captures details on the call',
				'Free for customers to call over WhatsApp',
			),
		),

		'visual' => array(
			'type' => 'call',
			'title' => 'WhatsApp call · Northside Auto Service',
			'meta' => 'Example call · AI voice agent · 7:48 PM',
			'badge' => 'Response < 300 ms',
			'messages' => array(
				array('who' => 'caller', 'text' => 'Hi, I booked a service for Friday. Can I move it to Saturday?'),
				array('who' => 'agent', 'text' => 'Of course. Saturday has 9:30 AM or 1:00 PM free. Which suits you?'),
				array('who' => 'caller', 'text' => '9:30, please.'),
				array('who' => 'agent', 'text' => 'Done. You’re booked for Saturday at 9:30 AM. Anything the technician should know?'),
			),
			'outcomes_label' => 'Actions completed during the example call',
			'outcomes' => array('Booking moved to Saturday', 'Call summary saved', 'Transcript logged'),
			'footnote' => 'Illustrative example of a WhatsApp voice call handled by a Botphonic agent.',
		),

		'facts' => array(
			'title' => 'WhatsApp integration at a glance',
			'items' => array(
				array('label' => 'Call type', 'value' => 'WhatsApp voice calls (VoIP) to your business number'),
				array('label' => 'How customers call', 'value' => 'The call button in your chat or profile, or a call link you share'),
				array('label' => 'Meta’s requirements', 'value' => 'A Cloud API number with calling on and a 2,000-recipient daily messaging limit'),
				array('label' => 'Calling customers', 'value' => 'Only after they grant call permission, as Meta requires'),
			),
		),

		'steps' => array(
			'title' => 'How WhatsApp calls reach your AI agent',
			'lede' => 'Meta controls who can call you and when. Botphonic handles the conversation once the call connects.',
			'items' => array(
				array(
					'icon' => 'list-check',
					'title' => 'Check your number',
					'text' => 'Meta’s Calling API works with business numbers on the WhatsApp Cloud API, not the WhatsApp Business app, and needs a daily messaging limit of at least 2,000 unique recipients.',
				),
				array(
					'icon' => 'link',
					'title' => 'Turn on calling and connect the agent',
					'text' => 'Enable calling on the number, then connect it to the Botphonic assistant that should answer. Run a few test calls before you show the call button to customers.',
				),
				array(
					'icon' => 'clock',
					'title' => 'Choose when calls come in',
					'text' => 'With an AI agent answering, you can keep calling on around the clock, or use WhatsApp’s business call hours and callback requests to limit it.',
				),
				array(
					'icon' => 'message',
					'title' => 'Let the agent handle it',
					'text' => 'The agent greets the caller, answers from your knowledge base, books or reschedules, and saves a summary and transcript to your Botphonic call logs.',
				),
			),
			'more' => array('label' => 'Read Meta’s WhatsApp calling guide', 'url' => $bpi_meta_doc),
		),

		'capabilities' => array(
			'title' => 'What the agent does on a WhatsApp call',
			'lede' => 'You build the assistant in Botphonic: a prompt, a knowledge base, a voice and the actions it’s allowed to take.',
			'items' => array(
				array(
					'icon' => 'clock',
					'title' => 'Answers out of hours',
					'text' => 'Calls placed after closing or at peak times get answered instead of ringing out.',
				),
				array(
					'icon' => 'calendar',
					'title' => 'Books and reschedules',
					'text' => 'With Cal.com or Calendly connected, the agent offers open slots and confirms the booking during the call.',
				),
				array(
					'icon' => 'list-check',
					'title' => 'Captures details cleanly',
					'text' => 'Information Extractor saves names, order numbers, preferences and answers as structured values.',
				),
				array(
					'icon' => 'search',
					'title' => 'Looks up orders and bookings',
					'text' => 'Custom actions call your own systems mid-call, for example to check an order status or a reservation.',
				),
				array(
					'icon' => 'note',
					'title' => 'Keeps a record',
					'text' => 'Every call gets a summary and transcript in Botphonic, so your team can see what was said and agreed.',
				),
				array(
					'icon' => 'globe',
					'title' => 'Speaks your customers’ language',
					'text' => 'Run the agent in the language your customers use and switch mid-call when they do. See <a href="' . esc_url(home_url('/product/multilingual-voice-ai-agents/')) . '">multilingual voice agents</a>.',
				),
			),
		),

		'use_cases' => array(
			'title' => 'Who gets the most from WhatsApp voice AI',
			'lede' => 'Businesses whose customers already reach them on WhatsApp before they think of dialling a number.',
			'tabs_label' => 'Choose a WhatsApp use case',
			'items' => array(
				array(
					'id' => 'whatsapp-clinics',
					'label' => 'Clinics and salons',
					'title' => 'Bookings and changes without phone tag',
					'text' => 'Patients and clients call from the chat they already use with you. The agent books, moves or cancels appointments and answers questions about hours, prices and preparation.',
					'points' => array(
						'Booking, rescheduling and cancelling on the call',
						'Answers from your knowledge base',
						'Summaries for your front desk',
						'Out-of-hours calls answered',
					),
					'link' => array('label' => 'See healthcare use cases', 'url' => home_url('/industry/healthcare/')),
				),
				array(
					'id' => 'whatsapp-retail',
					'label' => 'Retail and delivery',
					'title' => 'Order questions answered on the spot',
					'text' => 'Shoppers ask where their order is or how to return it. A custom action checks your order system mid-call, so the agent gives a real answer instead of a ticket number.',
					'points' => array(
						'Order lookups through your own API',
						'Return and delivery questions answered',
						'Details captured for anything that needs a person',
						'Every conversation transcribed',
					),
					'link' => array('label' => 'Explore AI customer service', 'url' => home_url('/solution/ai-customer-service/')),
				),
				array(
					'id' => 'whatsapp-travel',
					'label' => 'Travel and hospitality',
					'title' => 'Guests call from anywhere, at no cost to them',
					'text' => 'Guests call your WhatsApp number from wherever they are, and Meta doesn’t charge them for the call. The agent handles reservations, check-in questions and changes in their language.',
					'points' => array(
						'Reservation changes and questions handled',
						'Conversations in the guest’s language',
						'Summaries saved for your team',
						'Available around the clock',
					),
					'link' => array('label' => 'See travel and hospitality', 'url' => home_url('/industry/travel-and-hospitality/')),
				),
			),
		),

		'data_table' => array(
			'kicker' => 'Who does what',
			'title' => 'What WhatsApp controls and what Botphonic does',
			'lede' => 'Calling settings live in your WhatsApp Business account. The conversation is Botphonic’s job.',
			'caption' => 'Responsibilities for WhatsApp Business calls answered by Botphonic',
			'columns' => array('Area', 'Set in WhatsApp (Meta)', 'Handled by Botphonic'),
			'rows' => array(
				array('Who can call', 'Call button visibility, inbound call control and the countries where the call icon shows', 'Answers every call that reaches your number'),
				array('Opening hours', 'Business call hours and callback requests', 'Lets you keep calling on around the clock'),
				array('The conversation', 'Nothing to set here', 'Prompt, knowledge base, voice, booking and data capture'),
				array('Records', 'The call appears in the customer’s chat history', 'Summary and transcript in your Botphonic call logs'),
				array('Calling customers', 'Allowed only after the customer grants call permission, with limits on requests', 'Talk to us before planning outbound WhatsApp calls'),
			),
			'note' => 'Meta updates these rules. Check the <a href="' . esc_url($bpi_meta_doc) . '">WhatsApp Business Calling documentation</a> for current limits and country availability.',
		),

		'setup' => array(
			'title' => 'What you need to accept WhatsApp calls',
			'lede' => 'These are Meta’s requirements for the WhatsApp Business Calling API. We check your number against them before you go live.',
			'needs' => array(
				'A WhatsApp Business Platform number on the Cloud API. Numbers on the WhatsApp Business app can’t use calling',
				'A daily messaging limit of at least 2,000 unique recipients',
				'Calling enabled on the business number',
				'For calls you place: the customer’s call permission, and a business number in a country where Meta allows business-initiated calls',
				'A Botphonic assistant with your prompt, knowledge base and booking settings',
			),
			'note' => 'Meta limits call permission requests on production numbers to one per customer per day and two per week.',
			'links' => array(
				array('label' => 'Book a WhatsApp calling demo', 'url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery'),
				array('label' => 'Read Meta’s calling guide', 'url' => $bpi_meta_doc),
			),
		),

		'trust' => array(
			'title' => 'WhatsApp calls get the same safeguards as your phone line',
			'lede' => 'Every conversation Botphonic handles is encrypted in transit and at rest. Botphonic is built for regulated calls, with SOC 2 Type II readiness and regular third-party penetration tests.',
			'extra' => array(
				array('title' => 'A verified business identity', 'text' => 'WhatsApp’s business profile and verification show callers who they’re talking to.'),
			),
		),

		'related' => array(
			'links' => array(
				array('label' => 'See multilingual voice agents', 'url' => home_url('/product/multilingual-voice-ai-agents/')),
			),
		),

		'faq_title' => 'WhatsApp integration FAQ',
		'faqs' => array(
			array(
				'question' => 'Can an AI agent answer WhatsApp Business calls?',
				'answer' => 'Yes. With WhatsApp’s Business Calling API, customers can call your business number from WhatsApp, and a Botphonic AI voice agent answers, handles the request and saves a summary and transcript of the call.',
			),
			array(
				'question' => 'What do I need from Meta to receive WhatsApp calls?',
				'answer' => 'A business number on the WhatsApp Cloud API with calling enabled, an app subscribed to the calling events, and a daily messaging limit of at least 2,000 unique recipients. Numbers that use the WhatsApp Business app can’t use calling.',
			),
			array(
				'question' => 'Can the agent call customers on WhatsApp?',
				'answer' => 'Meta only allows business-initiated calls after a customer grants call permission, for example by accepting a permission request or by calling you with callback permission turned on. Requests are limited and availability depends on your business number’s country, so talk to us before planning outbound WhatsApp calls.',
			),
			array(
				'question' => 'Is it free for customers to call us on WhatsApp?',
				'answer' => 'Meta describes WhatsApp calling as free for your customers. Your Botphonic plan is billed on talk minutes, as shown on the <a href="' . esc_url(home_url('/pricing/')) . '">pricing page</a>, and Meta’s own pricing covers any charges on your WhatsApp number.',
			),
			array(
				'question' => 'Can we limit when customers can call?',
				'answer' => 'Yes. WhatsApp lets you set business call hours, turn inbound calling off and offer callback requests when you’re closed. With an AI agent answering, you can also leave calling on around the clock.',
			),
			array(
				'question' => 'How do customers find the call button?',
				'answer' => 'When calling is enabled, WhatsApp shows a call icon in your chat and business profile. You can also send call buttons in messages and share call links, for example on your website.',
			),
		),

		'cta' => array(
			'title' => 'Find out if your WhatsApp number is ready for AI calls',
			'text' => 'Book a demo and we’ll check your number against Meta’s calling requirements and show the agent handling calls like yours. Or start a free trial and build the assistant first.',
		),
	)
);

get_header();
get_template_part('template-parts/integrations/layout', null, array('page' => $bpi_page));
unset($bpi_meta_doc, $bpi_page);
get_footer();
