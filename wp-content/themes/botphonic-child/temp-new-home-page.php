<?php

/**
 * Template Name: New Home Page
 * Template Post Type: page
 * @package Botphonic
 */

defined('ABSPATH') || exit;
$bp_demo_url = defined('BOTPHONIC_LIVE_DEMO_URL')
	? BOTPHONIC_LIVE_DEMO_URL
	: 'https://app.botphonic.ai/voice-assistant';
$bp_demo_url = esc_url(apply_filters('botphonic_live_demo_url', $bp_demo_url));
get_header();
?>

<div class="bp no-js" id="bp-new-home">
	<script>
		document.getElementById('bp-new-home').classList.remove('no-js');
	</script>

	<main>
		<!-- ================= HERO ================= -->
		<section class="hero" aria-labelledby="h1">
			<div class="wrap">
				<div>
					<h1 id="h1">AI voice agents that answer, book and follow up on every business call</h1>
					<p class="lede">Botphonic picks up in under 300 ms, holds a natural conversation, books the appointment, updates your CRM and sends your team a summary. One AI phone agent for inbound answering, outbound campaigns and full call-center workloads.</p>
					<div class="cta-row">
						<a class="bp-btn bp-btn--coral" href="https://app.botphonic.ai/register/">Start 14-day free trial</a>
					</div>
					<ul class="proof">
						<li>
							<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M4 10.5l4 4 8-9" />
							</svg>Live in minutes, not weeks
						</li>
						<li>
							<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M4 10.5l4 4 8-9" />
							</svg>50+ languages
						</li>
						<li>
							<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M4 10.5l4 4 8-9" />
							</svg>HIPAA and PCI DSS ready
						</li>
					</ul>
				</div>
				<!-- Live call: the real agent, not a chat. Sizing lives in new-home-page.css. -->
				<div class="call" id="live-call">
					<iframe src="<?php echo $bp_demo_url; ?>" title="Live demo: talk to the Botphonic AI voice agent" allow="microphone; autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe>
				</div>
			</div>
		</section>

		<!-- ================= TRUST STRIP ================= -->
		<section class="strip" aria-labelledby="h-trust">
			<div class="wrap">
				<h2 id="h-trust">Trusted by 500+ businesses</h2>
				<div class="logo-marquee" data-logo-marquee data-speed="55">
					<ul class="logos">
						<li><img src="https://botphonic.ai/wp-content/uploads/2026/09/client-logo-Healthray.svg" alt="Healthray" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2026/09/client-logo-Superwork.svg" alt="Superwork" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2026/09/client-logo-digiuliogroup.webp" alt="Digiulio Group" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2026/09/client-logo-ashok-one.webp" alt="Ashok One" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2026/09/client-logo-jeevan-rekha-hospital.webp" alt="Jeevan Rekha Hospital" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2025/04/dark-client-img-2.png" alt="Client logo" width="140" height="34" loading="lazy"></li>
						<li><img src="https://botphonic.ai/wp-content/uploads/2025/04/dark-client-img-1.png" alt="Client logo" width="140" height="34" loading="lazy"></li>
					</ul>
				</div>
				<h3 class="stack-lbl">Works with your stack</h3>
				<ul class="stack" aria-label="Telephony and CRM integrations">
					<li><span class="pill"><img src="https://botphonic.ai/wp-content/uploads/2026/01/Twilio.svg" alt="" width="60" height="18" loading="lazy">Twilio</span></li>
					<li><span class="pill"><img src="https://botphonic.ai/wp-content/uploads/2026/01/Telnyx.svg" alt="" width="60" height="18" loading="lazy">Telnyx</span></li>
					<li><span class="pill"><img src="https://botphonic.ai/wp-content/uploads/2026/01/Plivo.svg" alt="" width="60" height="18" loading="lazy">Plivo</span></li>
					<li>
						<a class="pill" href="https://botphonic.ai/salesforce-integration/">
							<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M7 3v4M13 3v4M5 7h10v4a5 5 0 0 1-10 0V7zM10 16v2" />
							</svg>Salesforce</a>
					</li>
					<li>
						<a class="pill" href="https://botphonic.ai/hubspot-integration/">
							<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M7 3v4M13 3v4M5 7h10v4a5 5 0 0 1-10 0V7zM10 16v2" />
							</svg>HubSpot</a>
					</li>
					<li>
						<a class="pill" href="https://botphonic.ai/zoho-integration/">
							<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M7 3v4M13 3v4M5 7h10v4a5 5 0 0 1-10 0V7zM10 16v2" />
							</svg>Zoho</a>
					</li>
					<li>
						<a class="pill" href="https://botphonic.ai/whatsapp-integration/">
							<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M3 17l1.5-4A7 7 0 1 1 7 15.5L3 17z" />
							</svg>WhatsApp</a>
					</li>
					<li>
						<a class="pill" href="https://botphonic.ai/zapier-integration/">
							<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M11 2L4 11h6l-1 7 7-9h-6z" />
							</svg>Zapier</a>
					</li>
				</ul>
			</div>
		</section>

		<!-- ================= PRODUCTS ================= -->
		<section class="sec" id="products" aria-labelledby="h-products">
			<div class="wrap">
				<div class="head">
					<h2 id="h-products">One AI phone agent for every call your business takes or makes</h2>
					<p class="lede">Start with the job that hurts most. The same agent, voice and knowledge base carry across all four, so you never rebuild.</p>
				</div>
				<div class="prod">
					<article>
						<div class="glyph" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
							</svg>
						</div>
						<h3><a class="link" href="https://botphonic.ai/ai-answering-service/">AI answering service</a></h3>
						<p>Every inbound call answered on the first ring, 24/7, in the caller's language. No hold music, no voicemail, no missed leads after hours.</p>
						<ul>
							<li>Unlimited simultaneous calls</li>
							<li>Spam and robocall screening</li>
							<li>Warm transfer to a human when it matters</li>
						</ul>
					</article>
					<article>
						<div class="glyph" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<rect x="3" y="5" width="18" height="16" rx="2" />
								<path d="M8 3v4M16 3v4M3 10h18M8 15h3" />
							</svg>
						</div>
						<h3><a class="link" href="https://botphonic.ai/ai-receptionist/">AI receptionist</a></h3>
						<p>Checks live availability, books, reschedules and confirms appointments, then updates the record in your CRM or practice software.</p>
						<ul>
							<li>Google Calendar, Outlook, Calendly</li>
							<li>Reminders that cut no-shows</li>
							<li>HIPAA-ready for clinics and practices</li>
						</ul>
					</article>
					<article>
						<div class="glyph" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M4 12h12M12 6l6 6-6 6" />
								<path d="M4 5v14" />
							</svg>
						</div>
						<h3><a class="link" href="https://botphonic.ai/ai-phone-call/">Outbound AI phone agent</a></h3>
						<p>Follows up on leads within seconds of a form fill, runs reminder and renewal campaigns, and qualifies prospects before your reps pick up.</p>
						<ul>
							<li>Batch calling at any volume</li>
							<li>Branded caller ID, TCPA controls</li>
							<li>Lead scoring pushed to your CRM</li>
						</ul>
					</article>
					<article>
						<div class="glyph" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<rect x="3" y="4" width="18" height="12" rx="2" />
								<path d="M8 20h8M12 16v4M7 9h4M7 12h7" />
							</svg>
						</div>
						<h3><a class="link" href="https://botphonic.ai/ai-call-centre/">AI call center software</a></h3>
						<p>Replace or extend a staffed call center. SIP trunking into your telephony, custom workflows, analytics and human handoff in one dashboard.</p>
						<ul>
							<li>Twilio, Telnyx, Plivo or your own SIP</li>
							<li>Visual workflow builder</li>
							<li>Transcripts, sentiment, QA scoring</li>
						</ul>
					</article>
				</div>
			</div>
		</section>

		<!-- ================= CAPABILITIES ================= -->
		<section class="sec sec--tint" id="capabilities" aria-labelledby="h-cap">
			<div class="wrap">
				<div class="head">
					<h2 id="h-cap">Everything the AI voice agent does before, during and after a call</h2>
					<p class="lede">Not a call menu with a nicer voice. Each capability below is live in the platform today and controlled from one dashboard.</p>
				</div>

				<ul class="tabs" role="tablist" aria-label="Capabilities">
					<li><button role="tab" aria-selected="true" aria-controls="cap-1" id="tab-1">Call handling</button></li>
					<li><button role="tab" aria-selected="false" aria-controls="cap-2" id="tab-2">Scheduling &amp; sync</button></li>
					<li><button role="tab" aria-selected="false" aria-controls="cap-3" id="tab-3">Sales automation</button></li>
					<li><button role="tab" aria-selected="false" aria-controls="cap-4" id="tab-4">Batch calling</button></li>
					<li><button role="tab" aria-selected="false" aria-controls="cap-5" id="tab-5">Post-call actions</button></li>
					<li><button role="tab" aria-selected="false" aria-controls="cap-6" id="tab-6">Email &amp; SMS</button></li>
				</ul>

				<div class="panel is-active" role="tabpanel" id="cap-1" aria-labelledby="tab-1">
					<div>
						<h3>AI call management</h3>
						<p>The agent handles the whole conversation on inbound and outbound calls, and knows when a human should take over.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Spam and robocall screening</b><span>Detects fake numbers and automated dialers before your team hears them.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Human-like conversation</b><span>Under 300 ms response time, natural interruptions handled, 65+ voices to match your brand.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Inbound and outbound on one agent</b><span>Answers instantly and places calls from a list, a CRM trigger or a missed-call event.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Sentiment analysis</b><span>Reads caller emotion in real time and escalates frustrated callers to a person.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/01-ai-call-management.svg" alt="Botphonic AI call management dashboard showing inbound and outbound call handling" width="720" height="540" loading="lazy"></figure>
				</div>

				<div class="panel" role="tabpanel" id="cap-2" aria-labelledby="tab-2" hidden>
					<div>
						<h3>Scheduling and data sync</h3>
						<p>The AI receptionist books into live availability and writes everything back to the systems you already run.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Real-time scheduling</b><span>Finds open slots, books, reschedules and confirms while the caller is still on the line.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Multi-platform integration</b><span>Salesforce, HubSpot, Zoho, Google Calendar, Outlook, WhatsApp and 100+ tools via API or Zapier.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Low-code connectors</b><span>Ready-made connectors for the common systems, a visual workflow builder for the rest.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Conflict resolution</b><span>Applies your business rules so double bookings and stale records don't happen.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/02-scheduling-data-sync.svg" alt="Botphonic AI receptionist syncing appointments and contact data with a CRM and calendar" width="720" height="540" loading="lazy"></figure>
				</div>

				<div class="panel" role="tabpanel" id="cap-3" aria-labelledby="tab-3" hidden>
					<div>
						<h3>AI sales automation</h3>
						<p>Qualify, score and route every lead by phone, in the caller's language, before a rep spends a minute on it.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Lead qualification</b><span>Asks your qualifying questions, scores the answers and routes hot leads immediately.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Multilingual sales calls</b><span>50+ languages with mid-call switching, so one agent covers every market you sell into.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Conversion prediction</b><span>Flags which conversations are likely to close based on buyer behaviour on the call.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Live coaching for reps</b><span>Real-time prompts for your human sales team on handled-over calls.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/03-ai-sales-automation.svg" alt="Botphonic AI sales assistant qualifying and scoring inbound leads by phone" width="720" height="540" loading="lazy"></figure>
				</div>

				<div class="panel" role="tabpanel" id="cap-4" aria-labelledby="tab-4" hidden>
					<div>
						<h3>Outbound batch calling</h3>
						<p>Run compliant, high-volume outbound campaigns from the same agent that answers your inbound line.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Thousands of calls in parallel</b><span>No concurrency limits. Upload a list or trigger from your CRM.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Branded caller ID and verified numbers</b><span>Higher pickup rates and no "Scam likely" label.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>TCPA controls built in</b><span>Consent tracking, calling windows and suppression lists on every campaign.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>SIP trunking</b><span>Keep your Twilio, Telnyx, Plivo or in-house telephony. Nothing to port.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/04-outbound-batch-calling.svg" alt="Botphonic outbound AI phone agent running a batch calling campaign with live pickup and outcome tracking" width="720" height="540" loading="lazy"></figure>
				</div>

				<div class="panel" role="tabpanel" id="cap-5" aria-labelledby="tab-5" hidden>
					<div>
						<h3>Post-call actions</h3>
						<p>The call isn't finished until the record is updated and the next step is scheduled. The agent does both.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Recording and live transcription</b><span>Every call recorded and transcribed, with access controlled by role.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Call summaries</b><span>Key facts, requests and actions in a few lines, pushed to your CRM and inbox.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Feedback collection</b><span>A short satisfaction question at the end of the call, logged against the contact.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Conversation analytics</b><span>Answered calls, wait time, resolution rate, sentiment and topics across every line.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/05-post-call-actions.svg" alt="Botphonic post-call summary, transcript and CRM update after an AI voice agent call" width="720" height="540" loading="lazy"></figure>
				</div>

				<div class="panel" role="tabpanel" id="cap-6" aria-labelledby="tab-6" hidden>
					<div>
						<h3>Email and SMS follow-up</h3>
						<p>What the agent promised on the call, it sends after the call, so nothing waits on your inbox.</p>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Confirmations and reminders</b><span>Booking confirmations, reminders and reschedule links by SMS, WhatsApp or email.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Personalised follow-up</b><span>Emails written from the actual conversation, not a template.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>Automated sequences</b><span>Timed follow-ups for open leads and quotes until they answer or opt out.</span></div>
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>
								<div><b>One conversation across channels</b><span>Phone, WhatsApp, SMS and email history in a single thread per contact.</span></div>
							</li>
						</ul>
					</div>
					<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/06-email-sms-followup.svg" alt="One Botphonic AI agent handling phone, WhatsApp, SMS and email conversations in a single thread" width="720" height="540" loading="lazy"></figure>
				</div>
			</div>
		</section>

		<!-- ================= AUDIO SAMPLES ================= -->
		<section class="sec" id="listen" aria-labelledby="h-audio">
			<div class="wrap">
				<div class="head">
					<h2 id="h-audio">Hear the AI phone agent on real business calls</h2>
					<p class="lede">Six recordings, six industries. Each one shows what the agent does on the call and what your team receives afterwards.</p>
				</div>
				<ul class="audios">
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Female-voice-agent.webp" alt="Female AI voice agent for real estate lead qualification" width="56" height="56" loading="lazy">
							<div>
								<h3>Lead qualification</h3><small>Real estate · inbound · 2:41</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Real estate lead qualification call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/07/Sales-Assistant-Lead-Qualifier.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/07/Sales-Assistant-Lead-Qualifier.mp3">Download the real estate lead qualification call recording (MP3)</a>
						</audio>
						<ul>
							<li>Asks budget, area, timeline and financing status</li>
							<li>Books a viewing into the agent's calendar</li>
							<li>Scores the lead and pushes it to the CRM</li>
						</ul>
						<p class="out"><b>What you get</b>A qualified buyer with a viewing on the diary, not a missed call.</p>
					</li>
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Female-voice-agent-1.webp" alt="Female AI virtual receptionist for a healthcare clinic" width="56" height="56" loading="lazy">
							<div>
								<h3>Virtual receptionist</h3><small>Healthcare · inbound · 3:03</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Healthcare virtual receptionist call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/07/Healthcare-Receptionist.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/07/Healthcare-Receptionist.mp3">Download the healthcare virtual receptionist call recording (MP3)</a>
						</audio>
						<ul>
							<li>Verifies the patient and reason for the visit</li>
							<li>Offers the next open slot with the right clinician</li>
							<li>Sends confirmation and pre-visit instructions</li>
						</ul>
						<p class="out"><b>What you get</b>Front desk freed from the phone, HIPAA-ready record of the call.</p>
					</li>
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Male-voice-agent-2.webp" alt="Male AI voice agent booking a hotel reservation" width="56" height="56" loading="lazy">
							<div>
								<h3>Appointment booking</h3><small>Travel and hospitality · inbound · 1:45</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Hospitality booking call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/07/Customer-Support.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/07/Customer-Support.mp3">Download the hospitality booking call recording (MP3)</a>
						</audio>
						<ul>
							<li>Checks availability and quotes the rate</li>
							<li>Takes a PCI-compliant deposit</li>
							<li>Emails the confirmation while still on the call</li>
						</ul>
						<p class="out"><b>What you get</b>Bookings taken 24/7 without an overnight desk.</p>
					</li>
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Male-voice-agent-1.webp" alt="Male AI voice agent for insurance policy renewal calls" width="56" height="56" loading="lazy">
							<div>
								<h3>Policy renewal and upsell</h3><small>Insurance · outbound · 2:13</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Insurance renewal call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/08/Policy-Renewal-Upselling.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/08/Policy-Renewal-Upselling.mp3">Download the insurance renewal call recording (MP3)</a>
						</audio>
						<ul>
							<li>Calls before expiry with the renewal quote</li>
							<li>Offers a relevant add-on based on the policy</li>
							<li>Transfers to a licensed agent to close</li>
						</ul>
						<p class="out"><b>What you get</b>Renewal campaigns that run themselves, warm transfers only.</p>
					</li>
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Male-voice-agent.webp" alt="Male AI voice agent for car dealership service offers" width="56" height="56" loading="lazy">
							<div>
								<h3>Service offers and test drives</h3><small>Car dealership · outbound · 1:40</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Car dealership outbound call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/08/Special-Offers-and-Promo-Up-Sell.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/08/Special-Offers-and-Promo-Up-Sell.mp3">Download the car dealership outbound call recording (MP3)</a>
						</audio>
						<ul>
							<li>Reaches past customers with a seasonal service offer</li>
							<li>Books the service slot or a test drive</li>
							<li>Logs the outcome to the dealer CRM</li>
						</ul>
						<p class="out"><b>What you get</b>Service bays filled from your own customer list.</p>
					</li>
					<li class="audio">
						<div class="top"><img src="https://botphonic.ai/wp-content/uploads/2025/08/Female-voice-agent-2.webp" alt="Female AI voice agent screening job candidates" width="56" height="56" loading="lazy">
							<div>
								<h3>Candidate screening</h3><small>Recruitment · outbound · 1:52</small>
							</div>
						</div>
						<audio controls preload="metadata" aria-label="Recruitment screening call">
							<source src="https://botphonic.ai/wp-content/uploads/2025/08/Recruitment-Talent-Scout.mp3" type="audio/mpeg">
							<a href="https://botphonic.ai/wp-content/uploads/2025/08/Recruitment-Talent-Scout.mp3">Download the recruitment screening call recording (MP3)</a>
						</audio>
						<ul>
							<li>Confirms availability, location and salary range</li>
							<li>Asks role-specific screening questions</li>
							<li>Schedules the interview with the hiring manager</li>
						</ul>
						<p class="out"><b>What you get</b>Only screened candidates reach your recruiters' calendars.</p>
					</li>
				</ul>
			</div>
		</section>

		<!-- ================= LANGUAGES ================= -->
		<section class="sec sec--ink" id="languages" aria-labelledby="h-lang">
			<div class="wrap langs">
				<div>
					<h2 id="h-lang" style="margin-bottom:14px; color: #fff">Speaks 50+ languages, and switches the moment the caller does</h2>
					<p class="lede">One multilingual AI voice agent covers every market you sell into. It detects the caller's language on the first sentence, replies in kind, and can change language mid-call without a transfer. Indian languages are first-class, not an add-on.</p>
					<div class="lang-stats">
						<div><b>50+</b><span>Languages and regional variants</span></div>
						<div><b>65+</b><span>Natural voices, male and female, by accent</span></div>
						<div><b>Mid-call</b><span>Language switching, no hand-off</span></div>
						<div><b>1 agent</b><span>Same script, knowledge base and rules in every language</span></div>
					</div>
					<p><a class="link" href="https://botphonic.ai/multilingual-voice-ai-agents/" style="color:var(--bpl-white)">See the full language and voice list</a></p>
				</div>
				<div>
					<div class="lang-group">
						<h3>Europe and the Americas</h3>
						<ul class="chips">
							<li>English <span>US · UK · AU</span></li>
							<li>Spanish <span lang="es">Español</span></li>
							<li>Portuguese <span lang="pt">Português</span></li>
							<li>French <span lang="fr">Français</span></li>
							<li>German <span lang="de">Deutsch</span></li>
							<li>Italian <span lang="it">Italiano</span></li>
							<li>Dutch <span lang="nl">Nederlands</span></li>
							<li>Polish <span lang="pl">Polski</span></li>
							<li>Russian <span lang="ru">Русский</span></li>
							<li>Turkish <span lang="tr">Türkçe</span></li>
						</ul>
					</div>
					<div class="lang-group">
						<h3>Middle East and Asia-Pacific</h3>
						<ul class="chips">
							<li>Arabic <span lang="ar">العربية</span></li>
							<li>Mandarin <span lang="zh">中文</span></li>
							<li>Japanese <span lang="ja">日本語</span></li>
							<li>Korean <span lang="ko">한국어</span></li>
							<li>Indonesian <span lang="id">Bahasa Indonesia</span></li>
							<li>Vietnamese <span lang="vi">Tiếng Việt</span></li>
							<li>Thai <span lang="th">ไทย</span></li>
							<li>Filipino</li>
							<li class="more">+ 20 more</li>
						</ul>
					</div>
					<div class="lang-group">
						<h3>Indian languages</h3>
						<ul class="chips">
							<li>Hindi <span lang="hi">हिन्दी</span></li>
							<li>Gujarati <span lang="gu">ગુજરાતી</span></li>
							<li>Marathi <span lang="mr">मराठी</span></li>
							<li>Tamil <span lang="ta">தமிழ்</span></li>
							<li>Telugu <span lang="te">తెలుగు</span></li>
							<li>Kannada <span lang="kn">ಕನ್ನಡ</span></li>
							<li>Bengali <span lang="bn">বাংলা</span></li>
							<li>Malayalam <span lang="ml">മലയാളം</span></li>
							<li>Punjabi <span lang="pa">ਪੰਜਾਬੀ</span></li>
							<li>Urdu <span lang="ur">اردو</span></li>
							<li>Indian English</li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<!-- ================= HOW IT WORKS ================= -->
		<section class="sec sec--tint" aria-labelledby="h-how">
			<div class="wrap">
				<div class="head">
					<h2 id="h-how">How an AI voice agent handles a call on Botphonic</h2>
					<p class="lede">Four stages, from the first ring to the CRM note. You control the script, the voice and the rules at each one.</p>
				</div>
				<ol class="steps">
					<li class="step">
						<h3>Answer or dial</h3>
						<p>The agent answers inbound calls instantly or places outbound calls from a list or a CRM trigger. Spam and fake numbers are screened before a word is spoken.</p>
					</li>
					<li class="step">
						<h3>Understand the caller</h3>
						<p>Natural conversation in 50+ languages with under 300 ms response time. The agent follows your knowledge base and business logic, and detects intent and sentiment as it goes.</p>
					</li>
					<li class="step">
						<h3>Take the action</h3>
						<p>Books the slot, looks up an order, captures a payment, qualifies the lead or transfers to the right person, using live data from your systems.</p>
					</li>
					<li class="step">
						<h3>Hand over cleanly</h3>
						<p>Recording, transcript and a short summary land in your CRM and your team's inbox, with follow-up calls, texts or emails already scheduled.</p>
					</li>
				</ol>
			</div>
		</section>

		<!-- ================= INDUSTRIES ================= -->
		<section class="sec" id="industries" aria-labelledby="h-ind">
			<div class="wrap">
				<div class="head">
					<h2 id="h-ind">Built for the industries where a missed call is a lost customer</h2>
					<p class="lede">Each industry page includes a sample call, the integrations that matter and the compliance rules the agent follows.</p>
				</div>
				<ul class="inds">
					<li>
						<a href="https://botphonic.ai/healthcare/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M12 4v16M4 12h16" />
								</svg>
							</span><b>Healthcare</b><small>Patient scheduling, reminders, HIPAA-ready intake</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/real-estate/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M3 11l9-7 9 7v9H3z M9 20v-6h6v6" />
								</svg>
							</span><b>Real estate</b><small>Listing enquiries, buyer qualification, viewings</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/car-dealerships/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M3 14l2-6h14l2 6v5H3zM6 14h12M7 19v1M17 19v1" />
								</svg>
							</span><b>Car dealerships</b><small>Test drives, service bookings, follow-ups</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/home-services/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M14 4l6 6-9 9H5v-6z M12 6l6 6" />
								</svg>
							</span><b>Home services</b><small>Job bookings, quotes, emergency dispatch</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/finance/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M3 9l9-5 9 5H3zM5 9v8M12 9v8M19 9v8M3 20h18" />
								</svg>
							</span><b>Financial services</b><small>Verification, collections, policy questions</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/insurance/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z" />
								</svg>
							</span><b>Insurance</b><small>Renewals, claims status, upsell calls</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/recruitment/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<circle cx="9" cy="8" r="3" />
									<path d="M3 20a6 6 0 0 1 12 0M16 4a3 3 0 0 1 0 6M21 20a6 6 0 0 0-5-5.9" />
								</svg>
							</span><b>Recruitment</b><small>Candidate screening, interview scheduling</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/solar/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<circle cx="12" cy="12" r="4" />
									<path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M5 19l2-2M17 7l2-2" />
								</svg>
							</span><b>Solar</b><small>Lead qualification, site-visit booking</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/education/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M2 9l10-5 10 5-10 5z M6 11v5c2 2 10 2 12 0v-5" />
								</svg>
							</span><b>Education</b><small>Admissions enquiries, fee reminders</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/travel-and-hospitality/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M3 20h18M5 20v-9h14v9M9 11V7h6v4M11 20v-4h2v4" />
								</svg>
							</span><b>Travel and hospitality</b><small>Reservations, changes, concierge</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/bpo-customer-service/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<path d="M4 13a8 8 0 0 1 16 0M4 13v4a2 2 0 0 0 2 2h2v-6H4zM20 13v4a2 2 0 0 1-2 2h-2v-6h4z" />
								</svg>
							</span><b>BPO and contact centers</b><small>Overflow, after-hours, tier-1 resolution</small></a>
					</li>
					<li>
						<a href="https://botphonic.ai/agency/"><span class="ic">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
									<rect x="3" y="7" width="18" height="13" rx="2" />
									<path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18" />
								</svg>
							</span><b>Agencies</b><small>White-label AI receptionists for your clients</small></a>
					</li>
				</ul>
			</div>
		</section>

		<!-- ================= COMPARISON ================= -->
		<section class="sec sec--tint" aria-labelledby="h-cmp">
			<div class="wrap">
				<div class="head">
					<h2 id="h-cmp">AI answering service vs. human answering service vs. traditional IVR</h2>
					<p class="lede">What actually changes when an AI voice agent takes the phone.</p>
				</div>
				<div class="tbl-wrap">
					<table>
						<caption class="sr">Comparison of Botphonic AI voice agent, a human answering service and traditional IVR</caption>
						<thead>
							<tr>
								<th scope="col"></th>
								<th scope="col" class="best">Botphonic AI voice agent</th>
								<th scope="col">Human answering service</th>
								<th scope="col">Traditional IVR / voicemail</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">Time to answer</th>
								<td class="yes best">Under 1 second, every call</td>
								<td class="no">20–60 s, queues at peak</td>
								<td class="no">Instant, but callers hang up on menus</td>
							</tr>
							<tr>
								<th scope="row">Simultaneous calls</th>
								<td class="yes best">Unlimited</td>
								<td class="no">Limited by staff on shift</td>
								<td class="no">Unlimited, no resolution</td>
							</tr>
							<tr>
								<th scope="row">Books appointments</th>
								<td class="yes best">Yes, into your live calendar</td>
								<td class="no">Takes a message for callback</td>
								<td class="no">No</td>
							</tr>
							<tr>
								<th scope="row">Updates CRM</th>
								<td class="yes best">Automatically, with summary</td>
								<td class="no">Manual, if at all</td>
								<td class="no">No</td>
							</tr>
							<tr>
								<th scope="row">Languages</th>
								<td class="yes best">50+, switches mid-call</td>
								<td class="no">1–2 per agent</td>
								<td class="no">Pre-recorded only</td>
							</tr>
							<tr>
								<th scope="row">Outbound campaigns</th>
								<td class="yes best">Yes, thousands in parallel</td>
								<td class="no">Priced per agent hour</td>
								<td class="no">Robocall only</td>
							</tr>
							<tr>
								<th scope="row">Typical cost</th>
								<td class="yes best">$0.20–$0.40 per talk minute</td>
								<td class="no">$1–2 per minute, monthly minimums</td>
								<td class="no">Low, but loses the lead</td>
							</tr>
							<tr>
								<th scope="row">Setup time</th>
								<td class="yes best">Minutes</td>
								<td class="no">1–3 weeks onboarding</td>
								<td class="no">Days of menu design</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<!-- ================= PROOF BAND ================= -->
		<section class="sec sec--ink" aria-labelledby="h-proof">
			<div class="wrap band">
				<div>
					<h2 id="h-proof" style="margin-bottom:14px; color:#fff">Enterprise-grade AI call center software, without the enterprise timeline</h2>
					<p class="lede" style="margin-bottom:36px">Botphonic runs regulated, high-volume calling for healthcare groups, lenders and BPOs. The infrastructure is already in place, and a voice-AI team is on hand for setup and tuning.</p>
					<div class="nums">
						<div class="num"><b>&lt;300 ms</b><span>Response latency, so conversation feels natural</span></div>
						<div class="num"><b>99.99%</b><span>Platform uptime</span></div>
						<div class="num"><b>50+</b><span>Languages, with mid-call switching</span></div>
						<div class="num"><b>100+</b><span>Integrations via API, SIP and Zapier</span></div>
					</div>
				</div>
				<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/Enterprise-proof-band.svg" alt="Customer speaking with a Botphonic AI voice agent with under 300 ms response latency" width="600" height="450" loading="lazy"></figure>
			</div>
		</section>

		<!-- ================= COMPLIANCE ================= -->
		<section class="sec" id="compliance" aria-labelledby="h-comp">
			<div class="wrap">
				<div class="head head--center">
					<h2 id="h-comp">Compliance built for regulated calls</h2>
					<p class="lede">Healthcare, payments and outbound sales each have their own rules. The agent follows them on every call, and the platform proves it.</p>
				</div>
				<ul class="comp-badges" aria-label="Security and compliance badges">
					<li><img src="https://botphonic.ai/wp-content/uploads/2026/01/soc2.webp" alt="SOC 2 Type II ready" width="120" height="64" loading="lazy"></li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2026/01/Hipaa.webp" alt="HIPAA aligned" width="120" height="64" loading="lazy"></li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2026/04/GDPR.webp" alt="GDPR aligned" width="120" height="64" loading="lazy"></li>
				</ul>
				<ul class="comp">
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<rect x="3" y="6" width="18" height="12" rx="2" />
								<path d="M3 10h18M7 15h4" />
							</svg>
						</div>
						<h3>PCI DSS compliant payments</h3>
						<p>Capture card details on a call without them touching your recordings, transcripts or agents.</p>
					</li>
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M12 4v16M4 12h16" />
								<circle cx="12" cy="12" r="9" />
							</svg>
						</div>
						<h3>HIPAA-ready healthcare workflows</h3>
						<p>PHI handled under BAA-compatible controls for clinics, hospitals and payers, with audit logs.</p>
					</li>
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z" />
								<path d="M9 12l2 2 4-4" />
							</svg>
						</div>
						<h3>GDPR-aligned data handling</h3>
						<p>Encryption in transit and at rest, regional data residency options, retention controls and a DPA on request.</p>
					</li>
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M4 20l6-6M14 4l6 6-8 8H6v-6z" />
							</svg>
						</div>
						<h3>Continuous penetration testing</h3>
						<p>Real-world attack simulations run on a schedule, so weaknesses are found before they become incidents.</p>
					</li>
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
							</svg>
						</div>
						<h3>TCPA-compliant outbound</h3>
						<p>Consent tracking, calling-hour windows, suppression lists and opt-out handling built into every campaign.</p>
					</li>
					<li>
						<div class="ic">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<rect x="5" y="10" width="14" height="10" rx="2" />
								<path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3" />
							</svg>
						</div>
						<h3>MFA and role-based access</h3>
						<p>Decide who can hear recordings, read transcripts or change a script. Nothing moves without explicit permission.</p>
					</li>
				</ul>
				<p style="text-align:center;margin-top:28px"><a class="link" href="https://botphonic.ai/security/">Read the full security overview</a></p>
			</div>
		</section>

		<!-- ================= RESULTS + TESTIMONIALS ================= -->
		<section class="sec sec--tint" aria-labelledby="h-results">
			<div class="wrap">
				<div class="head">
					<h2 id="h-results">Results from teams that moved their phones to Botphonic</h2>
				</div>

				<div class="case">
					<div class="story">
						<div>
							<h3>Serenity sees 150% ROI after switching its call handling to Botphonic AI</h3>
							<p>Inbound answering, appointment booking and follow-up moved to the AI voice agent. Staff kept the conversations that needed a person.</p>
						</div>
						<img src="https://botphonic.ai/wp-content/uploads/2026/09/Serenity-sees-150-ROI-after-switching-its-call-handling-to-Botphonic-AI.svg" alt="Serenity team using Botphonic AI call handling" width="560" height="315" loading="lazy">
						<a class="link" href="https://botphonic.ai/success-stories/" style="color:var(--bpl-white)">Read the success story</a>
					</div>
					<div class="metrics">
						<div><b>25%</b>
							<span>Higher conversion on inbound enquiries</span>
						</div>
						<div><b>50%</b>
							<span>Shorter call handling time</span>
						</div>
						<div><b>20%</b>
							<span>Fewer human errors in bookings and records</span>
						</div>
						<div><b>15%</b>
							<span>Higher agent satisfaction</span>
						</div>
					</div>
				</div>

				<div class="quotes">
					<blockquote>
						<span class="tag">Operations</span>
						<p>Botphonic helps us handle calls instantly and never miss a customer opportunity. The AI sounds natural, works 24/7, and saves our team valuable time.</p>
						<footer>
							<span class="ini" aria-hidden="true">AV</span>
							<div><b>Alpesh Vaghasiya</b>CEO, Superworks</div>
						</footer>
					</blockquote>

					<blockquote>
						<span class="tag">Healthcare</span>
						<p>Botphonic makes handling customer calls effortless. Its AI handles routine conversations 24/7, reducing our team’s workload while keeping every caller supported.</p>
						<footer>
							<span class="ini" aria-hidden="true">KM</span>
							<div><b>Ketan Mangukiya</b>Founder &amp; CEO, Healthray</div>
						</footer>
					</blockquote>

					<blockquote>
						<span class="tag">Healthcare</span>
						<p>Botphonic feels like an extra team member. It handles calls, captures key details, and keeps our team focused on what matters.</p>
						<footer>
							<span class="ini" aria-hidden="true">DA</span>
							<div><b>Dr. Alphonsa</b>Executive Director, Ashok One Hospital</div>
						</footer>
					</blockquote>
				</div>
			</div>
		</section>

		<!-- ================= RATINGS ================= -->
		<section class="sec" aria-labelledby="h-rate">
			<div class="wrap">
				<div class="head head--center">
					<h2 id="h-rate">Rated across the major software review platforms</h2>
				</div>
				<ul class="ratings">
					<li><img src="https://botphonic.ai/wp-content/uploads/2025/12/g2-logo.webp" alt="G2" width="80" height="28" loading="lazy">
						<div class="score">5.0<small>/5</small></div>
						<div class="stars" aria-hidden="true"><svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
						</div><a href="https://www.g2.com/products/botphonic-ai-call-assistant/reviews" rel="noopener">Read reviews</a>
					</li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2025/12/capterra-1.webp" alt="Capterra" width="80" height="28" loading="lazy">
						<div class="score">4.8<small>/5</small></div>
						<div class="stars" aria-hidden="true"><svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
						</div><span style="font-size:var(--bpl-t-xs);color:var(--bpl-muted)">Capterra reviews</span>
					</li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2025/12/softwaresuggest-logo.webp" alt="SoftwareSuggest" width="80" height="28" loading="lazy">
						<div class="score">4.8<small>/5</small></div>
						<div class="stars" aria-hidden="true">
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
						</div><span style="font-size:var(--bpl-t-xs);color:var(--bpl-muted)">SoftwareSuggest reviews</span>
					</li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2025/12/trustpilot-1.webp" alt="Trustpilot" width="80" height="28" loading="lazy">
						<div class="score">4.5<small>/5</small></div>
						<div class="stars" aria-hidden="true">
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor" style="opacity:.35">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
						</div><a href="https://www.trustpilot.com/review/botphonic.ai" rel="noopener">Read reviews</a>
					</li>
					<li><img src="https://botphonic.ai/wp-content/uploads/2025/12/saasworthy.webp" alt="SaaSworthy" width="80" height="28" loading="lazy">
						<div class="score">4.2<small>/5</small></div>
						<div class="stars" aria-hidden="true">
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
							<svg viewBox="0 0 20 20" fill="currentColor" style="opacity:.35">
								<path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.9L10 14.9l-5.3 2.8 1.1-5.9L1.5 7.7l5.9-.8z" />
							</svg>
						</div><span style="font-size:var(--bpl-t-xs);color:var(--bpl-muted)">SaaSworthy reviews</span>
					</li>
				</ul>
			</div>
		</section>

		<!-- ================= PRICING ================= -->
		<section class="sec" id="pricing" aria-labelledby="h-price" style="padding-top:0">
			<div class="wrap">
				<div class="price">
					<div>
						<h2 id="h-price" style="margin-bottom:14px">Pay for talk time, not seats</h2>
						<p class="lede">No per-agent licences, no minimum call volume, no setup fee. Scale from one line to a full call center on the same plan.</p>
						<div class="cta-row" style="margin-top:26px;margin-bottom:0">
							<a class="bp-btn bp-btn--ink" href="https://app.botphonic.ai/register/">Start free trial</a>
							<a class="bp-btn bp-btn--ghost" href="https://botphonic.ai/pricings-plans/">See all plans</a>
						</div>
					</div>
					<div>
						<div class="big"><span>$0.20–$0.40</span><small>per talk minute</small></div>
						<ul>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>14-day free trial
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>Inbound and outbound on the same plan
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>All 50+ languages and 65+ voices included
							</li>
							<li>
								<svg class="tick" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2">
									<path d="M4 10.5l4 4 8-9" />
								</svg>Volume pricing and white-label for agencies
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<!-- ================= COMPARE ================= -->
		<section class="sec sec--tint" aria-labelledby="h-alt">
			<div class="wrap">
				<div class="head">
					<h2 id="h-alt">Comparing AI voice agent platforms?</h2>
					<p class="lede">Side-by-side pricing, latency, integrations and compliance against the tools you're probably shortlisting.</p>
				</div>
				<ul class="alts">
					<li>
						<a href="https://botphonic.ai/synthflow-alternative/">Botphonic vs Synthflow</a>
					</li>
					<li>
						<a href="https://botphonic.ai/retell-ai-alternative/">Botphonic vs Retell AI</a>
					</li>
					<li>
						<a href="https://botphonic.ai/vapi-alternative/">Botphonic vs Vapi</a>
					</li>
					<li>
						<a href="https://botphonic.ai/bland-ai-alternative/">Botphonic vs Bland AI</a>
					</li>
					<li>
						<a href="https://botphonic.ai/voiceflow-alternative/">Botphonic vs Voiceflow</a>
					</li>
					<li>
						<a href="https://botphonic.ai/category/alternative/">All comparisons</a>
					</li>
				</ul>
			</div>
		</section>

		<!-- ================= FAQ ================= -->
		<section class="sec" aria-labelledby="h-faq">
			<div class="wrap faq">
				<div>
					<h2 id="h-faq">Questions buyers ask before switching to an AI answering service</h2>
					<p class="lede" style="margin-top:14px">Still unsure? <a class="link" href="https://calendly.com/contact-botphonic/product-discovery">Book a 20-minute demo</a> and hear the agent on your own use case.</p>
				</div>
				<?php
				$bp_home_faqs = array(
					array(
						'question' => 'What is an AI voice agent?',
						'answer' => "An AI voice agent is software that holds a real phone conversation on your behalf. It answers or places calls, understands what the caller wants, takes an action such as booking an appointment or updating a CRM record, and hands your team a summary. Botphonic's agents respond in under 300 ms so the conversation feels natural.",
					),
					array(
						'question' => 'How is an AI answering service different from a human answering service?',
						'answer' => 'A human answering service takes messages and passes them on. An AI answering service like Botphonic answers instantly, 24/7, handles unlimited simultaneous calls, books directly into your calendar and updates your CRM, at a per-minute cost that is a fraction of staffed services.',
					),
					array(
						'question' => 'Can the AI receptionist book appointments into my calendar?',
						'answer' => 'Yes. The AI receptionist checks live availability in Google Calendar, Outlook or your scheduling tool, offers open slots to the caller, confirms the booking and sends a confirmation. It also updates the contact record in Salesforce, HubSpot or Zoho.',
					),
					array(
						'question' => 'Can Botphonic make outbound calls as well as answer them?',
						'answer' => 'Yes. The same AI phone agent runs outbound campaigns: lead follow-up, appointment reminders, payment reminders, surveys and re-engagement. Batch calling runs thousands of calls in parallel with branded caller ID and TCPA-compliant controls.',
					),
					array(
						'question' => 'Does it work with my existing phone system?',
						'answer' => 'Yes. Botphonic connects over SIP trunking to Twilio, Telnyx, Plivo and most VoIP providers, so you keep your current numbers. You can also buy new local or toll-free numbers inside the platform.',
					),
					array(
						'question' => 'Which languages does the AI voice agent speak?',
						'answer' => 'Botphonic speaks 50+ languages and switches language mid-call when the caller does. This includes English variants, Spanish, French, German, Arabic, Hindi, Gujarati, Marathi, Tamil and other Indian languages.',
					),
					array(
						'question' => 'Is Botphonic HIPAA and PCI DSS compliant?',
						'answer' => 'Botphonic is built for regulated calls: HIPAA-ready workflows for healthcare, PCI DSS-compliant payment capture, GDPR-aligned data handling, SOC 2 controls, continuous penetration testing, encryption in transit and at rest, and role-based access to recordings and transcripts.',
					),
					array(
						'question' => 'How much does an AI call center cost?',
						'answer' => 'Botphonic is priced per minute of talk time, from $0.20 to $0.40 per minute depending on volume, with no seat licences and no minimum call volume. Every plan starts with a 14-day free trial.',
					),
				);

				if (function_exists('botphonic_render_faq_group')) {
					echo botphonic_render_faq_group($bp_home_faqs, array('schema' => false)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer.
				}
				unset($bp_home_faqs);
				?>
			</div>
		</section>

		<!-- ================= FINAL CTA ================= -->
		<section class="sec sec--ink final" aria-labelledby="h-final">
			<div class="wrap">
				<div>
					<h2 id="h-final">Give your phones to an AI agent this afternoon</h2>
					<p class="lede">Connect a number, paste your FAQs, pick a voice. Your first call is answered in minutes, and a voice-AI engineer is available if you want help tuning it.</p>
					<div class="cta-row">
						<a class="bp-btn bp-btn--coral" href="https://app.botphonic.ai/register/">Start 14-day free trial</a>
						<a class="bp-btn bp-btn--ghost" href="https://calendly.com/contact-botphonic/product-discovery">Book a demo</a>
					</div>
				</div>
				<figure><img src="https://botphonic.ai/wp-content/uploads/2026/09/07-give-your-phones-cta.svg" alt="Setting up a Botphonic AI voice agent in the dashboard" width="600" height="450" loading="lazy"></figure>
			</div>
		</section>

	</main>

</div><!-- /#bp-new-home -->

<?php
get_footer();