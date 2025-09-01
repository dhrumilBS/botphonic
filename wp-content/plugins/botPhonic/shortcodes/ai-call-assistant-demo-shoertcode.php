<?php
function botphonic_ai_call_assistant_demo()
{
	ob_start();
?>

<style>
	.custom-gradient-text { /* background: linear-gradient(to right, hsl(215, 100%, 47%), hsl(278, 52%, 47%));  */background: linear-gradient(304deg, #FF37DF 21.73%, #6E00FF 56.6%); ; -webkit-background-clip: text; color: transparent; }

	.botphonic-liveDemo-section { background-color: #F9F9FF; }
	.botphonic-liveDemo-section .custom-gradient-header { background: linear-gradient(to right, hsl(215, 100%, 47%), hsl(220, 83%, 53%)); }

</style>

<section class="botphonic-liveDemo-section position-relative section-padded custom-bg-background d-none d-md-block">
	<div class="container px-3">
		<!-- Section Heading -->
		<div class="text-center mb-5">
			<h2 class="mb-3"> Experience <span class="custom-gradient-text">Botphonic AI </span>Live </h2>
			<p class="lead mx-auto" style="max-width: 720px;">
				See our AI call assistant in action and discover how it can transform your business communications
			</p>
		</div>

		<!-- Video Card -->
		<div class="mx-auto" style="max-width: 1200px;">
			<div class="card border-1 shadow-lg rounded-4 overflow-hidden">
				<!-- Card Header with Gradient -->
				<div class="text-center p-3 custom-gradient-header">
					<h3 class="text-white fw-semibold mb-0">
						Live Demo - Botphonic AI Voice Assistant
					</h3>
				</div>

				<!-- Responsive Video -->
				<div class="ratio ratio-16x9">
					<iframe src="https://app.botphonic.ai/voice-assistant"  class="border-0"  allow="microphone"  allowfullscreen title="Botphonic AI Voice Assistant Demo"> </iframe>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	return ob_get_clean();
}
add_shortcode('ai_call_assistant_demo', 'botphonic_ai_call_assistant_demo');
