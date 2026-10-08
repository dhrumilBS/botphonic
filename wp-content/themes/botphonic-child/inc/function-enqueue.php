<?php

/**
 * Botphonic child theme — asset enqueueing.
 *
 * @package Botphonic
 */
if (!function_exists('botphonic_child_asset_ver')) {
	function botphonic_child_asset_ver($relative_path)
	{
		$absolute = get_stylesheet_directory() . $relative_path;

		return file_exists($absolute) ? (string) filemtime($absolute) : '1';
	}
}

/**
 * Handle of the shared landing design system (assets/css/common-landing.css).
 *
 * It holds the brand tokens plus the layout, typography, button, ratings, table
 * and accordion primitives shared by the landing templates, and is loaded on
 * every request so the common header/footer and any landing page can rely on
 * it. Every rule inside is scoped to a landing root, so it cannot affect other
 * pages or posts.
 *
 * A new landing template only has to declare it as a dependency:
 *
 *     wp_enqueue_style(
 *         'my-landing',
 *         get_stylesheet_directory_uri() . '/assets/css/my-landing.css',
 *         [BOTPHONIC_LANDING_STYLE],
 *         botphonic_child_asset_ver('/assets/css/my-landing.css')
 *     );
 *
 * which guarantees the shared layer is printed first and stays overridable.
 */
if (!defined('BOTPHONIC_LANDING_STYLE')) {
	define('BOTPHONIC_LANDING_STYLE', 'botphonic-common-landing');
}

/*
 * The shared table of contents (template-parts/toc.php + assets/js/toc.js).
 * Its own handle rather than a per-view one, because the blog, the customer
 * stories and the comparison guides all render the same card and the script is
 * the only asset of the three views that has no natural owner: blog.js is not
 * loaded on the guides, and alternatives.js is not loaded anywhere else. The
 * card's styling stays in blog.css §§ 5.1 / 17, which all three already load.
 */
if (!defined('BOTPHONIC_TOC_SCRIPT')) {
	define('BOTPHONIC_TOC_SCRIPT', 'botphonic-toc');
}

