<?php

/**
 * Blog listing (the page assigned as "Posts page") and fallback index.
 *
 * Shares the hero / filter rail / card grid / CTA band with archive.php and
 * category.php. The query, pagination URLs and post links are unchanged.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$bpg_paged = max(1, (int) get_query_var('paged'));
$bpg_total = (int) $GLOBALS['wp_query']->found_posts;
?>

<div class="bpg-archive" id="blog-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<p class="bpg-eyebrow">Botphonic blog</p>

				<h1 class="bpg-arc-hero__title">The playbook for AI-powered phone conversations</h1>

				<p class="bpg-arc-hero__lede">How AI voice agents answer, qualify and book &mdash; with the benchmarks, comparisons and implementation detail teams need before putting one on a live line.</p>

				<div class="bpg-search"><?php get_search_form(); ?></div>

			</div>

			<?php botphonic_blog_category_filter(); ?>

		</div>
	</header>

	<!-- ── Listing ──────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<?php if (have_posts()): ?>

					<?php if ($bpg_total): ?>
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

					<div class="bpg-grid">
						<?php
						while (have_posts()):
							the_post();
							get_template_part('loop-templates/content', 'bpgcard');
						endwhile;
						?>
					</div>

					<div class="bpg-pagination-wrap">
						<?php understrap_pagination(array(), 'bpg-pagination'); ?>
					</div>

				<?php else: ?>

					<div class="bpg-empty">
						<h2 class="bpg-empty__title">No articles yet</h2>
						<p>Nothing has been published here so far. Try a search instead.</p>
						<?php get_search_form(); ?>
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

</div><!-- #blog-wrapper -->

<?php get_footer(); ?>