<?php

/**
 * Shortcode: [botphonic_audio_player]
 * Description: Renders a static audio player with hardcoded audio file and label text.
 */

add_shortcode('botphonic_audio_player', 'botphonic_render_audio_player');

function botphonic_render_audio_player()
{
    // ✅ Static fallback data (you can change this later)
    $audio_url = 'https://botphonic.ai/wp-content/uploads/2025/07/Customer-Support.mp3';
    $label_text = 'Listen to a Sample Call';
    ob_start();
?>
    <div id="audioWrapper" class="d-flex gap-3" style="align-items:center; padding:10px 16px; border:2px solid #ddd; border-radius:12px; transition:border-color 0.3s ease; background:#f9f9f9;">
        <audio id="audioPlayer">
            <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
        </audio>
        <button id="playPauseBtn" onclick="toggleAudio()" class="audio-button"> ▶ </button>
        <h5 style="margin:0;"><?php echo esc_html($label_text); ?></h5>
    </div>
    <style>
        .audio-button { background: #006dee; color: #fff; padding: 12px 20px; border: none; border-radius: 50%; font-size: 16px; cursor: pointer; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var audio = document.getElementById("audioPlayer");
            var playBtn = document.getElementById("playPauseBtn");
            var wrapper = document.getElementById("audioWrapper");
            window.toggleAudio = function() {
                if (audio.paused) {
                    audio.play();
                    playBtn.innerText = "⏸";
                    playBtn.classList.add("playing");
                    wrapper.style.borderColor = "#006dee";
                } else {
                    audio.pause();
                    playBtn.innerText = "▶";
                    playBtn.classList.remove("playing");
                    wrapper.style.borderColor = "#ddd";
                }
            };
            audio.addEventListener("ended", function() {
                playBtn.innerText = "▶";
                playBtn.classList.remove("playing");
                wrapper.style.borderColor = "#ddd";
            });
        });
    </script>
<?php
    return ob_get_clean();
}