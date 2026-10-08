<?php

/**
 * Blog archive (tags, dates and any archive without a more specific template).
 *
 * Shares the hero / filter rail / card grid / CTA band with category.php and
 * index.php so the listing experience is identical wherever a reader lands.
 * Queries, permalinks and pagination URLs are unchanged.
 *
 * Every result is rendered with the same card: there is no featured / "Latest"
 * treatment for the first post, so a page of results reads as one consistent
 * grid. The number of cards follows Settings → Reading (currently 12), which is
 * what fills the three-column grid exactly four rows deep.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$bpg_paged = max(1, (int) get_query_var('paged'));
$bpg_total = (int) $GLOBALS['wp_query']->found_posts;
$bpg_pages = (int) $GLOBALS['wp_query']->max_num_pages;
$bpg_description = get_the_archive_description();
$bpg_posts_page_id = (int) get_option('page_for_posts');
$bpg_labels = botphonic_blog_archive_labels();
?>

<div class="bpg-archive" id="archive-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<?php
				// Blog → Archive only: no Home crumb, no "Page N" tail.
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array(
						'show_home' => false,
						'show_paged' => false,
					));
				}
				?>

				<p class="bpg-eyebrow"><?php echo esc_html($bpg_labels['eyebrow']); ?></p>

				<?php
				// The archive type lives in the eyebrow above, so the heading is
				// just the thing being browsed — the_archive_title() would print
				// "Tag: Name" and say it twice.
				?>
				<h1 class="bpg-arc-hero__title"><?php echo esc_html($bpg_labels['title']); ?></h1>

				<?php if ($bpg_description) : ?>
				<div class="bpg-arc-hero__lede"><?php echo wp_kses_post($bpg_description); ?></div>
				<?php else : ?>
				<p class="bpg-arc-hero__lede">
					Practical guidance on AI voice agents, call automation and customer experience &mdash;
					written for teams running phone operations at scale.
				</p>
				<?php endif; ?>

				<div class="bpg-search"><?php get_search_form(); ?></div>

			</div>

			<?php botphonic_blog_category_filter(); ?>

		</div>
	</header>

	<!-- ── Listing ──────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<?php if (have_posts()) : ?>

					<?php if ($bpg_total) : ?>
					<p class="bpg-arc-count">
						<?php
						echo esc_html(number_format_i18n($bpg_total)) . ' ';
						echo esc_html(_n('article', 'articles', $bpg_total, 'botphonic'));

						if ($bpg_pages > 1) {
							echo esc_html(
								sprintf(
									/* translators: 1: current page, 2: total pages. */
									' · page %1$s of %2$s',
									number_format_i18n($bpg_paged),
									number_format_i18n($bpg_pages)
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
							get_template_part('loop-templates/content', 'bpgcard');
						endwhile;
						?>
					</div>

					<?php
					// Only printed when there is more than one page: an empty
					// wrapper would still contribute its top margin.
					if ($bpg_pages > 1) :
					?>
					<div class="bpg-pagination-wrap">
						<?php understrap_pagination(array(), 'bpg-pagination'); ?>
					</div>
					<?php endif; ?>

				<?php else : ?>

					<div class="bpg-empty">
						<h2 class="bpg-empty__title">Nothing here yet</h2>
						<p>No articles match this archive. Try a search, or browse the full blog.</p>
						<?php get_search_form(); ?>
						<?php if ($bpg_posts_page_id) : ?>
						<p class="bpg-empty__action">
							<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url(get_permalink($bpg_posts_page_id)); ?>">Browse all articles</a>
						</p>
						<?php endif; ?>
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
					<p class="bpg-eyebrow">See it on a live call</p>
					<h2 class="bpg-band__title" id="bpg-band-title">Hear your own AI voice agent in under five minutes</h2>
					<p class="bpg-band__text">
						Spin up an agent, point it at a number, and let it answer, qualify and book &mdash; 24/7, in 50+ languages.
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
