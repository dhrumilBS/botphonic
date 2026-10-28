<?php

/**
 * Site search results.
 *
 * One page for the whole site: blog articles, competitor comparisons,
 * customer stories and pages, narrowed with the type tabs (?type=) and sorted
 * by relevance or date (?orderby=date). Query scoping, counts, snippets and
 * the live-suggestion endpoint live in inc/function-search.php.
 *
 * Three states:
 *   - a term with results  → toolbar, result grid, pagination
 *   - a term, no results   → recovery: other tabs that do match, tips, topics,
 *                            popular reads
 *   - no term (/?s=)       → a browse page: topics plus the latest of each type
 *
 * Shares the hero / filter pills / card grid / CTA band of the blog archive
 * (blog.css), so search reads as part of the same site.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$bps_term = trim(get_search_query(false));
$bps_has_term = '' !== $bps_term;
$bps_types = botphonic_search_types();
$bps_type = botphonic_search_current_type();
$bps_sort = botphonic_search_current_sort();
$bps_counts = $bps_has_term ? botphonic_search_type_counts($bps_term) : array();
$bps_total = (int) $GLOBALS['wp_query']->found_posts;
$bps_pages = (int) $GLOBALS['wp_query']->max_num_pages;
$bps_paged = max(1, (int) get_query_var('paged'));
$bps_per_page = (int) $GLOBALS['wp_query']->get('posts_per_page');
$bps_posts_page_id = (int) get_option('page_for_posts');

/**
 * A small titled rail of cards, used by the no-results and browse states.
 *
 * @param string $title     Section heading.
 * @param array  $query     WP_Query args.
 * @param string $more_url  Optional "view all" link.
 * @param string $more_text Optional "view all" label.
 */
$bps_rail = static function ($title, $query, $more_url = '', $more_text = 'View all') {
	$rail = new WP_Query(
		array_merge(
			array(
				'post_status' => 'publish',
				'posts_per_page' => 3,
				'ignore_sticky_posts' => true,
				'no_found_rows' => true,
				'post__not_in' => botphonic_search_excluded_ids(),
			),
			$query
		)
	);

	if (!$rail->have_posts()) {
		return;
	}

	$id = 'bps-rail-' . sanitize_title($title);
?>
	<section class="bps-rail" aria-labelledby="<?php echo esc_attr($id); ?>">
		<div class="bpg-sec-head">
			<h2 class="bpg-sec-head__title" id="<?php echo esc_attr($id); ?>"><?php echo esc_html($title); ?></h2>
			<?php if ($more_url) : ?>
				<a class="bpg-sec-head__link" href="<?php echo esc_url($more_url); ?>">
					<?php echo esc_html($more_text); ?>
					<?php echo botphonic_search_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
					?>
				</a>
			<?php endif; ?>
		</div>
		<div class="bpg-grid">
			<?php
			while ($rail->have_posts()) :
				$rail->the_post();
				get_template_part('loop-templates/content', 'searchcard', array('snippet' => false));
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
<?php
};

/**
 * Popular topics: the busiest blog categories as pills.
 */
$bps_topics = static function () {
	$categories = get_categories(
		array(
			'hide_empty' => true,
			'orderby' => 'count',
			'order' => 'DESC',
			'number' => 8,
		)
	);

	if (empty($categories)) {
		return;
	}
?>
	<div class="bps-topics">
		<p class="bps-topics__label">Popular topics</p>
		<div class="bps-topics__list">
			<?php foreach ($categories as $category) : ?>
				<a class="bpg-filter" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
					<?php echo esc_html($category->name); ?>
					<span class="bpg-filter__count"><?php echo absint($category->count); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
<?php
};
?>

