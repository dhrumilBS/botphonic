<?php
/* 
Template Name: Ai Voice Agents
*/
?>
<?php get_header(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<section class="hero-section position-relative overflow-hidden section-padded">
	<!-- Blurred Background Bubbles -->
	<div class="position-absolute top-0 end-0 custom-bubble blue"></div>
	<div class="position-absolute bottom-0 start-0 custom-bubble indigo delay"></div>
	<div class="position-absolute top-50 start-50 translate-middle custom-bubble purple center"></div>

	<div class="container text-center position-relative z-1">
		<h1 class="mb-3 display-2 fw-bold"> Launch Your <span class="custom-gradient-text">AI Call Assistant</span> </h1>
		<p class="lead mx-auto mb-4" style="max-width: 720px;"> With the use of following ready-made templates, you can use any of them to solve your real-life problems.		</p>
	</div>
</section>

<section class="marketplace-templates">
	<div class="container-fluid">
		<div class="d-flex gap-1 flex-column flex-md-row">
			<div class="template-filter-wrap p-2">
				<div class="template-filter">
					<div class="filter-col rounded rounded-3">
						<div class="d-flex align-items-center">
							<div class="h4 m-0">Filter</div>
							<a class="h6 clear-all-btn m-0 ms-auto" href="#" id="clearAllBtn"> Clear All </a>
						</div>
						<div class="pt-1 col-5 bg-opacity-10 bg-primary rounded-pill"></div>
						<div class="mt-2">
							<div class="h6 m-0">By Industry</div>
							<div id="industryInputs">
								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Healthcare" value="Healthcare">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Healthcare">Healthcare</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Education" value="Education">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Education">Education</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Recruitment" value="Recruitment">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Recruitment">Recruitment</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Solar" value="Solar">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Solar">Solar</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Mortgage" value="Mortgage">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Mortgage">Mortgage</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Hospitality" value="Hospitality">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Hospitality">Hospitality</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Insurance" value="Insurance">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Insurance">Insurance</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Real Estate" value="Real Estate">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Real Estate">Real Estate</label>
								</div>

								<div class="form-check ">
									<input class="form-check-input" type="checkbox" name="formCheckInput" id="Car Dealership" value="Car Dealership">
									<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name" for="Car Dealership">Car Dealership</label>
								</div>
							</div>
						</div>
					</div>

					<div class="filter-col rounded rounded-3">
						<div class="d-flex align-items-center">
							<div class="h4 m-0">Filter</div>
							<a class="h6 clear-all-btn m-0 ms-auto" href="#" id="clearAllBtn"> Clear All </a>
						</div>
						<div class="pt-1 col-5 bg-opacity-10 bg-primary rounded-pill"></div>
						<div class="mt-2">
							<div class="h6 m-0">By Type</div>
							<div id="tempTypeInputs">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="typeFormCheckInput" value="Inbound" id="Inbound">
									<label class="form-check-label h6 m-0 fw-semibold text-sm type-name" for="Inbound">Inbound</label>
								</div>

								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="typeFormCheckInput" value="Outbound" id="Outbound">
									<label class="form-check-label h6 m-0 fw-semibold text-sm type-name" for="Outbound">Outbound</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Example Card -->
			<template id="indstryFormCheckInput">
				<div class="form-check ">
					<input class="form-check-input" type="checkbox" name="formCheckInput" id="Industry1"
						   value="Industry" />
					<label class="form-check-label h6 m-0 fw-semibold text-sm industry-name"
						   for="Industry1">Industry</label>
				</div>
			</template>
			<template id="typeFormCheckInput">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="typeFormCheckInput" value="type" />
					<label class="form-check-label h6 m-0 fw-semibold text-sm type-name"
						   for="typeFormCheckInput">Type</label>
				</div>
			</template>
			<template id="templateGridItem">
				<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Recruitment">
					<div class="card template-card h-100">
						<div class="card-body d-flex flex-column">
							<div class="d-flex justify-content-between mb-2">
								<span class="badge industry-badge">Recruitment</span>
								<span class="badge price bg-success">Free</span>
							</div>
							<h5 class="fw-semibold">Talent Scout</h5>
							<p class="text-muted small mb-2 text-content">Professional Voice AI Talent Scout for
								your recruiting company, specializing in pre-...</p>

							<div class="mt-auto mb-2 d-flex align-items-center gap-2">
								<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
								<div class="tags d-flex">
								</div>
							</div>

							<div class="d-flex justify-content-between align-items-center pt-2 border-top">
								<div class="text-muted small">
									<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
									<span class="downloads">30</span>
								</div>
								<button class="theme-btn templateModal">See Detail</button>
							</div>
						</div>
					</div>
				</div>
			</template>
			<div class="p-2">
				<div id="templateGrid" class="row g-3">
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Healthcare"
						 data-id="68528b32595e0ebbd6e22fd6" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Healthcare</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Receptionist</h5>
								<p class="text-muted small mb-2 text-content">Meet Jessica, an AI assistant for your Healthcare company, dedicated to streamlining appointment scheduling and improving patient access to healthcare services. Her primary role is to assist callers with booking appointments, confirming necessary details, and connecting patients with their preferred physicians at the right office. Jessica ensures the entire process is efficient and easy while maintaining a professional and supportive tone to enhance the patient experience.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Receptionist</span><span class="hashtag">Real-Time
										Booking</span><span class="hashtag">Inbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">10</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Education"
						 data-id="68528b32595e0ebbd6e22fd3" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Education</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">College Receptionist</h5>
								<p class="text-muted small mb-2 text-content">Meet Brian, the dedicated College AI Receptionist for your college or school. As a friendly and knowledgeable virtual assistant, Brian ensures every caller gets the help they need by routing them to the right department, answering general inquiries, and providing key information about the college’s programs, services, and events. He is designed to create a seamless caller experience, offering quick responses to common questions about admissions, financial aid, campus life, and more.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Receptionist</span><span class="hashtag">Inbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Recruitment"
						 data-id="68528b32595e0ebbd6e22fd0" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Recruitment</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Talent Scout</h5>
								<p class="text-muted small mb-2 text-content">Meet Taylor, a professional Voice AI Talent Scout for your recruiting company, specializing in engaging candidates, assessing qualifications, and scheduling discovery calls. Taylor creates a personalized experience by providing key role details, understanding candidates’ goals, and aligning opportunities with their skills. With a professional yet conversational tone, Taylor streamlines the recruitment process, ensuring every interaction highlights the candidate’s value while connecting them to career opportunities that fit their aspirations.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span
																							 class="hashtag">Pre-screening</span><span class="hashtag">Outbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">34</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Solar"
						 data-id="68528b32595e0ebbd6e22fcd" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Solar</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Utility Scout</h5>
								<p class="text-muted small mb-2 text-content">Meet Timmy, a professional Voice AI Utility Scout for your solar company, specializing in engaging homeowners interested in solar energy, qualifying them based on property details and energy needs, and scheduling personalized consultations with solar consultants. Timmy builds trust by addressing questions, highlighting solar benefits, and ensuring prospects feel confident about exploring sustainable energy solutions. From discussing savings potential to answering financing questions, Timmy connects clients with tailored solar options for a seamless and informative experience.
								</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span class="hashtag">Real-Time
										Booking</span><span class="hashtag">Outbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">4</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Mortgage"
						 data-id="68528b32595e0ebbd6e22fca" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Mortgage</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Lead Qualifier</h5>
								<p class="text-muted small mb-2 text-content">Meet Emma, an AI representative for your mortgage company. Her primary role is to reconnect with potential clients who have previously shown interest in a mortgage loan. With a friendly, conversational style, she's here to gather information about the lead, engage them with the company’s offerings, and, if they’re ready, schedule an appointment with a specialist to further discuss options.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span class="hashtag">Lead
										Qualification</span><span class="hashtag">Outbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">4</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Hospitality"
						 data-id="68528b32595e0ebbd6e22fc7" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Hospitality</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Demand Generator</h5>
								<p class="text-muted small mb-2 text-content">Meet Riley, an AI representative for your events, dedicated to ensuring maximum event attendance and participant engagement. Her primary role is to contact registered attendees, reminding them of critical event details such as time, location, and agenda while confirming their attendance. Riley also gathers updated preferences, like dietary restrictions or session selections, and answers common event-related questions. By offering personalized recommendations, such as nearby accommodations or transportation options. Riley’s proactive outreach helps organizers reduce no-shows, foster excitement, and create a seamless experience for all participants.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead #Pre-screening</span><span class="hashtag">Appt.
										Reminder</span><span class="hashtag">Outbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">2</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Insurance"
						 data-id="68528b32595e0ebbd6e22fc4" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Insurance</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Policy Renewal/Upselling</h5>
								<p class="text-muted small mb-2 text-content">Meet Sandy, an AI representative for
									your Life Insurance company. Her primary role is to assist clients with renewing
									their current policies and introducing them to upgraded options or new coverage
									benefits. With a knowledgeable and approachable demeanor, Sandy ensures that
									clients understand their policies, answers any questions, and helps them secure
									the best solutions for their needs.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Reactivation</span><span
																							class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">2</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Real Estate"
						 data-id="68528b32595e0ebbd6e22fc1" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Real Estate</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Lead Reactivation</h5>
								<p class="text-muted small mb-2 text-content">Meet Jessica, an AI assistant
									dedicated to real estate seller engagement. Jessica’s primary objective is to
									gather essential details about properties and their owners’ goals, whether for
									selling or renting. Through structured conversations, Jessica provides real
									estate teams with the insights they need to tailor their approach.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span class="hashtag">Appt.
										Reminder</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">5</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Car Dealership"
						 data-id="68528b32595e0ebbd6e22fbe" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Car Dealership</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Service Reminder</h5>
								<p class="text-muted small mb-2 text-content">Meet Luna, the Voice AI Car Service
									Reminder Specialist for your car dealership. Designed to ensure customers never
									miss a critical maintenance appointment, Luna provides personalized reminders,
									schedules or reschedules services, and answers inquiries about additional
									automotive care. With a friendly yet professional tone, Luna confirms key
									details like service type, date, and time, while also suggesting other essential
									services to keep vehicles in top condition. By streamlining the scheduling
									process and enhancing customer engagement, Luna helps AutoCare Plus deliver a
									seamless and stress-free experience for every car owner.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span class="hashtag">Appt.
										Reminder</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">2</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Real Estate"
						 data-id="686fa8e05fa06cceb00c925b" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Real Estate</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Lead Qualification · Buyer</h5>
								<p class="text-muted small mb-2 text-content">Meet Paul, an AI assistant designed
									for real estate lead qualification. Paul's primary objective is to identify the
									preferences and requirements of potential buyers or renters through professional
									conversations. By collecting and organizing key details, Paul supports real
									estate teams in creating personalized client experiences.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span class="hashtag">Lead
										Qualification</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Recruitment"
						 data-id="686fa8e05fa06cceb00c925a" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Recruitment</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Recruiting Appointment Scheduler</h5>
								<p class="text-muted small mb-2 text-content">Meet Drew, an AI Recruiter for your
									company. His primary role is to conduct pre-qualification interviews, assessing
									candidates' compatibility with specific job openings based on their experience,
									skills, and career goals. With a professional yet approachable tone, Drew guides
									candidates through the process, addressing their questions, gathering essential
									details, and providing clear next steps. Whether discussing compensation,
									exploring career opportunities, or scheduling follow-ups, Drew ensures a
									seamless recruitment experience tailored to the needs of both candidates and
									employers.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Pre-screening</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Recruitment"
						 data-id="686fa8e05fa06cceb00c9259" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Recruitment</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Appointment Reminder</h5>
								<p class="text-muted small mb-2 text-content">Meet Adam, an AI assistant for candidate engagement. Adam’s primary role is to provide timely follow-ups on job applications, ensuring candidates remain informed and engaged. His approach fosters trust and maintains interest in the hiring process.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Pre-screening</span><span class="hashtag">Outbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">1</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Recruitment"
						 data-id="686fa8e05fa06cceb00c9258" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Recruitment</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Interview Scheduler</h5>
								<p class="text-muted small mb-2 text-content">Say hello to Beatrice, a virtual
									assistant for managing interview timelines. Beatrice handles the complexities of
									aligning availability between candidates and hiring managers. This tool
									guarantees a hassle-free scheduling experience for recruitment teams.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Pre-screening</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">1</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Car Dealership"
						 data-id="686fa8e05fa06cceb00c9257" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Car Dealership</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Special Offers and Promo Up-Sell</h5>
								<p class="text-muted small mb-2 text-content">Introducing Arthor, a virtual
									assistant for promotional campaigns. Arthor's role is to inform customers about
									exciting deals and exclusive services available at the dealership. His outreach
									ensures customers are always aware of the latest opportunities.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span
																							 class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Real Estate"
						 data-id="686fa8e05fa06cceb00c9256" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Real Estate</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Property Reconnector</h5>
								<p class="text-muted small mb-2 text-content">Meet Alex, an AI representative for
									your Real Estate company. His primary role is to reconnect with dormant leads
									who have previously expressed interest in real estate opportunities. With a
									friendly yet assertive conversational style, he gathers information about the
									lead's property goals, shares Elite Realty's exclusive offerings, and seamlessly
									schedules a meeting with a real estate professional to help them explore their
									options.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span class="hashtag">Real-Time
										Booking</span><span class="hashtag">Pre-screening</span><span
																									  class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">2</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Real Estate"
						 data-id="686fa8e05fa06cceb00c9255" data-type="Outbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Real Estate</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Sales Appointment Scheduler</h5>
								<p class="text-muted small mb-2 text-content">Introducing Allen, a virtual assistant
									committed to helping homeowners sell their properties. His main role is to
									initiate conversations, provide tailored advice, and arrange valuation
									appointments that fit the seller’s needs. With a focus on clarity and
									efficiency, Allen supports homeowners every step of the way.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span class="hashtag">Real-Time
										Booking</span><span class="hashtag">Outbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Car Dealership"
						 data-id="686fa8e05fa06cceb00c9254" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Car Dealership</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Customer Support</h5>
								<p class="text-muted small mb-2 text-content">Introducing Samantha, an AI assistant
									for automotive dealership customer support.Samantha specializes in addressing
									customer concerns, from scheduling service appointments to answering
									sales-related queries.</p>

								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Receptionist</span><span class="hashtag">Inbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Insurance"
						 data-id="686fa8e05fa06cceb00c9253" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Insurance</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Customer Support</h5>
								<p class="text-muted small mb-2 text-content">Meet Sophie, an AI representative for your Insurance Company, specializing in assisting customers with policy inquiries, support questions, and lead qualification. With a friendly, conversational tone, she handles everything from explaining benefits to confirming claim statuses while ensuring a smooth, efficient experience. Sophie gathers essential details, schedules appointments, and connects clients with the right resources to meet their insurance needs.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Lead Qualification</span><span class="hashtag">Real-Time
										Booking</span><span class="hashtag">Receptionist</span><span
																									 class="hashtag">Inbound</span>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Hospitality"
						 data-id="686fa8e05fa06cceb00c9252" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Hospitality</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Restaurant Appointment Scheduler</h5>
								<p class="text-muted small mb-2 text-content">Meet Laura, an AI assistant for Gourmet Table, a fine dining restaurant. Her primary role is to assist callers in scheduling reservations, managing availability, and handling special requests to ensure a seamless dining experience. With a warm and efficient tone, Laura gathers the necessary details, confirms bookings, and leaves guests excited for their upcoming visit.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Receptionist</span><span class="hashtag">Inbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Insurance"
						 data-id="686fa8e05fa06cceb00c9251" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Insurance</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Claims Receptionist</h5>
								<p class="text-muted small mb-2 text-content">Meet Beto, an AI assistant for your insurance company, specializing in handling claims and policy-related inquiries. His primary role is to verify caller identity, retrieve policy or claim information, and provide accurate updates to clients. With a friendly yet professional demeanor, Beto ensures customers receive clear answers and, when needed, facilitates smooth connections with representatives for further assistance.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Receptionist</span><span class="hashtag">Inbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3 template-card-wrapper" data-industry="Mortgage"
						 data-id="686fa8e05fa06cceb00c9250" data-type="Inbound">
						<div class="card template-card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between mb-2">
									<span class="badge industry-badge">Mortgage</span>
									<span class="badge price bg-success">Free</span>
								</div>
								<h5 class="fw-semibold">Mortgage Receptionist</h5>
								<p class="text-muted small mb-2 text-content">Meet Michael, an AI assistant for your mortgage company, committed to supporting clients with their home loan needs. His primary role is to gather essential caller details, guide users through pre-approval or account-related inquiries, and provide clear next steps for loan applications. Michael ensures an efficient and seamless process, helping clients navigate mortgage options confidently and effectively.</p>
								<div class="mt-auto mb-2 d-flex align-items-center gap-2">
									<img src="<?php echo esc_url( get_theme_file_uri('assets/img/tag.svg') ); ?>" alt="Tag Icon" width="20" height="20"/>
									<div class="tags d-flex">
										<span class="hashtag">Real-Time Booking</span><span
																							class="hashtag">Receptionist</span><span class="hashtag">Inbound</span>
									</div>
								</div>

								<div class="d-flex justify-content-between align-items-center pt-2 border-top">
									<div class="text-muted small">
										<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/download.svg')); ?>" alt="Download Icon" width="20" height="20"/>
										<span class="downloads">0</span>
									</div>
									<button class="theme-btn templateModal">See Detail</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>

<!-- Modal -->
<div id="templateModal" class="fade modal">
	<div class="modal-dialog modal-lg modal-dialog-centered">

		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalToggleLabel"> </h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<p id="templateText"></p>
				<div class="details-grid">
					<div><strong>Industry:</strong> <span id="templateIndustry"></span></div>
					<div><strong>Language:</strong> <span id="templateLanguage"></span></div>
					<div><strong>Type:</strong> <span id="templateType"></span></div>
					<div><strong>Voice:</strong> <span id="templateVoice"></span></div>
				</div>
				<div class="h4">Actions included</div>
				<div id="templateActions" class="actions-wrap"></div>
				<div id="templateDetails" class="mt-3"></div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>