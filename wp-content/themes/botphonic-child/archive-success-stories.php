<?php

/**
 * Customer Stories archive.
 *
 * Structural twin of archive.php, so the listing reads as one family with the
 * blog: the same .bpg-arc-hero / .bpg-grid / .bpg-pagination-wrap / .bpg-band
 * skeleton from assets/css/blog.css, with the story-specific pieces
 * (proof row, result chips on the cards) layered on by
 * assets/css/success-stories.css.
 *
 * The inline <style> block that used to live here is gone: .success-card and
 * friends were the only rules in the child theme not sharing the brand tokens.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$story_paged = max(1, (int) get_query_var('paged'));
$story_total = (int) $GLOBALS['wp_query']->found_posts;
$story_pages = (int) $GLOBALS['wp_query']->max_num_pages;
$story_description = get_the_archive_description();

// The first story on page one carries the listing: a case study earns more
// space than a blog post, and the featured card is already in the design
// system (blog.css § 14).
$story_index = 0;
$story_feature_first = (1 === $story_paged && $story_total > 2);

$story_proof = array(
	array('icon' => 'phone', 'text' => 'Live production deployments'),
	array('icon' => 'trend', 'text' => 'Measured before-and-after results'),
	array('icon' => 'target', 'text' => 'Named teams, real call volumes'),
);
?>

<?php
// `bpg-archive` opts into the blog design system (reset, headings, cards,
// pagination); `bpg-story` is the story layer's own hook. Both are repeated on
// the wrapper rather than relied on from body_class, so the view still styles
// correctly if the page is ever rendered outside the main query.
?>
<div class="bpg-archive bpg-story" id="archive-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<?php
				// Home → Customer Stories. No "Page N" tail: the count line
				// below already says which page this is.
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array(
						'show_paged' => false,
					));
				}
				?>

				<p class="bpg-eyebrow">Customer stories</p>

				<h1 class="bpg-arc-hero__title">See what Botphonic changed, in numbers</h1>

				<?php if ($story_description) : ?>
				<div class="bpg-arc-hero__lede"><?php echo wp_kses_post($story_description); ?></div>
				<?php else : ?>
				<p class="bpg-arc-hero__lede">
					How real teams put their phone lines on autopilot &mdash; the problem they started with,
					what their AI voice agent does today, and the results they can measure.
				</p>
				<?php endif; ?>

				<ul class="bpg-arc-hero__proof">
					<?php foreach ($story_proof as $story_item) : ?>
					<li>
						<?php echo botphonic_story_icon($story_item['icon']); ?>
						<?php echo esc_html($story_item['text']); ?>
					</li>
					<?php endforeach; ?>
				</ul>

			</div>
		</div>
	</header>

	<!-- ── Listing ──────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<?php if (have_posts()) : ?>

					<?php if ($story_total) : ?>
					<p class="bpg-arc-count">
						<?php
						echo esc_html(number_format_i18n($story_total)) . ' ';
						echo esc_html(_n('customer story', 'customer stories', $story_total, 'botphonic'));

						if ($story_pages > 1) {
							echo esc_html(
								sprintf(
									/* translators: 1: current page, 2: total pages. */
									' · page %1$s of %2$s',
									number_format_i18n($story_paged),
									number_format_i18n($story_pages)
								)
							);
						}
						?>
					</p>
					<?php endif; ?>

					<div class="bpg-grid">
						<?php
						while (have_posts()) :
							the_post();
							$story_index++;
							$story_is_feature = ($story_feature_first && 1 === $story_index);

							get_template_part(
								'loop-templates/content',
								'storycard',
								array(
									'variant' => $story_is_feature ? 'featured' : 'default',
									'flag' => $story_is_feature ? 'Latest story' : '',
								)
							);
						endwhile;
						?>
					</div>

					<?php
					// Only printed when there is more than one page: an empty
					// wrapper would still contribute its top margin.
					if ($story_pages > 1) :
					?>
					<div class="bpg-pagination-wrap">
						<?php understrap_pagination(array(), 'bpg-pagination'); ?>
					</div>
					<?php endif; ?>

				<?php else : ?>

					<div class="bpg-empty">
						<h2 class="bpg-empty__title">No stories published yet</h2>
						<p>The first customer stories are being written up. In the meantime, see what the platform does on a live call.</p>
						<p class="bpg-empty__action">
							<a class="bpg-btn bpg-btn--ghost" href="/contact/">Book a demo</a>
						</p>
					</div>

				<?php endif; ?>

			</main>
		</div>
	</div>

	<!-- ── CTA band ─────────────────────────────────────────────────── -->
	<section class="bpg-band" aria-labelledby="bpg-band-title">
		<div class="bpg-shell">
			<div class="bpg-band__inner">
				<div class="bpg-band__copy">
					<p class="bpg-eyebrow">Your story next</p>
					<h2 class="bpg-band__title" id="bpg-band-title">Put the same agent on your busiest line</h2>
					<p class="bpg-band__text">
						Spin up an AI voice agent, point it at a number, and let it answer, qualify and book &mdash;
						24/7, in 50+ languages, with a transcript and summary after every call.
					</p>
				</div>
				<div class="bpg-band__row">
					<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register" rel="noopener">Start free trial</a>
					<a class="bpg-btn bpg-btn--ghost" href="/contact/">Book a demo</a>
				</div>
			</div>
		</div>
	</section>

</div><!-- #archive-wrapper -->

<?php get_footer(); ?>
