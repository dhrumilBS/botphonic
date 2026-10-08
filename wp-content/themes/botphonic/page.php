<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();
$container = get_theme_mod('understrap_container_type');
?>

<div class="main-wrapper " id="page-wrapper">
	<?php the_content(); ?>
</div><!-- #page-wrapper -->

<?php
get_footer();
