<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Audio_Sample_Box extends Widget_Base
{

    public function get_name()
    {
        return 'botphonic-audio-sample-box';
    }

    public function get_title()
    {
        return __('BotPhonic Sample Call Box', 'botphonic');
    }

    public function get_icon()
    {
        return 'eicon-headphones';
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }

    protected function register_controls()
    {
        $this->start_controls_section('content_section', [
            'label' => __('Audio Settings', 'botphonic')
        ]);

        $this->add_control('audio_file', [
            'label' => __('Audio File', 'botphonic'),
            'type' => Controls_Manager::MEDIA,
            'media_types' => ['audio'],
            'default' => [],
        ]);

        $this->add_control('title_text', [
            'label' => __('Title Text', 'botphonic'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Listen to a Sample Call',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $audio_url = $settings['audio_file']['url'] ?? '';
        $title = esc_html($settings['title_text']);
        $uid = uniqid('botphonic_audio_box_');

        if (!$audio_url) return;

?>
        <div id="<?= $uid ?>_wrapper" class="d-flex gap-3" style="align-items:center; padding:10px 16px; border:2px solid #ddd; border-radius:12px; transition:border-color 0.3s ease; background:#f9f9f9;">
            <audio id="<?= $uid ?>_player">
                <source src="<?= esc_url($audio_url) ?>" type="audio/mpeg">
            </audio>
            <button id="<?= $uid ?>_btn" class="audio-button">▶</button>
            <h5 style="margin:0;"><?= $title ?></h5>
        </div>

        <style>
            .audio-button {
                background: #006dee;
                color: #fff;
                padding: 12px 20px;
                border: none;
                border-radius: 50%;
                font-size: 16px;
                cursor: pointer;
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const audio = document.getElementById("<?= $uid ?>_player");
                const btn = document.getElementById("<?= $uid ?>_btn");
                const wrapper = document.getElementById("<?= $uid ?>_wrapper");

                btn.addEventListener("click", () => {
                    if (audio.paused) {
                        audio.play();
                        btn.innerText = "⏸";
                        wrapper.style.borderColor = "#006dee";
                    } else {
                        audio.pause();
                        btn.innerText = "▶";
                        wrapper.style.borderColor = "#ddd";
                    }
                });

                audio.addEventListener("ended", () => {
                    btn.innerText = "▶";
                    wrapper.style.borderColor = "#ddd";
                });
            });
        </script>
<?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\BotPhonic_Audio_Sample_Box());