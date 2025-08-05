<?php get_header(); ?>
<div class="post-single">
	<div class="light-bg-2 section-padded">
		<div class="container">
			<div class="row align-items-center ">
				<div class="post-title text-center">
					<div class="elementor-temnplate"> Preview Of elementor_library_<?= get_the_ID(); ?> </div>
					<h1><?= get_the_title(); ?></h1>
				</div>
			</div>
		</div>
	</div>

	<div class="post-continer section-padded">
		<div class="post-content"> <?php the_content(); ?> </div>
	</div>
</div>
<?php get_footer(); ?>