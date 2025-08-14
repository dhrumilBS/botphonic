<?php
define('CUSTOME_STORY_SLUG', 'success-stories');

require_once get_stylesheet_directory() . '/inc/function-cpt.php';
require_once get_stylesheet_directory() . '/inc/function-admin.php';
require_once get_stylesheet_directory() . '/inc/function-popup.php';

// ============================================================================================================================================================
function prioritize_lwptoc_main_css() {
	wp_dequeue_style('lwptoc-main-css');
	// wp_enqueue_style('lwptoc-main-css', plugin_dir_url(__FILE__) . 'luckywp-table-of-contents/assets/css/main.min.css', array(),  '2.1.4');
}
add_action('wp_enqueue_scripts', 'prioritize_lwptoc_main_css', 1); // Lower number = higher priority
// =========================================================================================
// =========================================================================================
function enqueue_intl_tel_input_assets()
{
	
}
add_action('wp_enqueue_scripts', 'enqueue_intl_tel_input_assets', -1);


// -----------------------------------------------------------------------------------------
add_action('wp_enqueue_scripts', 'enqueue_child_theme_style', 9999);
function enqueue_child_theme_style()
{
	wp_enqueue_style('dtbwp_css_child', get_stylesheet_directory_uri() . '/style.css', array(), rand());
	wp_enqueue_style('dtbwp_fonts', get_stylesheet_directory_uri() . '/fonts/style.css', array(), rand());
	wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array(), rand(), true);

	wp_register_style('jv', get_stylesheet_directory_uri() . '/assets/css/jv.css', array(), rand());

	
	wp_register_style('pricing', get_stylesheet_directory_uri() . '/assets/css/pricing.css', array(), rand());
	wp_register_script('pricing', get_stylesheet_directory_uri() . '/assets/js/pricing.js', array(), rand(), true);
	wp_register_script('pricingNew', get_stylesheet_directory_uri() . '/assets/js/finalPricing.js', array(), rand(), true);

	wp_register_style('alternative', get_stylesheet_directory_uri() . '/assets/css/alternative.css', array(), rand());
	wp_register_script('alternative', get_stylesheet_directory_uri() . '/assets/js/alternative.js', array(), rand(), true);



	if (get_post_type() === CUSTOME_STORY_SLUG) {
		wp_enqueue_style(CUSTOME_STORY_SLUG, get_stylesheet_directory_uri() . '/assets/css/case-study.css', array(), rand());
	}

	if(is_single()){
		wp_enqueue_style('single', get_stylesheet_directory_uri() . '/assets/css/single.css', array(), rand());
		wp_enqueue_style('botphonic-faqs-css');
		wp_enqueue_script('botphonic-faqs-js');
		$inline_js = '
    window.onload = function () {
    if (window.innerWidth <= 767) {
        var toggleLink = document.querySelector(".lwptoc_toggle_label");
        if (toggleLink && toggleLink.textContent.trim().toLowerCase() === "hide") {
            toggleLink.click();
        }
    }
};
 ';
		wp_add_inline_script( 'lwptoc-main', $inline_js );
	}
}

// ----------------------------------------------------------------------------------------- 
add_action('add_attachment', 'update_image_meta_upon_image_upload');
function update_image_meta_upon_image_upload($post_ID)
{
	if (wp_attachment_is_image($post_ID)) {
		$my_image_title = get_post($post_ID)->post_title;
		$my_image_title = ucwords(str_replace(['-', '_'], ' ', $my_image_title));
		$my_image_meta = array(
			'ID'  => $post_ID,
			'post_title' => $my_image_title,
		);
		update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_title);
		wp_update_post($my_image_meta);
	}
}
// -----------------------------------------------------------------------------------------
add_filter('wpcf7_validate_text*', 'custom_cf7_validation_filter', 20, 2);
add_filter('wpcf7_validate_email*', 'custom_cf7_validation_filter', 20, 2);
add_filter('wpcf7_validate_tel*', 'custom_cf7_validation_filter', 20, 2);
add_filter('wpcf7_validate_select*', 'custom_cf7_validation_filter', 20, 2);
add_filter('wpcf7_validate_textarea*', 'custom_cf7_validation_filter', 20, 2);

function custom_cf7_validation_filter($result, $tag) {
	$name = $tag->name;
	error_log("Validating field: $name");
	switch ($name) {
		case 'your-name':
			if (empty($_POST['your-name'])) {
				$result->invalidate($tag, "Please enter your company name.");
			}
			break;
		case 'industry':
			if (empty($_POST['industry'])) {
				$result->invalidate($tag, "Please select your industry.");
			}
			break;
		case 'your-number':
			if (empty($_POST['your-number'])) {
				$result->invalidate($tag, "Please enter your phone number.");
			}
			break;
		case 'your-email':
			if (empty($_POST['your-email'])) {
				$result->invalidate($tag, "Please provide a valid email address.");
			}
			break;
		case 'usage-purpose':
			if (empty($_POST['usage-purpose'])) {
				$result->invalidate($tag, "Please select the purpose of AI Call Assist.");
			}
			break;
		case 'your-message':
			if (empty($_POST['your-message'])) {
				$result->invalidate($tag, "Please tell us about your requirements.");
			}
			break;
	} 

	return $result;
}

// -----------------------------------------------------------------------------------------
function force_gravatar_alt_to_author($avatar) {
    if (strpos($avatar, 'alt=') !== false) {
        $avatar = preg_replace('/alt=["\'].*?["\']/', 'alt="Author"', $avatar);
    } else {
        $avatar = str_replace('<img', '<img alt="Author"', $avatar);
    }
    return $avatar;
}
add_filter('get_avatar', 'force_gravatar_alt_to_author');

// -----------------------------------------------------------------------------------------