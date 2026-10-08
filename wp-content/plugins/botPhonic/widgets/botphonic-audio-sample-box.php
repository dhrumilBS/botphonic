<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Audio_Sample_Box extends Widget_Base
{

	public function get_name() { return 'botphonic-audio-sample-box'; }
	public function get_title() { return __('BotPhonic Sample Call Box', 'botphonic'); }
	public function get_icon() { return 'eicon-headphones'; }
	public function get_categories() { return ['botphonic-widgets']; }
	protected function register_controls()
	{
		$this->start_controls_section('content_section', [
			'label' => __('Content', 'botphonic'),
		]);

		$this->add_control('audio_file', [
			'label' => __('Audio File', 'botphonic'),
			'type' => Controls_Manager::MEDIA,
			'media_types' => ['audio'],
		]);

		$this->add_control('title_text', [
			'label' => __('Title', 'botphonic'),
			'type' => Controls_Manager::TEXT,
			'default' => 'Listen to a Sample Call',
		]);

		$this->end_controls_section();
		$this->start_controls_section('style_section', [
			'label' => __('Style', 'botphonic'),
			'tab' => Controls_Manager::TAB_STYLE,
		]);

		$this->add_control('primary_color', [
			'label' => __('Primary Color', 'botphonic'),
			'type' => Controls_Manager::COLOR,
			'default' => 'var(--accent)',
		]);

		$this->add_control('background_color', [
			'label' => __('Background Color', 'botphonic'),
			'type' => Controls_Manager::COLOR,
			'default' => 'var(--bg1)',
		]);

		$this->add_control('text_color', [
			'label' => __('Text Color', 'botphonic'),
			'type' => Controls_Manager::COLOR,
			'default' => 'var(--text-color)',
		]);
		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$audio_url = $settings['audio_file']['url'] ?? '';
		if (!$audio_url) return;
		$title = esc_html($settings['title_text']);
		$this->add_render_attribute('wrapper', 'class', 'botphonic-audio-box');
		$this->add_render_attribute('wrapper', 'style', '
			--primary:' . $settings['primary_color'] . ';
			--bg:' . $settings['background_color'] . ';
			--text:' . $settings['text_color'] . ';
		');
?>
<style>
	.botphonic-audio-box { display: flex; align-items: center; gap: 14px; padding: 16px; border-radius: 16px; background: var(--bg); color: var(--text); border: 1px solid rgba(0, 0, 0, 0.08); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; max-width: 500px; }
	.botphonic-btn { width: 50px; height: 50px; border-radius: 50% !important; border: none; background: var(--primary); display: flex; align-items: center; justify-content: center; transition: all 0.25s ease; }
	.botphonic-btn:hover { transform: scale(1); }
	.pause-icon { display: none; }
	.botphonic-audio-box.playing .play-icon { display: none; }
	.botphonic-audio-box.playing .pause-icon { display: inline; }
	.botphonic-audio-box.playing { border-color: var(--primary); }
	.botphonic-title { font-size: 18px; font-weight: 600; letter-spacing: 0.5px; }		
</style>
<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>

	<audio class="botphonic-audio">
		<source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
	</audio>

	<button class="botphonic-btn" aria-label="Play audio">
		<span class="play-icon">
			<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
				<path d="M8 5v14l11-7z" />
			</svg>
		</span>
		<span class="pause-icon">
			<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
				<path d="M6 5h4v14H6zm8 0h4v14h-4z" />
			</svg>
		</span>
	</button>
	<div class="botphonic-title"><?php echo $title; ?></div>
</div>
<script>
	(function($) {
		function initBotphonicAudio(scope) {
			const wrappers = scope.find('.botphonic-audio-box');
			wrappers.each(function() {
				const wrapper = $(this);
				const audio = wrapper.find('.botphonic-audio')[0];
				const btn = wrapper.find('.botphonic-btn');
				btn.on('click', function() {
					$('.botphonic-audio').each(function() {
						this.pause();
						$(this).closest('.botphonic-audio-box').removeClass('playing');
					});

					if (audio.paused) {
						audio.play();
						wrapper.addClass('playing');
					} else {
						audio.pause();
						wrapper.removeClass('playing');
					}
				});

				audio.addEventListener('ended', function() {
					wrapper.removeClass('playing');
				});
			});
		}

		$(window).on('elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/botphonic-audio-sample-box.default', initBotphonicAudio );
		});

	})(jQuery);
</script>
<?php
	}
}

Plugin::instance()->widgets_manager->register( new \Elementor\BotPhonic_Audio_Sample_Box() );