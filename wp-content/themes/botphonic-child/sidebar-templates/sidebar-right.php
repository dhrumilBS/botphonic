<?php
defined( 'ABSPATH' ) || exit;
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<style>
	.blog-cta-form-section { background:url(/wp-content/themes/botphonic-child/assets/img/blog-cta-bg.svg), var(--accent); background-position: bottom right; background-size: auto 250px; background-repeat:no-repeat; padding: 20px; border-radius: 16px; border: 1px solid #E4E7EC; }
	.widget_block + .widget_block { margin-top: 24px; }

	.blog-cta-form-wrapper .cta-title { color: #fff; font-size: 24px; font-weight: 700;line-height:1.25; margin-bottom: 10px; }
	.blog-cta-form-wrapper li { color: #FFF; margin-bottom: 8px; list-style: none; position: relative; }
	.blog-cta-form-wrapper li::before { content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 600; margin-right: 8px; }
	.blog-cta-form-section .blog-cta-form-wrapper .theme-btn { display: block; padding: 8px; text-align: center; color: #202020; background: #fff;  }
	@media screen and (max-width: 767px) {
		.blog-cta-form-section { margin: 24px auto 0; max-width: 540px; }
	}
	@media screen and (min-width: 1200px) {
		.wp-singular #right-sidebar { height: 90vh; margin-bottom: 20px; overflow: auto; }
	}
</style>

<div class="col-lg-3 widget-area" id="right-sidebar">
	<aside class="widget widget_block blog-cta-form-section">
		<div class="blog-cta-form-wrapper">
			<p class="cta-title">Explore Botphonic with No Costs</p>
			<ul>
				<li>Automate conversation</li>
				<li>Sales management</li>
				<li>50+ human-sounding voices</li>
			</ul>
			<a class="theme-btn button" href="https://app.botphonic.ai/register">Try it Now!</a>
		</div>
	</aside>
	
	<aside class="widget widget_block blog-toc-section">
		<div class="blog-toc-wrapper">
			<?= do_shortcode('[lwptoc]'); ?>
		</div>
	</aside>
</div>
