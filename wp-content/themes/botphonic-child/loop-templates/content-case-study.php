<?php 
get_header(); ?>

<div class="template-case-study case-study-single dark-bg--bg1">
    <div class="section-padded">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <span class="badge"><?php the_field('badge_label'); ?></span>
                    <h1 class="h2"><?php the_field('main_heading'); ?></h1>
                    <p><?php the_field('main_text'); ?></p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-box-wrapper">
                        <div class="stats">
                            <?php if (have_rows('stat_boxes')): ?>
                                <?php while (have_rows('stat_boxes')): the_row(); ?>
                                    <div class="stat-box">
                                        <div class="stat-number">
                                            <span class="prefix"><?php the_sub_field('prefix'); ?> </span>
                                            <span class="counter" data-target="<?php the_sub_field('target_value'); ?>"> <?php the_sub_field('target_value'); ?> </span>
                                            <span class="suffix"><?php the_sub_field('suffix'); ?> </span>
                                        </div>
                                        <p><?php the_sub_field('Label'); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" section-padded">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section">
                        <h2><?php the_field('label'); ?></h2>
                        <p><?php the_field('overview_content'); ?></p>
                        <div class="image-wrapper">
                            <img src="<?php the_field('overview_image'); ?>" alt="Overview Image">
                        </div>
                    </div>

                    <div class="section">
                        <h2><?php the_field('challenges_heading'); ?></h2>
                        <p><?php the_field('challenges_content'); ?></p>
                    </div>

                    <div class="section">
                        <h2><?php the_field('actions_heading'); ?></h2>
                        <p><?php the_field('actions_content'); ?></p>
                    </div>

                    <div class="section">
                        <h2><?php the_field('quantifiable_outcomes_heading'); ?></h2>
                        <p><?php the_field('quantifiable_outcomes_content'); ?></p>
                        <?php if (have_rows('stat_boxes')): ?>
                            <div class="icon-boxes">
                                <?php while (have_rows('stat_boxes')): the_row(); ?>
                                    <div class="icon-box">
                                        <div class="image-wrapper">
                                            <?= wp_get_attachment_image(get_sub_field('icon')['id'], 'full'); ?> 
                                        </div>
                                        <div class="icons">
                                            <strong><?php the_sub_field('prefix'); ?> <?php the_sub_field('target_value'); ?><?php the_sub_field('suffix'); ?> <?php the_sub_field('Label'); ?> </strong>
                                            <p><?php the_sub_field('description'); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>

                        <div class="section">
                            <h2><?php the_field('conclusion_heading'); ?></h2>
                            <p><?php the_field('conclusion_content'); ?></p>
                        </div>
                    </div>

                    <div class="col-3">
                        <?= do_shortcode('[elementor-template id="601"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- CTA Section -->
    <div class="cta-shortcode">
        <?php echo do_shortcode('[elementor-template id="600"]'); ?>
    </div>


    <script>
        // Animated counter functionality
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let count = 0;
            const step = target / 100;
            const updateCount = () => {
                count += step;
                if (count < target) {
                    counter.innerText = Math.ceil(count);
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    </script>


    <?php geT_footer(); ?>