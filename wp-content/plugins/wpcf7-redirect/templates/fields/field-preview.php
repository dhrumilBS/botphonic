<?php
/**
 * Render preview iFrame
 *
 * @package Redirection for Contact Form 7
 */

defined( 'ABSPATH' ) || exit;

$preview_args             = $field['value'];
$preview_args['_wpnonce'] = wp_create_nonce( WPCF7r_Utils::PREVIEW_NONCE_ACTION );
?>

<div class="field-wrap field-wrap-<?php echo esc_attr( $field['name'] ); ?> <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : '' ); ?>">
	<iframe
		data-name="preview"
		class="preview-iframe"
		src="<?php echo esc_url( add_query_arg( $preview_args, admin_url() ) ); ?>"
		scrolling="no"
		style=" width: 100%; height: 500px;  overflow: hidden;"
	></iframe>
</div>
