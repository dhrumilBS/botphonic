<?php

/**
 * Shortcode: [botphonic_integration_slider]
 */

add_shortcode('botphonic_integration_slider', 'botphonic_integration_slider');

function botphonic_integration_slider($atts)
{
    ob_start();
    $a = shortcode_atts(array(
        'img' => 'https://botphonic.ai/wp-content/plugins/botPhonic/assets/img/integration_logo.webp',
    ), $atts);

    $integration_slider = [];

?>
    <style>
        .carousel {
            --blur: 6px;
            --contrast: 105%;
            --speed: 70s;
            height: 250px;
            width: 100%;
            position: relative;

        }

        .carousel .mask {
            position: absolute;
            inset: 0; 
            backdrop-filter: blur(var(--blur)) contrast(var(--contrast));
            -webkit-backdrop-filter: blur(var(--blur)) contrast(var(--contrast));
            -webkit-mask: linear-gradient(90deg, #000 50px, #0000 175px calc(100% - 175px), #fff calc(100% - 50px));
            pointer-events: none;
            background-size: contain;
        }
        
        .carousel .logos {
            animation: moveBg var(--speed) linear infinite;
            position: absolute;
            inset: 0;
            background: url(<?= $a['img']; ?>) 0 50% / 1567px auto repeat-x;
            -webkit-mask: linear-gradient(90deg, #0000 5px, #000 50px calc(100% - 50px), #0000 calc(100% - 5px));
         }

        @keyframes moveBg {
            from {
                background-position: 0 50%;
            }

            to {
                background-position: -2985px 50%;
            }
        }
    </style>
    <div class="carousel">
        <div class="logos"></div>
        <div class="mask"></div>
    </div>


    <div class="row g-4">
        <?php foreach ($integration_slider as $feature) : ?>
            <div class="col-md-4">
                <div class="icon-box p-4 h-100">
                    <div class="d-flex align-items-center mb-1">
                        <div class="icon me-2"><?= $feature['svg']; ?></div>
                        <div class="fw-semibold mb-0 h5"><?= $feature['title']; ?></div>
                    </div>
                    <div class="text-muted"><?= $feature['text']; ?> </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php
    return ob_get_clean();
}
