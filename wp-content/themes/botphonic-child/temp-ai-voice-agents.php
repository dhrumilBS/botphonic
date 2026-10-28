<?php
/*
Template Name: Ai Voice Agents
*/

/**
 * AI voice agent template library.
 *
 * Templates load live from the Botphonic API (assets/js/ai-voice-agents.js).
 * The cards below are the server-rendered fallback: they show before the API
 * answers, without JavaScript, and if the API is unreachable. The same data is
 * embedded as JSON so search, filters and details still work in that case.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpv_api = 'https://api.botphonic.ai/api/template?perPage=100';
$bpv_register = 'https://app.botphonic.ai/register/';
$bpv_demo = 'https://calendly.com/contact-botphonic/product-discovery';

// Fallback library, used until the API answers (and if it is unreachable). Same fields the API maps to.
$bpv_fallback = array(
	array('id' => '68528b32595e0ebbd6e22fd6', 'industry' => 'Healthcare', 'type' => 'Inbound', 'title' => 'Receptionist', 'downloads' => 10, 'tags' => array('Receptionist', 'Real-Time Booking'), 'text' => 'Meet Jessica, an AI assistant for your Healthcare company, dedicated to streamlining appointment scheduling and improving patient access to healthcare services. Her primary role is to assist callers with booking appointments, confirming necessary details, and connecting patients with their preferred physicians at the right office. Jessica ensures the entire process is efficient and easy while maintaining a professional and supportive tone to enhance the patient experience.'),
	array('id' => '68528b32595e0ebbd6e22fd3', 'industry' => 'Education', 'type' => 'Inbound', 'title' => 'College Receptionist', 'downloads' => 0, 'tags' => array('Receptionist'), 'text' => 'Meet Brian, the dedicated College AI Receptionist for your college or school. As a friendly and knowledgeable virtual assistant, Brian ensures every caller gets the help they need by routing them to the right department, answering general inquiries, and providing key information about the college’s programs, services, and events. He is designed to create a seamless caller experience, offering quick responses to common questions about admissions, financial aid, campus life, and more.'),
	array('id' => '68528b32595e0ebbd6e22fd0', 'industry' => 'Recruitment', 'type' => 'Outbound', 'title' => 'Talent Scout', 'downloads' => 34, 'tags' => array('Lead Qualification', 'Pre-screening'), 'text' => 'Meet Taylor, a professional Voice AI Talent Scout for your recruiting company, specializing in engaging candidates, assessing qualifications, and scheduling discovery calls. Taylor creates a personalized experience by providing key role details, understanding candidates’ goals, and aligning opportunities with their skills. With a professional yet conversational tone, Taylor streamlines the recruitment process, ensuring every interaction highlights the candidate’s value while connecting them to career opportunities that fit their aspirations.'),
	array('id' => '68528b32595e0ebbd6e22fcd', 'industry' => 'Solar', 'type' => 'Outbound', 'title' => 'Utility Scout', 'downloads' => 4, 'tags' => array('Lead Qualification', 'Real-Time Booking'), 'text' => 'Meet Timmy, a professional Voice AI Utility Scout for your solar company, specializing in engaging homeowners interested in solar energy, qualifying them based on property details and energy needs, and scheduling personalized consultations with solar consultants. Timmy builds trust by addressing questions, highlighting solar benefits, and ensuring prospects feel confident about exploring sustainable energy solutions. From discussing savings potential to answering financing questions, Timmy connects clients with tailored solar options for a seamless and informative experience.'),
	array('id' => '68528b32595e0ebbd6e22fca', 'industry' => 'Mortgage', 'type' => 'Outbound', 'title' => 'Lead Qualifier', 'downloads' => 4, 'tags' => array('Real-Time Booking', 'Lead Qualification'), 'text' => 'Meet Emma, an AI representative for your mortgage company. Her primary role is to reconnect with potential clients who have previously shown interest in a mortgage loan. With a friendly, conversational style, she\'s here to gather information about the lead, engage them with the company’s offerings, and, if they’re ready, schedule an appointment with a specialist to further discuss options.'),
	array('id' => '68528b32595e0ebbd6e22fc7', 'industry' => 'Hospitality', 'type' => 'Outbound', 'title' => 'Demand Generator', 'downloads' => 2, 'tags' => array('Lead #Pre-screening', 'Appt. Reminder'), 'text' => 'Meet Riley, an AI representative for your events, dedicated to ensuring maximum event attendance and participant engagement. Her primary role is to contact registered attendees, reminding them of critical event details such as time, location, and agenda while confirming their attendance. Riley also gathers updated preferences, like dietary restrictions or session selections, and answers common event-related questions. By offering personalized recommendations, such as nearby accommodations or transportation options. Riley’s proactive outreach helps organizers reduce no-shows, foster excitement, and create a seamless experience for all participants.'),
	array('id' => '68528b32595e0ebbd6e22fc4', 'industry' => 'Insurance', 'type' => 'Outbound', 'title' => 'Policy Renewal/Upselling', 'downloads' => 2, 'tags' => array('Lead Reactivation'), 'text' => 'Meet Sandy, an AI representative for your Life Insurance company. Her primary role is to assist clients with renewing their current policies and introducing them to upgraded options or new coverage benefits. With a knowledgeable and approachable demeanor, Sandy ensures that clients understand their policies, answers any questions, and helps them secure the best solutions for their needs.'),
	array('id' => '68528b32595e0ebbd6e22fc1', 'industry' => 'Real Estate', 'type' => 'Outbound', 'title' => 'Lead Reactivation', 'downloads' => 5, 'tags' => array('Real-Time Booking', 'Appt. Reminder'), 'text' => 'Meet Jessica, an AI assistant dedicated to real estate seller engagement. Jessica’s primary objective is to gather essential details about properties and their owners’ goals, whether for selling or renting. Through structured conversations, Jessica provides real estate teams with the insights they need to tailor their approach.'),
	array('id' => '68528b32595e0ebbd6e22fbe', 'industry' => 'Car Dealership', 'type' => 'Outbound', 'title' => 'Service Reminder', 'downloads' => 2, 'tags' => array('Real-Time Booking', 'Appt. Reminder'), 'text' => 'Meet Luna, the Voice AI Car Service Reminder Specialist for your car dealership. Designed to ensure customers never miss a critical maintenance appointment, Luna provides personalized reminders, schedules or reschedules services, and answers inquiries about additional automotive care. With a friendly yet professional tone, Luna confirms key details like service type, date, and time, while also suggesting other essential services to keep vehicles in top condition. By streamlining the scheduling process and enhancing customer engagement, Luna helps AutoCare Plus deliver a seamless and stress-free experience for every car owner.'),
	array('id' => '686fa8e05fa06cceb00c925b', 'industry' => 'Real Estate', 'type' => 'Outbound', 'title' => 'Lead Qualification · Buyer', 'downloads' => 0, 'tags' => array('Real-Time Booking', 'Lead Qualification'), 'text' => 'Meet Paul, an AI assistant designed for real estate lead qualification. Paul\'s primary objective is to identify the preferences and requirements of potential buyers or renters through professional conversations. By collecting and organizing key details, Paul supports real estate teams in creating personalized client experiences.'),
	array('id' => '686fa8e05fa06cceb00c925a', 'industry' => 'Recruitment', 'type' => 'Outbound', 'title' => 'Recruiting Appointment Scheduler', 'downloads' => 0, 'tags' => array('Real-Time Booking', 'Pre-screening'), 'text' => 'Meet Drew, an AI Recruiter for your company. His primary role is to conduct pre-qualification interviews, assessing candidates\' compatibility with specific job openings based on their experience, skills, and career goals. With a professional yet approachable tone, Drew guides candidates through the process, addressing their questions, gathering essential details, and providing clear next steps. Whether discussing compensation, exploring career opportunities, or scheduling follow-ups, Drew ensures a seamless recruitment experience tailored to the needs of both candidates and employers.'),
	array('id' => '686fa8e05fa06cceb00c9259', 'industry' => 'Recruitment', 'type' => 'Outbound', 'title' => 'Appointment Reminder', 'downloads' => 1, 'tags' => array('Real-Time Booking', 'Pre-screening'), 'text' => 'Meet Adam, an AI assistant for candidate engagement. Adam’s primary role is to provide timely follow-ups on job applications, ensuring candidates remain informed and engaged. His approach fosters trust and maintains interest in the hiring process.'),
	array('id' => '686fa8e05fa06cceb00c9258', 'industry' => 'Recruitment', 'type' => 'Outbound', 'title' => 'Interview Scheduler', 'downloads' => 1, 'tags' => array('Real-Time Booking', 'Pre-screening'), 'text' => 'Say hello to Beatrice, a virtual assistant for managing interview timelines. Beatrice handles the complexities of aligning availability between candidates and hiring managers. This tool guarantees a hassle-free scheduling experience for recruitment teams.'),
	array('id' => '686fa8e05fa06cceb00c9257', 'industry' => 'Car Dealership', 'type' => 'Outbound', 'title' => 'Special Offers and Promo Up-Sell', 'downloads' => 0, 'tags' => array('Lead Qualification'), 'text' => 'Introducing Arthor, a virtual assistant for promotional campaigns. Arthor\'s role is to inform customers about exciting deals and exclusive services available at the dealership. His outreach ensures customers are always aware of the latest opportunities.'),
	array('id' => '686fa8e05fa06cceb00c9256', 'industry' => 'Real Estate', 'type' => 'Outbound', 'title' => 'Property Reconnector', 'downloads' => 2, 'tags' => array('Lead Qualification', 'Real-Time Booking', 'Pre-screening'), 'text' => 'Meet Alex, an AI representative for your Real Estate company. His primary role is to reconnect with dormant leads who have previously expressed interest in real estate opportunities. With a friendly yet assertive conversational style, he gathers information about the lead\'s property goals, shares Elite Realty\'s exclusive offerings, and seamlessly schedules a meeting with a real estate professional to help them explore their options.'),
	array('id' => '686fa8e05fa06cceb00c9255', 'industry' => 'Real Estate', 'type' => 'Outbound', 'title' => 'Sales Appointment Scheduler', 'downloads' => 0, 'tags' => array('Lead Qualification', 'Real-Time Booking'), 'text' => 'Introducing Allen, a virtual assistant committed to helping homeowners sell their properties. His main role is to initiate conversations, provide tailored advice, and arrange valuation appointments that fit the seller’s needs. With a focus on clarity and efficiency, Allen supports homeowners every step of the way.'),
	array('id' => '686fa8e05fa06cceb00c9254', 'industry' => 'Car Dealership', 'type' => 'Inbound', 'title' => 'Customer Support', 'downloads' => 0, 'tags' => array('Real-Time Booking', 'Receptionist'), 'text' => 'Introducing Samantha, an AI assistant for automotive dealership customer support.Samantha specializes in addressing customer concerns, from scheduling service appointments to answering sales-related queries.'),
	array('id' => '686fa8e05fa06cceb00c9253', 'industry' => 'Insurance', 'type' => 'Inbound', 'title' => 'Customer Support', 'downloads' => 0, 'tags' => array('Lead Qualification', 'Real-Time Booking', 'Receptionist'), 'text' => 'Meet Sophie, an AI representative for your Insurance Company, specializing in assisting customers with policy inquiries, support questions, and lead qualification. With a friendly, conversational tone, she handles everything from explaining benefits to confirming claim statuses while ensuring a smooth, efficient experience. Sophie gathers essential details, schedules appointments, and connects clients with the right resources to meet their insurance needs.'),
	array('id' => '686fa8e05fa06cceb00c9252', 'industry' => 'Hospitality', 'type' => 'Inbound', 'title' => 'Restaurant Appointment Scheduler', 'downloads' => 0, 'tags' => array('Real-Time Booking', 'Receptionist'), 'text' => 'Meet Laura, an AI assistant for Gourmet Table, a fine dining restaurant. Her primary role is to assist callers in scheduling reservations, managing availability, and handling special requests to ensure a seamless dining experience. With a warm and efficient tone, Laura gathers the necessary details, confirms bookings, and leaves guests excited for their upcoming visit.'),
	array('id' => '686fa8e05fa06cceb00c9251', 'industry' => 'Insurance', 'type' => 'Inbound', 'title' => 'Claims Receptionist', 'downloads' => 0, 'tags' => array('Receptionist'), 'text' => 'Meet Beto, an AI assistant for your insurance company, specializing in handling claims and policy-related inquiries. His primary role is to verify caller identity, retrieve policy or claim information, and provide accurate updates to clients. With a friendly yet professional demeanor, Beto ensures customers receive clear answers and, when needed, facilitates smooth connections with representatives for further assistance.'),
	array('id' => '686fa8e05fa06cceb00c9250', 'industry' => 'Mortgage', 'type' => 'Inbound', 'title' => 'Mortgage Receptionist', 'downloads' => 0, 'tags' => array('Real-Time Booking', 'Receptionist'), 'text' => 'Meet Michael, an AI assistant for your mortgage company, committed to supporting clients with their home loan needs. His primary role is to gather essential caller details, guide users through pre-approval or account-related inquiries, and provide clear next steps for loan applications. Michael ensures an efficient and seamless process, helping clients navigate mortgage options confidently and effectively.'),
);

$bpv_icons = array(
	'inbound' => '<path d="M16 2v6h6"/><path d="m22 2-6 6"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
	'outbound' => '<path d="M22 8V2h-6"/><path d="m16 8 6-6"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
	'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
	'filter' => '<path d="M3 5h18M6 12h12M10 19h4"/>',
	'download' => '<path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>',
	'close' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
	'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	'empty' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path d="M8.5 11h5"/>',
);
$bpv_icon = static function ($name, $size = 18) use ($bpv_icons) {
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $bpv_icons[$name] . '</svg>';
};

// One card markup for the server-rendered fallback and the JS <template>.
$bpv_card = static function ($agent) use ($bpv_icon) {
	$type = strtolower($agent['type']) === 'inbound' ? 'inbound' : 'outbound';
	ob_start();
?>
	<li class="bpv-card" data-id="<?php echo esc_attr($agent['id']); ?>" data-industry="<?php echo esc_attr($agent['industry']); ?>" data-type="<?php echo esc_attr($agent['type']); ?>">
		<div class="bpv-card__top">
			<span class="bpv-type bpv-type--<?php echo esc_attr($type); ?>" data-f="typechip">
				<span class="bpv-type__icon bpv-type__icon--inbound"><?php echo $bpv_icon('inbound', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																		?></span>
				<span class="bpv-type__icon bpv-type__icon--outbound"><?php echo $bpv_icon('outbound', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																		?></span>
				<span data-f="type"><?php echo esc_html($agent['type']); ?></span>
			</span>
			<span class="bpv-price" data-f="price">Free</span>
		</div>
		<h3 class="bpv-card__title" data-f="title"><?php echo esc_html($agent['title']); ?></h3>
		<p class="bpv-card__industry" data-f="industry"><?php echo esc_html($agent['industry']); ?></p>
		<p class="bpv-card__text" data-f="text"><?php echo esc_html($agent['text']); ?></p>
		<ul class="bpv-tags" data-f="tags" aria-label="Tags">
			<?php foreach ($agent['tags'] as $tag) : ?>
				<li><?php echo esc_html($tag); ?></li>
			<?php endforeach; ?>
		</ul>
		<div class="bpv-card__foot">
			<span class="bpv-downloads"><?php echo $bpv_icon('download', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
										?><span data-f="downloads"><?php echo esc_html(number_format_i18n($agent['downloads'])); ?></span><span class="screen-reader-text"> downloads</span></span>
			<button type="button" class="bpv-btn bpv-btn--card" data-bpv-open>View details<span class="screen-reader-text" data-f="srtitle">: <?php echo esc_html($agent['title']); ?></span></button>
		</div>
	</li>
<?php
	return ob_get_clean();
};

// Filter options with counts, from the fallback data (rebuilt from live data in JS).
$bpv_count_by = static function ($key) use ($bpv_fallback) {
	$counts = array_count_values(array_column($bpv_fallback, $key));
	ksort($counts);
	return $counts;
};
$bpv_types = $bpv_count_by('type');
$bpv_industries = $bpv_count_by('industry');

get_header();
?>

<div id="bp-voice-agents" class="bpv no-js" data-api="<?php echo esc_url($bpv_api); ?>">
	<main>
		<section class="bpv-hero" aria-labelledby="bpv-title">
			<div class="bpv-wrap">
				<nav class="bpv-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<span aria-current="page">AI voice agents</span>
				</nav>
				<p class="bpv-kicker">AI voice agent templates</p>
				<h1 id="bpv-title">Launch an AI voice agent from a ready-made template</h1>
				<p class="bpv-lede">Pick a template built for your industry, see exactly what it handles on a call, and go live in minutes. Receptionists, lead qualifiers, reminders and follow-ups, ready to use.</p>

				<form class="bpv-search bpv-js-only" role="search" data-bpv-search-form>
					<label class="screen-reader-text" for="bpv-search">Search templates</label>
					<?php echo $bpv_icon('search', 20); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
					?>
					<input id="bpv-search" type="search" placeholder="Search templates, e.g. receptionist, booking, real estate" autocomplete="off" data-bpv-search>
				</form>

				<ul class="bpv-stats" aria-label="Template library at a glance">
					<li><strong data-bpv-stat="templates"><?php echo esc_html(number_format_i18n(count($bpv_fallback))); ?></strong> templates</li>
					<li><strong data-bpv-stat="industries"><?php echo esc_html(number_format_i18n(count($bpv_industries))); ?></strong> industries</li>
					<li><strong>Inbound</strong> &amp; outbound</li>
					<li><strong>Free</strong> to use</li>
				</ul>
			</div>
		</section>

		<section class="bpv-library" aria-labelledby="bpv-library-title">
			<div class="bpv-wrap bpv-layout">
				<h2 id="bpv-library-title" class="screen-reader-text">Template library</h2>

				<aside class="bpv-filters bpv-js-only" aria-label="Filter templates">
					<details class="bpv-filters__panel" open data-bpv-filters-panel>
						<summary class="bpv-filters__toggle">
							<?php echo $bpv_icon('filter', 18); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
							?>
							Filters <span class="bpv-filters__active" data-bpv-active-count hidden></span>
						</summary>
						<div class="bpv-filters__body">
							<div class="bpv-filters__head">
								<p class="bpv-filters__title">Filters</p>
								<button type="button" class="bpv-link" data-bpv-clear>Clear all</button>
							</div>

							<fieldset class="bpv-group">
								<legend>Call type</legend>
								<div data-bpv-options="type">
									<?php foreach ($bpv_types as $bpv_value => $bpv_count) : ?>
										<label class="bpv-option"><input type="checkbox" value="<?php echo esc_attr($bpv_value); ?>"><span class="bpv-option__label"><?php echo esc_html($bpv_value); ?></span><span class="bpv-option__count"><?php echo esc_html($bpv_count); ?></span></label>
									<?php endforeach; ?>
								</div>
							</fieldset>

							<fieldset class="bpv-group">
								<legend>Industry</legend>
								<div data-bpv-options="industry">
									<?php foreach ($bpv_industries as $bpv_value => $bpv_count) : ?>
										<label class="bpv-option"><input type="checkbox" value="<?php echo esc_attr($bpv_value); ?>"><span class="bpv-option__label"><?php echo esc_html($bpv_value); ?></span><span class="bpv-option__count"><?php echo esc_html($bpv_count); ?></span></label>
									<?php endforeach; ?>
								</div>
							</fieldset>
						</div>
					</details>
				</aside>

				<div class="bpv-results">
					<div class="bpv-toolbar bpv-js-only">
						<p class="bpv-count" aria-live="polite" data-bpv-count><?php echo esc_html(sprintf('%s templates', number_format_i18n(count($bpv_fallback)))); ?></p>
						<label class="bpv-sort">
							<span>Sort by</span>
							<select data-bpv-sort>
								<option value="featured">Featured</option>
								<option value="popular">Most downloaded</option>
								<option value="name">Name, A to Z</option>
							</select>
						</label>
					</div>

					<ul class="bpv-grid" data-bpv-grid>
						<?php
						foreach ($bpv_fallback as $bpv_agent) {
							echo $bpv_card($bpv_agent); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside $bpv_card.
						}
						?>
					</ul>

					<div class="bpv-empty" data-bpv-empty hidden>
						<span class="bpv-empty__icon"><?php echo $bpv_icon('empty', 28); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
														?></span>
						<p class="bpv-empty__title">No templates match your filters</p>
						<p>Try another search, or clear the filters to see every template.</p>
						<button type="button" class="bpv-btn bpv-btn--outline" data-bpv-clear>Clear all filters</button>
					</div>
				</div>
			</div>
		</section>

		<section class="bpv-section" aria-labelledby="bpv-cta-title">
			<div class="bpv-wrap">
				<div class="bpv-cta">
					<div>
						<h2 id="bpv-cta-title">Need an agent built around your own calls?</h2>
						<p>Start from a blank agent in Agent Studio, no code needed, or book a demo and we’ll help you shape one for your use case.</p>
					</div>
					<div class="bpv-actions">
						<a class="bpv-btn bpv-btn--coral" href="<?php echo esc_url(home_url('/ai-agent-studio/')); ?>">Open Agent Studio<?php echo $bpv_icon('arrow', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																																		?></a>
						<a class="bpv-btn bpv-btn--ghost" href="<?php echo esc_url($bpv_demo); ?>">Book a demo</a>
					</div>
				</div>
			</div>
		</section>
	</main>

	<template id="bpv-card-template">
		<?php
		echo $bpv_card(array('id' => '', 'industry' => '', 'type' => 'Inbound', 'title' => '', 'text' => '', 'tags' => array(), 'downloads' => 0)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside $bpv_card.
		?>
	</template>

	<dialog class="bpv-modal" aria-labelledby="bpv-modal-title" data-bpv-modal>
		<div class="bpv-modal__head">
			<div class="bpv-modal__chips">
				<span class="bpv-type" data-m="typechip">
					<span class="bpv-type__icon bpv-type__icon--inbound"><?php echo $bpv_icon('inbound', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																			?></span>
					<span class="bpv-type__icon bpv-type__icon--outbound"><?php echo $bpv_icon('outbound', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																			?></span>
					<span data-m="type"></span>
				</span>
				<span class="bpv-chip" data-m="industry"></span>
			</div>
			<h2 id="bpv-modal-title" class="bpv-modal__title" data-m="title"></h2>
			<button type="button" class="bpv-modal__close" data-bpv-close aria-label="Close"><?php echo $bpv_icon('close', 20); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																								?></button>
		</div>
		<div class="bpv-modal__body">
			<p class="bpv-modal__text" data-m="text"></p>
			<dl class="bpv-details">
				<div>
					<dt>Industry</dt>
					<dd data-m="industry2"></dd>
				</div>
				<div>
					<dt>Call type</dt>
					<dd data-m="type2"></dd>
				</div>
				<div>
					<dt>Language</dt>
					<dd data-m="language"></dd>
				</div>
				<div>
					<dt>Voice</dt>
					<dd data-m="voice"></dd>
				</div>
			</dl>
			<div class="bpv-modal__actions" data-m="actionswrap">
				<h3>Actions included</h3>
				<ul class="bpv-actionlist" data-m="actions"></ul>
			</div>
		</div>
		<div class="bpv-modal__foot">
			<p class="bpv-modal__meta">
				<span class="bpv-downloads"><?php echo $bpv_icon('download', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
											?><span data-m="downloads"></span>&nbsp;downloads</span>
				<span class="bpv-price" data-m="price"></span>
			</p>
			<a class="bpv-btn bpv-btn--coral" href="<?php echo esc_url($bpv_register); ?>">Use this template<?php echo $bpv_icon('arrow', 16); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. 
																											?></a>
		</div>
	</dialog>

	<script type="application/json" id="bpv-fallback-data">
		<?php echo wp_json_encode($bpv_fallback); ?>
	</script>
</div>

<?php
unset($bpv_api, $bpv_register, $bpv_demo, $bpv_fallback, $bpv_icons, $bpv_icon, $bpv_card, $bpv_count_by, $bpv_types, $bpv_industries);
get_footer();
