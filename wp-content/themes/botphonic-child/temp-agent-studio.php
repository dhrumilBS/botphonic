<?php /* Template Name: Agent Studio*/ ?>
<?php get_header(); ?>

<section class="section-padded hero">
	<div class="hero-bg"></div>
	<div class="hero-grid"></div>
	<div class="hero-content">
		<div class="hero-badge"> <span class="badge-dot"></span> Agent Studio Is Here </div>
		<h1><span class="grad-text">AI Agent Studio - </span>Design, Deploy &amp; Govern Voice AI Agents</h1>
		<p class="hero-sub">AI Agent Studio by Botphonic is an enterprise-ready platform to design, orchestrate, deploy, and govern intelligent voice AI agents. Automate conversations, streamline workflows, and scale your voice infrastructure with ease.</p>
		<div class="hero-actions">
			<a class="btn-primary" href="https://app.botphonic.ai/register" target="_blank">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<path d="M12 2L2 7l10 5 10-5-10-5z" />
					<path d="M2 17l10 5 10-5" />
					<path d="M2 12l10 5 10-5" />
				</svg>
				Start Building Free →
			</a>
		</div>
		<p style="font-size:0.8rem;">No credit card · Deploy in minutes · 7-Days free Trial</p>
		<div class="hero-trust">
			<span> <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
				</svg> SOC 2 Ready </span>
			<span class="trust-sep">·</span>
			<span> <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<rect x="2" y="3" width="20" height="14" rx="2" />
					<path d="M8 21h8M12 17v4" />
				</svg> Enterprise SLA </span>
			<span class="trust-sep">·</span>
			<span> <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
					<path d="M7 11V7a5 5 0 0110 0v4" />
				</svg> Private Deployment </span>
		</div>
	</div>
</section>

<section class="trust-section section-padded">
	<div class="section-inner">
		<div class="trust-header">
			<p>Trusted by forward-thinking enterprises worldwide</p>
		</div>

		<div class="trust-stats">
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M22 12h-4l-3 9L9 3l-3 9H2" />
					</svg></div>
				<div class="trust-stat-text"> <strong>99.99% Uptime</strong> <span>Guaranteed infrastructure SLA</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="12" cy="12" r="10" />
						<path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Global Telephony</strong> <span>Multi-region voice infrastructure</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="3" y="11" width="18" height="11" rx="2" />
						<path d="M7 11V7a5 5 0 0110 0v4" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Secure Voice Data</strong> <span>E2E encryption & data isolation</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Compliance-Ready</strong> <span>SOC 2, GDPR, HIPAA aligned</span> </div>
			</div>
		</div>
	</div>
</section>

