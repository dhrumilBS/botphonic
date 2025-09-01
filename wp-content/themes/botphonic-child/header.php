<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$bootstrap_version = get_theme_mod( 'understrap_bootstrap_version', 'bootstrap4' );
$navbar_type       = get_theme_mod( 'understrap_navbar_type', 'collapse' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<script>
			(function(w,d,s,l,i){
				w[l]=w[l]||[];
				w[l].push({ 'gtm.start':new Date().getTime(), event:'gtm.js' });
				var f=d.getElementsByTagName(s)[0], j=d.createElement(s), dl=l!='dataLayer'?'&l='+l:'';
				j.async=true;
				j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-KCWJMQZW');
		</script>

		<meta name="google-site-verification" content="NfdpW5_1t-4uTF5Rx4WNx2QGTDQuZNiDTCN8E5VFiC4" />
		<?php wp_head(); ?>
		<script type="application/ld+json">
		{
			"@context": "https://schema.org", "@type": "Organization", "name": "Botphonic", "url": "https://botphonic.ai/", "logo": "https://botphonic.ai/wp-content/uploads/2025/04/Botphonic-logo-1.svg", "sameAs": [ "https://facebook.com/botphonic/", "https://x.com/botphonic/", "https://instagram.com/botphonicai/", "https://youtube.com/@BotPhonic/", "https://linkedin.com/company/botphonic/" ], "email": "mailto:contact@botphonic.ai", "description": "Botphonic is an AI-powered call assistant platform that helps businesses automate and scale customer communication with smart voice technology.", "founder": { "@type": "Person", "name": "Ketan Mangukiya", "jobTitle": "Founder & CEO" }, "address": { "@type": "PostalAddress", "streetAddress": "1915, 447 Broadway, 2nd Floor", "addressLocality": "New York", "addressRegion": "NY", "postalCode": "10013", "addressCountry": "US" } }
		</script>
		<script type="application/ld+json">
		{ "@context": "https://schema.org", "@type": "CreativeWorkSeries", "name": "Automate Your Business With Botphonic AI Call Assistant", "aggregateRating": { "@type": "AggregateRating", "ratingValue": 5, "bestRating": 5, "ratingCount": 25527 } }
		</script>

	</head>

	<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
		<?php do_action( 'wp_body_open' ); ?>
		<div class="site" id="page">

			<!-- ******************* The Navbar Area ******************* -->
			<style>
				header{ padding: 0 12px; background: var(--bg-light-2); }
				header .container{ padding: 0;}
				header .navbar{ height: 90px; }
				header .navbar .site-logo{ max-width: 150px; }
				header .navbar #main-menu a{ line-height: normal; }
			</style>
			<header id="wrapper-navbar">
				<nav id="main-nav" class="navbar navbar-expand-md" aria-labelledby="main-nav-label">
					<div class="container">
						<div class="site-logo">
							<?php the_custom_logo(); ?>
						</div>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>">
							<span class="navbar-toggler-icon"></span>
						</button>

						<!-- The WordPress Menu goes here -->
						<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'primary',
								'container_class' => 'collapse navbar-collapse',
								'container_id'    => 'navbarNavDropdown',
								'menu_class'      => 'navbar-nav ms-auto',
								'fallback_cb'     => '',
								'menu_id'         => 'main-menu',
								'depth'           => 2,
								'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
							)
						);
						?>
					</div><!-- .container(-fluid) -->
				</nav> 
			</header><!-- #wrapper-navbar -->