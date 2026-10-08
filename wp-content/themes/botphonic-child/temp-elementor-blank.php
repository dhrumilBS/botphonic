<?php
/**
 * Template Name: Elementor Blank (No Header/Footer)
 * Template Post Type: page
 */

get_header(); // Optional, not actually needed

while ( have_posts() ) : the_post();

the_content();

endwhile;

get_footer(); // Optional, not actually needed