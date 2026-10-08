<?php

/**
 * Category archive.
 *
 * Same hero / filter rail / card grid / CTA band as archive.php and index.php.
 * The category name, description, query and pagination URLs are untouched, so
 * existing category URLs and their SEO output behave exactly as before.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$bpg_paged = max(1, (int) get_query_var('paged'));
$bpg_total = (int) $GLOBALS['wp_query']->found_posts;
$bpg_description = category_description();
$bpg_posts_page_id = (int) get_option('page_for_posts');
?>

<div class="bpg-archive" id="archive-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<?php
				// Blog → Category only: no Home crumb, no "Page N" tail.
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array(
						'show_home' => false,
						'show_paged' => false,
					));
				}
				?>

				<p class="bpg-eyebrow">Topic</p>

				<h1 class="bpg-arc-hero__title"><?php single_cat_title(); ?></h1>

				<?php if ($bpg_description) : ?>
				<div class="bpg-arc-hero__lede"><?php echo wp_kses_post($bpg_description); ?></div>
				<?php else : ?>
				<p class="bpg-arc-hero__lede">
					Every Botphonic article on <strong><?php echo esc_html(single_cat_title('', false)); ?></strong> &mdash;
					playbooks, comparisons and implementation notes for AI-powered phone operations.
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
						if ($bpg_paged > 1) {
							echo esc_html(sprintf(' · page %d', $bpg_paged));
						}
						?>
					</p>
					<?php endif; ?>

					<?php
					// One uniform grid, identical to index.php and archive.php.
					// The first post on page 1 used to be promoted to the
					// 'featured' variant with a "Latest" ribbon, which gave the
					// category listing a different shape from the blog listing
					// and made the newest post read as a separate section.
					?>
					<div class="bpg-grid">
						<?php
						while (have_posts()) :
							the_post();
							get_template_part('loop-templates/content', 'bpgcard');
						endwhile;
						?>
					</div>

					<div class="bpg-pagination-wrap">
						<?php understrap_pagination(array(), 'bpg-pagination'); ?>
					</div>

				<?php else : ?>

					<div class="bpg-empty">
						<h2 class="bpg-empty__title">Nothing here yet</h2>
						<p>There are no published articles in this topic. Try a search, or browse the full blog.</p>
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
