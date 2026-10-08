<?php
add_action('widgets_init', function () {
	$footer_columns = 8;

	for ($i = 1; $i <= $footer_columns; $i++) {
		register_sidebar([
			'name'          => "Footer Column $i",
			'id'            => "footer-column-$i",
			'description'   => "Footer widget area - column $i",
			'before_widget' => '<aside id="%1$s" class="footer-list widget %2$s" role="complementary"><div class="footer-items">',
			'after_widget'  => '</div></aside>',
			'before_title'  => '<h4 class="subtitle small light widget-title">',
			'after_title'   => '</h4>',
		]);
	}
});