<section class=section-padded>
	<div class="section-inner">
		<div class="section-label">Core Platform</div>
		<h2 class="section-title">Complete Control with AI Agent Studio</h2>
		<!--             <p class="section-sub">Four powerful modules that work together to give you complete control over your voice AI pipeline—from design to deployment.</p> -->
		<?php

		$features = [
			[
				"title" => "Visual Agent Builder",
				"description" => "Drag and drop design enables you to have enhanced conversational flows and even branching logic looks seamless.",
				"tag" => "no-code / low-code",
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="url(#grad1)" stroke-width="2">
						<defs>
							<linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
								<stop offset="0%" style="stop-color:#2563EB"></stop>
								<stop offset="100%" style="stop-color:#4F46E5"></stop>
							</linearGradient>
						</defs>
						<path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"></path>
					</svg>'
			],
			[
				"title" => "LLM Orchestration",
				"description" => "Route across different major LLM models, creating custom fine-tuned stacks with cost caps fallbacks, and latency controls built-in.",
				"tag" => "multi-model routing",
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="url(#grad2)" stroke-width="2">
						<defs>
							<linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%">
								<stop offset="0%" style="stop-color:#2563EB"></stop>
								<stop offset="100%" style="stop-color:#4F46E5"></stop>
							</linearGradient>
						</defs>
						<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path>
						<path d="M7.5 4.21l4.5 2.6 4.5-2.6M7.5 19.79V14.6L3 12M21 12l-4.5 2.6v5.19M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"></path>
					</svg>'
			],
			[
				"title" => "Real-Time Voice Intelligence",
				"description" => "Sub-300ms conversational turnarounds with live transcription, sentiment and intent detection, and barge-in handling still feels natural.",
				"tag" => "real-time ASR/TTS",
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="url(#grad3)" stroke-width="2">
						<defs>
							<linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="100%">
								<stop offset="0%" style="stop-color:#2563EB"></stop>
								<stop offset="100%" style="stop-color:#4F46E5"></stop>
							</linearGradient>
						</defs>
						<path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
					</svg>'
			],
			[
				"title" => "Enterprise Integrations",
				"description" => "Easily perform deep integration with Salesforce, HubSpot, ServiceNow, Twilio, and 40+ systems along with full REST API to sync data and workflow.",
				"tag" => "40+ connectors",
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="url(#grad4)" stroke-width="2">
						<defs>
							<linearGradient id="grad4" x1="0%" y1="0%" x2="100%" y2="100%">
								<stop offset="0%" style="stop-color:#2563EB"></stop>
								<stop offset="100%" style="stop-color:#4F46E5"></stop>
							</linearGradient>
						</defs>
						<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path>
						<path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path>
					</svg>'
			]
		];

		?>
		<div class="features-grid">
			<?php foreach ($features as $feature): ?>
				<div class="feature-card">
					<div class="feature-icon">
						<?= $feature['icon']; ?>
					</div>
					<h3><?= $feature['title']; ?></h3>
					<p><?= $feature['description']; ?></p>
					<span class="feature-tag"><?= $feature['tag']; ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-padded section bf-scroll-tabs">
	<!-- Section header -->
	<div class="bf-section-header">
		<div class="section-label"><?= get_field('personas_subtitle'); ?></div>
		<h2 class="section-title"><?= get_field('personas_title'); ?></h2>
		<p class="section-sub" style="margin:0 auto;text-align:center"><?= get_field('personas_text'); ?></p>
	</div>

	<div class="bf-scroll-container" id="bfScrollContainer">
		<div class="bf-sticky-section">
			<?php if (have_rows('personas')) { ?>
				<!-- TABS NAV -->
				<nav class="bf-tabs-nav">
					<div class="bf-tabs-wrapper">
						<?php $i = 0; ?>
						<?php while (have_rows('personas')) {
							the_row(); ?>
							<button class="bf-tab-button <?= $i == 0 ? 'bf-active' : ''; ?>" data-bf-tab="<?= get_row_index(); ?>">
								<div class="bf-tab-text">
									<span class="bf-tab-name"><?= get_sub_field('subtitle'); ?></span>
									<span class="bf-tab-description hidden-medium hidden-small hidden-tiny"><?= get_sub_field('tab_text'); ?></span>
								</div>
								<div class="bf-tab-indicator"></div>
							</button>
						<?php $i++;
						} ?>
					</div>
				</nav>

				<!-- CONTENT PANELS -->
				<div class="bf-content-area">
					<?php $i = 0; ?>
					<?php while (have_rows('personas')): the_row(); ?>
						<div class="bf-content-panel <?= $i == 0 ? 'bf-active' : ''; ?>" data-bf-panel="<?php echo $i; ?>">
							<div class="bf-content-inner">
								<div class="bf-content-grid">
									<div class="bf-panel-text-col">
										<div class="section-label" style="margin-bottom:12px"> <?php the_sub_field('subtitle'); ?> </div>
										<h3><?php the_sub_field('title'); ?></h3>
										<p><?php the_sub_field('text'); ?></p>

										<?php the_sub_field('point'); ?>

										<?php $cta = get_sub_field('cta');
										if ($cta && $cta['cta_text']): ?>
											<a href="<?= esc_url($cta['cta_url']); ?>" class="bf-panel-cta" target="_blank"> <?= esc_html($cta['cta_text']); ?> </a>
										<?php endif; ?>
									</div>

									<div class="bf-square-img-wrapper">
										<?= wp_get_attachment_image(get_sub_field('image'), 'full') ?>
									</div>
								</div>
							</div>
						</div>
					<?php $i++;
					endwhile; ?>
				</div><!-- /bf-content-area -->
			<?php } ?>
		</div><!-- /bf-sticky-section -->
	</div><!-- /bf-scroll-container -->
</section>

