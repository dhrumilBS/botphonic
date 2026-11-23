<?php
/**
 * Template Name: Industries Hub
 * Template Post Type: page
 *
 * Industries hub (/industry/). Lists every published child page of the page
 * it is assigned to (the Industry parent page). Known industries get a curated
 * icon, line and highlights; any new child page still appears, using its
 * Yoast meta description. The copy lives here; the layout is shared with the
 * Products and Solutions hubs (template-parts/hub-page.php,
 * inc/function-hub-pages.php).
 *
 * Facts checked 18 Nov 2026 against the site: each industry page's own copy,
 * compliance and customers (template-about.php, functions.php) and
 * integrations (page-integrations.php).
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bphub_page = botphonic_hub_boot(
	'industry',
	array(
		'label' => 'Industries',
		'noun' => array('industry', 'industries'),
		'list_name' => 'Botphonic AI voice agents by industry',
		'default_icon' => 'building',

		'hero' => array(
			'kicker' => 'Industries',
			'title' => 'AI voice agents built for the way your industry takes calls',
			'lede' => 'Botphonic answers, books and follows up on calls for {count} industries, from clinics and dealerships to contact centers. Pick yours to see what the agent handles on a real call and which rules it works within.',
			'proof' => array(
				array('500+', 'businesses'),
				array('50+', 'languages'),
				array('<300 ms', 'response time'),
				array('24/7', 'answering'),
			),
		),

		'list' => array(
			'kicker' => 'All industries',
			'title' => 'Choose your industry',
			'lede' => 'Each page shows the calls the agent handles in that industry and how it connects to the tools you already use.',
		),

		'groups' => array(
			'health' => 'Health & education',
			'sales' => 'Sales & property',
			'finance' => 'Finance & insurance',
			'service' => 'Service & operations',
		),

		// Curated card content by page slug, in display order. Copy comes from each page's own description.
		'items' => array(
			'healthcare' => array('group' => 'health', 'icon' => 'heart', 'line' => 'Answer every patient call, day or night.', 'tasks' => array('Books appointments', 'Verifies insurance', 'HIPAA-ready')),
			'real-estate' => array('group' => 'sales', 'icon' => 'home', 'line' => 'Never miss a buyer or seller inquiry.', 'tasks' => array('Answers listing calls', 'Qualifies buyers', 'Books showings')),
			'finance' => array('group' => 'finance', 'icon' => 'bank', 'line' => 'Client calls handled with compliance built in.', 'tasks' => array('Handles client calls', 'Flags fraud', 'PCI DSS workflows')),
			'insurance' => array('group' => 'finance', 'icon' => 'shield', 'line' => 'Faster claims and policy answers, 24/7.', 'tasks' => array('Speeds up claims', 'Detects fraud', 'Answers policy questions')),
			'bpo-customer-service' => array('group' => 'service', 'icon' => 'headset', 'line' => 'Absorb call peaks without adding seats.', 'tasks' => array('Handles routine inquiries', 'Routes complex calls', 'Qualifies leads')),
			'home-services' => array('group' => 'service', 'icon' => 'wrench', 'line' => 'Book jobs while your crew is on site.', 'tasks' => array('Answers 24/7', 'Books jobs instantly', 'Routes to the right technician')),
			'car-dealerships' => array('group' => 'sales', 'icon' => 'car', 'line' => 'Turn every inquiry into a showroom visit.', 'tasks' => array('Handles sales inquiries', 'Schedules test drives', 'Follows up with customers')),
			'solar' => array('group' => 'sales', 'icon' => 'sun', 'line' => 'Qualify homeowners while interest is high.', 'tasks' => array('Qualifies leads', 'Schedules site visits', 'Calculates ROI on the call')),
			'travel-and-hospitality' => array('group' => 'service', 'icon' => 'plane', 'line' => 'Guest calls answered in their language.', 'tasks' => array('Handles bookings', 'Manages cancellations', '20+ languages')),
			'education' => array('group' => 'health', 'icon' => 'cap', 'line' => 'Take admin calls off your staff’s desks.', 'tasks' => array('Answers school calls', 'Counsels students', 'FERPA-ready')),
			'recruitment' => array('group' => 'sales', 'icon' => 'users', 'line' => 'Screen candidates without the phone tag.', 'tasks' => array('Screens candidates', 'Books interviews', 'Cuts hiring time')),
			'agency' => array('group' => 'sales', 'icon' => 'briefcase', 'line' => 'Offer AI calling to every client you serve.', 'tasks' => array('Books discovery calls', 'Qualifies leads', 'Handles client inquiries')),
			'logistic' => array('group' => 'service', 'icon' => 'truck', 'label' => 'Logistics', 'line' => 'Keep shippers and drivers updated on every call.', 'tasks' => array('Shares status updates', 'Streamlines operations', 'Keeps clients informed')),
		),

		// Rows: industry pages (card slugs) or text, the calls handled, and the rules supported.
		'guide' => array(
			'kicker' => 'Compliance',
			'title' => 'Built for the rules your industry works under',
			'lede' => 'Every agent runs on the same secure platform. This is what each industry setup is built to support.',
			'caption' => 'Botphonic industry setups and the rules they support',
			'columns' => array('Industry', 'Calls the agent handles', 'Built to support'),
			'rows' => array(
				array(array('healthcare'), 'Appointment booking, insurance checks and after-hours patient calls', 'HIPAA-ready workflows, with PHI handled under BAA-compatible controls'),
				array(array('finance'), 'Client calls, payment calls and fraud flags', 'PCI DSS payment capture, plus GLBA and SOX workflows'),
				array(array('education'), 'Admissions, enrolment and school admin calls', 'FERPA-ready workflows'),
				array(array('bpo-customer-service'), 'Routine inquiries, routing and lead qualification at volume', 'HIPAA and GDPR aligned call handling'),
				array(array('real-estate', 'solar', 'car-dealerships'), 'Lead follow-up, qualification and booked viewings, site visits or test drives', 'TCPA-compliant controls for outbound calls'),
				array('Every industry', 'Inbound and outbound calls', 'GDPR-aligned data handling, SOC 2 Type II ready controls, encryption in transit and at rest'),
			),
		),

		'features' => array(
			'kicker' => 'In every industry',
			'title' => 'One agent, tuned to your calls',
			'lede' => 'Whatever your industry, every Botphonic agent comes with the same foundations.',
			'items' => array(
				array('icon' => 'headset', 'title' => 'Answers every call', 'text' => 'Picks up on the first ring in under 300 ms, day or night, and takes several calls at once.'),
				array('icon' => 'users', 'title' => 'Hands off to your team', 'text' => 'Urgent, complex or upset callers reach a person, with the context of the call so far.'),
				array('icon' => 'briefcase', 'title' => 'Works in your tools', 'text' => 'Books into your calendar and updates HubSpot, Salesforce or Zoho CRM, or any system with a REST API.'),
				array('icon' => 'globe', 'title' => 'Speaks your callers’ language', 'text' => '50+ languages and regional accents, switching mid-call when the caller does.'),
			),
		),

		'related' => array(
			'kicker' => 'Keep exploring',
			'title' => 'Go deeper on what the agent does',
			'items' => array(
				array('icon' => 'layers', 'title' => 'Solutions', 'text' => 'AI receptionist, answering service, IVR, appointment booking and sales calls, explained by use case.', 'url' => home_url('/solution/'), 'cta' => 'See all solutions'),
				array('icon' => 'grid', 'title' => 'Products', 'text' => 'Build agents in Agent Studio, start from templates and run calls in 50+ languages.', 'url' => home_url('/product/'), 'cta' => 'See all products'),
				array('icon' => 'star', 'title' => 'Customer stories', 'text' => 'How businesses use Botphonic to answer, book and follow up on their calls.', 'url' => home_url('/success-stories/'), 'cta' => 'Read customer stories'),
			),
		),

		'faq' => array(
			'title' => 'Questions about AI voice agents by industry',
			'lede' => 'Have a call flow that doesn’t fit a box? We’ll map it with you on a short demo.',
		),
		'faqs' => array(
			array(
				'question' => 'Which industries use Botphonic AI voice agents?',
				'answer' => 'More than 500 businesses use Botphonic, including healthcare practices and hospitals, real estate teams, lenders, insurers, contact centers and agencies. Each card above links to a page that shows the calls the agent handles in that industry.',
			),
			array(
				'question' => 'Is Botphonic HIPAA compliant for healthcare calls?',
				'answer' => 'Botphonic offers HIPAA-ready workflows for healthcare. Protected health information is handled under BAA-compatible controls with audit logs, and access to recordings and transcripts is role-based. See <a href="' . esc_url(home_url('/industry/healthcare/')) . '">AI voice agents for healthcare</a> for patient call flows.',
			),
			array(
				'question' => 'Can the agent follow my industry’s scripts and rules?',
				'answer' => 'Yes. Each agent follows your knowledge base and business rules, so it answers with your own policies, hours and prices. For regulated calls it supports PCI DSS payment capture, FERPA-ready workflows for schools and TCPA-compliant controls for outbound calling.',
			),
			array(
				'question' => 'Does Botphonic work with the software my industry uses?',
				'answer' => 'Botphonic connects to HubSpot, Salesforce and Zoho CRM, books meetings through Cal.com and Calendly, and runs on your Twilio, Telnyx, Plivo or Exotel numbers. Custom actions can call any system with a REST API, so the agent can look up or update records in industry software during the call.',
			),
			array(
				'question' => 'My industry isn’t listed. Can Botphonic still help?',
				'answer' => 'Yes. Botphonic works for any business that takes calls. Start with the <a href="' . esc_url(home_url('/solution/ai-receptionist/')) . '">AI receptionist</a> or the <a href="' . esc_url(home_url('/solution/ai-answering-service/')) . '">AI answering service</a>, or book a demo and we’ll map the agent to your call flow.',
			),
			array(
				'question' => 'Which languages can the agent use with my customers?',
				'answer' => 'Botphonic speaks 50+ languages, including English variants, Spanish, French, German, Arabic, Hindi and other Indian languages, and switches language mid-call when the caller does.',
			),
		),

		'cta' => array(
			'title' => 'Don’t see your industry?',
			'text' => 'Botphonic works for any business that takes calls. Tell us how yours come in, and we’ll show you the agent on your own use case.',
			'primary' => array('label' => 'Book a demo', 'url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery'),
			'secondary' => array('label' => 'Contact us', 'url' => home_url('/contact/')),
		),
	)
);

get_header();
get_template_part('template-parts/hub-page', null, array('page' => $bphub_page));
unset($bphub_page);
get_footer();
