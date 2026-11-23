<?php
/**
 * Solutions hub (/solution/).
 *
 * WordPress uses this file for the page with the slug "solution". It lists the
 * published child pages of that page, one per job the AI voice agent does on
 * the phone. The copy lives here; the layout is shared with the Products and
 * Industries hubs (template-parts/hub-page.php, inc/function-hub-pages.php).
 *
 * Facts checked 18 Nov 2026 against the site: plans (temp-pricings.php),
 * ratings and integrations (template-parts/integrations/hero.php,
 * page-integrations.php), home page FAQ and each solution page's own copy.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bphub_page = botphonic_hub_boot(
	'solution',
	array(
		'label' => 'Solutions',
		'noun' => array('solution', 'solutions'),
		'list_name' => 'Botphonic AI voice agent solutions',
		'default_icon' => 'layers',

		'hero' => array(
			'kicker' => 'AI voice agent solutions',
			'title' => 'AI voice agent solutions for every kind of business call',
			'lede' => 'Pick the job you want done on the phone: answer and route calls, book appointments, qualify leads or run outbound campaigns. Every solution runs on the same Botphonic agent, which picks up in under 300 ms, speaks 50+ languages and hands off to your team when a person is needed.',
			'proof' => array(
				array('5.0', 'on G2'),
				array('4.8', 'on Capterra'),
				array('500+', 'businesses'),
				array('24/7', 'answering'),
			),
		),

		'list' => array(
			'kicker' => 'All solutions',
			'title' => 'Choose a solution by the job it does',
			'lede' => 'Start with one and add more later: every plan includes unlimited AI agents.',
		),

		'groups' => array(
			'front-desk' => 'Front desk & answering',
			'service' => 'Customer service',
			'booking' => 'Scheduling',
			'sales' => 'Sales & outbound',
		),

		// Curated card content by page slug, in display order.
		'items' => array(
			'ai-receptionist' => array(
				'group' => 'front-desk',
				'icon' => 'phone-incoming',
				'line' => 'Answers every call, books appointments and transfers callers to the right person.',
				'tasks' => array('Books appointments', 'Qualifies leads', 'Human handoff'),
			),
			'ai-answering-service' => array(
				'group' => 'front-desk',
				'icon' => 'message',
				'line' => 'Picks up every call in seconds, takes the details and logs each conversation for your team.',
				'tasks' => array('Answers 24/7', '50+ languages', 'From $12/month'),
			),
			'ai-ivr' => array(
				'group' => 'front-desk',
				'icon' => 'list',
				'line' => 'Lets callers say what they need instead of pressing 1, then routes or resolves the call.',
				'tasks' => array('Understands full sentences', 'Smart routing', 'Real-time API triggers'),
			),
			'ai-call-center' => array(
				'group' => 'front-desk',
				'icon' => 'headphones',
				'line' => 'Handles high call volume, inbound and outbound, without adding seats.',
				'tasks' => array('High volume', 'Inbound and outbound', 'Call analytics'),
			),
			'ai-customer-service' => array(
				'group' => 'service',
				'icon' => 'smile',
				'line' => 'Resolves routine customer questions at any hour and personalizes every conversation.',
				'tasks' => array('24/7 support', 'Instant answers', 'Personalized replies'),
			),
			'ai-concierge' => array(
				'group' => 'service',
				'icon' => 'bell',
				'line' => 'Gives callers personal help around the clock, from bookings to requests, in 50+ languages.',
				'tasks' => array('Schedules meetings', 'Finds new leads', '50+ languages'),
			),
			'onboarding' => array(
				'group' => 'service',
				'icon' => 'user-plus',
				'label' => 'Client Onboarding',
				'line' => 'Guides new clients through their first weeks with personalized calls and messages.',
				'tasks' => array('Welcome calls', 'Personalized messages', 'KPI tracking'),
			),
			'ai-appointment-booking' => array(
				'group' => 'booking',
				'icon' => 'calendar-check',
				'line' => 'Books callers into open slots in real time and sends reminders to cut no-shows.',
				'tasks' => array('Live availability', 'Reminders', 'Fewer no-shows'),
			),
			'ai-appointment-setter' => array(
				'group' => 'booking',
				'icon' => 'calendar',
				'line' => 'Qualifies prospects and books sales meetings straight into your calendar.',
				'tasks' => array('Intent detection', 'Smart reminders', 'Calendar booking'),
			),
			'ai-sales-assistant' => array(
				'group' => 'sales',
				'icon' => 'trending',
				'line' => 'Qualifies new leads, books calls and follows up automatically, with a summary of every conversation.',
				'tasks' => array('Lead qualification', 'Call summaries', 'Follow-ups'),
			),
			'sales-automation' => array(
				'group' => 'sales',
				'icon' => 'zap',
				'label' => 'AI Sales Automation',
				'line' => 'Handles first sales conversations, qualification and follow-ups, then hands qualified prospects to your reps.',
				'tasks' => array('Lead qualification', 'Meeting booking', 'CRM workflow'),
			),
			'ai-telemarketing' => array(
				'group' => 'sales',
				'icon' => 'megaphone',
				'line' => 'Runs outbound calling campaigns at scale, with custom scripts and TCPA-compliant controls.',
				'tasks' => array('Custom scripts', 'Lead qualification', 'Best time to call'),
			),
		),

		// Rows: call problem, then card slugs to start with and to consider next.
		'guide' => array(
			'kicker' => 'Where to start',
			'title' => 'Which solution fits your calls?',
			'lede' => 'Start from the problem you see today. Each row points to the solution built for it and the one teams usually add next.',
			'caption' => 'Botphonic solutions matched to common call problems',
			'columns' => array('If this happens today', 'Start with', 'Also consider'),
			'rows' => array(
				array('Calls go to voicemail after hours or when the team is busy', array('ai-answering-service'), array('ai-receptionist')),
				array('Your front desk spends the day on the phone', array('ai-receptionist'), array('ai-appointment-booking')),
				array('Callers get lost in “press 1” phone menus', array('ai-ivr'), array('ai-receptionist')),
				array('Call volume spikes beyond what your team can staff', array('ai-call-center'), array('ai-ivr')),
				array('Customers wait on hold for answers to routine questions', array('ai-customer-service'), array('ai-call-center')),
				array('Booking an appointment takes several calls', array('ai-appointment-booking'), array('ai-appointment-setter')),
				array('New leads wait hours for a first call', array('ai-sales-assistant'), array('ai-appointment-setter', 'sales-automation')),
				array('You need to reach a long list of prospects', array('ai-telemarketing'), array('sales-automation')),
				array('VIP customers or guests expect personal service at any hour', array('ai-concierge'), array('ai-customer-service')),
				array('New clients go quiet in their first weeks', array('onboarding'), array('ai-customer-service')),
			),
		),

		'features' => array(
			'kicker' => 'How it works',
			'title' => 'How every Botphonic solution handles a call',
			'lede' => 'The job changes from one solution to the next. The way the agent works on the line stays the same.',
			'numbered' => true,
			'items' => array(
				array('title' => 'Answers straight away', 'text' => 'The agent picks up at any hour and handles several callers at the same time, so nobody waits on hold.'),
				array('title' => 'Understands the request', 'text' => 'It follows your knowledge base and business rules, and detects intent and sentiment as the caller talks.'),
				array('title' => 'Takes the action', 'text' => 'It books the slot, qualifies the lead or updates the record in your calendar and CRM while the caller is on the line.'),
				array('title' => 'Hands off and reports', 'text' => 'Complex calls go to your team with context, and every call ends with a summary, transcript and recording.'),
			),
		),

		'related' => array(
			'kicker' => 'Keep exploring',
			'title' => 'See solutions in context',
			'items' => array(
				array('icon' => 'building', 'title' => 'Industries', 'text' => 'How the agent handles calls in healthcare, real estate, finance, insurance, education and more.', 'url' => home_url('/industry/'), 'cta' => 'See all industries'),
				array('icon' => 'grid', 'title' => 'Products', 'text' => 'Agent Studio, ready-made templates, AI phone calls in 50+ languages and the cold email tools.', 'url' => home_url('/product/'), 'cta' => 'See all products'),
				array('icon' => 'plug', 'title' => 'Integrations', 'text' => 'Connect HubSpot, Salesforce, Zoho CRM, Cal.com, Calendly and the phone numbers you already use.', 'url' => home_url('/integrations/'), 'cta' => 'See all integrations'),
			),
		),

		'faq' => array(
			'title' => 'Questions about AI voice agent solutions',
			'lede' => 'Not sure which one fits? We’ll map it to your call flow on a short demo.',
		),
		'faqs' => array(
			array(
				'question' => 'What is an AI voice agent solution?',
				'answer' => 'It’s a Botphonic AI voice agent set up for one job on your phone line, such as answering as a receptionist, booking appointments or qualifying sales leads. Every solution runs on the same agent, with its own call flow, knowledge base and actions.',
			),
			array(
				'question' => 'Which solution should I start with?',
				'answer' => 'Start with the calls you lose today. If calls go to voicemail, start with the AI answering service or AI receptionist. If booking takes several calls, start with AI appointment booking. If new leads wait for a call back, start with the AI sales assistant. Every plan includes unlimited AI agents, so you can add more solutions later.',
			),
			array(
				'question' => 'What’s the difference between an AI receptionist and an AI answering service?',
				'answer' => 'An AI answering service makes sure every call is picked up, answered and logged, with messages passed on to your team. An AI receptionist does more at the front desk: it greets callers, answers questions, books appointments and transfers calls to the right person.',
			),
			array(
				'question' => 'Can the same agent handle inbound and outbound calls?',
				'answer' => 'Yes. The agent that answers your line can also run outbound campaigns such as lead follow-up, appointment reminders, payment reminders and surveys, with TCPA-compliant controls for outbound calling.',
			),
			array(
				'question' => 'When does the AI hand a call to a person?',
				'answer' => 'You decide. Set rules for urgent, complex or upset callers, and the agent transfers them to your team with the context of the call so far, so nobody has to repeat themselves.',
			),
			array(
				'question' => 'How quickly can a solution go live?',
				'answer' => 'Connect a number, add your FAQs and pick a voice, and the agent can answer its first call within minutes. If you’d like help tuning the call flow, a Botphonic voice AI engineer can work through it with you on a demo.',
			),
		),

		'cta' => array(
			'title' => 'Not sure which solution fits?',
			'text' => 'Tell us how calls come in today, and we’ll show you the agent handling them on a 20-minute demo.',
			'primary' => array('label' => 'Book a demo', 'url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery'),
			'secondary' => array('label' => 'Start free trial', 'url' => defined('BOTPHONIC_REGISTER_URL') ? BOTPHONIC_REGISTER_URL : 'https://app.botphonic.ai/register/'),
		),
	)
);

get_header();
get_template_part('template-parts/hub-page', null, array('page' => $bphub_page));
unset($bphub_page);
get_footer();
