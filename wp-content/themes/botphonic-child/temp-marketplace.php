<?php
/* Template Name: Marketplace */
?>
<?php get_header(); ?>

<section class="marketplace-hero-modern py-5">
	<div class="container">
		<div class="col-lg-8 text-center mx-auto">
			<div class="badge-pill mb-4 d-inline-flex gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" height="20" width="20">
					<path d="M14.5 5.625h-1.125V5A4.38 4.38 0 0 0 9 .625H7A4.38 4.38 0 0 0 2.625 5v.625H1.5C.742 5.625.125 6.242.125 7v3c0 .758.617 1.375 1.375 1.375h1.163c.19 1.683 1.604 3 3.337 3h.184c.164.575.689 1 1.316 1h1c.758 0 1.375-.617 1.375-1.375s-.617-1.375-1.375-1.375h-1c-.627 0-1.152.425-1.316 1H6A2.628 2.628 0 0 1 3.375 11V5A3.629 3.629 0 0 1 7 1.375h2A3.629 3.629 0 0 1 12.625 5v6c0 .207.168.375.375.375h1.5c.758 0 1.375-.617 1.375-1.375V7c0-.758-.617-1.375-1.375-1.375zm-7 7.75h1a.626.626 0 0 1 0 1.25h-1a.626.626 0 0 1 0-1.25zM.875 10V7c0-.345.28-.625.625-.625h1.125v4.25H1.5A.626.626 0 0 1 .875 10zm14.25 0c0 .345-.28.625-.625.625h-1.125v-4.25H14.5c.345 0 .625.28.625.625z" />
					<path d="M7.5 5.125H7a.376.376 0 0 0-.358.263l-1.25 4a.375.375 0 0 0 .716.224l.23-.737h1.823l.23.737a.376.376 0 0 0 .717-.224l-1.25-4a.376.376 0 0 0-.358-.263zm-.927 3 .677-2.168.677 2.168zM10.25 5.125a.375.375 0 0 0-.375.375v4a.375.375 0 0 0 .75 0v-4a.375.375 0 0 0-.375-.375z" />
				</svg>
				<span>Human-Like Conversations</span>
			</div>
			<div class="heading">
				<h1 class=" mb-3">AI Call Agent<span class="gradient-text"> Marketplace</span></h1>
				<p class="mb-4 mx-auto">Pre-built AI call agents that save you hours of setup, fully customizable, and ready to deploy within seconds.</p>
				<a href="https://app.botphonic.ai/register" class="d-inline-block theme-btn">Get Started Free</a>
			</div>

			<div class="mt-3 d-flex flex-wrap gap-4 justify-content-center">
				<div>
					<h3>50+</h3>
					<p>Ready-to-Use Agents</p>
				</div>
				<div>
					<h3>50K+</h3>
					<p>Active Deployments</p>
				</div>
				<div>
					<h3>99.9%</h3>
					<p>Uptime Guarantee</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="integrations-section py-5">
	<div class="container">
		<div class="text-center mb-3">
			<h2 class="section-title">What Makes Botphonic Marketplace Different</h2>
		</div>

		<div class="row">
			<?php
			$integrations = [
				[
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7B5CFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers"><path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"></path><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"></path><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"></path></svg>',
					'title' => 'CELL Framework',
					'description' => 'Built on Construct, Evaluate, Launch, and Learn — a structured approach that reduces the risk of failure once an agent goes live'
				],
				[
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7B5CFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mouse-pointer"><path d="M12.586 12.586 19 19"></path><path d="M3.688 3.037a.497.497 0 0 0-.651.651l6.5 15.999a.501.501 0 0 0 .947-.062l1.569-6.083a2 2 0 0 1 1.448-1.479l6.124-1.579a.5.5 0 0 0 .063-.947z"></path></svg>',
					'title' => 'Drag-and-Drop Interface',
					'description' => 'Customize conversation flows and modify each agent\'s structure to match your exact needs'
				],
				[
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7B5CFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-repeat"><path d="m17 2 4 4-4 4"></path><path d="M3 11v-1a4 4 0 0 1 4-4h14"></path><path d="m7 22-4-4 4-4"></path><path d="M21 13v1a4 4 0 0 1-4 4H3"></path></svg>',
					'title' => 'Omnichannel Support',
					'description' => 'Agent templates work seamlessly across channels, including WhatsApp, Facebook, and SMS'
				]
			];

			foreach ($integrations as $integration): ?>
			<div class="col-md-4 col-sm-6 mb-4">
				<div class="integration-card">
					<div class="icon-wrapper">
						<?php echo $integration['icon']; ?>
					</div>
					<h5><?php echo esc_html($integration['title']); ?></h5>
					<p><?php echo esc_html($integration['description']); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="industry-agents-section py-5">
	<?php
	$industry_cards = [
		['icon' => '🏥', 'title' => 'Healthcare', 'desc' => 'AI solutions for medical workflows'],
		['icon' => '🏠', 'title' => 'Real Estate', 'desc' => 'Assist clients with property queries'],
		['icon' => '🏢', 'title' => 'Agency', 'desc' => 'Automate agency processes'],
		['icon' => '☀️', 'title' => 'Solar', 'desc' => 'Innovate solar business operations'],
		['icon' => '🚗', 'title' => 'Car Dealerships', 'desc' => 'Transform car dealership services'],
		['icon' => '💼', 'title' => 'Recruitment', 'desc' => 'Automate recruiting processes'],
		['icon' => '💳', 'title' => 'Financial Services', 'desc' => 'Reshape financial industry operations'],
		['icon' => '🎓', 'title' => 'Education', 'desc' => 'Streamline educational systems'],
		['icon' => '📞', 'title' => 'BPO', 'desc' => 'Automate BPO services'],
		['icon' => '🛡️', 'title' => 'Insurance', 'desc' => 'AI for insurance claims'],
		['icon' => '✈️', 'title' => 'Travel & Hospitality', 'desc' => 'AI for travel services'],
		['icon' => '📦', 'title' => 'Logistics', 'desc' => 'Automate logistics operations'],
		['icon' => '🏡', 'title' => 'Home Services', 'desc' => 'Revolutionize home services']
	];
	$industry_rows = array_chunk($industry_cards, ceil(count($industry_cards) / 2));
	?>
	<div class="container">
		<div class="header-block">
			<h2>AI Voice Agents for Every Industry</h2>
			<p>Discover powerful AI voice assistants crafted to streamline workflows, boost customer engagement, and drive growth across diverse industries.</p>
		</div>
	</div>
	<div class="container-fluid gx-0">
		<div class="industry-slider">
			<?php foreach ($industry_rows as $i => $industry_row):
			$dir = ($i % 2 === 0) ? 'row-ltr' : 'row-rtl';
			$industry = array_merge(...array_fill(0, 4, $industry_row));
			?>
			<div class="<?= esc_attr($dir); ?> slide-track industry-row">
				<?php foreach ($industry as $card): ?>
				<div class="slide info-card">
					<div class="icon-wrap"><?= htmlspecialchars($card['icon']); ?></div>
					<div class="icon-content">
						<h5><?= htmlspecialchars($card['title']); ?></h5>
						<p><?= htmlspecialchars($card['desc']); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="ai-voice-agents py-5">
	<?php
	$agents = [
		[
			'featured' => true,
			'image' => 'https://botphonic.ai/wp-content/uploads/2025/04/This-is-Myra.webp',
			'alt' => 'Customer Support Pro',
			'category' => 'Customer Support',
			'rating' => '4.9',
			'title' => 'Customer Support Pro',
			'description' => 'Professional customer service assistant with advanced problem-solving capabilities and multilingual support. Handles inquiries, resolves issues, and provides exceptional customer experience.',
			'tags' => ['multilingual', '24/7'],
			'downloads' => '1,247',
			'status' => 'Active',
			'price' => 'Free',
			'price_note' => '',
			'button_text' => 'Get Agent'
		],
		[
			'featured' => false,
			'image' => 'https://botphonic.ai/wp-content/uploads/2025/05/AI-Jessica.webp',
			'alt' => 'Sales Conversion Expert',
			'category' => 'Sales Assistant',
			'rating' => '4.8',
			'title' => 'Sales Conversion Expert',
			'description' => 'Expert sales assistant specializing in lead qualification, product demonstrations, and closing deals with intelligent conversation flows and objection handling.',
			'tags' => ['lead-qualification', 'conversion'],
			'downloads' => '892',
			'status' => 'Active',
			'price' => '$29',
			'price_note' => 'one-time',
			'button_text' => 'Get Agent'
		],
		[
			'featured' => true,
			'image' => 'https://botphonic.ai/wp-content/uploads/2025/04/This-is-Myra.webp',
			'alt' => 'Healthcare Receptionist',
			'category' => 'Healthcare',
			'rating' => '4.7',
			'title' => 'Healthcare Receptionist',
			'description' => 'Medical appointment scheduling assistant built for HIPAA-ready workflows, with insurance verification and patient intake management for healthcare practices.',
			'tags' => ['HIPAA-ready', 'appointments'],
			'downloads' => '654',
			'status' => 'Active',
			'price' => '$49',
			'price_note' => 'one-time',
			'button_text' => 'Get Agent'
		]
	];
	?>
	<div class="container">
		<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
			<div>
				<h2 class="section-title mb-1">AI Voice Agents</h2>
				<p class="section-subtitle"><?php echo count($agents); ?> agents available</p>
			</div>
		</div>
		<div class="swiper agent-slider">
			<div class="swiper-wrapper">
				<?php foreach ($agents as $agent): ?>
				<div class="swiper-slide">
					<div class="agent-card">
						<?php if (!empty($agent['featured'])): ?>
						<div class="featured-badge">🌟 Featured</div>
						<?php endif; ?>
						<div class="agent-image-wrapper">
							<img src="<?php echo htmlspecialchars($agent['image']); ?>"
								 alt="<?php echo htmlspecialchars($agent['alt']); ?>"
								 class="img-fluid agent-image">
						</div>
						<div class="agent-content">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="category-badge"><?php echo htmlspecialchars($agent['category']); ?></span>
								<div class="d-flex fw-semibold align-items-center gap-1 lh-base">
									⭐ <?php echo htmlspecialchars($agent['rating']); ?>
								</div>
							</div>
							<h3 class="agent-title"><?php echo esc_html($agent['title']); ?></h3>
							<p class="agent-description"><?php echo htmlspecialchars($agent['description']); ?></p>
							<div class="d-flex tag-list mb-3 gap-2 flex-wrap">
								<img src="<?php echo esc_url(get_theme_file_uri('assets/img/tag.svg')); ?>" alt="Tag Icon" width="20" height="20" />
								<?php foreach ($agent['tags'] as $tag): ?>
								<span class="tag-badge mb-2">#<?php echo htmlspecialchars($tag); ?></span>
								<?php endforeach; ?>
							</div>
							<div class="details-row mb-3 d-flex justify-content-between">
								<div class="d-flex gap-2">
									<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20" />
									<?php echo htmlspecialchars($agent['downloads']); ?>
								</div>
								<div>
									<?php echo htmlspecialchars($agent['status']); ?>
								</div>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<?php if (strtolower($agent['price']) === 'free'): ?>
								<span class="price text-primary fw-bold fs-5">
									<?php echo htmlspecialchars($agent['price']); ?>
								</span>
								<?php else: ?>
								<div>
									<span class="price text-primary fw-bold fs-5">
										<?php echo htmlspecialchars($agent['price']); ?>
									</span>
									<?php if (!empty($agent['price_note'])): ?>
									<small class="d-block text-muted">
										<?php echo htmlspecialchars($agent['price_note']); ?>
									</small>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<a href="https://app.botphonic.ai/register/" class="marketplace-btn">
									<?php echo htmlspecialchars($agent['button_text']); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-pagination"></div>

		</div>
	</div>
