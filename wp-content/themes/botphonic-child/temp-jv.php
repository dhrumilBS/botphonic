<?php /* Template Name: JV */ ?>
<?php get_header(); ?>
<?php wp_enqueue_style("jv"); ?>

<style>
	.section-padded{ --padd: 40px; }
	@media screen and (max-width: 768){
		.section-padded{ --padd: 30px; }
	}
	@media screen and (max-width: 440px){
		.section-padded{ --padd: 25px; }
	}

	.custom-logo-link img{ max-width: 180px; height: auto; }
</style>

<section class="py-4 bg-light">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-4">
				<div class="logo-wrap">
					<?php the_custom_logo(); ?>
				</div>
			</div>
			<div class="col-8 text-end">
				<div class="affiliate-link-btn">
					<a class="theme-btn" href="#" target="_blank">Get Affiliate Link</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="hero-section position-relative overflow-hidden custom-bg-gradient section-padded">
	<!-- Blurred Background Bubbles -->
	<div class="position-absolute top-0 end-0 custom-bubble blue"></div>
	<div class="position-absolute bottom-0 start-0 custom-bubble indigo delay"></div>
	<div class="position-absolute top-50 start-50 translate-middle custom-bubble purple center"></div>

	<div class="container  text-center position-relative z-1">
		<!-- Floating Badge -->
		<div class="d-flex justify-content-center mb-4">
			<div class="d-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-white bg-opacity-75 border shadow custom-badge">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning">
					<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"> </path>
				</svg>
				<span class="fw-medium">Botphonic AI</span>
			</div>
		</div>

		<!-- Heading -->
		<h1 class="mb-3 display-2 fw-bold">
			<span class="custom-text-gradient">Revolutionary AI</span> Call Assistant<br />
			<span class="fs-4">JV Launch</span>
		</h1>

		<!-- Subtext -->
		<p class="lead mx-auto mb-4" style="max-width: 720px;">
			Join the most profitable AI affiliate launch of 2025! Botphonic transforms businesses with intelligent call automation.
		</p>

		<!-- Launch Info -->
		<div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mb-4">
			<div class="d-none align-items-center text-success gap-2 fw-semibold">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"> </path> <path d="M20 3v4"> </path> <path d="M22 5h-4"> </path> <path d="M4 17v2"> </path> <path d="M5 18H3"> </path> </svg>
				Launch: February 5–12, 2025
			</div>
			<div class="d-none d-sm-none bg-primary rounded-circle" style="width: 6px; height: 6px;"></div>
			<div class="text-success fw-semibold">25% Commission + Bonuses</div>
		</div>

		<!-- CTA Buttons -->
		<div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
			<a class="theme-btn">Get Affiliate Link</a>
		</div>
	</div>
</section>

<?php echo do_shortcode('[ai_call_assistant_demo]'); ?>


<section class="section-padded bg-light affiliate-benefits-section">
	<div class="container">
		<!-- Section Heading -->
		<div class="text-center mb-5">
			<h2 class="mb-3">
				Why Promote
				<span class="custom-gradient-text">Botphonic?</span>
			</h2>
			<p class="lead mx-auto" style="max-width: 720px;">
				Join the most lucrative AI affiliate opportunity of 2025. Here's why smart affiliates choose Botphonic:
			</p>
		</div>

		<!-- Features Grid -->
		<div class="row g-4 justify-content-center">
			<!-- Card 1 -->
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border border-light transition-scale hover-shadow">
					<div class="card-body text-center p-4">
						<div class="mb-4">
							<div class="mx-auto p-3 rounded-circle bg-primary bg-opacity-10 border border-primary border-opacity-25 d-inline-flex">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="green" stroke-width="2">
									<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
									<polyline points="16 7 22 7 22 13"></polyline>
								</svg>
							</div>
						</div>
						<h5 class="fw-bold mb-2 text-dark">High Converting Funnel</h5>
						<div class="mb-2">
							<span class="fw-bold bg-text-gradient">12% Conversion Rate</span>
						</div>
						<p class="text-muted">
							Proven 12% front-end conversion rate with optimized upsell sequence. Our VSL converts cold traffic into buyers consistently.
						</p>
					</div>
				</div>
			</div>

			<!-- Card 2 -->
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border border-light transition-scale hover-shadow">
					<div class="card-body text-center p-4">
						<div class="mb-4">
							<div class="mx-auto p-3 rounded-circle bg-primary bg-opacity-10 border border-primary border-opacity-25 d-inline-flex">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="orange" stroke-width="2">
									<line x1="12" x2="12" y1="2" y2="22"></line>
									<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
								</svg>
							</div>
						</div>
						<h5 class="fw-bold mb-2 text-dark">Massive Commissions</h5>
						<div class="mb-2">
							<span class="fw-bold bg-text-gradient">$50K+ Potential</span>
						</div>
						<p class="text-muted">
							Earn 25% on front-end + all upsells. Average order value $297. Top affiliates earning $50K+ in launch week alone.
						</p>
					</div>
				</div>
			</div>

			<!-- Card 3 -->
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border border-light transition-scale hover-shadow">
					<div class="card-body text-center p-4">
						<div class="mb-4">
							<div class="mx-auto p-3 rounded-circle bg-primary bg-opacity-10 border border-primary border-opacity-25 d-inline-flex">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2">
									<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
									<circle cx="9" cy="7" r="4"></circle>
									<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
								</svg>
							</div>
						</div>
						<h5 class="fw-bold mb-2 text-dark">Red-Hot Market</h5>
						<div class="mb-2">
							<span class="fw-bold bg-text-gradient">Unsaturated Market</span>
						</div>
						<p class="text-muted">
							AI automation is exploding! Every business needs call assistance. Perfect timing with massive market demand and zero saturation.
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Callout Badge -->
		<div class="text-center mt-5 d-none">
			<div class="d-inline-flex align-items-center gap-2 px-4 py-3 border rounded-pill bg-primary bg-opacity-10 backdrop-blur-sm">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#ffc107" stroke-width="2">
					<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679..."></path>
				</svg>
				<span class="fw-semibold text-dark">Limited JV Spots Available</span>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#0d6efd" stroke-width="2" class="animate__animated animate__flash animate__infinite">
					<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2..."></path>
				</svg>
			</div>
		</div>
	</div>
