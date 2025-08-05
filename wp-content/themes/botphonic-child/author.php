<?php

/**
 * The template for displaying the author pages
 *
 * Learn more: https://codex.wordpress.org/Author_Templates
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');
$author_id = get_the_author_meta('ID');
?>

<div class="wrapper" id="author-wrapper">
	<?php global $post;
	$author = get_queried_object();
	
	// print_r(get_user_meta(get_the_author_meta('ID')));
	?>
	<style>
		.author-template .container { max-width: 991px !important; }
		.author-hero { min-height: 250px; background-color: var(--theme-20); display: flex; align-items: center; justify-content: center; padding: 50px 0; margin-bottom: 150px; }
		.author-hero .author-section { display: flex; justify-content: center; align-items: center; margin-bottom: -155px; }
		.author-hero .author-section .author { display: flex; max-width: 100%; padding: 12px; background: #fff; border-radius: 8px; box-shadow: rgba(17, 12, 46, 0.15) 0px 48px 100px 0px;flex-wrap: wrap; gap: 16px; width: 100%;}
		.author-hero .author-section .author .author-profile { flex-shrink: 0;}
		.author-hero .author-section .author .author-profile img { border-radius: 10px; width: 100%; height: auto}
		.author-hero .author-section .author .about-author { display: flex; flex-direction: column;}
		.author-hero .author-section .author .about-author .author-name{ font-size: 20px;}

		.author-hero .author-section .author .about-author .author-follow { flex-grow: 1; align-content: flex-end}
		.author-hero .author-follow li { color: var(--p-color); }
		.author-hero .author-follow p { margin-bottom: 8px; font-size: 20px; line-height: 30px;}
		.author-hero .author-follow .social-media-list { display: flex; list-style: none; padding: 0; margin: 12px 0 0; gap: 16px}
		.author-hero .author-follow .social-media-list li a { display: flex; justify-content: center; align-items: center; padding: 12px; border-radius: 8px; background-color: rgb(var(--primary-rgb), 0.12);}
		.author-hero .author-follow .social-media-list li a:hover { color: #fff; background: var(--secondary);}
		.author-hero .author-follow .social-media-list li a svg { width: 24px; height: 24px;}
		@media screen and (min-width:575px) {
			.author-hero .author-section .author {flex-wrap: nowrap; 	padding: 20px; 	border-radius: 20px; }
			.author-hero .author-section .author .author-name{ font-size: 24px;}
			.author-hero .author-section .author .author-profile img { 	width: 200px; 	height: auto; }
		}
		@media screen and (min-width:991px) {
			.author-hero .author-section .author { 	max-width: 65%; }
			.author-hero .author-section .author .author-name{ font-size: 30px;}
		}

		@media screen and (min-width:1200px) {
			.author-hero .author-section .author { 	max-width: 90%; }
			.author-hero .author-section .author .author-name{ font-size: 36px;}
		}	

		.author-content { align-content font-size: 17px; text-align: justify; }
		.author-content .box-container { padding-bottom: 30px }
		.author-content .title { margin-bottom: 0.45em }
		.author-content p { margin-bottom: .5em; font-size: 17px; text-align: justify; }
		.author-content ul{ list-style-type: none; }
		.author-content li { color: var(--p-color);  position: relative; text-align: justify; margin-bottom: 8px;}
		.author-content li:before {content: ''; position: absolute; left: -20px; height: 100%; width: 12px; background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12ZM4.73731 12C4.73731 16.0111 7.98893 19.2627 12 19.2627C16.0111 19.2627 19.2627 16.0111 19.2627 12C19.2627 7.98893 16.0111 4.73731 12 4.73731C7.98893 4.73731 4.73731 7.98893 4.73731 12Z' fill='%232B7F75'/%3E%3C/svg%3E%0A"); background-size: contain; background-position:top 5px center; background-repeat: no-repeat;}
		.author-content a:not(.btn) { font-weight: 600; color: var(--theme-color); }
		.author-content .theme-btn { margin-top: 20px; }
	</style>
	<div class="author-template">
		<section class="author-hero hero">
			<div class="container">
				<div class="author-section">
					<div class="author">
						<div class="author-profile">
							<?php // get_field('user_profile_photo'); ?>
							<?= get_avatar(get_the_author_meta('ID'), '250'); ?>
						</div>

						<div class="about-author">
							<h1 class="author-name" ><?= $author->display_name; ?></h1>
							<p class="author-position"><?php echo get_user_meta( $author_id )['user_role'][0]; // get_user_meta( $author_id )['wpseo_title'][0]; ?></p> 

							<div class="author-follow">
								<ul class="social-media-list">
									<li>
										<a href="mailto:<?=get_the_author_email(); ?>" target="_blank" rel="nofollow noopener" title="E-mail">											
											<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" fill="currentcolor" viewBox="0 0 512 512" ><path d="m62.843 98.364 138.32 138.38c30.168 30.11 79.482 30.136 109.675 0l138.32-138.38a3.144 3.144 0 0 0-.426-4.814c-14.108-9.839-31.273-15.672-49.763-15.672H113.033c-18.491 0-35.656 5.834-49.764 15.672a3.144 3.144 0 0 0-.426 4.814zm-36.964 66.667a86.483 86.483 0 0 1 9.955-40.353 3.144 3.144 0 0 1 5.019-.762l136.569 136.569c43.247 43.31 113.885 43.335 157.158 0l136.569-136.569a3.144 3.144 0 0 1 5.019.762 86.498 86.498 0 0 1 9.955 40.353v181.937c0 48.093-39.121 87.154-87.154 87.154H113.033c-48.032 0-87.154-39.061-87.154-87.154z"></path></svg>
										</a>
									</li> 
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="author-content ">
			<div class="container">
				<?= get_user_meta( $author_id )['user_description'][0]; ?>
			</div>
		</section>
	</div>

</div><!-- #author-wrapper -->

<?php
get_footer();
