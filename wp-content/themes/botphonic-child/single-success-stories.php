<?php

/**
 * Single Customer Story.
 *
 * Structural twin of single.php, so a case study reads as part of the same
 * family as the blog: the reading-progress rail, .bpg-shell, the sticky
 * sidebar (.bpg-layout / .bpg-aside / .bpg-main), the .bpg-toc section nav,
 * the share rail, .bpg-endcta and the related grid all come from
 * assets/css/blog.css and assets/js/blog.js unchanged. The story layer adds the
 * dark hero, the metric row, the numbered narrative sections and the outcome
 * cards (assets/css/success-stories.css).
 *
 * The narrative itself comes from botphonic_story_sections(), which is the one
 * place that decides which ACF sections exist and in what order — so the nav
 * in the sidebar can never list a section the body does not render.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

/*
 * Enter the loop before reading anything. the_post() is what sets up $authordata
 * and the $page/$pages globals, which the_content() and any block or plugin
 * filter hanging off it expect to find — relying on the $post global alone
 * (as this template used to) leaves them unset.
 */
if (have_posts()) {
	the_post();
}

global $post;

$story_badge = botphonic_story_text('badge_label');
$story_headline = botphonic_story_headline($post);
$story_lede = botphonic_story_lede($post);
$story_stats = botphonic_story_stats($post);
$story_hero_stats = array_slice($story_stats, 0, 4);
$story_sections = botphonic_story_sections($post);
$story_has_cover = has_post_thumbnail($post);
$story_share = function_exists('botphonic_blog_share_links') ? botphonic_blog_share_links($post) : array();
$story_permalink = get_permalink($post);
$story_related = botphonic_story_related($post, 3);
$story_archive_url = get_post_type_archive_link(botphonic_story_post_type());

/*
 * Guarded on shortcode_exists(): do_shortcode() leaves an unregistered tag
 * untouched, so if the botPhonic plugin is ever deactivated or mid-update the
 * unguarded call would print the literal "[botphonic_faq]" to visitors inside a
 * styled FAQ section.
 */
$story_faq = shortcode_exists('botphonic_faq') ? do_shortcode('[botphonic_faq]') : '';

// The editor is optional on this post type — the story is told through the ACF
// sections. Whatever is in the editor is appended as a closing section.
$story_body = trim(apply_filters('the_content', get_the_content()));
?>

<div class="bpg-progress" aria-hidden="true">
	<div class="bpg-progress__bar" data-bpg-progress></div>
</div>

