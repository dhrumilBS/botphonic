<?php
// Theme asset enqueueing.
if (!function_exists('botphonic_child_asset_ver')) {
	function botphonic_child_asset_ver($relative_path)
	{
		$absolute = get_stylesheet_directory() . $relative_path;
		return file_exists($absolute) ? (string) filemtime($absolute) : '1';
	}
}

// Shared landing styles handle.
if (!defined('BOTPHONIC_LANDING_STYLE')) {
	define('BOTPHONIC_LANDING_STYLE', 'botphonic-common-landing');
}

// Shared TOC script handle.
if (!defined('BOTPHONIC_TOC_SCRIPT')) {
	define('BOTPHONIC_TOC_SCRIPT', 'botphonic-toc');
}

add_action('admin_enqueue_scripts', 'my_enqueue_admin');
function my_enqueue_admin()
{
	// Manrope in editor.
	$font_deps = array();

	if (defined('BOTPHONIC_FONT_HANDLE') && defined('BOTPHONIC_FONT_SRC')) {
		wp_enqueue_style(BOTPHONIC_FONT_HANDLE, BOTPHONIC_FONT_SRC, array(), null);
		$font_deps[] = BOTPHONIC_FONT_HANDLE;
	}
	wp_enqueue_style('my-admin-theme', get_stylesheet_directory_uri() . '/assets/css/admin-style.css', $font_deps, botphonic_child_asset_ver('/assets/css/admin-style.css'));
	wp_enqueue_script('admin-script', get_stylesheet_directory_uri() . '/assets/js/admin-script.js', ['wp-data'], botphonic_child_asset_ver('/assets/js/admin-script.js'), true);
}

add_filter('style_loader_src', 'apply_global_asset_version', 9999, 2);
add_filter('script_loader_src', 'apply_global_asset_version', 9999, 2);
function apply_global_asset_version($src, $handle)
{
	if (empty($_GET['ver']) || !current_user_can('manage_options')) {
		return $src;
	}

	$version = sanitize_text_field(wp_unslash($_GET['ver']));
	$src = remove_query_arg('ver', $src);
	$src = add_query_arg('ver', $version, $src);

	return $src;
}

