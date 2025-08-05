<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Agent_Grid extends Widget_Base
{
    public function get_name()
    {
        return 'botphonic-audio-agent-grid';
    }

    public function get_title()
    {
        return __('Audio Agent Grid', 'botphonic');
    }

    public function get_style_depends()
    {
        return ['botphonic-agent-grid-css'];
    }

    public function get_script_depends()
    {
        return ['botphonic-agent-grid-js'];
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }

    protected function register_controls()
    {
        $repeater = new Repeater();

        $repeater->add_control('agent_name', [
            'label' => __('Agent Name', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Clara'
        ]);

        $repeater->add_control('agent_role', [
            'label' => __('Role/Industry', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Medical'
        ]);

        $repeater->add_control('agent_title', [
            'label' => __('Agent Title', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Dental Clinic'
        ]);

        $repeater->add_control('agent_description', [
            'label' => __('Description', 'botphonic'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Listen how our AI Voice Agent politely reminds a client about their upcoming dental appointment.'
        ]);

        $repeater->add_control('agent_image', [
            'label' => __('Agent Image', 'botphonic'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => 'https://via.placeholder.com/200']
        ]);

        $repeater->add_control('agent_audio', [
            'label' => __('Audio File', 'botphonic'),
            'type' => Controls_Manager::MEDIA,
            'media_type' => 'audio'
        ]);

        $repeater->add_control('agent_background_color', [
            'label' => __('Box Background Color', 'botphonic'),
            'type' => Controls_Manager::COLOR,
            'default' => '#C2E8F1'
        ]);

        $this->start_controls_section('agents_section', [
            'label' => __('Agent Boxes', 'botphonic')
        ]);

        $this->add_control('agents', [
            'label' => __('Agent Items', 'botphonic'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => []
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $uid = uniqid('botphonic_agent_grid');

        if (empty($settings['agents'])) return;

?>
        <div id="<?= $uid; ?> " class="ai-voices-agents-grid">
            <div class="ai-voices-agents-row">
                <?php
                foreach ($settings['agents'] as $index => $agent) {
                    $bg_color = $agent['agent_background_color'] ?: '';
                ?>
                    <?php $is_open_class = ($index === 0) ? ' open' : ''; ?>
                    <div class="ai-voices-agents-box<?php echo $is_open_class; ?>" style="background: <?php echo esc_attr($bg_color); ?>;">

                        <div class="voices-agents-img">
                            <img src="<?php echo esc_url($agent['agent_image']['url']); ?>" alt="<?php echo esc_attr($agent['agent_name']); ?>">
                            <span><?php echo esc_html($agent['agent_name']); ?></span>
                        </div>
                        <div class="voices-agents-content">
                            <div class="voices-agents-content-inner">
                                <span><?php echo esc_html($agent['agent_role']); ?></span>
                                <h3><?php echo esc_html($agent['agent_title']); ?></h3>
                                <p><?php echo esc_html($agent['agent_description']); ?></p>
                                <div class="voice_name">
                                    <div class="audio-player-preview">
                                        <div class="voice_fill">
                                            <div class="audio_fill">
                                                <audio class="audio_player_grid" src="<?php echo esc_url($agent['agent_audio']['url']); ?>"></audio>

                                                <button class="playbutton p-0">
                                                    <div class="play_audio audio-btn">
                                                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="3" y="3" width="3" height="10" rx="1.5" fill="currentcolor" />
                                                            <rect x="9" y="3" width="3" height="10" rx="1.5" fill="currentcolor" />
                                                        </svg>
                                                    </div>
                                                    <div class="pasue_audio audio-btn">
                                                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10.6432 7.15202L5.53 3.95626C4.86395 3.53997 4 4.01841 4 4.80385V11.1982C4 11.9836 4.86394 12.46 5.52999 12.0437L10.6432 8.84802C11.2699 8.45636 11.2699 7.54369 10.6432 7.15202Z" fill="currentcolor" />
                                                        </svg>
                                                    </div>
                                                </button>
                                                <div class="progressbar">
                                                    <img src="<?php echo plugin_dir_url(__DIR__); ?>assets/img/progressbar.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
<?php
    }

    public function _content_template() {}
}
Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\BotPhonic_Agent_Grid());