<?php
// `bpg-single` opts into the blog design system; `bpg-story` is the story
// layer's own hook. Both are repeated here rather than relied on from
// body_class, so the view still styles correctly in any secondary context.
?>
<div class="main-wrapper bpg-single bpg-story" id="single-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-story-hero">
		<div class="bpg-shell">
			<div class="bpg-story-hero__grid<?php echo $story_has_cover ? ' bpg-story-hero__grid--split' : ''; ?>">

				<div class="bpg-story-hero__body">

					<?php
					// Home → Customer Stories. The title is the <h1> directly
					// below, so the trail stops before repeating it.
					if (function_exists('wpdev_breadcrumbs')) {
						wpdev_breadcrumbs(array(
							'show_current' => false,
						));
					}
					?>

					<?php if ($story_badge) : ?>
					<p class="bpg-story-badge">
						<?php echo botphonic_story_icon('building'); ?>
						<?php echo esc_html($story_badge); ?>
					</p>
					<?php else : ?>
					<p class="bpg-eyebrow">Customer story</p>
					<?php endif; ?>

					<h1 class="bpg-story-hero__title"><?php echo esc_html($story_headline); ?></h1>

					<?php if ($story_lede) : ?>
					<p class="bpg-story-hero__lede"><?php echo esc_html($story_lede); ?></p>
					<?php endif; ?>

					<?php if ($story_hero_stats) : ?>
					<?php
					// The metric count drives the grid: success-stories.css § 3
					// keeps them on one row, so four tiles read as four
					// quarters of one result set instead of 3 + 1.
					?>
					<ul class="bpg-stats" style="--bpg-stat-count:<?php echo esc_attr(count($story_hero_stats)); ?>">
						<?php foreach ($story_hero_stats as $story_stat) : ?>
						<li class="bpg-stat">
							<p class="bpg-stat__num">
								<?php if ('' !== $story_stat['prefix']) : ?>
								<span class="bpg-stat__affix"><?php echo esc_html($story_stat['prefix']); ?></span>
								<?php endif; ?>

								<?php
								/*
								 * The final value is printed server-side and the
								 * count-up in assets/js/success-stories.js only
								 * takes over when the tile scrolls into view, so
								 * the figure is correct with JavaScript off and
								 * for anything reading the page as text.
								 */
								$story_target = botphonic_story_stat_target($story_stat['value']);
								?>
								<span<?php echo $story_target ? ' data-bpg-count="' . esc_attr($story_target) . '" data-bpg-count-text="' . esc_attr($story_stat['value']) . '"' : ''; ?>><?php echo esc_html($story_stat['value']); ?></span>

								<?php if ('' !== $story_stat['suffix']) : ?>
								<span class="bpg-stat__affix"><?php echo esc_html($story_stat['suffix']); ?></span>
								<?php endif; ?>
							</p>
							<?php if ($story_stat['label']) : ?>
							<p class="bpg-stat__label"><?php echo esc_html($story_stat['label']); ?></p>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<div class="bpg-story-hero__meta">
						<p class="bpg-meta">
							<span class="bpg-meta__item">
								<?php echo botphonic_story_icon('calendar'); ?>
								<time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('F j, Y')); ?></time>
							</span>
							<span class="bpg-meta__sep" aria-hidden="true"></span>
							<span class="bpg-meta__item">
								<?php echo botphonic_story_icon('spark'); ?>
								Verified customer story
							</span>
						</p>
					</div>

				</div>

				<?php if ($story_has_cover) : ?>
				<figure class="bpg-story-hero__media">
					<?php
					the_post_thumbnail(
						'full',
						array(
							'fetchpriority' => 'high',
							'decoding' => 'async',
						)
					);
					?>
				</figure>
				<?php endif; ?>

			</div>
		</div>
	</header>

	<!-- ── Body ─────────────────────────────────────────────────────── -->
	<div class="bpg-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<div class="bpg-layout">

				<aside class="bpg-aside" aria-label="Story navigation">
					<div class="bpg-aside__sticky">

						<?php
						/*
						 * The shared card. botphonic_story_sections() entries go
						 * in as they are — the partial reads their 'title' where
						 * the other two views pass a 'label'.
						 *
						 * The count guard stays here rather than moving into the
						 * partial: an empty list prints nothing on its own, but
						 * "one section is not worth a nav" is this view's own
						 * judgement about a five-part blueprint that can collapse
						 * to one when most of the ACF fields are empty.
						 */
						if (count($story_sections) > 1) {
							get_template_part('template-parts/toc', '', array(
								'items' => $story_sections,
								'title' => 'In this story',
								'close_label' => 'Unpin story navigation',
								'nav_id' => 'bpg-story-nav',
							));
						}
						?>

						<div class="bpg-side-cta">
							<p class="bpg-side-cta__eyebrow">Free 14-day trial</p>
							<p class="bpg-side-cta__title">Get these results on your own phone line</p>
							<ul class="bpg-side-cta__list">
								<li>Answers in under 300&nbsp;ms, 24/7</li>
								<li>Books straight into your calendar</li>
								<li>Syncs with Salesforce, HubSpot &amp; Zoho</li>
							</ul>
							<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register" rel="noopener">Start free trial</a>
							<p class="bpg-side-cta__note">No seat licences. Cancel anytime.</p>
						</div>

					</div>
				</aside>

				<main class="site-main bpg-main" id="main">

					<div data-bpg-article>

						<?php if ($story_sections) : ?>
							<?php foreach ($story_sections as $story_section) : ?>
							<section class="bpg-story-sec" aria-labelledby="<?php echo esc_attr($story_section['id']); ?>">

								<h2 class="bpg-story-sec__title" id="<?php echo esc_attr($story_section['id']); ?>">
									<span><?php echo esc_html($story_section['title']); ?></span>
								</h2>

								<?php if ($story_section['content']) : ?>
								<div class="bpg-story-sec__body">
									<?php echo botphonic_story_prose($story_section['content']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_story_prose(). ?>
								</div>
								<?php endif; ?>

								<?php if ($story_section['image']) : ?>
								<figure class="bpg-story-figure">
									<?php
									if ($story_section['image_id']) {
										echo wp_get_attachment_image(
											$story_section['image_id'],
											'large',
											false,
											array(
												'loading' => 'lazy',
												'decoding' => 'async',
											)
										);
									} else {
										printf(
											'<img src="%1$s" alt="%2$s" loading="lazy" decoding="async" />',
											esc_url($story_section['image']),
											esc_attr($story_section['title'] . ' — ' . get_the_title())
										);
									}
									?>
								</figure>
								<?php endif; ?>

								<?php if ($story_section['outcomes']) : ?>
								<ul class="bpg-outcomes">
									<?php foreach ($story_stats as $story_stat) : ?>
									<li class="bpg-outcome">

										<span class="bpg-outcome__icon" aria-hidden="true">
											<?php
											$story_icon_id = botphonic_story_image_id($story_stat['icon']);

											if ($story_icon_id) {
												echo wp_get_attachment_image(
													$story_icon_id,
													'thumbnail',
													false,
													array(
														'alt' => '',
														'loading' => 'lazy',
													)
												);
											} else {
												echo botphonic_story_icon('trend');
											}
											?>
										</span>

										<?php if ('' !== $story_stat['value']) : ?>
										<p class="bpg-outcome__num">
											<?php if ('' !== $story_stat['prefix']) : ?>
											<span class="bpg-stat__affix"><?php echo esc_html($story_stat['prefix']); ?></span>
											<?php endif; ?>
											<span><?php echo esc_html($story_stat['value']); ?></span>
											<?php if ('' !== $story_stat['suffix']) : ?>
											<span class="bpg-stat__affix"><?php echo esc_html($story_stat['suffix']); ?></span>
											<?php endif; ?>
										</p>
										<?php endif; ?>

										<?php if ($story_stat['label']) : ?>
										<p class="bpg-outcome__label"><?php echo esc_html($story_stat['label']); ?></p>
										<?php endif; ?>

										<?php if ($story_stat['description']) : ?>
										<p class="bpg-outcome__text"><?php echo esc_html($story_stat['description']); ?></p>
										<?php endif; ?>

									</li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>

							</section>
							<?php endforeach; ?>
						<?php endif; ?>

						<?php
						// Anything typed into the editor. Kept last so the ACF
						// narrative stays the spine of the page.
						if ($story_body) :
						?>
						<section class="bpg-story-sec">
							<div class="bpg-story-sec__body">
								<?php echo $story_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already through the_content filters. ?>
							</div>
						</section>
						<?php endif; ?>

						<?php if (!$story_sections && !$story_body) : ?>
						<div class="bpg-empty">
							<h2 class="bpg-empty__title">This story is still being written</h2>
							<p>Check back shortly, or browse the stories that are already published.</p>
							<?php if ($story_archive_url) : ?>
							<p class="bpg-empty__action">
								<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url($story_archive_url); ?>">All customer stories</a>
							</p>
							<?php endif; ?>
						</div>
						<?php endif; ?>

					</div>

					<!-- Share -->
					<?php if ($story_share) : ?>
					<div class="bpg-share">
						<p class="bpg-share__label">Share this story</p>
						<div class="bpg-share__row">
							<?php foreach ($story_share as $story_link) : ?>
							<a class="bpg-share__btn" href="<?php echo esc_url($story_link['url']); ?>" target="_blank" rel="noopener nofollow" aria-label="Share on <?php echo esc_attr($story_link['label']); ?>">
								<?php echo botphonic_story_icon($story_link['network']); ?>
							</a>
							<?php endforeach; ?>
							<button class="bpg-share__btn bpg-share__btn--copy" type="button" data-bpg-copy="<?php echo esc_url($story_permalink); ?>">
								<?php echo botphonic_story_icon('link'); ?>
								<span data-bpg-copy-label>Copy link</span>
							</button>
						</div>
					</div>
					<?php endif; ?>

					<!-- End-of-story CTA -->
					<section class="bpg-endcta" aria-labelledby="bpg-endcta-title">
						<p class="bpg-eyebrow">Ready for results like these?</p>
						<h2 class="bpg-endcta__title" id="bpg-endcta-title">Put every business call on autopilot</h2>
						<p class="bpg-endcta__text">
							Inbound answering, AI reception and outbound campaigns from one platform &mdash; sub-300&nbsp;ms responses,
							50+&nbsp;languages, and a full transcript and summary after every call.
						</p>
						<div class="bpg-endcta__row">
							<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register" rel="noopener">Start free trial</a>
							<a class="bpg-btn bpg-btn--ghost" href="/contact/">Talk to sales</a>
						</div>
						<ul class="bpg-endcta__proof">
							<li>14-day free trial</li>
							<li>No seat licences</li>
							<li>HIPAA &amp; PCI&nbsp;DSS ready</li>
						</ul>
					</section>

					<!-- FAQs (ACF-driven; shortcode and its schema are unchanged) -->
					<?php if (trim($story_faq)) : ?>
					<section class="bpg-faqs" aria-label="Frequently asked questions">
						<?php echo $story_faq; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output. ?>
					</section>
					<?php endif; ?>

					<!-- More customer stories -->
					<?php if ($story_related instanceof WP_Query) : ?>
					<section class="bpg-related" aria-labelledby="bpg-related-title">
						<div class="bpg-sec-head">
							<h2 class="bpg-sec-head__title" id="bpg-related-title">More customer stories</h2>
							<?php if ($story_archive_url) : ?>
							<a class="bpg-sec-head__link" href="<?php echo esc_url($story_archive_url); ?>">
								All stories
								<?php echo botphonic_story_icon('arrow-right'); ?>
							</a>
							<?php endif; ?>
						</div>

						<div class="bpg-grid bpg-grid--related">
							<?php
							while ($story_related->have_posts()) :
								$story_related->the_post();
								get_template_part('loop-templates/content', 'storycard');
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</section>
					<?php endif; ?>

				</main>
			</div>
		</div>
	</div>

</div><!-- #single-wrapper -->

<?php get_footer(); ?>
