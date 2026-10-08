<?php
/* Template Name: Unified Mail */
?>
<?php get_header(); ?>

<section class="hero-section section-padded">
	<!-- Background blobs -->
	<div class="hero-bg">
		<div class="blob blob-1"></div>
		<div class="blob blob-2"></div>
		<div class="blob blob-3"></div>
	</div>

	<div class="container position-relative py-5 hero-content">
		<div class="row justify-content-center text-center">
			<div class="col-lg-10">
				<div class="hero-badge d-inline-flex align-items-center gap-2 mb-4">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
					</svg>
					<span>Manage All Conversations in One Place</span>
				</div>
				<h1 class="display-2 fw-bold mb-4"> One Unified Inbox for All <span class="text-gradient">Your Prospect Conversations</span> </h1>
				<p class="lead mb-5"> Botphonic’s unified inbox helps you manage replies, messages, and all the prospect conversations from a single and centralized inbox, no more switching platforms </p>
				<a href="#" class="btn btn-secondary d-inline-flex align-items-center gap-2">
					<span>Get Started Free</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
					<path d="M5 12h14"></path>
					<path d="m12 5 7 7-7 7"></path>
					</svg>
				</a>

				<div class="hero-icons d-flex justify-content-center gap-5 mt-5">
					<div class="icon-box accent-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square">
							<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
						</svg>
					</div>
					<div class="icon-box accent-icon icon-box-lg">
						<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox w-10 h-10 md:w-12 md:h-12 text-primary-foreground">
							<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
							<path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
						</svg>
					</div>
					<div class="icon-box accent-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap w-8 h-8 md:w-10 md:h-10 text-primary-foreground">
							<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
						</svg>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
$features = [
	[
		'title' => 'Centralized Communication',
		'text' => 'All your emails, chats, and social messages in one unified dashboard. No more tab-switching chaos',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>'
	],
	[
		'title' => 'Faster Response Times',
		'text' => 'AI-powered suggestions and quick templates help you respond to customers in seconds, not hours',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path></svg>'
	],
	[
		'title' => 'Intelligent Organization',
		'text' => 'Smart labels, priority sorting, and automated workflows keep your inbox clean and manageable',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-kanban"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path><path d="M8 10v4"></path><path d="M12 10v2"></path><path d="M16 10v6"></path></svg>'
	],
	[
		'title' => 'Team Collaboration',
		'text' => 'Assign conversations, leave internal notes, and collaborate seamlessly with your team',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>'
	],
];
?>
<section class="features-section section-padded">
	<div class="container">

		<!-- Section Header -->
		<div class="text-center mx-auto mb-5 features-header">
			<span class="section-eyebrow opacity-95">Features</span>
			<h2 class="display-5 fw-bold mb-4"> Designed to Help You <span class="secondary">Close Faster</span></h2>
			<p class="lead text-muted"> Unified inbox is built for teams that rely heavily on email to drive their business growth. From outbound outreach to inbound lead responses, this tool helps sales and lead generation teams to manage all prospect conversations in one centralized inbox </p>
		</div>

		<!-- Features Grid -->
		<div class="row g-4 g-lg-5">
			<?php foreach($features as $feature): ?>
			<div class="col-md-6">
				<div class="feature-card">
					<div class="feature-icon accent-icon"><?= $feature['icon'] ?></div>
					<h3 class="h5 fw-semibold mb-3"><?= $feature['title'] ?></h3>
					<p class="text-muted"><?= $feature['text'] ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section id="integrations" class="integrations-section position-relative overflow-hidden section-padded" style="background-color: #000; ">
	<!-- Background blur -->
	<div class="integrations-bg"></div>

	<div class="container position-relative">
		<div class="section-header mx-auto col-lg-8 text-center mb-3 mb-lg-5 ">
			<span class="section-eyebrow">Integrations</span>
			<h2 class="display-5 fw-bold text-white mb-4"> Works with your <span class="text-gradient">favourite tools</span></h2>
			<p class="lead text-white opacity-85 mb-4 "> Connect all your email accounts, CRMs, and communication platforms in one place. Seamlessly sync data with 50+ integrations and keep your workflow unified without switching between apps </p>
		</div>

		<ul class="list-unstyled integrations-list w-md-75 mx-auto">
			<li class="d-flex justify-content-center gap-3">
				<span class="list-icon accent-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell">
					<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
					<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
					</svg>
				</span>
				<span class="text-muted">Push replies into your CRM with full thread context</span>
			</li>
			<li class="d-flex justify-content-center gap-3">
				<span class="list-icon accent-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell">
					<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
					<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
					</svg></span>
				<span class="text-muted">Get real-time Slack alerts when leads respond</span>
			</li>
			<li class="d-flex justify-content-center gap-3">
				<span class="list-icon accent-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell">
					<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
					<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
					</svg></span>
				<span class="text-muted">Sync data with Google Sheets automatically</span>
			</li>
			<li class="d-flex justify-content-center gap-3">
				<span class="list-icon accent-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell">
					<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
					<path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
					</svg></span>
				<span class="text-muted">Connect calendars for seamless meeting scheduling</span>
			</li>
		</ul>
		<?php echo do_shortcode('[botphonic_integration_slider]'); ?>

	</div>
