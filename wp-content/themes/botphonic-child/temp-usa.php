<?php
/**
 * Template Name: USA Landing
 * Template Post Type: page
 */
defined('ABSPATH') || exit;

get_header('usa');
?>

<section class="hero-section section-padded">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="text-center">
					<div class="heading text-center">
						<div class="d-inline-block badge-us mb-3"><small> Built for USA Businesses</small> </div>
						<h1 class="hero-title title"> Scale Every Call In the USA With <span class="custom-gradient-text">AI Call Automation</span> </h1>
						<p class="hero-subtitle"> Botphonic is a TCPA-compliant, HIPAA-ready AI call automation platform for USA businesses. Booking meetings, qualifying leads, or presenting top-notch customer support, AI call automation helps you with all, while scaling and streamlining the processes. </p>
						<p class="mt-3 text-muted"> <strong> Natural AI Voice </strong> • <strong> 50+ Languages </strong> • <strong> 24/7 Available </strong> </p>
					</div>

					<div class="herobadgeLists d-flex mt-3">
						<img decoding="async" src="/wp-content/uploads/2025/12/HIPAA.svg" width="110" height="110">
						<img decoding="async" src="/wp-content/uploads/2025/12/FCC.svg" width="110" height="110">
						<img decoding="async" src="/wp-content/uploads/2025/12/TCPA.svg" width="110" height="110">
					</div>

					<div class="mt-3">
						<?= do_shortcode('[contact-form-7 id="fdc94f2" title="Sign UP Form"]'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="why-section section-padded">
	<div class="container">
		<div class="text-center mb-5">
			<h2 class="section-title"> Why U.S. Businesses Need AI Call Automation </h2>
			<p class="section-subtitle"> AI call automation is changing how U.S. businesses are engaging with customers, with AI-powered phone systems, companies can offer 24/7 customer support in 50+ languages improving overall customer satisfaction. Businesses need AI call automation for: </p>
		</div>
		<?php

		$features = [
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="languages" class="lucide lucide-languages"><path d="m5 8 6 6"></path><path d="m4 14 6-6 2-3"></path><path d="M2 5h12"></path><path d="M7 2h1"></path><path d="m22 22-5-10-5 10"></path><path d="M14 18h6"></path></svg>',
				"title" => "Multilingual Support",
				"description" => "AI call assistants are interacting in more than 50 languages, allowing companies to serve a global client base"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user-check" class="lucide lucide-user-check"><path d="m16 11 2 2 4-4"></path><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>',
				"title" => "Boost Lead Qualification",
				"description" => "AI agents can qualify leads effectively by offering 24/7 availability and asking targeted questions, increasing conversion rates"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user-check" class="lucide lucide-user-check"><path d="m16 11 2 2 4-4"></path><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>',
				"title" => "Reduce Costs with AI Automation",
				"description" => "Automating routine tasks reduces the need for large customer service and results in cost-efficient solutions"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="shield-check" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg>',
				"title" => "Secure & Compliant",
				"description" => "AI call automation ensures compliance with data protection laws such as HIPAA and TCPA, following strict security protocols"
			]
		];
		?>

		<div class="row g-4">
			<?php foreach ($features as $feature): ?>
			<div class="col-md-6 col-lg-3">
				<div class="feature-card h-100">
					<div class="d-flex align-items-center mb-1">
						<div class="icon me-2"> <?= $feature['icon']; ?></div>
						<div class="fw-semibold mb-0 h5 title"><?= $feature['title']; ?></div>
					</div>
					<p><?= $feature['description']; ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="stats-box mt-5 text-center">
			<p>As per research reported there are about <strong>95%</strong> companies using AI-powered automation who have reported higher productivity </p>
			<p>Also, about <strong>80%</strong> of businesses have stated AI automation helps them scale faster. </p>
		</div>
	</div>
</section>

