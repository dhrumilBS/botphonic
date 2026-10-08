<?php

/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<a href="<?php echo esc_url(get_permalink()); ?>" class="link-img d-block">

		<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('full');
		}
		?>
	</a>

	<div class="entry-content">
		<?php
		$categories = get_the_category();
		if (!is_single() && !empty($categories)) {
			$cat = $categories[0];
		?>
			<div class="entry-meta d-flex align-items-center gap-2">
				<a class="cat-name --fs14" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
					<?php echo esc_html($cat->cat_name); ?>
				</a>
				<span class="date --fs12"><?php echo esc_html(get_the_date('d/m/Y')); ?></span>
			</div>
		<?php } ?>

		<?php
		the_title(
			sprintf('<h2 class="entry-title h3 mb-0"><a href="%s" rel="bookmark">', esc_url(get_permalink())),
			'</a></h2>'
		);
		?>
	</div>
</article>