</section>

<?php
// Inbox filters
$inboxFilters = [
	['label' => 'Interested', 'count' => 24, 'class' => 'bg-success'],
	['label' => 'Not Interested', 'count' => 8, 'class' => 'bg-danger'],
	['label' => 'Meeting', 'count' => 12, 'class' => 'bg-primary'],
	['label' => 'Auto', 'count' => 15, 'class' => 'bg-warning'],
];

// Inbox messages
$inboxMessages = [
	['avatar' => 'S', 'name' => 'Sarah Johnson', 'badge' => 'Interested', 'badgeClass' => 'bg-success', 'subject' => 'Re: Partnership Opportunity', 'time' => '2m ago', 'active' => true],
	['avatar' => 'M', 'name' => 'Mike Chen', 'badge' => 'Meeting Booked', 'badgeClass' => 'bg-primary', 'subject' => 'Meeting confirmed for Thursday', 'time' => '15m ago', 'active' => false],
	['avatar' => 'A', 'name' => 'Alex Rivera', 'badge' => 'Automatic Response', 'badgeClass' => 'bg-warning', 'subject' => 'Out of office until Jan 30', 'time' => '1h ago', 'active' => false],
	['avatar' => 'E', 'name' => 'Emma Wilson', 'badge' => 'Interested', 'badgeClass' => 'bg-success', 'subject' => 'Re: Product Demo Request', 'time' => '2h ago', 'active' => false],
];

