<?php

add_action('init', function () {
	if (isset($_GET['h']) && $_GET['h'] == 'true') {
		add_filter('show_admin_bar', '__return_true');
	} else {
		add_filter('show_admin_bar', '__return_false');
	}
});