</section>

<section class="marketplace-templates container py-5">
	<div class="text-center">
		<h2 class="fw-bold">AI Assistant Templates</h2>
		<p class="section-desc">Browse ready-to-use AI voice assistant templates tailored for multiple industries, from sales and customer support to healthcare and education. Save time, automate workflows, and launch faster with pre-built templates.</p>
	</div>
	<?php
	$templates = [
		[
			'industry' => 'Healthcare',
			'price' => 'Free',
			'title' => 'Receptionist',
			'description' => 'Meet Jessica, an AI assistant for your healthcare practice, dedicated to streamlining appointment scheduling and improving patient access to care.',
			'hashtags' => ['Receptionist', 'Real-Time Booking', 'Inbound'],
			'likes' => 30,
			'button_text' => 'Use Template'
		],
		[
			'industry' => 'Education',
			'price' => 'Free',
			'title' => 'College Receptionist',
			'description' => 'Meet Brian, a dedicated AI receptionist for your college or school. A friendly, knowledgeable virtual assistant, Brian makes sure every caller gets routed to the help they need.',
			'hashtags' => ['Receptionist', 'Inbound'],
			'likes' => 45,
			'button_text' => 'Use Template'
		],
		[
			'industry' => 'Mortgage',
			'price' => 'Free',
			'title' => 'Lead Qualifier',
			'description' => 'Meet Emma, an AI representative for your mortgage company. Her primary role is reconnecting with potential clients who\'ve previously shown interest in a mortgage loan.',
			'hashtags' => ['Lead Qualification', 'Pre-screening', 'Outbound'],
			'likes' => 18,
			'button_text' => 'Use Template'
		],
		[
			'industry' => 'Hospitality',
			'price' => 'Free',
			'title' => 'Demand Generator',
			'description' => 'Meet Riley, an AI representative for your events, dedicated to maximizing attendance and engagement. Her primary role is contacting registered attendees with key event reminders.',
			'hashtags' => ['lead-qualification', 'pre-screening'],
			'likes' => 30,
			'button_text' => 'Use Template'
		],
		[
			'industry' => 'Recruitment',
			'price' => 'Free',
			'title' => 'Interview Scheduler',
			'description' => 'Say hello to Beatrice, a virtual assistant for managing interview timelines. Beatrice handles the back-and-forth of aligning availability between candidates and hiring managers.',
			'hashtags' => ['Real-Time Booking', 'Pre-screening', 'Outbound'],
			'likes' => 45,
			'button_text' => 'Use Template'
		],
		[
			'industry' => 'Real Estate',
			'price' => 'Free',
			'title' => 'Sales Appointment Scheduler',
			'description' => 'Introducing Allen, a virtual assistant committed to helping homeowners sell their properties. His main role is starting conversations, offering tailored advice, and arranging valuation appointments that fit each seller\'s needs.',
			'hashtags' => ['Real-Time Booking', 'Outbound'],
			'likes' => 18,
			'button_text' => 'Use Template'
		]
	];
	?>
	<div id="templateGrid" class="row g-3">
		<?php foreach ($templates as $template): ?>
		<div class="col-12 col-sm-6 col-lg-4 template-card-wrapper" data-industry="<?php echo htmlspecialchars($template['industry']); ?>">
			<div class="card template-card">
				<div class="card-body">
					<span class="badge industry-badge d-inline-block mb-2">
						<?php echo htmlspecialchars($template['industry']); ?>
					</span>
					<h5 class="fw-semibold"><?php echo htmlspecialchars($template['title']); ?></h5>
					<p class="text-muted small mb-3">
						<?php echo htmlspecialchars($template['description']); ?>
					</p>
					<div class="d-flex mb-3 gap-2 flex-wrap">
						<img src="<?php echo esc_url(get_theme_file_uri('assets/img/tag.svg')); ?>" alt="Tag Icon" width="20" height="20" />
						<?php foreach ($template['hashtags'] as $hashtag): ?>
						<span class="hashtag">
							<?php echo htmlspecialchars($hashtag); ?>
						</span>
						<?php endforeach; ?>
					</div>
					<div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
						<div class="d-flex gap-2 text-muted small">
							<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20" />
							<?php echo (int) $template['likes']; ?>
						</div>
						<span class="badge bg-success">
							<?php echo htmlspecialchars($template['price']); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="text-center mt-4">
		<a href="https://botphonic.ai/ai-voice-agents/" class="theme-btn button">Explore All Templates →</a>
	</div>
</section>
<?php echo do_shortcode('[usa_cta title="Plug Into a Growing Marketplace of Sales-Ready Integrations" subtitle="Discover verified tools, data providers, and workflows that connect seamlessly with Botphonic so you can launch smarter campaigns, faster."]'); ?>

<?php get_footer(); ?>