<?php
$workflow = [
	[
		"number" => 1,
		"title" => "Design",
		"description" => "Visual conversation flows, agent personas, and knowledge source attachments from day one."
	],
	[
		"number" => 2,
		"title" => "Configure",
		"description" => "Model selection, voice profiles, latency criteria, fallback strategies, and core data connections."
	],
	[
		"number" => 3,
		"title" => "Connect",
		"description" => "Telephony, APIs, CRMs, and webhook connections with a controlled test environment."
	],
	[
		"number" => 4,
		"title" => "Deploy",
		"description" => "Launch in an instant with live monitoring, alerts, and version control."
	]
];

?>
<section class="section-padded hiw-section" id="hiw-section">
	<div class="section-inner">

		<div class="section-label">Workflow</div>
		<h2 class="section-title"> Build with AI Agent Studio in Four Steps </h2>
		<p class="section-sub"> The enterprise development process includes built-in testing and staging and production governance which enables control over the entire development lifecycle. </p>

		<div class="steps-row" id="steps-row">

			<div class="hiw-progress-wrap">
				<div class="hiw-progress-fill" id="hiw-fill"></div>
			</div>

			<?php foreach ($workflow as $step): ?>
				<div class="step" id="step-<?= $step['number']; ?>">
					<div class="step-num"> <?= htmlspecialchars($step['number']); ?> </div>
					<h3> <?= htmlspecialchars($step['title']); ?> </h3>
					<p> <?= htmlspecialchars($step['description']); ?> </p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-padded arch-section">
	<div class="section-inner">
		<div class="section-label">Architecture</div>
		<h2 class="section-title">AI Agent Studio Infrastructure for Enterprises</h2>
		<p class="section-sub">A modular, auditable architecture that fits into your existing security perimeter and
			compliance requirements.</p>
		<?php

		$architecture_layers = [
			[
				"title" => "Voice Input Layer",
				"description" => "Telephony ingress, noise cancellation, ASR streaming",
				"color" => "var(--cyan)",
				"flow" => "↓ encrypted stream"
			],
			[
				"title" => "AI Orchestration Core",
				"description" => "Intent engine, LLM router, context manager",
				"color" => "var(--blue)",
				"flow" => "↓ role-based dispatch"
			],
			[
				"title" => "Integration Middleware",
				"description" => "CRM sync, webhooks, real-time data fetch",
				"color" => "var(--indigo)",
				"flow" => "↓ TLS 1.3 encrypted"
			],
			[
				"title" => "Voice Output Layer",
				"description" => "Neural TTS, DTMF, call control signals",
				"color" => "var(--secondary)",
				"flow" => "↓ AES-256 at rest"
			],
			[
				"title" => "Observability & Storage",
				"description" => "Encrypted logs, transcripts, audit trail",
				"color" => "#10B981",
				"flow" => null
			]
		];

		$architecture_features = [
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
				"title" => "Modular AI Layer",
				"description" => "Independently upgradeable ASR, NLU, and TTS components without downtime."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>',
				"title" => "Secure Voice Pipeline",
				"description" => "TLS 1.3 in transit, AES-256 at rest. Audio data never leaves your designated region."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<circle cx="12" cy="12" r="10"></circle>
							<path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"></path>
						</svg>',
				"title" => "Distributed Routing",
				"description" => "Multi-region active-active deployment with sub-100ms failover and traffic balancing."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <rect x="3" y="3" width="7" height="7"></rect> <rect x="14" y="3" width="7" height="7"></rect> <rect x="14" y="14" width="7" height="7"></rect> <rect x="3" y="14" width="7" height="7"></rect> </svg>',
				"title" => "Role-Based Access",
				"description" => "Granular RBAC with SSO, MFA, and full audit logging of every user action."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
				"title" => "Real-Time Monitoring",
				"description" => "Live dashboards, call analytics, latency tracing, and configurable anomaly alerts."
			]
		];

		?>

		<div class="arch-grid">
			<div class="arch-visual">
				<div class="arch-visual-bg"></div>
				<?php foreach ($architecture_layers as $index => $layer): ?>

					<div class="arch-layer">
						<div class="arch-layer-dot" style="background:<?= htmlspecialchars($layer['color']); ?>"></div>
						<div class="arch-layer-text">
							<strong><?= htmlspecialchars($layer['title']); ?></strong>
							<span><?= htmlspecialchars($layer['description']); ?></span>
						</div>
					</div>

					<?php if (!empty($layer['flow'])): ?>
						<div style="text-align:center;font-size:0.7rem;color:#b6b8bd;padding:4px 0;font-family:var(--mono-font)"> <?= htmlspecialchars($layer['flow']); ?> </div>
					<?php endif; ?>

				<?php endforeach; ?>
			</div>
			<ul class="arch-list">
				<?php foreach ($architecture_features as $feature): ?>
					<li>
						<?= $feature['icon'] ?>
						<div>
							<strong><?= htmlspecialchars($feature['title']); ?></strong> <br>
							<span style="font-size:0.82rem;color:var(--muted)"> <?= htmlspecialchars($feature['description']); ?> </span>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<section class="section-padded dev-section">
	<div class="section-inner">
		<div class="section-label">Developer Platform</div>
		<h2 class="section-title">AI Agent Studio for Developers</h2>
		<p class="section-sub">First-class APIs, type-safe SDKs, and full observability. Integrate Botphonic into any stack in hours, not weeks.</p>

		<?php
		$dev_features = [
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path> <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path> </svg>',
				"title" => "REST API + Webhooks",
				"description" => "OpenAPI 3.0 spec with full TypeScript and Python SDK support. Webhook delivery with automatic retries."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <polyline points="16 18 22 12 16 6"></polyline> <polyline points="8 6 2 12 8 18"></polyline> </svg>',
				"title" => "SDKs & CLI Tools",
				"description" => "Official SDKs for Node.js, Python, Go. CLI for local testing, agent scaffolding, and CI/CD pipelines."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <circle cx="12" cy="12" r="3"></circle> <path d="M19.07 4.93l-1.41 1.41M5.34 18.66l-1.41 1.41M21 12h-2M5 12H3m16.07 7.07l-1.41-1.41M5.34 5.34L3.93 3.93"></path> </svg>',
				"title" => "Staging Environments",
				"description" => "Full parity staging environments with production. Deploy confidently using blue/green rollouts."
			],
			[
				"icon" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
				"title" => "Logs & Version Control",
				"description" => "Immutable call logs, agent version history, diff view, and one-click rollback to any previous state."
			]
		];

		?>

		<div class="dev-grid">
			<div>
				<div class="code-block">
					<div class="code-header">
						<div class="code-dot" style="background:#EF4444"></div>
						<div class="code-dot" style="background:#F59E0B"></div>
						<div class="code-dot" style="background:#10B981"></div>
						<span class="code-filename">deploy-agent.ts</span>
					</div>
					<div class="code-body">
						<span class="c-comment">// Deploy a Voice AI Agent via REST API</span><br>
						<span class="c-key">import</span>
						<span class="c-op">{</span>
						<span class="c-fn">BotphonicClient</span>
						<span class="c-op">}</span>
						<span class="c-key">from</span>
						<span class="c-str">'@botphonic/sdk'</span>
						<span class="c-op">;</span><br>
						<br>
						<span class="c-key">const</span>
						<span class="c-val">client</span>
						<span class="c-op">=</span>
						<span class="c-key">new</span>
						<span class="c-fn">BotphonicClient</span>
						<span class="c-op">({</span><br>
						&nbsp;&nbsp;<span class="c-key">apiKey</span>
						<span class="c-op">:</span>
						<span class="c-val">process</span>
						<span class="c-op">.</span>
						<span class="c-val">env</span>
						<span class="c-op">.</span>
						<span class="c-str">BOTPHONIC_KEY</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;<span class="c-key">region</span>
						<span class="c-op">:</span>
						<span class="c-str">'us-east-1'</span>
						<span class="c-op">,</span><br>
						<span class="c-op">});</span><br>
						<br>
						<span class="c-key">const</span>
						<span class="c-val">agent</span>
						<span class="c-op">=</span>
						<span class="c-key">await</span>
						<span class="c-val">client</span>
						<span class="c-op">.</span>
						<span class="c-fn">agents</span>
						<span class="c-op">.</span>
						<span class="c-fn">deploy</span>
						<span class="c-op">({</span><br>
						&nbsp;&nbsp;<span class="c-key">agentId</span>
						<span class="c-op">:</span>
						<span class="c-str">'ag_reception_v2'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;<span class="c-key">environment</span>
						<span class="c-op">:</span>
						<span class="c-str">'production'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;<span class="c-key">telephony</span>
						<span class="c-op">:</span>
						<span class="c-op">{</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">provider</span>
						<span class="c-op">:</span>
						<span class="c-str">'twilio'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">phoneNumber</span>
						<span class="c-op">:</span>
						<span class="c-str">'+1 (555) 000-DEMO'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;<span class="c-op">},</span><br>
						&nbsp;&nbsp;<span class="c-key">llm</span>
						<span class="c-op">:</span>
						<span class="c-op">{</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">primary</span>
						<span class="c-op">:</span>
						<span class="c-str">'gpt-4o'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">fallback</span>
						<span class="c-op">:</span>
						<span class="c-str">'claude-3-haiku'</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="c-key">maxLatencyMs</span>
						<span class="c-op">:</span>
						<span class="c-num">700</span>
						<span class="c-op">,</span><br>
						&nbsp;&nbsp;<span class="c-op">},</span><br>
						<span class="c-op">});</span><br>
						<br>
						<span class="c-val">console</span>
						<span class="c-op">.</span>
						<span class="c-fn">log</span>
						<span class="c-op">(</span>
						<span class="c-str">`Agent live: <span class="c-val">${agent.endpoint}</span>`</span>
						<span class="c-op">);</span>
					</div>
				</div>
			</div>
			<div class="dev-features">
				<?php foreach ($dev_features as $feature): ?>
					<div class="dev-feat">
						<div class="dev-feat-icon"> <?= $feature['icon']; ?> </div>
						<div>
							<h4><?= htmlspecialchars($feature['title']); ?></h4>
							<p><?= htmlspecialchars($feature['description']); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<section class="section-padded usecases-section">
	<div class="section-inner">
		<div class="section-label">Use Cases</div>
		<h2 class="section-title">AI Agent Studio Use Cases for Enterprises</h2>
		<p class="section-sub">From the front desk to the back office Botphonic agents handle high-volume voice interactions reliably at scale.</p>
		<?php

		$usecases = [
			[
				"icon" => "🎙️",
				"title" => "AI Receptionist",
				"description" => "Handles inbound calls throughout the day to every department while handling visitor information and responding to common questions without using hold tones."
			],
			[
				"icon" => "🎯",
				"title" => "Lead Qualification",
				"description" => "Gives sales teams the ability to qualify inbound leads through dynamic scripts which require active customer relationship management and immediate lead distribution."
			],
			[
				"icon" => "💬",
				"title" => "Customer Support",
				"description" => "Manages both level one and level two support calls through their automated system which transfers complicated cases to human agents with complete case details."
			],
			[
				"icon" => "📅",
				"title" => "Appointment Scheduling",
				"description" => "Enables callers to make appointments through voice calls while they can also change or cancel their bookings which links to the current schedule and sends confirmation messages."
			],
			[
				"icon" => "📊",
				"title" => "Collections & Payments",
				"description" => "Allows organizations to handle payment reminders through automation while customers can create custom payment schedules with running total collection processes which follow every legal requirement."
			],
			[
				"icon" => "⚙️",
				"title" => "Internal Automation",
				"description" => "Enables organizations to automate their IT helpdesk and HR and internal operations through agents who work with their existing enterprise systems."
			]
		];

		?>

		<div class="usecases-grid">
			<?php foreach ($usecases as $usecase): ?>
				<div class="usecase-card">
					<div class="usecase-card-icon"><?= htmlspecialchars($usecase['icon']); ?></div>
					<h3><?= htmlspecialchars($usecase['title']); ?></h3>
					<p><?= htmlspecialchars($usecase['description']); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-padded security-section">
	<div class="section-inner">
		<div class="section-label">Security & Compliance</div>
		<h2 class="section-title">AI Agent Studio Enterprise Security</h2>
		<p class="section-sub">Every company needs to establish its security posture through the security requirements of regulated industries which our team used to create Botphonic.</p>
		<?php
		$securities = [
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>',
				"title" => 'AES-256 Encryption',
				"text" => 'All audio transcripts and metadata encrypted at rest and in transit with TLS 1.3.',
			],
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>',
				"title" => 'SOC 2 Type II Ready',
				"text" => 'Protects data through security controls that meets the standards of SOC 2 Trust Service Criteria.',
			],
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path
                            d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                    </svg>',
				"title" => 'GDPR Ready',
				"text" => 'Data residency controls, right-to-erasure workflows and DPA agreements for European deployments make the system.',
			],
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                    </svg>',
				"title" => 'Data Isolation',
				"text" => 'The Data remains completely separate from other organizations because tenant-level isolation keeps all data in its dedicated space.',
			],
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                    </svg>',
				"title" => 'Private Cloud',
				"text" => 'Enables organizations to operate their entire system inside their VPC or on-premises area which gives them control over their data and their entire network.',
			],
			[
				"badge" => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>',
				"title" => 'Role-Based Access',
				"text" => 'control system which provides detailed access management through its RBAC system while supporting SSO/SAML and MFA requirements.',
			],
		]
		?>
		<div class="security-grid">
			<?php foreach ($securities as $security): ?>
				<div class="security-card">
					<div class="security-badge"> <?= $security['badge']; ?> </div>
					<h3> <?= htmlspecialchars($security['title']); ?> </h3>
					<p> <?= htmlspecialchars($security['text']); ?> </p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-padded metrics-section" id="metrics-section">
	<div class="metrics-bg-glow"></div>
	<div class="section-inner">
		<div class="section-label" style="text-align:center">Performance</div>
		<h2 class="section-title" style="text-align:center">Numbers that matter<br>to enterprise teams</h2>

		<svg width="0" height="0" style="position:absolute">
			<defs>
				<linearGradient id="rg1" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#06B6D4" />
					<stop offset="100%" stop-color="#2563EB" />
				</linearGradient>
				<linearGradient id="rg2" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#2563EB" />
					<stop offset="100%" stop-color="#4F46E5" />
				</linearGradient>
				<linearGradient id="rg3" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#4F46E5" />
					<stop offset="100%" stop-color="#8B5CF6" />
				</linearGradient>
				<linearGradient id="rg4" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#10B981" />
					<stop offset="100%" stop-color="#06B6D4" />
				</linearGradient>
			</defs>
		</svg>


		<?php

		$metrics = [
			[
				"id" => "m1",
				"ring_id" => "ring1",
				"value" => "<300<span style='font-size:0.65em'>ms</span>",
				"label" => "latency",
				"description" => "End-to-end voice<br>response time",
				"target_offset" => "242",
				"stroke" => "url(#rg1)"
			],
			[
				"id" => "m2",
				"ring_id" => "ring2",
				"value" => "99.99<span style='font-size:0.65em'>%</span>",
				"label" => "uptime",
				"description" => "Guaranteed platform<br>availability SLA",
				"target_offset" => "10",
				"stroke" => "url(#rg2)"
			],
			[
				"id" => "m3",
				"ring_id" => "ring3",
				"value" => "15<span style='font-size:0.65em'>+</span>",
				"label" => "regions",
				"description" => "Global deployment<br>infrastructure",
				"target_offset" => "168",
				"stroke" => "url(#rg3)"
			],
			[
				"id" => "m4",
				"ring_id" => "ring4",
				"value" => "Real-<br>time",
				"label" => "handling",
				"description" => "Smart interruption<br>detection",
				"target_offset" => "27",
				"stroke" => "url(#rg4)"
			]
		];
		?>
		<div class="metrics-grid" id="metrics-grid">
			<?php foreach ($metrics as $metric): ?>
				<div class="metric-item" id="<?= htmlspecialchars($metric['id']); ?>" data-target-offset="<?= htmlspecialchars($metric['target_offset']); ?>" data-stroke="<?= htmlspecialchars($metric['stroke']); ?>">
					<div class="metric-ring-wrap">
						<svg class="metric-ring-svg" viewBox="0 0 108 108">
							<circle class="metric-ring-bg" cx="54" cy="54" r="43" />
							<circle class="metric-ring-fill" id="<?= htmlspecialchars($metric['ring_id']); ?>" cx="54" cy="54" r="43" stroke="<?= htmlspecialchars($metric['stroke']); ?>" />
						</svg>
						<div class="metric-ring-center">
							<span class="metric-ring-val"> <?= $metric['value']; ?> </span>
							<span class="metric-ring-label"> <?= htmlspecialchars($metric['label']); ?> </span>
						</div>
					</div>
					<span class="metric-desc"> <?= $metric['description']; ?> </span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php echo do_shortcode('[botphonic_faq]'); ?>

<?php echo do_shortcode('[cold_mail_cta title="Start Building Your First AI Agent Today" text="Get in and create your first AI-powered voice agent with Botphonic"]'); ?>

<?php get_footer(); ?>