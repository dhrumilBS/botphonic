<?php

/**
 * Alternatives archive — the listing of comparison guides.
 *
 * Structural twin of archive-success-stories.php, so the three listings on the
 * site (blog, customer stories, comparisons) read as one family: the same
 * .bpg-arc-hero / .bpg-grid / .bpg-pagination-wrap / .bpg-band skeleton from
 * assets/css/blog.css, with the comparison-specific pieces (proof row, card
 * ribbon and rating line) layered on by assets/css/alternatives.css.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$alt_paged = max(1, (int) get_query_var('paged'));
$alt_total = (int) $GLOBALS['wp_query']->found_posts;
$alt_pages = (int) $GLOBALS['wp_query']->max_num_pages;
$alt_description = get_the_archive_description();

// The newest comparison carries page one, the same way the blog and the story
// archive promote their lead item. The featured card is already in the design
// system (blog.css § 14), so this is one class.
$alt_index = 0;
$alt_feature_first = (1 === $alt_paged && $alt_total > 2);

$alt_proof = array(
	array('icon' => 'scale', 'text' => __('Feature-by-feature, not marketing copy', 'botphonic')),
	array('icon' => 'star', 'text' => __('Backed by published review scores', 'botphonic')),
	array('icon' => 'shield', 'text' => __('Unpublished pricing is labelled, never guessed', 'botphonic')),
);
?>

<?php
// `bpg-archive` opts into the blog design system (reset, headings, cards,
// pagination); `bpg-alt` is the comparison layer's own hook. Both are repeated
// on the wrapper rather than relied on from body_class, so the view still styles
// correctly if it is ever rendered outside the main query.
?>
<div class="bpg-archive bpg-alt" id="archive-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<?php
				// Home → Alternatives. No "Page N" tail: the count line below
				// already says which page this is.
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array(
						'show_paged' => false,
					));
				}
				?>

				<p class="bpg-eyebrow"><?php esc_html_e('Comparison guides', 'botphonic'); ?></p>

				<h1 class="bpg-arc-hero__title">How Botphonic compares to the platforms you&rsquo;re weighing up</h1>

				<?php if ($alt_description) : ?>
				<div class="bpg-arc-hero__lede"><?php echo wp_kses_post($alt_description); ?></div>
				<?php else : ?>
				<p class="bpg-arc-hero__lede">
					Honest, side-by-side breakdowns of the AI voice and call platforms buyers shortlist alongside us &mdash;
					what each one is genuinely good at, where it falls short, and which is the right fit for your call volume.
				</p>
				<?php endif; ?>

				<?php
				// Scoped to the post type, so a search from this page returns
				// comparisons rather than dropping the visitor into the blog.
				// The markup is the stock Understrap search form, which is what
				// blog.css § 13.1 styles.
				?>
				<div class="bpg-search bpg-alt-search">
					<form role="search" class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Search comparison guides', 'botphonic'); ?>">
						<label class="screen-reader-text" for="bpg-alt-search-field"><?php esc_html_e('Search comparison guides:', 'botphonic'); ?></label>
						<div class="input-group">
							<input type="search" class="field search-field form-control" id="bpg-alt-search-field" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search comparisons…', 'botphonic'); ?>">
							<input type="hidden" name="post_type" value="<?php echo esc_attr(botphonic_alt_post_type()); ?>">
							<span class="input-group-append">
								<input type="submit" class="submit search-submit btn btn-primary" value="<?php esc_attr_e('Search', 'botphonic'); ?>">
							</span>
						</div>
					</form>
				</div>

				<?php if ($alt_total) : ?>
				<ul class="bpg-alt-proof">
					<?php foreach ($alt_proof as $alt_item) : ?>
					<li>
						<?php echo botphonic_alt_icon($alt_item['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
						<?php echo esc_html($alt_item['text']); ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

			</div>
		</div>
	</header>

	<!-- ── Listing ──────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<?php if (have_posts()) : ?>

					<?php if ($alt_total) : ?>
					<p class="bpg-arc-count">
						<?php
						echo esc_html(number_format_i18n($alt_total)) . ' ';
						echo esc_html(_n('comparison guide', 'comparison guides', $alt_total, 'botphonic'));

						if ($alt_pages > 1) {
							echo esc_html(
								sprintf(
									/* translators: 1: current page, 2: total pages. */
									' · page %1$s of %2$s',
									number_format_i18n($alt_paged),
									number_format_i18n($alt_pages)
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
							$alt_index++;
							$alt_is_feature = ($alt_feature_first && 1 === $alt_index);

							get_template_part(
								'loop-templates/content',
								'altcard',
								array(
									'variant' => $alt_is_feature ? 'featured' : 'default',
									'flag' => $alt_is_feature ? __('Latest comparison', 'botphonic') : '',
								)
							);
						endwhile;
						?>
					</div>

					<?php
					// Only printed when there is more than one page: an empty
					// wrapper would still contribute its top margin.
					if ($alt_pages > 1) :
					?>
					<div class="bpg-pagination-wrap">
						<?php understrap_pagination(array(), 'bpg-pagination'); ?>
					</div>
					<?php endif; ?>

				<?php else : ?>

					<div class="bpg-empty">
						<h2 class="bpg-empty__title"><?php esc_html_e('No comparisons published yet', 'botphonic'); ?></h2>
						<p>The first head-to-head guides are being written up. In the meantime, see what the platform does on a live call.</p>
						<p class="bpg-empty__action">
							<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url(botphonic_alt_default_cta_url()); ?>">Book a demo</a>
						</p>
					</div>

				<?php endif; ?>

			</main>
		</div>
	</div>

	<!-- ── CTA band ─────────────────────────────────────────────────── -->
	<?php if (have_posts()) : ?>
	<section class="bpg-band" aria-labelledby="bpg-alt-band-title">
		<div class="bpg-shell">
			<div class="bpg-band__inner">
				<div class="bpg-band__copy">
					<p class="bpg-eyebrow">Skip the spreadsheet</p>
					<h2 class="bpg-band__title" id="bpg-alt-band-title">Hear the difference on your own phone line</h2>
					<p class="bpg-band__text">
						Twenty minutes on a call beats a week of tab-hopping. Point an agent at a real number and
						judge it on your own traffic &mdash; answering, qualifying and booking, 24/7, in 50+ languages.
					</p>
				</div>
				<div class="bpg-band__row">
					<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register" rel="noopener">Start free trial</a>
					<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url(botphonic_alt_default_cta_url()); ?>">Book a demo</a>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

</div><!-- #archive-wrapper -->

<?php get_footer(); ?>