<div class="bpg-archive bps-page" id="search-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-arc-hero bps-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner">

				<?php
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array('show_paged' => false));
				}
				?>

				<p class="bpg-eyebrow">Search</p>

				<h1 class="bpg-arc-hero__title">
					<?php if ($bps_has_term) : ?>
						Results for <span class="bps-hero__term">&ldquo;<?php echo esc_html($bps_term); ?>&rdquo;</span>
					<?php else : ?>
						Search Botphonic
					<?php endif; ?>
				</h1>

				<p class="bpg-arc-hero__lede">
					<?php
					if ($bps_has_term) {
						$bps_all = isset($bps_counts['all']) ? (int) $bps_counts['all'] : 0;
						echo esc_html(
							sprintf(
								/* translators: %s: number of results. */
								_n('%s result across articles, comparisons, customer stories and pages.', '%s results across articles, comparisons, customer stories and pages.', $bps_all, 'botphonic'),
								number_format_i18n($bps_all)
							)
						);
					} else {
						echo 'Find guides, competitor comparisons, customer stories and product pages &mdash; all in one place.';
					}
					?>
				</p>

				<div class="bpg-search bps-hero__search">
					<?php
					get_search_form(
						array(
							'aria_label' => 'Search the site',
							'type' => $bps_type,
							'orderby' => $bps_sort,
							'autofocus' => !$bps_has_term,
						)
					);
					?>
				</div>

			</div>

			<?php if ($bps_has_term) : ?>
				<nav class="bpg-filters bps-tabs" aria-label="Filter results by type">
					<div class="bpg-filters__track">
						<?php
						$bps_tab = static function ($key, $label, $icon, $count) use ($bps_term, $bps_type) {
							$active = ($key === $bps_type);

							if (!$count && !$active) {
								printf(
									'<span class="bpg-filter bps-tab is-empty" aria-disabled="true">%1$s%2$s<span class="bpg-filter__count">0</span></span>',
									botphonic_search_icon($icon), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
									esc_html($label)
								);
								return;
							}

							printf(
								'<a class="bpg-filter bps-tab%1$s" href="%2$s"%3$s>%4$s%5$s<span class="bpg-filter__count">%6$s</span></a>',
								$active ? ' is-active' : '',
								esc_url(botphonic_search_url($bps_term, $key)),
								$active ? ' aria-current="page"' : '',
								botphonic_search_icon($icon), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
								esc_html($label),
								esc_html(number_format_i18n($count))
							);
						};

						$bps_tab('', 'All results', 'grid', $bps_counts['all']);

						foreach ($bps_types as $bps_key => $bps_def) {
							$bps_tab($bps_key, $bps_def['label'], $bps_def['icon'], isset($bps_counts[$bps_key]) ? $bps_counts[$bps_key] : 0);
						}
						?>
					</div>
				</nav>
			<?php endif; ?>

		</div>
	</header>

	<!-- ── Results ──────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<?php if ($bps_has_term && have_posts()) : ?>

					<div class="bps-toolbar">
						<p class="bps-toolbar__count">
							<?php
							$bps_from = ($bps_paged - 1) * $bps_per_page + 1;
							$bps_to = $bps_from + (int) $GLOBALS['wp_query']->post_count - 1;

							printf(
								/* translators: 1: first result, 2: last result, 3: total, 4: type label. */
								esc_html__('Showing %1$s–%2$s of %3$s %4$s', 'botphonic'),
								'<strong>' . esc_html(number_format_i18n($bps_from)) . '</strong>',
								'<strong>' . esc_html(number_format_i18n($bps_to)) . '</strong>',
								'<strong>' . esc_html(number_format_i18n($bps_total)) . '</strong>',
								esc_html($bps_type ? strtolower($bps_types[$bps_type]['label']) : _n('result', 'results', $bps_total, 'botphonic'))
							);
							?>
						</p>

						<div class="bps-sort" role="group" aria-label="Sort results">
							<span class="bps-sort__label"><?php echo botphonic_search_icon('sort'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
															?>Sort</span>
							<a class="bps-sort__opt<?php echo 'relevance' === $bps_sort ? ' is-active' : ''; ?>" href="<?php echo esc_url(botphonic_search_url($bps_term, null, 'relevance')); ?>" <?php echo 'relevance' === $bps_sort ? ' aria-current="true"' : ''; ?>>Most relevant</a>
							<a class="bps-sort__opt<?php echo 'date' === $bps_sort ? ' is-active' : ''; ?>" href="<?php echo esc_url(botphonic_search_url($bps_term, null, 'date')); ?>" <?php echo 'date' === $bps_sort ? ' aria-current="true"' : ''; ?>>Newest</a>
						</div>
					</div>

					<div class="bpg-grid bps-grid">
						<?php
						while (have_posts()) :
							the_post();
							get_template_part('loop-templates/content', 'searchcard');
						endwhile;
						?>
					</div>

					<?php if ($bps_pages > 1) : ?>
						<div class="bpg-pagination-wrap">
							<?php understrap_pagination(array(), 'bpg-pagination'); ?>
						</div>
					<?php endif; ?>

				<?php elseif ($bps_has_term) : ?>

					<div class="bps-empty">
						<span class="bps-empty__icon"><?php echo botphonic_search_icon('search'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
														?></span>

						<?php if ($bps_type && !empty($bps_counts['all'])) : ?>
							<h2 class="bps-empty__title">
								No <?php echo esc_html(strtolower($bps_types[$bps_type]['label'])); ?> match &ldquo;<?php echo esc_html($bps_term); ?>&rdquo;
							</h2>
							<p>
								There <?php echo 1 === (int) $bps_counts['all'] ? 'is' : 'are'; ?>
								<?php echo esc_html(number_format_i18n($bps_counts['all'])); ?>
								<?php echo esc_html(_n('result', 'results', (int) $bps_counts['all'], 'botphonic')); ?> elsewhere on the site.
							</p>
							<p class="bps-empty__actions">
								<a class="bpg-btn bpg-btn--coral" href="<?php echo esc_url(botphonic_search_url($bps_term, '')); ?>">
									See all results
									<?php echo botphonic_search_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. 
									?>
								</a>
							</p>
						<?php else : ?>
							<h2 class="bps-empty__title">Nothing matches &ldquo;<?php echo esc_html($bps_term); ?>&rdquo;</h2>
							<p>Try one of these, or browse a topic below:</p>
							<ul class="bps-empty__tips">
								<li>Check the spelling, or use fewer words</li>
								<li>Search a broader term &mdash; &ldquo;voice agent&rdquo; rather than a product name</li>
								<li>Look for a competitor name to find its comparison guide</li>
							</ul>
						<?php endif; ?>
					</div>

					<?php
					$bps_topics();
					$bps_rail('Popular reads', array('post_type' => 'post'), $bps_posts_page_id ? get_permalink($bps_posts_page_id) : '', 'Browse the blog');
					?>

				<?php else : ?>

					<?php
					$bps_topics();

					$bps_rail('Latest articles', array('post_type' => 'post'), $bps_posts_page_id ? get_permalink($bps_posts_page_id) : '', 'All articles');

					if (isset($bps_types['comparisons'])) {
						$bps_rail('Competitor comparisons', array('post_type' => $bps_types['comparisons']['post_type']), get_post_type_archive_link($bps_types['comparisons']['post_type']), 'All comparisons');
					}

					if (isset($bps_types['stories'])) {
						$bps_rail('Customer stories', array('post_type' => $bps_types['stories']['post_type']), get_post_type_archive_link($bps_types['stories']['post_type']), 'All stories');
					}
					?>

				<?php endif; ?>

			</main>
		</div>
	</div>

	<!-- ── CTA band ─────────────────────────────────────────────────── -->
	<section class="bpg-band bpg-band--light" aria-labelledby="bpg-band-title">
		<div class="bpg-shell">
			<div class="bpg-band__inner">
				<div class="bpg-band__copy">
					<p class="bpg-eyebrow">Can&rsquo;t find it?</p>
					<h2 class="bpg-band__title" id="bpg-band-title">Hear your own AI voice agent in under five minutes</h2>
					<p class="bpg-band__text">
						Spin up an agent, point it at a number, and let it answer, qualify and book &mdash; 24/7, in 50+ languages.
					</p>
				</div>
				<div class="bpg-band__row">
					<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register" rel="noopener">Start free trial</a>
					<a class="bpg-btn bpg-btn--ghost" href="/contact/">Talk to our team</a>
				</div>
			</div>
		</div>
	</section>

</div><!-- #search-wrapper -->

<?php get_footer(); ?>