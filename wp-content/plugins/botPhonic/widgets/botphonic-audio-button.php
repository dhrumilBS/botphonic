<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Audio_Button extends Widget_Base
{

    public function get_name()
    {
        return 'botphonic-audio-button';
    }

    public function get_title()
    {
        return __('BotPhonic Audio Button', 'botphonic');
    }

    public function get_icon()
    {
        return 'eicon-play';
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
        $this->start_controls_section('section_audio', [
            'label' => __('Audio Settings', 'botphonic'),
        ]);

        $this->add_control('audio_file', [
            'label' => __('Upload Audio', 'botphonic'),
            'type' => Controls_Manager::MEDIA,
            'media_types' => ['audio'],
            'default' => [],
        ]);

        $this->add_control('button_text_start', [
            'label' => __('Start Button Text', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Start a call',
        ]);

        $this->add_control('button_text_end', [
            'label' => __('End Button Text', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'End a call',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $audio_url = $settings['audio_file']['url'] ?? '';
        $btn_start = esc_html($settings['button_text_start']);
        $btn_end = esc_html($settings['button_text_end']);
        $uid = uniqid('botphonic_audio_');

        if (!$audio_url) return;

?>
        <div class="botphonic-audio-wrapper">
            <a id="<?= $uid ?>_btn" class="elementor-button elementor-button-link elementor-size-sm" style="cursor:pointer;">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text"><?= $btn_start ?></span>
                </span>
            </a>
            <audio id="<?= $uid ?>_audio" src="<?= esc_url($audio_url) ?>" preload="auto"></audio>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('<?= $uid ?>_btn');
                const audio = document.getElementById('<?= $uid ?>_audio');
                const text = btn.querySelector('.elementor-button-text');

                const startText = <?= json_encode($btn_start) ?>;
                const endText = <?= json_encode($btn_end) ?>;

                btn.addEventListener('click', function() {
                    if (audio.paused) {
                        audio.play();
                        text.textContent = endText;
                    } else {
                        audio.pause();
                        text.textContent = startText;
                    }
                });

                audio.addEventListener('ended', function() {
                    text.textContent = startText;
                });
            });
        </script>
<?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\BotPhonic_Audio_Button());