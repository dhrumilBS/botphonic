<?php
add_shortcode('ai_call_assistant_demo', 'botphonic_ai_call_assistant_demo');
function botphonic_ai_call_assistant_demo()
{
	ob_start();
?>
<section class="botphonic-liveDemo-section position-relative section-padded custom-bg-background d-none d-md-block">
	<div class="container px-3">
		<div class="text-center mb-5">
			<h2 class="mb-3"> Experience <span class="custom-gradient-text">Botphonic AI </span>Live </h2>
			<p class="lead mx-auto" style="max-width: 520px;"> See our AI call assistant in action and discover how it can transform your business communications </p>
		</div>
		<div class="card border-1 shadow-lg rounded-4 overflow-hidden">
			<div class="text-center p-3" style="background: var(--blue);"> <h3 class="text-white fw-semibold mb-0"> Live Demo - Botphonic AI Voice Assistant </h3> </div>
			<div class="ratio ratio-16x9"> <iframe src="https://app.botphonic.ai/voice-assistant"  class="border-0"  allow="microphone"  allowfullscreen title="Botphonic AI Voice Assistant Demo"> </iframe> </div>
		</div>
	</div>
</section>
<?php
	return ob_get_clean();
}