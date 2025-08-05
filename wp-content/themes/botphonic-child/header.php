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
		<meta name="robots" content="noindex, nofollow">
		<?php wp_head(); ?>
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
						<button
								class="navbar-toggler"
								type="button"
								data-bs-toggle="collapse"
								data-bs-target="#navbarNavDropdown"
								aria-controls="navbarNavDropdown"
								aria-expanded="false"
								aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
								>
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
