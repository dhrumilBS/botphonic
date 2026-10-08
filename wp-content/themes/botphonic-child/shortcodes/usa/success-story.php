<?php
add_shortcode('success_story', 'success_story_fn');


function success_story_fn($atts)
{
	wp_enqueue_style('swiper-css');
	wp_enqueue_script('swiper-js');
	wp_enqueue_script('success-story-swiper');

	$a = shortcode_atts(
		[
			'pretitle' => 'Get Started',
			'title' => 'Try Your AI Customer Service Agent Now',
			'text' => 'Discover how Botphonic AI automation has helped businesses across industries achieve measurable success.',
		],
		$atts
	);

	ob_start();

	$success_stories = [];
	$q = new WP_Query(['post_type' => 'success-stories', 'posts_per_page' => 10]);
	while ($q->have_posts()): $q->the_post();
		$tags = wp_get_post_terms(get_the_ID(), 'post_tag', ['fields' => 'names']);
		$text = get_the_content();

		$statics = [];
		if (have_rows('statics')) :
			while (have_rows('statics')) : the_row();
				$statics[] = ['value' => get_sub_field('value'), 'label' => get_sub_field('label'),];
			endwhile;
		endif;

		$success_stories[] = ['id' => get_the_ID(), 'title' => get_the_title(), 'tags' => $tags, 'text' => wp_strip_all_tags($text), 'statics' => $statics,];
	endwhile;
	wp_reset_postdata();

	$success_stories = [
		[
			'id' => 3507,
			'tags' => ["Customer Support", "Sales Qualification", "Voice AI Agent"],
			'text' => "Granting loans to customers and managing money for future transactions. Utilized Botphonic Voice AI Agent for client interaction at scale and examining data in a few seconds.",
			'statics' => [
				['value' => '+30%', 'label' => 'Churn Reduction'],
				['value' => '+32%', 'label' => 'Enhance Client Satisfaction'],
				['value' => '20%', 'label' => 'Decrease Customer Costs'],
			]
		],
		[
			'id' => 3528,
			'tags' => ["Lead Reactivation", "Client Retention", "AI Call Assistant"],
			'text' => "By using Botphonic AI call assistant, a tech company automated lead re-engagement across different regions and boosts sales conversion. The AI re-engaged cold leads with human-like conversations, qualified demand across regions and languages, and most importantly increased conversions.",
			'statics' => [
				['value' => '+40%', 'label' => 'Response Rate'],
				['value' => '60%', 'label' => 'Minimize Workload'],
				['value' => '30%', 'label' => 'Enhance Qualify Leads'],
			]
		],
		[
			'id' => 3026,
			'tags' => ["Call Management", "Instant Notifications ", "Voice AI"],
			'text' => "A leading digital marketing company replaced traditional customer communication methods with Botphonic’s Voice AI agents to respond quickly across regions and channels. AI helped them handle high inquiry volumes, delivered professional, and personalized responses.",
			'statics' => [
				['value' => '+82%', 'label' => 'Managing Client Queries'],
				['value' => '+40%', 'label' => 'Minimizing Agents Costs'],
				['value' => '+20%', 'label' => 'Uplifting Workforce Productivity'],
			]
		],
	]; ?>


	<div class="section-padded">
		<div class="container">
			<div class="text-center mb-5 mx-auto" style="max-width: 800px;">
				<div class="text-gradient small-text text-uppercase h6"><?= $a['pretitle'] ?></div>
				<h2 class="fw-bold display-6"><?= $a['title'] ?></h2>
				<p><?= $a['text'] ?></p>
			</div>
		</div>
		<div class="container" style="max-width: 1200px;">
			<div class="swiper successStorySwiper p-2">
				<div class="swiper-wrapper">
					<?php foreach ($success_stories as $story) { ?>
						<div class="swiper-slide">
							<div class="case-card" href="#">
								<div class="case-card_content">
									<div class="case-card_top">
										<?php foreach ($story['tags'] as $tag) { ?>
											<div data-wf--label--variant="base" class="label-main"><?= $tag ?></div>
										<?php } ?>
									</div>
									<div class="case-card_mid">
										<div class="p-16 dark-70"><?= $story['text'] ?></div>
									</div>
									<div class="case-card_bot">
										<?php foreach ($story['statics'] as $stat) { ?>
											<div class="case-card_results">
												<div class="text-gradient"><?= $stat['value']; ?></div>
												<div class="mono"><?= $stat['label']; ?></div>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="case-card_img-wrap">
									<?= get_the_post_thumbnail($story['id'], 'full', ['class' => "case-card_img"]) ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="swiper-abs_arrows">
					<div class="case-arrow prev">
						<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 8 15" fill="none" class="case-svg new">
							<path d="M7 13.5L1 7.5L7 1.5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</div>
					<div class="case-arrow next">
						<svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 8 15" fill="none" class="case-svg new">
							<path d="M1 13.5L7 7.5L1 1.5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}
?>