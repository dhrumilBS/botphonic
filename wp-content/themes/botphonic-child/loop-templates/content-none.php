<?php

/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<section class="no-results not-found">
	<div class="page-content">
		<h2 class="page-title"><?php esc_html_e('Nothing Found', 'understrap'); ?></h2>
		<p> Sorry, but nothing matched your search terms. Please try again with some different keywords. </p>
 		<a class="theme-btn d-inline-block " href="<?= site_url(); ?>"> Goto Home</a>
	</div>
</section>