</section>

<section class="botphonic-meet-section section-padded bg-light">
	<div class="container px-3">
		<div class="text-center mb-5">
			<h2 class="mb-3 custom-text-foreground ">
				Meet <span class="custom-gradient-text">Botphonic</span>
			</h2>
			<p class="lead mx-auto " style="max-width: 720px;">
				The world’s most advanced AI call assistant that handles customer inquiries, bookings, and support with human‑like intelligence.
			</p>
		</div>

		<div class="row gx-5 align-items-center">
			<div class="col-lg-6 mb-5 mb-lg-0">
				<div class="p-4 rounded-4 border shadow-sm bg-white custom-backdrop-blur">
					<h3 class="h3 fw-bold mb-4 custom-text-foreground ">Revolutionary Features</h3>
					<div class="row row-cols-1 row-cols-sm-2 g-3">
						<!-- Feature Items -->
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="  text-primary">
									<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">

									</path>
								</svg>
								<span class="fw-medium">24/7 AI Call Handling</span>
							</div>
						</div>
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="  text-purple-600">
									<path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z">
									</path>
									<path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"></path>
									<path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"></path>
									<path d="M17.599 6.5a3 3 0 0 0 .399-1.375"></path>
									<path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"></path>
									<path d="M3.477 10.896a4 4 0 0 1 .585-.396"></path>
									<path d="M19.938 10.5a4 4 0 0 1 .585.396"></path>
									<path d="M6 18a4 4 0 0 1-1.967-.516"></path>
									<path d="M19.967 17.484A4 4 0 0 1 18 18"></path>
								</svg>
								<span class="fw-medium">Natural Language Processing</span>
							</div>
						</div>
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="  text-green-600">
									<circle cx="12" cy="12" r="10"></circle>
									<polyline points="12 6 12 12 16 14"></polyline>
								</svg>
								<span class="fw-medium">Instant Response Time</span>
							</div>
						</div>
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="  text-pink-600">
									<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
									<circle cx="9" cy="7" r="4"></circle>
									<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
								</svg>
								<span class="fw-medium">Multi-Language Support</span>
							</div>
						</div>
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-no-axes-column-increasing  text-yellow-600">
									<line x1="12" x2="12" y1="20" y2="10"></line>
									<line x1="18" x2="18" y1="20" y2="4"></line>
									<line x1="6" x2="6" y1="20" y2="16"></line>
								</svg>
								<span class="fw-medium">Advanced Analytics</span>
							</div>
						</div>
						<div class="col">
							<div class="d-flex align-items-center p-2 gap-2 custom-muted-bg rounded border ">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="  text-orange-600">
									<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
								</svg>
								<span class="fw-medium">Easy Integration</span>
							</div>
						</div>
					</div>
				</div>

				<div class="mt-4 p-4 rounded-4 border  custom-backdrop-blur">
					<div class="text-center">
						<h4 class="fw-bold mb-1 custom-text-foreground ">Save 80% on Call Costs</h4>
						<p class="text-muted fw-semibold m-0">Average business saves $5,000+ monthly</p>
					</div>
				</div>
			</div>

			<div class="col-lg-6 text-center">
				<div class="position-relative d-inline-block">
					<div class="card p-4 rounded-4 border shadow-lg custom-animate-float">
						<div class="rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-4 bg-primary" style="width:64px; height:64px;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" w-8 h-8 text-white">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
							</svg>
						</div>
						<div class="mb-4 custom-text-foreground">
							<h5 class="fw-bold ">AI Assistant Active</h5>
							<p class="text-muted ">Handling customer call...</p>
						</div>
						<div class="mb-4">
							<div class="text-start border-start border-primary bg-light rounded p-3 mb-2">
								<span class="d-block text-muted small ">Customer:</span>
								<span class="custom-text-foreground ">“I need to book an appointment”</span>
							</div>
							<div class="text-start border-start border-success bg-light rounded p-3">
								<span class="d-block text-muted small ">Botphonic AI:</span>
								<span class="custom-text-foreground ">“I’d be happy to help! What time works best for you?”</span>
							</div>
						</div>
						<div class="d-none">
							<span class="spinner-grow spinner-grow-sm text-success ms-2" role="status"></span>
							<span class="badge bg-success text-white ">Live Call in Progress</span>
						</div>
					</div>

					<!-- Floating badges -->
					<div class="position-absolute top-0 end-0 translate-middle bg-success text-white p-3 rounded-3">99.9%<br><small>Uptime</small></div>
					<div class="position-absolute bottom-0 start-0 translate-middle bg-warning text-white p-3 rounded-3 bottom-box"><small>&lt;2s</small><br><small>Response</small></div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-padded position-relative bg-light overflow-hidden" style="background: linear-gradient(to bottom right, rgba(0, 123, 255, 0.05), transparent, rgba(255, 193, 7, 0.05));">
	<div class="container position-relative text-center">
		<div class="mb-5">
			<div class="d-inline-flex align-items-center gap-2 px-3 py-2 mb-4 border rounded-pill bg-white shadow-sm">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-graph-up text-primary" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm14.293 4.707-3.5 3.5-2.5-2.5-3.5 3.5-.707-.707L8 4.5l2.5 2.5 2.793-2.793.707.707z"></path>
				</svg>
				<span class="text-primary fw-semibold small">Start Earning Today</span>
			</div>

			<h2 class="mb-3">
				<span class="text-primary">Get Your <span class="custom-gradient-text">Affiliate Link</span> Here</span>
			</h2>
			<h3 class="h4 fw-bold text-dark">Get Instant Commissions</h3>
			<p class="lead text-muted mx-auto" style="max-width: 640px;">Straight to your PayPal account through Botphonic's secure platform</p>
		</div>

		<div class="mx-auto" style="max-width: 960px;">
			<div class="position-relative p-3 p-md-5 bg-white border rounded-4 shadow">
				<div class="row mb-4">
					<div class="col-md-4 mb-3 mb-md-0 text-center">
						<h4 class="text-primary fw-bold">25%</h4>
						<p class="text-muted small mb-0">Commission Rate</p>
					</div>
					<div class="col-md-4 mb-3 mb-md-0 text-center">
						<h4 class="text-primary fw-bold">24h</h4>
						<p class="text-muted small mb-0">Fast Payouts</p>
					</div>
					<div class="col-md-4 text-center">
						<h4 class="text-primary fw-bold">∞</h4>
						<p class="text-muted small mb-0">Unlimited Earnings</p>
					</div>
				</div>

				<div class="text-center mb-4">
					<h4 class="h3 fw-bold mb-3">GRAB YOUR BOTPHONIC<br><span class="text-primary">AFFILIATE LINK</span></h4>
					<div class="bg-light p-4 rounded shadow-sm mx-auto mb-4" style="max-width: 360px;">
						<div class="d-flex justify-content-center align-items-center gap-2 mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" text-yellow-500 animate-pulse">
								<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"> </path>
							</svg>
							<h5 class="fw-bold text-primary mb-0">Botphonic</h5>
						</div>
						<small class="text-muted d-block">AI Call Assistant Platform</small>
					</div>
				</div>

				<div class="text-center">
					<a href="#" class="theme-btn px-5 py-3 d-inline-flex align-items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-link-45deg d-none " viewBox="0 0 16 16">
							<path d="M4.715 6.542a3.002 3.002 0 0 1 0-4.242l1.004-1.004a3 3 0 0 1 4.243 4.242l-.708.708-.708-.708a2 2 0 1 0-2.829 2.828l.708.708-.708.708-.708-.708a3 3 0 0 1 0-4.242l.708-.708z"></path>
							<path d="M6.121 8.95l.708-.708.708.708a2 2 0 1 0 2.828-2.828l-.708-.708.708-.708.708.708a3 3 0 0 1-4.242 4.243l-.708-.708z"></path>
						</svg>
						Get Your Affiliate Link
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-cash-stack d-none " viewBox="0 0 16 16">
							<path d="M14 7a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h11zm0-1H3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"></path>
							<path d="M13 9.5a.5.5 0 0 1 .5.5v4h-11v-4a.5.5 0 0 1 .5-.5h10z"></path>
							<path d="M7.5 11a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"></path>
						</svg>
					</a>
					<p class="small text-muted mt-3">Join 10,000+ successful affiliates earning passive income</p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>