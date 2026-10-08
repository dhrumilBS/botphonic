<?php

/**
 * Single blog post.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

global $post;

$author_id = (int) $post->post_author;
$display_name = get_the_author_meta('display_name', $author_id);
if (empty($display_name)) {
	$display_name = get_the_author_meta('nickname', $author_id);
}

$author_position = get_user_meta($author_id, 'user_position', true);
$user_description = get_the_author_meta('user_description', $author_id);
$user_posts = get_author_posts_url($author_id);
$reading_time = botphonic_blog_reading_time($post);
$permalink = get_permalink($post);
$has_hero_image = has_post_thumbnail($post);
$share_links = botphonic_blog_share_links($post);
$hero_lede = has_excerpt($post) ? botphonic_blog_excerpt($post, 34) : '';

ob_start();
while (have_posts()):
	the_post();

	if (has_category(15)) {
		get_template_part('loop-templates/content', 'single-alternative');
	} else {
		get_template_part('loop-templates/content', 'single');
	}
endwhile;
$article_html = ob_get_clean();

$toc_html = do_shortcode('[lwptoc title="" toggle="0" width="full" float="none" colorScheme="inherit" smoothScroll="0"]');
$faq_html = do_shortcode('[botphonic_faq]');

$related = botphonic_blog_related_posts($post, 3);
$posts_page_id = (int) get_option('page_for_posts');
?>

<div class="bpg-progress" aria-hidden="true">
	<div class="bpg-progress__bar" data-bpg-progress></div>
</div>

<div class="main-wrapper bpg-single" id="single-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-hero">
		<div class="bpg-shell">
			<div class="bpg-hero__grid<?php echo $has_hero_image ? ' bpg-hero__grid--split' : ''; ?>">

				<div class="bpg-hero__body">

					<?php
					if (function_exists('wpdev_breadcrumbs')) {
						wpdev_breadcrumbs(array(
							'show_home' => false,
							'show_current' => false,
						));
					}
					?>

					<h1 class="bpg-hero__title"><?php echo esc_html(get_the_title()); ?></h1>

					<?php if ($hero_lede): ?>
						<p class="bpg-hero__lede"><?php echo esc_html($hero_lede); ?></p>
					<?php endif; ?>

					<div class="bpg-hero__byline">
						<div class="bpg-author-inline">
							<?php echo get_avatar($author_id, 88, '', esc_attr($display_name), array('loading' => 'lazy')); ?>
							<span class="bpg-author-inline__text">
								<a class="bpg-author-inline__name" href="<?php echo esc_url($user_posts); ?>" rel="author">
									<?php echo esc_html($display_name); ?>
								</a>
								<?php if (!empty($author_position)): ?>
									<span class="bpg-author-inline__role"><?php echo esc_html($author_position); ?></span>
								<?php endif; ?>
							</span>
						</div>

						<p class="bpg-meta">
							<span class="bpg-meta__item">
								<?php echo botphonic_blog_icon('calendar'); ?>
								<time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('F j, Y')); ?></time>
							</span>
							<span class="bpg-meta__sep" aria-hidden="true"></span>
							<span class="bpg-meta__item">
								<?php echo botphonic_blog_icon('clock'); ?>
								<?php echo esc_html($reading_time); ?>
							</span>
						</p>

					</div>

				</div>

				<?php if ($has_hero_image): ?>
					<figure class="bpg-hero__media">
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

				<aside class="bpg-aside" aria-label="Article tools">
					<div class="bpg-aside__sticky">

						<?php
						/*
						 * The shared card. This view is the only one of the three
						 * that has no entries array to hand it: the headings come
						 * from the lwptoc shortcode above as finished markup, so
						 * they go in as 'html' and the partial passes them
						 * through. assets/js/toc.js removes the card if the plugin
						 * found nothing to list.
						 */
						get_template_part('template-parts/toc', '', array(
							'html' => $toc_html,
							'aria_label' => 'Table of contents',
							'close_label' => 'Unpin table of contents',
						));
						?>

						<div class="bpg-side-cta">
							<p class="bpg-side-cta__eyebrow">Free 14-day trial</p>
							<p class="bpg-side-cta__title">Let an AI agent answer every call you miss</p>
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
						<?php echo $article_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered template output. ?>
					</div>

					<!-- Share -->
					<div class="bpg-share">
						<p class="bpg-share__label">Share this article</p>
						<div class="bpg-share__row">
							<?php foreach ($share_links as $share): ?>
								<a class="bpg-share__btn" href="<?php echo esc_url($share['url']); ?>" target="_blank" rel="noopener nofollow" aria-label="Share on <?php echo esc_attr($share['label']); ?>">
									<?php echo botphonic_blog_icon($share['network']); ?>
								</a>
							<?php endforeach; ?>
							<button class="bpg-share__btn bpg-share__btn--copy" type="button" data-bpg-copy="<?php echo esc_url($permalink); ?>">
								<?php echo botphonic_blog_icon('link'); ?>
								<span data-bpg-copy-label>Copy link</span>
							</button>
						</div>
					</div>

					<!-- End-of-post CTA -->
					<section class="bpg-endcta" aria-labelledby="bpg-endcta-title">
						<p class="bpg-eyebrow">Botphonic AI voice agents</p>
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
					<?php if (trim($faq_html)): ?>
						<section class="bpg-faqs" aria-label="Frequently asked questions">
							<?php echo $faq_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output. ?>
						</section>
					<?php endif; ?>

					<!-- Author -->
					<section class="bpg-authorbox" aria-labelledby="bpg-authorbox-name">

						<div class="bpg-authorbox__head">
							<div class="bpg-authorbox__avatar">
								<?php echo get_avatar($author_id, 168, '', esc_attr($display_name), array('loading' => 'lazy')); ?>
							</div>
							<div class="bpg-authorbox__ident">
								<p class="bpg-authorbox__label">About the author</p>
								<h2 class="bpg-authorbox__name" id="bpg-authorbox-name"><?php echo esc_html($display_name); ?></h2>
								<?php if (!empty($author_position)): ?>
									<p class="bpg-authorbox__role"><?php echo esc_html($author_position); ?></p>
								<?php endif; ?>
							</div>
						</div>

						<?php if (!empty($user_description)): ?>
							<p class="bpg-authorbox__desc"><?php echo wp_kses_post(nl2br($user_description)); ?></p>
						<?php endif; ?>

						<a class="bpg-authorbox__link" href="<?php echo esc_url($user_posts); ?>">
							View all posts by <?php echo esc_html($display_name); ?>
							<?php echo botphonic_blog_icon('arrow-right'); ?>
						</a>

					</section>

					<!-- Related articles -->
					<?php if ($related instanceof WP_Query): ?>
						<section class="bpg-related" aria-labelledby="bpg-related-title">
							<div class="bpg-sec-head">
								<h2 class="bpg-sec-head__title" id="bpg-related-title">Keep reading</h2>
								<?php if ($posts_page_id): ?>
									<a class="bpg-sec-head__link" href="<?php echo esc_url(get_permalink($posts_page_id)); ?>">
										All articles
										<?php echo botphonic_blog_icon('arrow-right'); ?>
									</a>
								<?php endif; ?>
							</div>

							<div class="bpg-grid bpg-grid--related">
								<?php
								while ($related->have_posts()):
									$related->the_post();
									get_template_part('loop-templates/content', 'bpgcard');
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