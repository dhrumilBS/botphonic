<?php
function botphonic_audio_use_case_slider()
{
    ob_start();
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), null, true);
?>
    <style>
        .audio-swiper-wrapper { padding: 30px 0; color: #fff; }
        .audio-swiper .swiper-slide { background: var(--bg-dark-1); border-radius: 20px; padding: 30px 20px; text-align: center; }
        .audio-swiper .industry-label { font-weight: 700; color: #FF845E; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.7px; font-size: 20px; }
        .audio-swiper .soundwave-icon { width: 20px; height: 20px; }
        .audio-swiper .description { color: #E1E8ED; margin-top: 15px; line-height: 1.5; }
        .audio-swiper .swiper-button-next,
        .audio-swiper .swiper-button-prev { color: #fff; }
        .audio-swiper .swiper-pagination-bullet { background: #fff; opacity: 0.5; }
        .audio-swiper .swiper-pagination-bullet-active { background: #006DEE; opacity: 1; }
        .audio-wave-icon { display: flex; justify-content: center; align-items: end; gap: 3px; height: 30px; margin-bottom: 15px; }
        .audio-wave-icon .bar { width: 4px; height: 6px; background: #00BFFF; border-radius: 2px; animation: none; }
        .audio-wave-icon.playing .bar1 { animation: bounce 1s infinite ease-in-out; animation-delay: 0s; }
        .audio-wave-icon.playing .bar2 { animation: bounce 1s infinite ease-in-out; animation-delay: 0.1s; }
        .audio-wave-icon.playing .bar3 { animation: bounce 1s infinite ease-in-out; animation-delay: 0.2s; }
        .audio-wave-icon.playing .bar4 { animation: bounce 1s infinite ease-in-out; animation-delay: 0.3s; }
        .audio-wave-icon.playing .bar5 { animation: bounce 1s infinite ease-in-out; animation-delay: 0.4s; }
        @keyframes bounce {
            0%, 100% { height: 6px; }
            50% { height: 20px; }
        }

        @media screen and (max-width: 768px) {
            .audio-swiper-wrapper { padding: 20px; }
            .audio-swiper .swiper-slide { padding: 20px; }
        }
    </style>

    <div class="audio-swiper-wrapper">
        <div class="swiper audio-swiper">
            <div class="swiper-wrapper">
                <?php
                $use_cases = [
                    [
                        'industry' => 'Real Estate',
                        'audio' => site_url() . '/wp-content/uploads/2025/07/Sales-Assistant-Lead-Qualifier.mp3',
                        'description' => 'Convert leads instantly with automated property call assistants.'
                    ],
                    [
                        'industry' => 'Health Care',
                        'audio' => site_url() . '/wp-content/uploads/2025/07/Healthcare-Receptionist.mp3',
                        'description' => 'Schedule appointments and answer patient queries 24/7.'
                    ],
                    [
                        'industry' => 'E-Commerce',
                        'audio' => site_url() . '/wp-content/uploads/2025/07/Customer-Support.mp3',
                        'description' => 'Resolve orders, tracking, and returns using voice bots.'
                    ],
                ];

                foreach ($use_cases as $use_case) :
                ?>
                    <div class="swiper-slide">
                        <div class="industry-label"><?php echo esc_html($use_case['industry']); ?></div>

                        <!-- Audio Wave SVG -->
                        <!-- Animated Audio Bars -->
                        <div class="audio-wave-icon">
                            <span class="bar bar1"></span>
                            <span class="bar bar2"></span>
                            <span class="bar bar3"></span>
                            <span class="bar bar4"></span>
                            <span class="bar bar5"></span>
                        </div>


                        <!-- Try It Button -->
                        <button class="btn" data-audio="<?php echo esc_url($use_case['audio']); ?>">
                            <svg class="soundwave-icon play-icon" viewBox="0 0 64 64" fill="currentColor">
                                <polygon points="16,12 52,32 16,52" />
                            </svg>
                            <svg class="soundwave-icon pause-icon" viewBox="0 0 64 64" fill="currentColor" style="display: none;">
                                <rect x="16" y="12" width="10" height="40" />
                                <rect x="38" y="12" width="10" height="40" />
                            </svg>
                            Try it
                        </button>

                        <div class="description"><?php echo esc_html($use_case['description']); ?></div>
                    </div>

                <?php endforeach; ?>
            </div>

            <!-- <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div> -->
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const swiper = new Swiper(".audio-swiper", {
                loop: false,
                spaceBetween: 40,
                grabCursor: true,
                centeredSlides: false,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1.1
                    },
                    768: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    },
                }
            });

            let currentAudio = null;
            let currentBtn = null;
            let currentWave = null;

            document.querySelectorAll(".btn").forEach(button => {
                button.addEventListener("click", function() {
                    const audioUrl = this.dataset.audio;
                    const wave = this.closest(".swiper-slide").querySelector(".audio-wave-icon");

                    // Pause and reset current playing
                    if (currentAudio && !currentAudio.paused) {
                        currentAudio.pause();
                        if (currentBtn) {
                            currentBtn.querySelector(".play-icon").style.display = "inline";
                            currentBtn.querySelector(".pause-icon").style.display = "none";
                        }
                        if (currentWave) {
                            currentWave.classList.remove("playing");
                        }
                    }

                    if (!currentAudio || audioUrl !== currentAudio.src) {
                        currentAudio = new Audio(audioUrl);
                        currentAudio.play();
                        currentBtn = this;
                        currentWave = wave;

                        this.querySelector(".play-icon").style.display = "none";
                        this.querySelector(".pause-icon").style.display = "inline";
                        wave.classList.add("playing");

                        currentAudio.onended = () => {
                            this.querySelector(".play-icon").style.display = "inline";
                            this.querySelector(".pause-icon").style.display = "none";
                            wave.classList.remove("playing");
                        };
                    } else {
                        // If clicking same audio again, stop
                        this.querySelector(".play-icon").style.display = "inline";
                        this.querySelector(".pause-icon").style.display = "none";
                        wave.classList.remove("playing");
                        currentAudio = null;
                        currentBtn = null;
                        currentWave = null;
                    }
                });
            });

        });
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('audio_use_cases', 'botphonic_audio_use_case_slider');
