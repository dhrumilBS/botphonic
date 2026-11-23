<?php
/**
 * Products hub (/product/).
 *
 * WordPress uses this file for the page with the slug "product". It lists the
 * published child pages of that page: the voice AI products and the cold email
 * tools. The copy lives here; the layout is shared with the Solutions and
 * Industries hubs (template-parts/hub-page.php, inc/function-hub-pages.php).
 *
 * Facts checked 18 Nov 2026 against the site: plans and trial terms
 * (temp-pricings.php, functions.php), integrations (page-integrations.php),
 * security (template-about.php) and each product page's own copy.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bphub_page = botphonic_hub_boot(
	'product',
	array(
		'label' => 'Products',
		'noun' => array('product', 'products'),
		'list_name' => 'Botphonic products',
		'default_icon' => 'grid',

		'hero' => array(
			'kicker' => 'Botphonic products',
			'title' => 'Botphonic products: AI voice agents and cold email tools',
			'lede' => 'Botphonic is an AI voice agent platform with cold email tools alongside it. Build an agent in Agent Studio or start from a template, and it answers and places calls in 50+ languages. The email tools cover the rest of your outreach, from verified lists and warmed-up inboxes to personalized sequences and one inbox for every reply.',
			'proof' => array(
				array('<300 ms', 'response time'),
				array('50+', 'languages'),
				array('65+', 'voices'),
				array('99.99%', 'uptime'),
			),
		),

		'list' => array(
			'kicker' => 'All products',
			'title' => 'Browse every Botphonic product',
			'lede' => 'Filter by what you want to do. Each card opens the product page with its features and setup steps.',
		),

		'groups' => array(
			'voice' => 'Voice AI',
			'outreach' => 'Cold email',
			'deliverability' => 'Deliverability & data',
		),

		// Curated card content by page slug, in display order.
		'items' => array(
			'ai-phone-call' => array(
				'group' => 'voice',
				'icon' => 'phone-call',
				'line' => 'Answers and places calls 24/7, screening, booking and routing every caller.',
				'tasks' => array('Inbound and outbound', 'Call routing', 'HIPAA-ready'),
			),
			'ai-agent-studio' => array(
				'group' => 'voice',
				'icon' => 'wand',
				'line' => 'Design, test and deploy voice agents with no-code tools, plus staging and governance controls.',
				'tasks' => array('No-code builder', 'Built-in testing', 'SOC 2 ready'),
			),
			'voice-agent-templates' => array(
				'group' => 'voice',
				'icon' => 'bot',
				'line' => 'Launch a receptionist, lead qualifier, reminder or follow-up agent from a ready-made template.',
				'tasks' => array('By industry', 'Inbound and outbound', 'Free to use'),
			),
			'multilingual-voice-ai-agents' => array(
				'group' => 'voice',
				'icon' => 'globe',
				'line' => 'Talks to callers in 50+ languages and regional accents, and switches mid-call when they do.',
				'tasks' => array('50+ languages', 'Switches mid-call', 'Regional accents'),
			),
			'visual-workflow-builder' => array(
				'group' => 'voice',
				'icon' => 'flow',
				'line' => 'Maps call flows and follow-ups on a visual canvas, with branching, delays and API calls.',
				'tasks' => array('No-code canvas', 'Conditional branching', 'Live execution logs'),
			),
			'cold-email' => array(
				'group' => 'outreach',
				'icon' => 'send',
				'line' => 'Automates cold email outreach with A/B testing, ready-made templates, verified emails and team management.',
				'tasks' => array('Email automation', 'A/B testing', 'Team management'),
			),
			'ai-cold-email-sequences' => array(
				'group' => 'outreach',
				'icon' => 'layers',
				'line' => 'Builds multi-step sequences that personalize each email and scale outreach without hurting your sender reputation.',
				'tasks' => array('AI-written steps', 'Personalization', 'Reputation safeguards'),
			),
			'email-personalization' => array(
				'group' => 'outreach',
				'icon' => 'sparkles',
				'line' => 'Tailors each cold email to the prospect’s role, industry and time zone, well beyond merge tags.',
				'tasks' => array('Dynamic content', 'Spintax', 'Time zone sending'),
			),
			'automatic-follow-up-emails' => array(
				'group' => 'outreach',
				'icon' => 'repeat',
				'line' => 'Sends personalized follow-ups automatically, so no conversation goes quiet after the first email.',
				'tasks' => array('Automated follow-ups', 'CRM integration', 'Email finder'),
			),
			'unified-mail' => array(
				'group' => 'outreach',
				'icon' => 'inbox',
				'line' => 'Brings every prospect reply into one inbox and flags hot leads, so you answer them first.',
				'tasks' => array('One inbox', 'Hot-lead priority', 'Team collaboration'),
			),
			'white-label-cold-mail' => array(
				'group' => 'outreach',
				'icon' => 'tag',
				'line' => 'Lets agencies offer a fully branded cold email platform to clients without building the technology.',
				'tasks' => array('Your branding', 'Built for agencies', 'No development'),
			),
			'email-infrastructure' => array(
				'group' => 'deliverability',
				'icon' => 'server',
				'line' => 'Sets up sending domains with SPF, DKIM and DMARC configured for you, then monitors deliverability.',
				'tasks' => array('Custom domains', 'SPF, DKIM, DMARC', 'Deliverability monitoring'),
			),
			'warm-up-mail' => array(
				'group' => 'deliverability',
				'icon' => 'flame',
				'line' => 'Raises sending volume gradually and builds sender reputation before your campaigns go out.',
				'tasks' => array('Gradual sending', 'Sender reputation', 'Inbox placement'),
			),
			'email-verifier' => array(
				'group' => 'deliverability',
				'icon' => 'mail-check',
				'line' => 'Removes invalid and risky addresses before you send, one at a time or in bulk.',
				'tasks' => array('Bulk verification', 'Fewer bounces', 'Domain protection'),
			),
			'gmail-email-tracking' => array(
				'group' => 'deliverability',
				'icon' => 'eye',
				'line' => 'Shows opens and clicks in Gmail as they happen, so you follow up while interest is high.',
				'tasks' => array('Open tracking', 'Click tracking', 'Instant alerts'),
			),
			'ai-prospect-enrichment' => array(
				'group' => 'deliverability',
				'icon' => 'user-search',
				'line' => 'Adds real context to every prospect, so your cold emails go beyond first-name merge tags.',
				'tasks' => array('Prospect research', 'Lead context', 'Sharper personalization'),
			),
		),

		// Rows: goal, then card slugs (or label/url pairs) to start with and to add.
		'guide' => array(
			'kicker' => 'Where to start',
			'title' => 'Which Botphonic product do you need?',
			'lede' => 'Match your goal to a starting product. Most teams add the second one once the first is live.',
			'caption' => 'Botphonic products matched to common goals',
			'columns' => array('Your goal', 'Start with', 'Add when you need it'),
			'rows' => array(
				array('Answer every inbound call, day and night', array('ai-phone-call'), array('multilingual-voice-ai-agents')),
				array('Launch a voice agent today without building from scratch', array('voice-agent-templates'), array('ai-agent-studio')),
				array('Build a custom agent for your own call flow', array('ai-agent-studio'), array('visual-workflow-builder')),
				array('Automate what happens after each call', array('visual-workflow-builder'), array(array('label' => 'Integrations', 'url' => home_url('/integrations/')))),
				array('Run cold email campaigns', array('cold-email'), array('ai-cold-email-sequences', 'automatic-follow-up-emails')),
				array('Set up new sending domains the right way', array('email-infrastructure'), array('warm-up-mail')),
				array('Keep bounce rates low and protect your domain', array('email-verifier'), array('warm-up-mail')),
				array('Personalize every email at scale', array('email-personalization'), array('ai-prospect-enrichment')),
				array('Answer every reply while it’s hot', array('unified-mail'), array('gmail-email-tracking')),
				array('Offer cold email under your own brand', array('white-label-cold-mail'), ''),
			),
		),

		'features' => array(
			'kicker' => 'In every voice agent',
			'title' => 'Built into every Botphonic voice agent',
			'lede' => 'Whichever product you start with, the agent on the line runs on the same platform.',
			'items' => array(
				array('icon' => 'mic', 'title' => 'Natural, fast conversation', 'text' => 'Responds in under 300 ms, handles interruptions and offers 65+ voices to match your brand.'),
				array('icon' => 'plug', 'title' => 'Your numbers and tools', 'text' => 'Bring numbers from Twilio, Telnyx, Plivo or Exotel, and connect HubSpot, Salesforce, Zoho CRM, Cal.com or Calendly.'),
				array('icon' => 'file-text', 'title' => 'Every call on record', 'text' => 'Each call is saved with a recording, transcript and summary your team can review or send to the CRM.'),
				array('icon' => 'shield', 'title' => 'Ready for regulated calls', 'text' => 'HIPAA-ready workflows, PCI DSS payment capture, SOC 2 Type II ready controls and encryption in transit and at rest.'),
			),
		),

		'related' => array(
			'kicker' => 'Keep exploring',
			'title' => 'Find Botphonic by the job or by your industry',
			'items' => array(
				array('icon' => 'layers', 'title' => 'Solutions', 'text' => 'Receptionist, answering service, call center, appointment booking and sales agents, grouped by the job they do.', 'url' => home_url('/solution/'), 'cta' => 'See all solutions'),
				array('icon' => 'building', 'title' => 'Industries', 'text' => 'Healthcare, real estate, finance, insurance, education and more, with the calls each one handles.', 'url' => home_url('/industry/'), 'cta' => 'See all industries'),
				array('icon' => 'tag', 'title' => 'Pricing', 'text' => 'Plans from $12 per month billed yearly, with a 14-day free trial that includes 20 free minutes.', 'url' => home_url('/pricing/'), 'cta' => 'Compare plans'),
			),
		),

		'faq' => array(
			'title' => 'Questions about Botphonic products',
			'lede' => 'Still deciding? We’ll walk through your calls and outreach on a short demo.',
		),
		'faqs' => array(
			array(
				'question' => 'What products does Botphonic offer?',
				'answer' => 'Botphonic has two product families. The voice products build and run AI phone agents: AI Phone Call, Agent Studio, AI Voice Agent Templates, Multilingual Voice AI Agents and the Visual Workflow Builder. The cold email products run outbound email from setup to reply: Cold Email, AI Cold Email Sequences, Email Personalization, Auto Follow-ups, Unified Mail and White-Label Cold Email, with Email Infrastructure, Warm Up Mail, Email Verifier, Email Tracking for Gmail and AI Prospect Enrichment behind them.',
			),
			array(
				'question' => 'Do I need a developer to build an AI voice agent?',
				'answer' => 'No. Start from a ready-made template or build in Agent Studio’s no-code editor: add your FAQs, pick a voice and connect a number. Developers can go further with custom actions that call any REST API, webhooks and the Botphonic REST API.',
			),
			array(
				'question' => 'How do AI Phone Call, Agent Studio and the templates fit together?',
				'answer' => 'AI Voice Agent Templates give you a ready-made agent for a common job, such as a receptionist or lead qualifier. Agent Studio is where you design, test and govern your own agents. AI Phone Call covers what an agent does on the line: answering, screening, routing and placing calls, 24/7.',
			),
			array(
				'question' => 'Which tools does Botphonic connect to?',
				'answer' => 'HubSpot connects natively, Salesforce and Zoho CRM connect through their REST APIs, and Cal.com and Calendly let the agent book meetings during the call. Calls run on your own Twilio, Telnyx, Plivo or Exotel numbers, or on a number you buy in Botphonic. Zapier and custom actions reach most other tools. See <a href="' . esc_url(home_url('/integrations/')) . '">all integrations</a>.',
			),
			array(
				'question' => 'How much does Botphonic cost?',
				'answer' => 'Voice AI plans start at $12 per month on Starter, billed yearly, with 100 talk minutes and 5 concurrent calls. Every plan starts with a 14-day free trial that includes 20 free minutes. See <a href="' . esc_url(home_url('/pricing/')) . '">pricing</a> for Pro, Enterprise and pay-as-you-go options.',
			),
			array(
				'question' => 'Is Botphonic secure enough for regulated calls?',
				'answer' => 'Botphonic is built for regulated calls, with HIPAA-ready workflows, PCI DSS payment capture, GDPR-aligned data handling and SOC 2 Type II ready controls. Data is encrypted in transit with TLS 1.2 or 1.3 and at rest with AES-256. See the <a href="' . esc_url(home_url('/security/')) . '">security overview</a>.',
			),
		),

		'cta' => array(
			'title' => 'Build your first AI voice agent today',
			'text' => 'Start from a template or a blank agent, connect a number and hear it answer within minutes. Every plan starts with a 14-day free trial.',
			'primary' => array('label' => 'Start 14-day free trial', 'url' => defined('BOTPHONIC_REGISTER_URL') ? BOTPHONIC_REGISTER_URL : 'https://app.botphonic.ai/register/'),
			'secondary' => array('label' => 'Book a demo', 'url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery'),
		),
	)
);

get_header();
get_template_part('template-parts/hub-page', null, array('page' => $bphub_page));
unset($bphub_page);
get_footer();
