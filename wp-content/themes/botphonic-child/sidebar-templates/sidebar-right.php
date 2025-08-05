<?php
defined( 'ABSPATH' ) || exit;
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<style>
	.blog-cta-form-section { background: #fff; padding: 20px; border-radius: 16px ; box-shadow: 0 2px 20px #0e0e130d; border: 1px solid #E4E7EC; } 
	.widget_block + .widget_block { margin-top: 24px; } 

	.blog-cta-form-wrapper .cta-title { color: var(--primary); font-size: 20px; }
	.blog-cta-form-wrapper .cta-description { color: var(--p-color); font-size: 14px; }

	.blog-cta-form-wrapper .wpcf7-form .w-form .w-form-field{ height: 36px; border-radius: 8px; font-size: 14px;  }
	.blog-cta-form-wrapper .wpcf7-form .w-form .w-textarea{ height: 64px; }

	@media screen and (max-width: 767px){
		.blog-cta-form-section{ margin: 0 auto; margin-top: 24px; max-width: 540px; }
	}

	@media screen and ( min-width: 1200px ){
		.wp-singular #right-sidebar{ height: 100vh; margin-bottom: 20px; overflow: auto; }
	}
</style>

<div class="col-lg-4 widget-area" id="right-sidebar">
	<aside class="widget widget_block blog-cta-form-section">
		<div class="blog-cta-form-wrapper">
			<?= do_shortcode ('[contact-form-7 id="29405bd" title="Blog Single CTA Form"]'); ?>
		</div>
	</aside>

	<aside class="widget widget_block blog-toc-section">
		<div class="blog-toc-wrapper">
			<?= do_shortcode('[lwptoc]'); ?>
		</div>
	</aside>
</div><!-- #right-sidebar -->
