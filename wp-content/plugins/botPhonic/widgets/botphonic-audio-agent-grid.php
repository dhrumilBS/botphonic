<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Audio_Agent_Grid extends Widget_Base
{

	public function get_name()
	{
		return 'botphonic-audio-agent-grid';
	}

	public function get_title()
	{
		return __('BotPhonic Audio Agent Grid', 'botphonic');
	}

	public function get_icon()
	{
		return 'eicon-posts-grid';
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	public function get_style_depends()
	{
		return [];
	}

	public function get_script_depends()
	{
		return [];
	}

	protected function register_controls()
	{
		$this->start_controls_section('section_agents', [
			'label' => __('Agents', 'botphonic'),
		]);

		$repeater = new Repeater();

		$repeater->add_control('agent_image', [
			'label' => __('Agent Image', 'botphonic'),
			'type' => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		]);

		$repeater->add_control('agent_name', [
			'label' => __('Agent Name', 'botphonic'),
			'type' => Controls_Manager::TEXT,
			'default' => __('Virtual Receptionist', 'botphonic'),
		]);

		$repeater->add_control('agent_industry', [
			'label' => __('Industry', 'botphonic'),
			'type' => Controls_Manager::TEXT,
			'default' => __('Solar Industry', 'botphonic'),
		]);

		$repeater->add_control('agent_audio', [
			'label' => __('Audio File', 'botphonic'),
			'type' => Controls_Manager::MEDIA,
			'media_types' => ['audio'],
			'default' => [],
		]);

		$this->add_control('agents_list', [
			'label' => __('Agents List', 'botphonic'),
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [],
			'title_field' => '{{{ agent_name }}}',
		]);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		if (empty($settings['agents_list'])) return;

		$uid = uniqid('botphonic_agents_');
?>
<div class="botphonic-audio-grid" id="<?= $uid ?>">

	<div class="agents-wrapper">
		<?php foreach ($settings['agents_list'] as $item):
		$audio_url = $item['agent_audio']['url'] ?? '';
		$agent_uid = uniqid('agent_');
		?>
		<div class="agent-card" data-audio="<?= esc_url($audio_url) ?>" data-id="<?= $agent_uid ?>">
			<div class="agent-header">
				<div class="agent-image">
					<img src="<?= esc_url($item['agent_image']['url']) ?>" alt="<?= esc_attr($item['agent_name']) ?>">
					<span class="status-dot"></span>
				</div>
				<div>
					<p class="agent-name"><?= esc_html($item['agent_name']) ?></p>
					<p class="agent-industry"><?= esc_html($item['agent_industry']) ?></p>
				</div>
			</div>
			<div class="agent-audio">
				<button class="play-button"><svg data-icon="play" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none" class="">
					<path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path>
					</svg></button>
				<div class="waveform" id="waveform_<?= $agent_uid ?>"></div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<style>
	.botphonic-audio-grid .agents-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 35px; }

	.botphonic-audio-grid .agent-card { background: #fff; border-radius: 20px; padding: 22px; width: 100%; max-width: 360px; box-shadow: 0px 12px 25px rgba(0, 26, 94, 0.06); transition: transform 0.3s, box-shadow 0.3s; border: 1px solid #dcdcdc40; }
	.botphonic-audio-grid .agent-card:hover { transform: translateY(-6px); box-shadow: 0px 18px 30px rgba(0, 26, 94, 0.12); }
	.botphonic-audio-grid .agent-card .agent-header { display: flex; align-items: center; margin-bottom: 20px; }

	.botphonic-audio-grid .agent-header .agent-image { position: relative; margin-right: 12px; }
	.botphonic-audio-grid .agent-header .agent-image img { border-radius: 50%; width: 75px; height: 75px; object-fit: cover;  }

	.botphonic-audio-grid .agent-card.playing{ border: 1px solid #4a4ff7; }
	.botphonic-audio-grid .agent-card.playing .agent-image .status-dot { position: absolute; bottom: 3px; right: 3px; width: 12px; height: 12px; background: #12B76A; border: 2px solid #fff; border-radius: 50%; box-shadow: 0 0 6px rgba(18, 183, 106, 0.8); }

	.botphonic-audio-grid .agent-header .agent-name { font-weight: 600; margin: 0 0 6px; color: #111; }
	.botphonic-audio-grid .agent-header .agent-industry { font-size: 13px; background: #E6EEFF; color: #003BB3; padding: 2px 10px; border-radius: 20px; margin:0; display: inline-block; font-weight: 500; }
	.botphonic-audio-grid .agent-audio .waveform { flex-grow: 1; height: 40px; margin-left: 12px; }

	.botphonic-audio-grid .agent-card .agent-audio { display: flex; align-items: center; background: #F7F4FD; border-radius: 16px; padding: 12px 14px; transition: background 0.3s; }
	.botphonic-audio-grid .agent-card .agent-audio:hover { background: #f0ecfb; }

	.botphonic-audio-grid .agent-card .play-button { background: #4a4ff7; color: #fff;padding:0; border-radius: 50px; width: 36px; height: 36px; }
	.botphonic-audio-grid .agent-card .play-button:hover { background: #3737c4; transform: scale(1.05); }

</style>

<!-- Load Wavesurfer.js -->
<script src="https://unpkg.com/wavesurfer.js"></script>
<script>
	const play = `<svg data-icon="play" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none" class="">
<path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path>
	</svg>`;
	const pause = `<svg data-icon="pause" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none" class="">
<path d="M2.30038 3.6C2.30038 2.8456 2.30038 2.4688 2.53478 2.2344C2.76918 2 3.14598 2 3.90038 2C4.65478 2 5.03158 2 5.26598 2.2344C5.50038 2.4688 5.50038 2.8456 5.50038 3.6V8.4C5.50038 9.1544 5.50038 9.5312 5.26598 9.7656C5.03158 10 4.65478 10 3.90038 10C3.14598 10 2.76918 10 2.53478 9.7656C2.30038 9.5312 2.30038 9.1544 2.30038 8.4V3.6ZM7.10038 3.6C7.10038 2.8456 7.10038 2.4688 7.33478 2.2344C7.56918 2 7.94598 2 8.70038 2C9.45478 2 9.83158 2 10.066 2.2344C10.3004 2.4688 10.3004 2.8456 10.3004 3.6V8.4C10.3004 9.1544 10.3004 9.5312 10.066 9.7656C9.83158 10 9.45478 10 8.70038 10C7.94598 10 7.56918 10 7.33478 9.7656C7.10038 9.5312 7.10038 9.1544 7.10038 8.4V3.6Z" fill="currentColor"></path>
	</svg>` 
	document.addEventListener('DOMContentLoaded', function() {
		const wrapper = document.querySelector('#<?= $uid ?>');
		let currentWave = null;
		let currentBtn = null;
		let currentCard = null; // track which card is playing


		wrapper.querySelectorAll('.agent-card').forEach(card => {
			const btn = card.querySelector('.play-button');
			const audioUrl = card.dataset.audio;
			const waveformId = card.querySelector('.waveform').id;

			const wavesurfer = WaveSurfer.create({
				container: '#' + waveformId,
				waveColor: "#d1c9f5",
				progressColor: "#6d3ee3",
				cursorColor: "#6d3ee3",
				barWidth: 2,
				barRadius: 3,
				height: 40,
				responsive: true
			});

			wavesurfer.load(audioUrl);

			btn.addEventListener('click', () => {
				if (currentWave && currentWave !== wavesurfer) {
					currentWave.pause();
					if (currentBtn) currentBtn.innerHTML = play;
					if (currentCard) currentCard.classList.remove("playing");
				}

				// Toggle current
				if (wavesurfer.isPlaying()) {
					wavesurfer.pause();
					btn.innerHTML = play;
					card.classList.remove("playing");
					currentWave = null;
					currentBtn = null;
					currentCard = null;
				} else {
					wavesurfer.play();
					btn.innerHTML = pause;
					card.classList.add("playing");
					currentWave = wavesurfer;
					currentBtn = btn;
					currentCard = card;
				}
			});
			// Reset after finishing audio
			wavesurfer.on('finish', () => {
				btn.innerHTML = play;
				card.classList.remove("playing");
				currentWave = null;
				currentBtn = null;
				currentCard = null;
			});
		});
	});
</script>
<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\BotPhonic_Audio_Agent_Grid());