add_action('wp_enqueue_scripts', 'enqueue_child_theme_style', 25);
function enqueue_child_theme_style()
{
	wp_dequeue_style('understrap-styles');
	wp_deregister_style('classic-theme-styles');
	wp_deregister_style('global-styles');

	// Child theme stylesheet.
	wp_enqueue_style('dtbwp_css_child', get_stylesheet_directory_uri() . '/style.css', [], botphonic_child_asset_ver('/style.css'));

	// Shared landing design system.
	wp_enqueue_style(BOTPHONIC_LANDING_STYLE, get_stylesheet_directory_uri() . '/assets/css/common-landing.css', [], botphonic_child_asset_ver('/assets/css/common-landing.css'));

	wp_enqueue_style('hfe', get_stylesheet_directory_uri() . '/assets/css/hfe.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/hfe.css'));
	wp_enqueue_script('botphonic-utm', get_stylesheet_directory_uri() . '/assets/js/utm.js', [], botphonic_child_asset_ver('/assets/js/utm.js'), true);
	wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', [], botphonic_child_asset_ver('/assets/js/custom.js'), true);

	wp_register_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
	wp_register_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '1', true);

	wp_register_style('intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css', [], '19.5.3');
	wp_register_script('intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js', ['jquery'], '19.5.3', true);

	wp_register_style('iconbox', get_stylesheet_directory_uri() . '/assets/css/iconbox.css', [], botphonic_child_asset_ver('/assets/css/iconbox.css'));

	wp_register_style('calculator', get_stylesheet_directory_uri() . '/assets/css/roi-calculator.css', [], botphonic_child_asset_ver('/assets/css/roi-calculator.css'));
	wp_register_script('calculator', get_stylesheet_directory_uri() . '/assets/js/roi-calculator.js', [], botphonic_child_asset_ver('/assets/js/roi-calculator.js'), true);

	wp_register_style('jv', get_stylesheet_directory_uri() . '/assets/css/jv.css', [], botphonic_child_asset_ver('/assets/css/jv.css'));
	wp_register_script('success-story-swiper', get_stylesheet_directory_uri() . '/assets/js/success-story-swiper.js', ['swiper-js', 'jquery'], botphonic_child_asset_ver('/assets/js/success-story-swiper.js'), true);

	wp_register_style('marketplace', get_stylesheet_directory_uri() . '/assets/css/marketplace.css', ['swiper-css'], botphonic_child_asset_ver('/assets/css/marketplace.css'));
	wp_register_script('marketplace', get_stylesheet_directory_uri() . '/assets/js/marketplace.js', ['swiper-js'], botphonic_child_asset_ver('/assets/js/marketplace.js'), true);

	wp_register_script('botphonic-bootstrap-5-3-8', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', [], '5.3.8', true);
	wp_register_style('ai-voice-agents', get_stylesheet_directory_uri() . '/assets/css/ai-voice-agents.css', [], botphonic_child_asset_ver('/assets/css/ai-voice-agents.css'));
	wp_register_script('ai-voice-agents', get_stylesheet_directory_uri() . '/assets/js/ai-voice-agents.js', ['botphonic-bootstrap-5-3-8'], botphonic_child_asset_ver('/assets/js/ai-voice-agents.js'), true);

	wp_register_style('agent-studio', get_stylesheet_directory_uri() . '/assets/css/agent-studio.css', [], botphonic_child_asset_ver('/assets/css/agent-studio.css'));
	wp_register_script('agent-studio', get_stylesheet_directory_uri() . '/assets/js/agent-studio.js', [], botphonic_child_asset_ver('/assets/js/agent-studio.js'), true);
	wp_register_style('multilingual', get_stylesheet_directory_uri() . '/assets/css/multilingual.css', [], botphonic_child_asset_ver('/assets/css/multilingual.css'));
	wp_register_style('enrichment', get_stylesheet_directory_uri() . '/assets/css/enrichment.css', [], botphonic_child_asset_ver('/assets/css/enrichment.css'));
	wp_register_style('sitemap-css', get_stylesheet_directory_uri() . '/assets/css/sitemap.css', [], botphonic_child_asset_ver('/assets/css/sitemap.css'));
	wp_register_script('sitemap-js', get_stylesheet_directory_uri() . '/assets/js/sitemap.js', [], botphonic_child_asset_ver('/assets/js/sitemap.js'), true);
	wp_register_style('usa-style', get_stylesheet_directory_uri() . '/assets/css/usa.css', [], botphonic_child_asset_ver('/assets/css/usa.css'));

	wp_register_style('pricing', get_stylesheet_directory_uri() . '/assets/css/pricing.css', array(BOTPHONIC_LANDING_STYLE), botphonic_child_asset_ver('/assets/css/pricing.css'));
	wp_register_script('pricing', get_stylesheet_directory_uri() . '/assets/js/pricing.js', array(), botphonic_child_asset_ver('/assets/js/pricing.js'), true);

	// Shared FAQ assets.
	$uses_common_faq = is_page_template('temp-pricings.php')
		|| is_page_template('temp-new-home-page.php')
		|| is_page_template('temp-about-us.php')
		|| (function_exists('botphonic_is_alt_single_view') && botphonic_is_alt_single_view());

	if ($uses_common_faq) {
		if (wp_style_is('botphonic-faqs-css', 'registered')) {
			wp_enqueue_style('botphonic-faqs-css');
		}
		if (wp_script_is('botphonic-faqs-js', 'registered')) {
			wp_enqueue_script('botphonic-faqs-js');
		}
	}
	unset($uses_common_faq);

	// Mail product assets.
	wp_register_style('personalize', get_stylesheet_directory_uri() . '/assets/css/personalize.css', array(), botphonic_child_asset_ver('/assets/css/personalize.css'));
	wp_register_script('personalize', get_stylesheet_directory_uri() . '/assets/js/personalize.js', array(), botphonic_child_asset_ver('/assets/js/personalize.js'), true);
	wp_register_style('unified', get_stylesheet_directory_uri() . '/assets/css/unified.css', array(), botphonic_child_asset_ver('/assets/css/unified.css'));

	// Cold Email page.
	if (is_page_template('temp-cold-mail.php')) {
		wp_enqueue_style('cold-mail', get_stylesheet_directory_uri() . '/assets/css/cold-mail.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/cold-mail.css'));
		wp_enqueue_script('cold-mail', get_stylesheet_directory_uri() . '/assets/js/cold-mail.js', [], botphonic_child_asset_ver('/assets/js/cold-mail.js'), true);
	}

	if (is_page_template('temp-new-home-page.php')) {
		wp_enqueue_style('new-home-page', get_stylesheet_directory_uri() . '/assets/css/new-home-page.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/new-home-page.css'));
		wp_enqueue_script('new-home-page', get_stylesheet_directory_uri() . '/assets/js/new-home-page.js', [], botphonic_child_asset_ver('/assets/js/new-home-page.js'), true);
	}

	if (is_page_template('temp-about-us.php')) {
		wp_enqueue_style('about-us', get_stylesheet_directory_uri() . '/assets/css/about-us.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/about-us.css'));
		wp_enqueue_script('about-us', get_stylesheet_directory_uri() . '/assets/js/about-us.js', [], botphonic_child_asset_ver('/assets/js/about-us.js'), true);
	}

	// Industries hub.
	if (is_page_template('template-industries.php')) {
		wp_enqueue_style('botphonic-industries', get_stylesheet_directory_uri() . '/assets/css/industries.css', ['dtbwp_css_child'], botphonic_child_asset_ver('/assets/css/industries.css'));
		wp_enqueue_script('botphonic-industries', get_stylesheet_directory_uri() . '/assets/js/industries.js', [], botphonic_child_asset_ver('/assets/js/industries.js'), array('in_footer' => true, 'strategy' => 'defer'));
	}

	// In-content CTA blocks.
	if (is_single() || is_page_template('temp-cta.php')) {
		wp_enqueue_style('cta', get_stylesheet_directory_uri() . '/assets/css/cta.css', [], botphonic_child_asset_ver('/assets/css/cta.css'));
	}

	// CTA gallery blog styles.
	if (is_page_template('temp-cta.php') && defined('BOTPHONIC_BLOG_STYLE')) {
		wp_enqueue_style(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/css/blog.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/blog.css'));
	}

	// Shared TOC script.
	if (
		(function_exists('botphonic_is_blog_single_view') && botphonic_is_blog_single_view())
		|| (function_exists('botphonic_is_story_single_view') && botphonic_is_story_single_view())
		|| (function_exists('botphonic_is_alt_single_view') && botphonic_is_alt_single_view())
	) {
		wp_enqueue_script(BOTPHONIC_TOC_SCRIPT, get_stylesheet_directory_uri() . '/assets/js/toc.js', [], botphonic_child_asset_ver('/assets/js/toc.js'), true);
	}

	// Blog styles and script.
	if (function_exists('botphonic_is_blog_view') && botphonic_is_blog_view()) {
		wp_enqueue_style(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/css/blog.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/blog.css'));
		wp_enqueue_script(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/js/blog.js', [], botphonic_child_asset_ver('/assets/js/blog.js'), true);
	}

	// Author archive styles.
	if (is_author()) {
		wp_enqueue_style('botphonic-author', get_stylesheet_directory_uri() . '/assets/css/author.css', [BOTPHONIC_BLOG_STYLE], botphonic_child_asset_ver('/assets/css/author.css'));

		if (function_exists('botphonic_author_counts')) {
			$author_counts = botphonic_author_counts(get_queried_object_id());
			if (defined('BOTPHONIC_ALT_STYLE') && !empty($author_counts[defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives'])) {
				wp_enqueue_style(BOTPHONIC_ALT_STYLE, get_stylesheet_directory_uri() . '/assets/css/alternatives.css', [BOTPHONIC_BLOG_STYLE], botphonic_child_asset_ver('/assets/css/alternatives.css'));
			}

			if (defined('BOTPHONIC_STORY_STYLE') && !empty($author_counts[defined('CUSTOME_STORY_SLUG') ? CUSTOME_STORY_SLUG : 'success-stories'])) {
				wp_enqueue_style(BOTPHONIC_STORY_STYLE, get_stylesheet_directory_uri() . '/assets/css/success-stories.css', [BOTPHONIC_BLOG_STYLE], botphonic_child_asset_ver('/assets/css/success-stories.css'));
			}

			unset($author_counts);
		}
	}

	// Customer stories assets.
	if (function_exists('botphonic_is_story_view') && botphonic_is_story_view()) {
		wp_enqueue_style(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/css/blog.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/blog.css'));
		wp_enqueue_script(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/js/blog.js', [], botphonic_child_asset_ver('/assets/js/blog.js'), true);
		wp_enqueue_style(BOTPHONIC_STORY_STYLE, get_stylesheet_directory_uri() . '/assets/css/success-stories.css', [BOTPHONIC_BLOG_STYLE], botphonic_child_asset_ver('/assets/css/success-stories.css'));
		wp_enqueue_script(BOTPHONIC_STORY_STYLE, get_stylesheet_directory_uri() . '/assets/js/success-stories.js', [], botphonic_child_asset_ver('/assets/js/success-stories.js'), true);
	}

	// Alternatives comparison assets.
	if (function_exists('botphonic_is_alt_view') && botphonic_is_alt_view()) {
		wp_enqueue_style(BOTPHONIC_BLOG_STYLE, get_stylesheet_directory_uri() . '/assets/css/blog.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/blog.css'));
		wp_enqueue_style(BOTPHONIC_ALT_STYLE, get_stylesheet_directory_uri() . '/assets/css/alternatives.css', [BOTPHONIC_BLOG_STYLE], botphonic_child_asset_ver('/assets/css/alternatives.css'));
		if (botphonic_is_alt_single_view()) {
			wp_enqueue_script(BOTPHONIC_ALT_STYLE, get_stylesheet_directory_uri() . '/assets/js/alternatives.js', [], botphonic_child_asset_ver('/assets/js/alternatives.js'), true);
		}
	}

	// Solution pages styles.
	if (function_exists('botphonic_is_solution_page') && botphonic_is_solution_page()) {
		wp_enqueue_style(BOTPHONIC_SOLUTION_STYLE, get_stylesheet_directory_uri() . '/assets/css/solution-pages.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/solution-pages.css'));
	}

	// USA child pages.
	if (!is_page()) {
		return;
	}

	$current_page = get_queried_object();
	if ($current_page instanceof WP_Post && $current_page->post_parent) {
		$parent = get_post($current_page->post_parent);
		if ($parent && $parent->post_name === 'usa') {
			wp_enqueue_style('usa-style');
		}
	}
}


// Modern template assets.
add_action('wp_enqueue_scripts', 'botphonic_enqueue_modern_template_assets', 99);
function botphonic_enqueue_modern_template_assets()
{
	$template_key = botphonic_get_current_template_key();
	if ('' === $template_key) {
		return;
	}

	switch ($template_key) {
		case 'agent-studio':
			wp_enqueue_style('agent-studio');
			wp_enqueue_script('agent-studio');
			break;

		case 'ai-voice-agents':
			wp_enqueue_style('ai-voice-agents');
			wp_enqueue_script('botphonic-bootstrap-5-3-8');
			wp_enqueue_script('ai-voice-agents');
			break;

		case 'contact':
			wp_enqueue_style('intl-tel');
			wp_enqueue_script('intl-tel');
			break;

		case 'jv':
			wp_enqueue_style('jv');
			break;

		case 'marketplace':
			wp_enqueue_style('swiper-css');
			wp_enqueue_style('marketplace');
			wp_enqueue_script('swiper-js');
			wp_enqueue_script('marketplace');
			break;

		case 'multilingual':
			wp_enqueue_style('multilingual');
			// multilingual.js intentionally empty.
			break;

		case 'personalize-mail':
			wp_enqueue_style('personalize');
			break;

		case 'prospect-enrichment':
			wp_enqueue_style('enrichment');
			break;

		case 'sitemap':
			wp_enqueue_style('sitemap-css');
			wp_enqueue_script('sitemap-js');
			break;

		case 'unified-mail':
			wp_enqueue_style('unified');
			break;

		case 'usa':
			wp_enqueue_style('usa-style');
			wp_enqueue_style('swiper-css');
			wp_enqueue_script('swiper-js');
			wp_enqueue_script('success-story-swiper');
			break;
	}
	wp_enqueue_style(BOTPHONIC_MODERN_STYLE, get_stylesheet_directory_uri() . '/assets/css/template-modernization.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/template-modernization.css'));
}