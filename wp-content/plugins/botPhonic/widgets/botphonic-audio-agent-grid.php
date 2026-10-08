<?php
/**
 * BotPhonic – Audio Agent Grid (Elementor widget).
 *
 * Widget name, control IDs and markup classes are intentionally UNCHANGED so
 * every page already built with this widget keeps rendering identically.
 *
 * @package BotPhonic
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BotPhonic_Audio_Agent_Grid extends Widget_Base {
	const HANDLE = 'botphonic-audio-agent-grid';
	public function get_name() {
		return 'botphonic-audio-agent-grid';
	}

	public function get_title() {
		return __( 'BotPhonic Audio Agent Grid', 'botphonic' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return array( 'botphonic-widgets' );
	}

	public function get_keywords() {
		return array( 'audio', 'voice', 'agent', 'waveform', 'wavesurfer', 'botphonic' );
	}

	public function get_style_depends() {
		return array( self::HANDLE );
	}

	public function get_script_depends() {
		return array( self::HANDLE );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_agents',
			array(
				'label' => __( 'Agents', 'botphonic' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'agent_image',
			array(
				'label'   => __( 'Agent Image', 'botphonic' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'agent_name',
			array(
				'label'       => __( 'Agent Name', 'botphonic' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Virtual Receptionist', 'botphonic' ),
			)
		);

		$repeater->add_control(
			'agent_industry',
			array(
				'label'       => __( 'Industry', 'botphonic' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Solar Industry', 'botphonic' ),
			)
		);

		$repeater->add_control(
			'agent_audio',
			array(
				'label'       => __( 'Audio File', 'botphonic' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'audio' ),
				'default'     => array(),
			)
		);

		$this->add_control(
			'agents_list',
			array(
				'label'       => __( 'Agents List', 'botphonic' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'agent_name'     => __( 'Virtual Receptionist', 'botphonic' ),
						'agent_industry' => __( 'Solar Industry', 'botphonic' ),
					),
				),
				'title_field' => '{{{ agent_name }}}',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['agents_list'] ) || ! is_array( $settings['agents_list'] ) ) {
			return;
		}

		$widget_id   = $this->get_id();
		$label_play  = __( 'Play voice preview', 'botphonic' );
		$label_pause = __( 'Pause voice preview', 'botphonic' );
		?>
		<div class="botphonic-audio-grid" id="botphonic-agents-<?php echo esc_attr( $widget_id ); ?>">
			<div class="agents-wrapper">
				<?php
				foreach ( $settings['agents_list'] as $index => $item ) :

					$audio_url = ! empty( $item['agent_audio']['url'] ) ? $item['agent_audio']['url'] : '';

					if ( '' === $audio_url ) {
						continue;
					}

					$item_id   = ! empty( $item['_id'] ) ? $item['_id'] : (string) $index;
					$agent_uid = $widget_id . '-' . $item_id;
					$name      = isset( $item['agent_name'] ) ? $item['agent_name'] : '';
					$industry  = isset( $item['agent_industry'] ) ? $item['agent_industry'] : '';
					$image_url = ! empty( $item['agent_image']['url'] ) ? $item['agent_image']['url'] : '';
					?>
					<div class="agent-card"
						data-audio="<?php echo esc_url( $audio_url ); ?>"
						data-id="agent-<?php echo esc_attr( $agent_uid ); ?>"
						data-label-play="<?php echo esc_attr( $label_play ); ?>"
						data-label-pause="<?php echo esc_attr( $label_pause ); ?>">

						<div class="agent-header">
							<div class="agent-image">
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" width="75" height="75">
								<?php endif; ?>
								<span class="status-dot" aria-hidden="true"></span>
							</div>
							<div class="agent-meta">
								<?php if ( '' !== $name ) : ?>
									<p class="agent-name"><?php echo esc_html( $name ); ?></p>
								<?php endif; ?>
								<?php if ( '' !== $industry ) : ?>
									<p class="agent-industry"><?php echo esc_html( $industry ); ?></p>
								<?php endif; ?>
							</div>
						</div>

						<div class="agent-audio">
							<button type="button"
								class="play-button"
								aria-label="<?php echo esc_attr( $label_play ); ?>"
								aria-pressed="false">
								<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none"><path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path></svg>
							</button>
							<div class="waveform" id="waveform_agent-<?php echo esc_attr( $agent_uid ); ?>"></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

$botphonic_agent_grid_widget  = new \Elementor\BotPhonic_Audio_Agent_Grid();
$botphonic_agent_grid_manager = Plugin::instance()->widgets_manager;

if ( method_exists( $botphonic_agent_grid_manager, 'register' ) ) {
	$botphonic_agent_grid_manager->register( $botphonic_agent_grid_widget );
} else {
	$botphonic_agent_grid_manager->register_widget_type( $botphonic_agent_grid_widget );
}