add_action('admin_enqueue_scripts', 'my_enqueue_admin');
function my_enqueue_admin()
{
	/*
	 * Manrope in the editor too. admin-style.css sets the block canvas to the
	 * front-end stack, but botphonic_enqueue_global_fonts() hooks
	 * wp_enqueue_scripts and so never runs in admin — without this the canvas
	 * would silently fall back to a system face and the editor still would not
	 * match the published page.
	 *
	 * Reuses BOTPHONIC_FONT_HANDLE / BOTPHONIC_FONT_SRC from functions.php
	 * rather than repeating the URL, so the family and the weight range stay in
	 * one place.
	 */
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
	wp_enqueue_style('dtbwp_css_child', get_stylesheet_directory_uri() . '/style.css', [], botphonic_child_asset_ver('/style.css'));
	wp_enqueue_style(BOTPHONIC_LANDING_STYLE, get_stylesheet_directory_uri() . '/assets/css/common-landing.css', [], botphonic_child_asset_ver('/assets/css/common-landing.css'));

	wp_enqueue_style('hfe', get_stylesheet_directory_uri() . '/assets/css/hfe.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/hfe.css'));
	wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', ['botphonic-utm'], botphonic_child_asset_ver('/assets/js/custom.js'), true);

	// UTM persistence across pages, CF7 hidden fields and the app.botphonic.ai Register/Login links.
	wp_enqueue_script('botphonic-utm', get_stylesheet_directory_uri() . '/assets/js/utm.js', [], botphonic_child_asset_ver('/assets/js/utm.js'), ['in_footer' => true, 'strategy' => 'defer']);
	$utm_config = apply_filters('botphonic_utm_config', [
		'siteHosts' => array_values(array_unique([wp_parse_url(home_url(), PHP_URL_HOST), 'botphonic.ai', 'www.botphonic.ai'])),
		'appHosts'  => ['app.botphonic.ai'],
		'days'      => 30,
	]);
	wp_add_inline_script('botphonic-utm', 'window.botphonicUtmConfig = ' . wp_json_encode($utm_config) . ';', 'before');

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
	$uses_common_faq = is_page_template('temp-pricings.php')
		|| is_page_template('temp-new-home-page.php')
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
	wp_register_style('personalize', get_stylesheet_directory_uri() . '/assets/css/personalize.css', array(), botphonic_child_asset_ver('/assets/css/personalize.css'));
	wp_register_script('personalize', get_stylesheet_directory_uri() . '/assets/js/personalize.js', array(), botphonic_child_asset_ver('/assets/js/personalize.js'), true);

	wp_register_style('unified', get_stylesheet_directory_uri() . '/assets/css/unified.css', array(), botphonic_child_asset_ver('/assets/css/unified.css'));
	if (is_page_template('temp-cold-mail.php')) {
		wp_enqueue_style(
			'cold-mail',
			get_stylesheet_directory_uri() . '/assets/css/cold-mail.css',
			[BOTPHONIC_LANDING_STYLE],
			botphonic_child_asset_ver('/assets/css/cold-mail.css')
		);

		wp_enqueue_script(
			'cold-mail',
			get_stylesheet_directory_uri() . '/assets/js/cold-mail.js',
			[],
			botphonic_child_asset_ver('/assets/js/cold-mail.js'),
			true
		);
	}

	if (is_page_template('temp-new-home-page.php')) {
		wp_enqueue_style('new-home-page', get_stylesheet_directory_uri() . '/assets/css/new-home-page.css', [BOTPHONIC_LANDING_STYLE], botphonic_child_asset_ver('/assets/css/new-home-page.css'));
		wp_enqueue_script('new-home-page', get_stylesheet_directory_uri() . '/assets/js/new-home-page.js', [], botphonic_child_asset_ver('/assets/js/new-home-page.js'), true);
	}

	if (is_single() || is_page_template('temp-cta.php')) {
		wp_enqueue_style('cta', get_stylesheet_directory_uri() . '/assets/css/cta.css', [], botphonic_child_asset_ver('/assets/css/cta.css'));
	}

	if (
		(function_exists('botphonic_is_blog_single_view') && botphonic_is_blog_single_view())
		|| (function_exists('botphonic_is_story_single_view') && botphonic_is_story_single_view())
		|| (function_exists('botphonic_is_alt_single_view') && botphonic_is_alt_single_view())
	) {
		wp_enqueue_script(
			BOTPHONIC_TOC_SCRIPT,
			get_stylesheet_directory_uri() . '/assets/js/toc.js',
			[],
			botphonic_child_asset_ver('/assets/js/toc.js'),
			true
		);
	}

	if (function_exists('botphonic_is_blog_view') && botphonic_is_blog_view()) {
		wp_enqueue_style(
			BOTPHONIC_BLOG_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/blog.css',
			[BOTPHONIC_LANDING_STYLE],
			botphonic_child_asset_ver('/assets/css/blog.css')
		);

		wp_enqueue_script(
			BOTPHONIC_BLOG_STYLE,
			get_stylesheet_directory_uri() . '/assets/js/blog.js',
			[],
			botphonic_child_asset_ver('/assets/js/blog.js'),
			true
		);
	}

	if (function_exists('botphonic_is_story_view') && botphonic_is_story_view()) {
		wp_enqueue_style(
			BOTPHONIC_BLOG_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/blog.css',
			[BOTPHONIC_LANDING_STYLE],
			botphonic_child_asset_ver('/assets/css/blog.css')
		);

		wp_enqueue_script(
			BOTPHONIC_BLOG_STYLE,
			get_stylesheet_directory_uri() . '/assets/js/blog.js',
			[],
			botphonic_child_asset_ver('/assets/js/blog.js'),
			true
		);

		wp_enqueue_style(
			BOTPHONIC_STORY_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/success-stories.css',
			[BOTPHONIC_BLOG_STYLE],
			botphonic_child_asset_ver('/assets/css/success-stories.css')
		);

		wp_enqueue_script(
			BOTPHONIC_STORY_STYLE,
			get_stylesheet_directory_uri() . '/assets/js/success-stories.js',
			[],
			botphonic_child_asset_ver('/assets/js/success-stories.js'),
			true
		);
	}

	if (function_exists('botphonic_is_alt_view') && botphonic_is_alt_view()) {
		wp_enqueue_style(
			BOTPHONIC_BLOG_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/blog.css',
			[BOTPHONIC_LANDING_STYLE],
			botphonic_child_asset_ver('/assets/css/blog.css')
		);

		wp_enqueue_style(
			BOTPHONIC_ALT_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/alternatives.css',
			[BOTPHONIC_BLOG_STYLE],
			botphonic_child_asset_ver('/assets/css/alternatives.css')
		);

		if (botphonic_is_alt_single_view()) {
			wp_enqueue_script(
				BOTPHONIC_ALT_STYLE,
				get_stylesheet_directory_uri() . '/assets/js/alternatives.js',
				[],
				botphonic_child_asset_ver('/assets/js/alternatives.js'),
				true
			);
		}
	}

	if (function_exists('botphonic_is_solution_page') && botphonic_is_solution_page()) {
		wp_enqueue_style(
			BOTPHONIC_SOLUTION_STYLE,
			get_stylesheet_directory_uri() . '/assets/css/solution-pages.css',
			[BOTPHONIC_LANDING_STYLE],
			botphonic_child_asset_ver('/assets/css/solution-pages.css')
		);
	}

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
			/* multilingual.js is intentionally empty and remains dormant. */
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

	wp_enqueue_style(
		BOTPHONIC_MODERN_STYLE,
		get_stylesheet_directory_uri() . '/assets/css/template-modernization.css',
		[BOTPHONIC_LANDING_STYLE],
		botphonic_child_asset_ver('/assets/css/template-modernization.css')
	);
}
