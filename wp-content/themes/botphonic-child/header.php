<?php

/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$bootstrap_version = get_theme_mod('understrap_bootstrap_version', 'bootstrap4');
$navbar_type = get_theme_mod('understrap_navbar_type', 'collapse');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php
	if (has_post_thumbnail()) {
		$thumb_id = get_post_thumbnail_id();
		$image_src = wp_get_attachment_image_url($thumb_id, 'full');
		$image_srcset = wp_get_attachment_image_srcset($thumb_id, 'full');
		$image_sizes = wp_get_attachment_image_sizes($thumb_id, 'full');
		$preload_media = '';
		if (function_exists('botphonic_is_blog_single_view') && botphonic_is_blog_single_view()) {
			$preload_media = ' media="(min-width: 768px)"';
		}

		if ($image_src) {
			echo sprintf(
				'<link rel="preload" as="image" href="%s" imagesrcset="%s" imagesizes="%s" fetchpriority="high"%s>' . "\n",
				esc_url($image_src),
				esc_attr($image_srcset),
				esc_attr($image_sizes),
				$preload_media // Literal, built above.
			);
		}
	}
	?>
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/css/theme.min.css" as="style">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/theme.min.css">

	<link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
	<link rel="dns-prefetch" href="//www.google-analytics.com">

	<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
	<link rel="dns-prefetch" href="//cdn.jsdelivr.net">


	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17195576956"></script>
	<script async>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());
		gtag('config', 'AW-17195576956');
	</script>

	<script async>
		(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-KCWJMQZW');
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
	<?php do_action('wp_body_open'); ?>
	<div class="site" id="page">
		<header id="wrapper-navbar">
			<div class="container">
				<div id="main-nav" class="navbar navbar-expand-md justify-content-between main-nav" aria-labelledby="main-nav-label">
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div>

					<?php
					wp_nav_menu([
						'theme_location' => 'primary',
						'container_class' => 'collapse navbar-collapse',
						'container_id' => 'navbarNavDropdown',
						'menu_class' => 'navbar-nav ms-auto',
						'fallback_cb' => '',
					]);
					?>
				</div>
			</div><!-- .container(-fluid) -->
		</header><!-- #wrapper-navbar -->