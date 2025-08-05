<?php
defined( 'ABSPATH' ) || exit;

// if ( ! is_active_sidebar( 'left-sidebar' ) ) {
// 	return;
// }
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
<div class="col-md-3 widget-area" id="left-sidebar">
	<?php else : ?>
	<div class="col-md-4 widget-area" id="left-sidebar">
		<?php endif; ?>
		<style> 
			.blog-cta-form-section { background: var(--card-bg); padding: 20px; border-radius: var(--soft-radius); box-shadow: 0 2px 20px #0e0e130d; } 
			.blog-cta-container .cta-title { color: var(--primary); font-size: 20px; }
			.blog-cta-container .cta-description { color: var(--p-color); font-size: 14px; }
			.blog-cta-form-wrapper .wpcf7-form .w-form .w-form-field{ height: 36px; border-radius: 8px; font-size: 14px;  }
			.blog-cta-form-wrapper .wpcf7-form .w-form .w-textarea{ height: 96px; }
		</style>

		<section class="widget widget_block blog-cta-form-section">
			<div class="blog-cta-container">
				<h3 class="cta-title">Ready to Elevate Your Business?</h3>
				<p class="cta-description">Get in touch with our experts to explore how AI can transform your customer engagement.</p>
				<div class="blog-cta-form-wrapper"> <?= do_shortcode ('[contact-form-7 id="29405bd" title="Blog Single CTA Form"]'); ?> </div>
			</div>
		</section>

	</div><!-- #left-sidebar -->