<section class="compliance-section section-padded">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<h2 class="section-title">USA Compliance: HIPAA, TCPA, FCC</h2>
				<p> Botphonic understanding that compliance with key U.S. regulation is essential for businesses optimizing AI call automation. Our platform is designed to help businesses grow while adhering to the U.S. laws that protect both customers and businesses via data storage in U.S. servers. </p>

				<ul class="compliance-list">
					<li><strong>HIPAA:</strong> Botphonic ensures secure communication and encrypted data for businesses in healthcare via HIPAA-grade encryption</li>
					<li><strong>TCPA:</strong> Helps businesses adhere to telephone consumer protection act by TCPA auto-consent detection</li>
					<li><strong>FCC:</strong> AI-driven call automation at Botphonic complies with FCC robocall mitigation rules, ensuring accurate caller ID, and proper opt-out mechanisms</li>
					<li><strong>Secure Data Handling:</strong> Integrating end-to-end encryption ensures robust data protection</li>
				</ul>
			</div>

			<div class="col-lg-6">
				<div class="compliance-box">
					<img src="https://botphonic.ai/wp-content/uploads/2025/12/U.S.-Compliance_-HIPAA-TCPA-FCC.webp" alt="U.S. Compliance: HIPAA, TCPA, FCC">
				</div>
			</div>
		</div>
	</div>
</section>

<section class="industry-section section-padded">
	<div class="container">
		<div class="text-center mb-5">
			<h2 class="section-title">Industry-Specific Benefits</h2>
		</div>
		<?php
		$industries = [
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="heart-pulse" class="lucide lucide-heart-pulse"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path><path d="M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"></path></svg>',
				"title" => "Healthcare",
				"description" => "HIPAA-Compliant Patient Support Simplify appointment scheduling, send reminders, and engage with patients effortlessly"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="banknote" class="lucide lucide-banknote"><rect width="20" height="12" x="2" y="6" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>',
				"title" => "Financial Services",
				"description" => "Provide secure customer support, handle account inquiries, and process transactions 24/7 with automation"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="building-2" class="lucide lucide-building-2"><path d="M10 12h4"></path><path d="M10 8h4"></path><path d="M14 21v-3a2 2 0 0 0-4 0v3"></path><path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path><path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path></svg>',
				"title" => "Real Estate",
				"description" => "Automate lead qualifications, schedule property viewings, and answer inquiries instantly, boosting conversion rates"
			],
			[
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="briefcase" class="lucide lucide-briefcase"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>',
				"title" => "SMBs",
				"description" => "Save 20+ hours every week on manual calls, while delivering a seamless experience for your customers 24/7"
			]
		];
		?>

		<div class="row g-4">
			<?php foreach ($industries as $industry): ?>
			<div class="col-md-6 col-lg-3">
				<div class="industry-card h-100">
					<div class="d-flex align-items-center mb-1">
						<div class="icon me-2"> <?= $industry['icon']; ?></div>
						<div class="fw-semibold mb-0 h5 title"><?= $industry['title']; ?></div>
					</div>
					<p><?= $industry['description']; ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$bfSteps = [
	[
		"title" => "Customer Calls Business Number",
		"description" => "A customer calls your business number, just as they do with any traditional business.",
		"image" => "https://botphonic.ai/wp-content/uploads/2025/12/Customer-Calls-Business-Number-1.webp"
	],
	[
		"title" => "AI Agent Answers Using Natural Voice",
		"description" => "The Botphonic AI agent picks up the call, and greets customers using a natural-sounding voice to engage with them.",
		"image" => "https://botphonic.ai/wp-content/uploads/2025/12/AI-Agent-Answers-Using-Natural-Voice.webp"
	],
	[
		"title" => "Understands Intent",
		"description" => "The AI listens to the customer's request and understands intent such as appointment scheduling, inquiry, or payment.",
		"image" => "https://botphonic.ai/wp-content/uploads/2025/12/Understands-Intent.webp"
	],
	[
		"title" => "Takes Action",
		"description" => "Based on the detected intent, the AI books appointments, transfers calls, processes payments, or records details.",
		"image" => "https://botphonic.ai/wp-content/uploads/2025/12/Takes-Action.webp"
	],
	[
		"title" => "Syncs with CRM, EHR, Ticketing Tools",
		"description" => "After taking action, the system syncs with CRM, EHR, or ticketing tools for follow-up.",
		"image" => "https://botphonic.ai/wp-content/uploads/2025/12/Syncs-with-CRM-EHR-Ticketing-Tools.webp"
	]
];
?>

