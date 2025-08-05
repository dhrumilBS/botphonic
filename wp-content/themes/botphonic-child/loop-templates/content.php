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
	<a href=" <?= get_permalink($post->ID); ?>" class="link-img">
		<?php if(is_user_logged_in()){ ?> 
		<span class=img-cat-name><?= get_the_category()[0]->cat_name; ?></span>
		<?php } ?>

		<?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
	</a>
	<div class="entry-content">
		<?php if (!(is_single()) && !empty(get_the_category())) { ?>
		<div class="entry-meta">
			<a class="cat-name --fs14" href="<?= get_category_link(get_the_category()[0]->term_id); ?>"><?= get_the_category()[0]->cat_name; ?></a>
			<span class="date --fs12"><?= date('d/m/Y ', strtotime(get_the_date())); ?></span>
		</div>
		<?php } ?>

		<?php
		the_title( sprintf('<h2 class="entry-title h3 mb-0"> <a href="%s" rel="bookmark">', get_permalink($post->ID)), '</a></h2>' );
		?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->