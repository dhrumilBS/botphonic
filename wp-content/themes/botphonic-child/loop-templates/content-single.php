<?php
global $post;


wp_enqueue_style('botphonic-faqs-css');
wp_enqueue_script('botphonic-faqs-js');

?>
<article <?php post_class('card-layout'); ?> id="post-<?php the_ID(); ?>">
	<div class="single-hero-inside">
		<div class="image-container single-hero-fit">
			<?= get_the_post_thumbnail(); ?>
		</div>
		<div class="single-content-inner"> <?php the_content(); ?> </div>
	</div>
</article>


<div class="single-next-prev-posts-container">
	<div class="single-next-prev-posts card-layout">
		<div class="single-next-prev-posts-title">
			<div class="title h4">
				<span class="title-inner">
					<span class="title-text">F.A.Q s</span>
				</span>
			</div>
		</div>

		<?php if (have_rows('faqs')) { ?>
			<div class="faq-section accordion-list">
				<?php
				$faq_icon = '<div class="faq-icon-plus">
					<svg xmlns="http://www.w3.322000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" preserveAspectRatio="xMidYMid meet" aria-hidden="true" role="img">
						<path d="M8 16H24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M16 24V8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>
				<div class="faq-icon-minus">
					<svg xmlns="http://www.w3.322000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" preserveAspectRatio="xMidYMid meet" aria-hidden="true" role="img">
						<path d="M8 16H24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>';

				$faqs = get_field('faqs')
				?>

				<div class="faq-content">
					<div class="faq-content-wrap">
						<?php foreach ($faqs as $faq) { ?>
							<div class="faq-item">
								<div class="faq-head">
									<div class="faq-item-heading h3"><?= $faq['question']; ?></div>
									<?= $faq_icon; ?>
								</div>
								<div class="faq-text">
									<div class="faq-paragraph"><?= ($faq['answer']); ?></div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php
				$json = [
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => [],
				];

				foreach (get_field('faqs') as $index => $item) {
					$json['mainEntity'][] = [
						'@type' => 'Question',
						'name' => $item['question'],
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text' => esc_html($item['answer']),
						],
					];
				}
				?>
				<script type="application/ld+json">
					<?= wp_json_encode($json); ?>
				</script>
			</div>
		<?php
		} ?>

	</div>
</div>

<?php
$prev_post = get_previous_post();
$next_post = get_next_post();
if (!empty($prev_post) || !empty($next_post)) {
?>
	<div class="single-next-prev-posts-container">
		<div class="single-next-prev-posts card-layout">
			<div class="single-next-prev-posts-title">
				<div class="h4 title">
					<span class="title-inner">
						<span class="title-text">Other Articles</span>
					</span>
				</div>
			</div>

			<div class="next-prev-row">
				<?php
				if (!empty($prev_post)):
					$prev_thumb = get_the_post_thumbnail_url($prev_post->ID, 'thumbnail');
				?>
					<div class="next-prev-col">
						<div class="post-wrapper prev-post">
							<div class="image ">
								<a href="<?php echo get_permalink($prev_post->ID); ?>">
									<img src="<?php echo esc_url($prev_thumb); ?>" alt="<?php echo esc_attr($prev_post->post_title); ?>" width="150" height="150" />
								</a>
							</div>
							<div class="content">
								<div class="next-prev-label">
									<span class="text">Previous</span>
								</div>
								<div class="title h3">
									<a href="<?php echo get_permalink($prev_post->ID); ?>">
										<?php echo esc_html($prev_post->post_title); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php
				if (!empty($next_post)):
					$next_thumb = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
				?>
					<div class="next-prev-col">
						<div class="post-wrapper next-post">
							<div class="image">
								<a href="<?php echo get_permalink($next_post->ID); ?>">
									<img src="<?php echo esc_url($next_thumb); ?>" alt="<?php echo esc_attr($next_post->post_title); ?>" width="150" height="150" />
								</a>
							</div>
							<div class="content">
								<div class="next-prev-label">
									<span class="text">Next</span>
								</div>
								<div class="title h3">
									<a href="<?php echo get_permalink($next_post->ID); ?>">
										<?php echo esc_html($next_post->post_title); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php } ?>

<script>
	jQuery(document).ready(function() {
		botPhonicFaqs()
	});
</script>