// Feature list
$features = [
	[
		'title' => 'AI-Driven Inbox Categorization',
		'text' => 'With our advanced AI categorization engine, it can easily scan message content in real time while automatically labelling conversations based on intent',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg>'
	],
	[
		'title' => 'Smart Inbox Filters & Custom Views',
		'text' => 'Create a powerful inbox view by using AI-powered smart filters. Segment conversations by intent type, campaign or sequence, sender or domain, and more',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>'
	],
	[
		'title' => 'Trigger-Based Workflow Automation',
		'text' => 'Start automating your response workflows with intent-based triggers. When specific keywords or reply types are detected, Botphonic can work automatically',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path></svg>'
	],
	[
		'title' => 'Bulk Inbox Actions at Scale',
		'text' => 'Start managing thousands of conversations in seconds and handle high-volume inboxes with bulk inbox actions',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle></svg>'
	]
];
?>
<section class="ai-filters-section position-relative overflow-hidden section-padded">
	<div class="container">
		<div class="text-center mx-auto mb-5 section-header">
			<span class="section-eyebrow d-inline-flex align-items-center gap-2"> AI-Powered Unified Inbox Automation </span>
			<h2 class="display-5 fw-bold mb-4"> Track prospect intent, prioritize hot leads, and close faster </h2>
			<p class="lead text-muted"> Stop wasting your time and allow Botphonic's AI-powered inbox automation to analyze every incoming message, detect buyer intent, and organize conversations </p>
		</div>

		<div class="row align-items-center gy-5">

			<!-- Inbox mock -->
			<div class="col-lg-6 order-2 order-lg-1">
				<div class="inbox-card">
					<div class="inbox-header d-flex justify-content-between align-items-center">
						<div class="d-flex gap-2">
							<span class="dot red"></span>
							<span class="dot yellow"></span>
							<span class="dot green"></span>
						</div>
						<span class="text-muted small">AI Categorization</span>
					</div>

					<!-- Filters -->
					<div class="inbox-filters">
						<?php foreach($inboxFilters as $filter): ?>
						<span class="filter-pill">
							<span class="status-dot <?= $filter['class'] ?>"></span> <?= $filter['label'] ?> <small><?= $filter['count'] ?></small>
						</span>
						<?php endforeach; ?>
					</div>

					<!-- Messages -->
					<?php foreach($inboxMessages as $msg): ?>
					<div class="inbox-item <?= $msg['active'] ? 'active' : '' ?>">
						<div class="d-flex justify-content-between gap-3">
							<div class="d-flex gap-3">
								<div class="avatar"><?= $msg['avatar'] ?></div>
								<div>
									<strong><?= $msg['name'] ?></strong>
									<div class="badge <?= $msg['badgeClass'] ?> ms-2"><?= $msg['badge'] ?></div>
									<p class="text-muted small mb-0"><?= $msg['subject'] ?></p>
								</div>
							</div>
							<small class="text-muted"><?= $msg['time'] ?></small>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Feature list -->
			<div class="col-lg-6 order-1 order-lg-2">
				<div class="feature-list">
					<?php foreach($features as $feature): ?>
					<div class="feature-row">
						<div class="feature-icon accent-icon"><?= $feature['icon'] ?></div>
						<div>
							<h5><?= $feature['title'] ?></h5>
							<p><?= $feature['text'] ?></p>
						</div>
					</div>
					<?php endforeach; ?>

					<a href="#" class="btn theme-btn d-inline-flex gap-3 mt-3">
						<span> Try AI Filters Free</span>
						<span>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-5 h-5 group-hover:translate-x-1 transition-transform">
								<path d="M5 12h14"></path>
								<path d="m12 5 7 7-7 7"></path>
							</svg>
						</span>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>

<?php
$steps = [
	[
		'number' => '01',
		'title' => 'Connect Your Communication Channels',
		'text' => 'Connect your email accounts, chat tools, and social messaging platforms in just a few clicks',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload text-primary-foreground"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" x2="12" y1="3" y2="15"></line></svg>'
	],
	[
		'number' => '02',
		'title' => 'Configure Smart Workflows',
		'text' => 'Define how conversation flows across your team and set up automation rules, routine logic, and also ownership assignments',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text text-primary-foreground"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>'
	],
	[
		'number' => '03',
		'title' => 'Manage & Respond From One Unified Inbox',
		'text' => 'Handle every conversation from a single AI-powered inbox, use AI reply suggestions templates, and bulk actions to respond faster and collaborate better',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles text-primary-foreground"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path><path d="M20 3v4"></path><path d="M22 5h-4"></path><path d="M4 17v2"></path><path d="M5 18H3"></path></svg>'
	],
	[
		'number' => '04',
		'title' => 'Analyze Performance & Optimize Results',
		'text' => 'Monitor key outreach metrics in real time, including all the opens, replies, meetings booked, and conversions',
		'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send text-primary-foreground"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path><path d="m21.854 2.147-10.94 10.939"></path></svg>'
	]
];
?>
<section class="how-section section-padded gradient-feature">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Start in Minutes. Scale Without Limits</h2>
			<p class="section-subtitle"> Launch smarter outreach campaigns powered by AI-driven personalization </p>
		</div>

		<div class="how-desktop">
			<div class="timeline-line"></div>

			<div class="steps-grid">
				<?php foreach($steps as $step): ?>
				<div class="step">
					<div class="step-card">
						<div class="step-icon"><?= $step['icon'] ?></div>
						<span class="step-number"><?= $step['number'] ?></span>
					</div>
					<div class="step-content">
						<h3 class="step-title"><?= $step['title'] ?></h3>
						<p class="step-text"><?= $step['text'] ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php
