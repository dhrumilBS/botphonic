<?php
get_header();
$args = array(
	'post_type'      => 'attachment',
	'post_status'    => 'inherit',
	'posts_per_page' => -1,
);
$attachments = get_posts($args);
?>
<div class="container">
	<h1> Update Image ALT Text </h1>
	<div style='display: flex; flex-wrap: wrap; gap: 10px'>
		<?php foreach ($attachments as $attachment) {
	$alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

	if (strpos($attachment->post_mime_type, 'image/') !== false) {
		echo "<div>";
		echo wp_get_attachment_image($attachment->ID, [150, 150]);
		echo '<p><em>';
		echo  $alt_text = ucwords(str_replace(['-', '_'], ' ', $attachment->post_title));
		echo '</em></p>';
		echo "</div>";
		// 		update_post_meta($attachment->ID, '_wp_attachment_image_alt', $alt_text);
	} 
} ?>
	</div>
</div>

<?php
get_footer();