<section class="steps-section section-padded">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="heading text-center">
					<h2 class="title"> How Botphonic Works </h2>
					<p> Botphonic offers simple and efficient AI-powered call automation by following </p>
				</div>
			</div>
		</div>

		<div class="bf-row">
			<div class="bf-left">
				<?php foreach ($bfSteps as $index => $step): ?>
				<div class="bf-step <?= $index === 0 ? 'active' : '' ?>" data-img="img<?= $index + 1 ?>">
					<div class="bf-mobile-img">
						<?php if ($index === 0): ?>
						<img src="<?= $step['image']; ?>" alt="<?= $step['title']; ?>">
						<?php endif; ?>
					</div>
					<h2 class="h4 title"><?= $step['title']; ?></h2>
					<p><?= $step['description']; ?></p>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="bf-right">
				<img id="bf-preview" src="<?= $bfSteps[0]['image']; ?>" class="show" alt="Preview">
			</div>
		</div>
	</div>
</section>

<section class="section-padded">
	<?php echo do_shortcode('[success_story]'); ?>
</section>

<section>
	<?php echo do_shortcode('[usa_integrations]'); ?>
</section>

<section>
	<?php echo do_shortcode('[botphonic_faq]'); ?>
</section>

<section>
	<?php echo do_shortcode('[usa_cta title="Scale Every Call in the U.S. With AI" subtitle="Start automating inbound and outbound calls within days."]'); ?>
</section>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const steps = document.querySelectorAll('.bf-step');
		const preview = document.getElementById('bf-preview');
		const images = {
			img1: 'https://botphonic.ai/wp-content/uploads/2025/12/Customer-Calls-Business-Number-1.webp',
			img2: 'https://botphonic.ai/wp-content/uploads/2025/12/AI-Agent-Answers-Using-Natural-Voice.webp',
			img3: 'https://botphonic.ai/wp-content/uploads/2025/12/Understands-Intent.webp',
			img4: 'https://botphonic.ai/wp-content/uploads/2025/12/Takes-Action.webp',
			img5: 'https://botphonic.ai/wp-content/uploads/2025/12/Syncs-with-CRM-EHR-Ticketing-Tools.webp'
		};

		steps.forEach(step => {
			step.addEventListener('click', () => {
				steps.forEach(s => {
					s.classList.remove('active');
					s.querySelector('.bf-mobile-img').innerHTML = '';
				});
				step.classList.add('active');
				const img = images[step.dataset.img];

				if (window.innerWidth <= 768) {
					step.querySelector('.bf-mobile-img').innerHTML = `<img src="${img}">`;
				} else {
					preview.classList.remove('show');
					setTimeout(() => {
						preview.src = img;
						preview.classList.add('show');
					}, 150);
				}
			});
		});

		new Swiper(".successStorySwiper", {
			loop: true,
			spaceBetween: 30,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			breakpoints: {
				0: { slidesPerView: 1 },
				768: { slidesPerView: 2 },
				992: { slidesPerView: 3 }
			}
		});
	});
</script>
<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "HowTo",
		"name": "How Botphonic Works",
		"description": "Botphonic offers simple and efficient AI-powered call automation for businesses through AI voice agents that answer, understand, and act on customer calls.",
		"totalTime": "PT2M",
		"tool": [{
			"@type": "HowToTool",
			"name": "Botphonic AI Voice Agent"
		}],
		"step": [{
				"@type": "HowToStep",
				"position": 1,
				"name": "Customer Calls Business Number",
				"text": "A customer calls your business number just like a traditional business call."
			},
			{
				"@type": "HowToStep",
				"position": 2,
				"name": "AI Agent Answers Using Natural Voice",
				"text": "The Botphonic AI agent answers the call using a natural-sounding voice to engage customers."
			},
			{
				"@type": "HowToStep",
				"position": 3,
				"name": "Understands Customer Intent",
				"text": "The AI understands appointment requests, inquiries, or payments."
			},
			{
				"@type": "HowToStep",
				"position": 4,
				"name": "AI Takes Action",
				"text": "The AI books appointments, transfers calls, processes payments, or records details."
			},
			{
				"@type": "HowToStep",
				"position": 5,
				"name": "Syncs with CRM, EHR, and Ticketing Tools",
				"text": "All interactions sync with your CRM, EHR, or ticketing system automatically."
			}
		]
	}
</script>
<?php get_footer(); ?>