$impactData = [
	['number' => '10x', 'label' => 'More Replies', 'class' => 'primary'],
	['number' => '5x', 'label' => 'More Meetings', 'class' => 'secondary'],
	['number' => '2.3x', 'label' => 'Pipeline Growth', 'class' => 'accent'],
];

$testimonials = [
	[
		'avatar' => 'TF',
		'name' => 'Thomas Foley',
		'role' => 'Founder, HighTouch',
		'text' => 'The future of unified communication has arrived. The simplicity and straightforwardness of the product are exceptional.'
	],
	[
		'avatar' => 'CN',
		'name' => 'Cong Nguyen',
		'role' => 'Founder & CEO, Synodus',
		'text' => 'One of the simplest inbox automation platforms with great pricing. Customer support is incredibly helpful and responsive.'
	],
	[
		'avatar' => 'JL',
		'name' => 'Joshua Lim',
		'role' => 'Managing Director, Social Hackrs',
		'text' => 'A must-have in your communication arsenal. It has everything you’re looking for to accelerate business growth.'
	],
	[
		'avatar' => 'GT',
		'name' => 'Gino Taka',
		'role' => 'Founder, Skyrocket',
		'text' => 'The perfect unified platform for our business. You can add unlimited accounts to increase volume at any scale.'
	],
	[
		'avatar' => 'GH',
		'name' => 'Gareth Hayter',
		'role' => 'Founder & CEO, Slyce Software',
		'text' => 'Success on rails. Botphonic really helps guide and create trust in the tool from day one.'
	],
	[
		'avatar' => 'MS',
		'name' => 'Maria Santos',
		'role' => 'Head of Support, TechFlow',
		'text' => 'We reduced our response time by 80% after switching to Botphonic. Our customers love the faster support.'
	],
];
?>

<section id="testimonials" class="testimonials-section section-padded">
	<div class="container">
		<div class="impact-card">
			<?php foreach($impactData as $index => $impact): ?>
			<div class="impact-item">
				<p class="impact-number <?= $impact['class'] ?>"><?= $impact['number'] ?></p>
				<p class="impact-label"><?= $impact['label'] ?></p>
			</div>
			<?php if($index < count($impactData) - 1): ?>
			<span class="impact-arrow">→</span>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="container py-5">
		<div class="text-center mx-auto mb-5 section-header">
			<span class="section-eyebrow">Testimonials</span>
			<h2 class="display-5 fw-bold mb-4"> Trusted by <span class="text-gradient">industry leaders</span> </h2>
			<p class="lead text-muted"> See what our customers have to say about transforming their communication workflow with Botphonic.ai </p>
		</div>

		<div class="row g-4">
			<?php foreach($testimonials as $testimonial): ?>
			<div class="col-12 col-md-6 col-lg-4">
				<div class="testimonial-card">
					<div class="quote-icon">❝</div>
					<div class="stars">★★★★★</div>
					<p class="testimonial-text"> “<?= $testimonial['text'] ?>” </p>
					<div class="testimonial-author">
						<div class="avatar"><?= $testimonial['avatar'] ?></div>
						<div>
							<strong><?= $testimonial['name'] ?></strong>
							<div class="text-muted small"><?= $testimonial['role'] ?></div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<div class="faq-section accordion-list">
	<?php echo do_shortcode('[botphonic_faq]'); ?>
</div>

<?= do_shortcode('[cold_mail_cta title="Launch Your First Personalized Campaign and Get More Replies" text="Start booking more meetings with smarter outreaches and increase replies with personalized emails."]') ?>

<?php get_footer(); ?>