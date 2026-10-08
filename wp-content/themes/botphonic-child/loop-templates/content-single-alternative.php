<?php

/**
 * Single post body — "alternative" variant (category 15).
 *
 * Same shell as content-single.php minus the AI-summarise panel. The featured
 * image is rendered by the hero in single.php, so it is not repeated here.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;
?>

<article <?php post_class('bpg-article'); ?> id="post-<?php the_ID(); ?>">
	<div class="single-hero-inside">
		<div class="single-content-inner"><?php the_content(); ?></div>
	</div>
</article>
