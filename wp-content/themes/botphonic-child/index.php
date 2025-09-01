<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>

<div class="main-wrapper">

	<section class="hero-section section-padded dark-bg--bg1">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-11 col-md">
					<div class="text-center">
						<div class="heading text-center">
							<span class="pre-title"> Our Blogs </span>
							<h1 class="title">Discovering the Future of Client Experience With AI Phone Call Assistant</h1>
							<p>Learn more about the AI-powered assistant, their characteristics and benefits to industries. Exploring how it will impact potential customers. </p>
						</div>
						<div class="" style="max-width: 500px; width: 100%; margin:0 auto; ">
						<?php get_search_form(); ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<style>

		.botphonic-pagination{
			--bs-pagination-color: var(--text);
			--bs-pagination-active-border-color: var(--bg-dark-1);
			--bs-pagination-active-bg: var(--bg-dark-1);
		}
		.page-link{ min-width: 38px; text-align: center; }

		.elementor-kit-6 h2.entry-title{ font-size: 24px; }
		.entry-content .post-excerpt{ -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; display: -webkit-box;}
		@media screen and (max-width:545px){
			.elementor-kit-6 h2.entry-title{ font-size: 20px; }
		}

		@media screen and (max-width: 1100px){
			.container{ max-width: 100%; }	
		}

	</style>
	<section class="client-section section-padded">
		<div class="container">
			<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
				<?php
				if (have_posts()) {
					while (have_posts()) {
						the_post();
						get_template_part('loop-templates/content', get_post_format());
					}
				} else {
					echo "<div class='col-12 col-lg-6 text-center offset-lg-3' >";
					get_template_part('loop-templates/content', 'none');
					echo "</div>";
				}
				?>
			</div>

			<?php understrap_pagination([], 'botphonic-pagination pagination'); ?>
		</div><!-- .row -->
	</section>
</div><!-- #index-wrapper -->

<?php
get_footer();