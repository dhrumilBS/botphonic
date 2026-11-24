<?php
/**
 * Botphonic – Solutions landing page.
 * Automatically used by WordPress for a page with the slug "solution".
 * Location: wp-content/themes/botphonic-child/page-solution.php
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

// Use the shared modernization design system and the existing solution stylesheet.
add_filter('body_class', function ($classes) {
    $classes[] = 'bpm-page';
    $classes[] = 'bpm-page--solution';
    return array_unique($classes);
});

$solution_css = get_stylesheet_directory() . '/assets/css/solution-pages.css';
if (file_exists($solution_css)) {
    wp_enqueue_style('botphonic-solution-pages', get_stylesheet_directory_uri() . '/assets/css/solution-pages.css', array(), filemtime($solution_css));
}
// Existing site-wide template-modernization.css should already be enqueued by the theme.

get_header();

$solution_page = get_queried_object();
$parent_id     = ($solution_page instanceof WP_Post) ? (int) $solution_page->ID : 0;
$parent_url    = $parent_id ? get_permalink($parent_id) : home_url('/solution/');
$parent_path   = trim((string) wp_parse_url($parent_url, PHP_URL_PATH), '/');

// Include both genuine WordPress child pages and pages whose permalinks are
// placed under /solution/ by a custom permalink/rewrite configuration.
$pages = get_pages(array(
    'post_type'   => 'page',
    'post_status' => 'publish',
    'sort_column' => 'post_title',
    'sort_order'  => 'ASC',
));

$solutions = array();
foreach ($pages as $page) {
    if ((int) $page->ID === $parent_id) {
        continue;
    }

    $url  = get_permalink($page);
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    if (!$url || !$parent_path) {
        continue;
    }

    $under_solution_url = strpos($path . '/', $parent_path . '/') === 0;
    if ($under_solution_url) {
        $solutions[$page->ID] = $page;
    }
}

// Short descriptions for the solutions in the 30 October 2026 export.
// For new solutions, use their WordPress excerpt or a neutral fallback.
$solution_descriptions = array(
    'onboarding'             => 'Simplify onboarding workflows and guide customers through their first steps.',
    'sales-automation'       => 'Streamline sales conversations, follow-ups, and lead engagement.',
    'ai-telemarketing'       => 'Support outbound calling campaigns and prospect conversations with AI.',
    'ai-appointment-setter'  => 'Engage prospects and help schedule sales appointments.',
    'ai-appointment-booking' => 'Help customers book appointments through automated conversations.',
    'ai-ivr'                 => 'Route incoming callers and handle common requests through conversational AI.',
    'ai-sales-assistant'     => 'Assist sales teams with lead engagement and routine conversations.',
    'ai-call-center'         => 'Manage customer conversations and repetitive call center workflows.',
    'ai-customer-service'    => 'Respond to routine customer questions and support requests.',
    'ai-answering-service'   => 'Answer incoming calls and capture important caller information.',
    'ai-receptionist'        => 'Welcome callers, answer common questions, and direct enquiries.',
    'ai-concierge'           => 'Provide automated assistance for customer enquiries and requests.',
);
?>



<main id="primary" class="bps-directory">
    <section class="bps-directory__hero" aria-labelledby="bps-solutions-title">
        <div class="bps-directory__container">
            <span class="bps-directory__eyebrow"><?php esc_html_e('Botphonic Solutions', 'botphonic'); ?></span>
            <h1 id="bps-solutions-title"><?php esc_html_e('AI Voice Agent Solutions for Every Business Need', 'botphonic'); ?></h1>
            <p class="bps-directory__lead"><?php esc_html_e('Discover AI-powered solutions for customer support, sales, appointment scheduling, and business communication. Find the right solution for your workflow.', 'botphonic'); ?></p>
        </div>
    </section>

    <section class="bps-directory__main" aria-labelledby="bps-solutions-list-title">
        <div class="bps-directory__container">
            <div class="bps-directory__intro">
                <h2 id="bps-solutions-list-title"><?php esc_html_e('Explore Our AI Solutions', 'botphonic'); ?></h2>
                <p><?php esc_html_e('Choose a solution to learn how Botphonic can support your business communication workflows.', 'botphonic'); ?></p>
            </div>

            <?php if ($solutions) : ?>
                <div class="bps-directory__grid">
                    <?php foreach ($solutions as $index => $solution) :
                        $solution_url = get_permalink($solution);
                        $slug = $solution->post_name;
                        $excerpt = get_post_field('post_excerpt', $solution->ID);
                        $description = $excerpt ? wp_strip_all_tags($excerpt) : ($solution_descriptions[$slug] ?? 'Explore this Botphonic solution and learn how it can support your business workflows.');
                    ?>
                        <article class="bps-directory__card">
                            <div class="bps-directory__card-icon" aria-hidden="true"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></div>
                            <h3><a href="<?php echo esc_url($solution_url); ?>"><?php echo esc_html(get_the_title($solution)); ?></a></h3>
                            <p><?php echo esc_html(wp_trim_words($description, 24, '…')); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="bps-directory__empty"><?php esc_html_e('Solutions will appear here once their pages are published.', 'botphonic'); ?></p>
            <?php endif; ?>

            <?php
            // Keep optional editor content, including shortcodes and Elementor output.
            if ($parent_id && trim((string) $solution_page->post_content) !== '') :
                ?>
                <div class="bps-directory__content">
                    <?php
                    global $post;
                    $original_post = $post ?? null;
                    $post = $solution_page;
                    setup_postdata($post);
                    the_content();
                    wp_reset_postdata();
                    $post = $original_post;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>