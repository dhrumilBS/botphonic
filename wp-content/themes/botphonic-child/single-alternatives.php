<?php

/**
 * Single Alternatives guide — one head-to-head comparison article.
 *
 * Structural twin of single-success-stories.php, so a comparison reads as part
 * of the same family as the blog and the case studies: .bpg-shell, the sticky
 * sidebar (.bpg-layout / .bpg-aside / .bpg-main), .bpg-side-cta, .bpg-endcta
 * and .bpg-authorbox all come from assets/css/blog.css unchanged. The
 * comparison layer adds the split hero, the feature matrix, the numbered
 * platform write-ups with their pros/cons grid, and the FAQ list
 * (assets/css/alternatives.css).
 *
 * Everything the page renders comes from botphonic_alt_data(), which is the one
 * place that decides which sections exist. The table of contents is built from
 * the same array, so the sidebar can never link to an anchor the body did not
 * print.
 *
 * The three blocks inside a platform write-up that cannot be typed in the
 * editor — screenshot, star rating, pros/cons grid — are positioned by tokens
 * in the prose and split out by botphonic_alt_profile_parts(), so their markup
 * still lives in this template rather than in a PHP string.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

/*
 * Enter the loop before reading anything. the_post() is what sets up
 * $authordata and the $page/$pages globals that the_content() and any block or
 * plugin filter hanging off it expect to find.
 */
if (have_posts()) {
	the_post();
}

global $post;

$alt = botphonic_alt_data($post);
$alt_brand = botphonic_alt_brand();
$alt_has_cover = has_post_thumbnail($post);
$alt_archive_url = get_post_type_archive_link(botphonic_alt_post_type());
$alt_profile_count = count($alt['profiles']);
$alt_competitor_count = count($alt['competitors']);

/*
 * Whether the table of contents actually mixes levels. On the default "H3 only"
 * setting the list is the overview plus the platform write-ups, and indenting
 * every one of them under a single "Overview" link reads as a broken hierarchy.
 * The indent is only applied when there is a page-section entry to indent under.
 */
$alt_toc_mixed = in_array(2, wp_list_pluck($alt['toc'], 'level'), true);

// Author, for the closing bio box.
$alt_author_id = (int) get_the_author_meta('ID');
$alt_author_name = (string) get_the_author_meta('display_name');
$alt_author_bio = (string) get_the_author_meta('description');

// Anything typed into the editor. Kept as a closing section so a writer is
// never left with content that renders nowhere.
$alt_body = trim(apply_filters('the_content', get_the_content()));
?>

<?php
// `bpg-single` opts into the blog design system; `bpg-alt` is the comparison
// layer's own hook. Both are repeated here rather than relied on from
// body_class, so the view still styles correctly in any secondary context.
?>
<div class="main-wrapper bpg-single bpg-alt" id="single-wrapper">

	<!-- ── Hero ─────────────────────────────────────────────────────── -->
	<header class="bpg-alt-hero" id="bpg-alt-overview">
		<div class="bpg-shell">
			<div class="bpg-alt-hero__grid<?php echo $alt_has_cover ? ' bpg-alt-hero__grid--split' : ''; ?>">

				<div class="bpg-alt-hero__body">

					<?php
					// Home → Alternatives. The title is the <h1> directly below,
					// so the trail stops before repeating it.
					if (function_exists('wpdev_breadcrumbs')) {
						wpdev_breadcrumbs(array(
							'show_current' => false,
						));
					}
					?>

					<p class="bpg-eyebrow"><?php esc_html_e('Comparison guide', 'botphonic'); ?></p>

					<h1 class="bpg-alt-hero__title"><?php the_title(); ?></h1>

					<?php
					// No lede here. The hero opens on the title and goes straight
					// to the two actions: a paragraph between them restated the
					// Intro section below, and pushed the comparison jump-link
					// below the fold on a phone. The Intro section carries the
					// opening copy instead.
					?>
					<div class="bpg-alt-hero__row">
						<a class="bpg-btn bpg-btn--coral" href="<?php echo esc_url($alt['cta_url']); ?>" <?php echo botphonic_alt_link_target($alt['cta_url']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string. ?>>
							<?php echo esc_html($alt['cta_text']); ?>
						</a>

						<?php if ($alt['has_compare']): ?>
							<a class="bpg-alt-hero__jump" href="#bpg-alt-compare">
								<?php esc_html_e('Jump to the comparison', 'botphonic'); ?>
								<?php echo botphonic_alt_icon('chevron-down'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
							</a>
						<?php endif; ?>
					</div>

					<p class="bpg-meta bpg-alt-hero__meta">
						<span class="bpg-meta__item">
							<?php echo botphonic_alt_icon('calendar'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
							<time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
								<?php
								printf(
									/* translators: %s: formatted date. */
									esc_html__('Updated %s', 'botphonic'),
									esc_html(get_the_modified_date('F j, Y'))
								);
								?>
							</time>
						</span>

						<?php if ($alt_profile_count): ?>
							<span class="bpg-meta__sep" aria-hidden="true"></span>
							<span class="bpg-meta__item">
								<?php echo botphonic_alt_icon('scale'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
								<?php
								printf(
									/* translators: %s: number of platforms. */
									esc_html(_n('%s platform compared', '%s platforms compared', $alt_profile_count, 'botphonic')),
									esc_html(number_format_i18n($alt_profile_count))
								);
								?>
							</span>
						<?php endif; ?>
					</p>

				</div>

				<?php if ($alt_has_cover): ?>
					<figure class="bpg-alt-hero__media">
						<?php
						the_post_thumbnail(
							'large',
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

				<aside class="bpg-aside" aria-label="<?php esc_attr_e('Guide navigation', 'botphonic'); ?>">
					<div class="bpg-aside__sticky">

						<?php
						/*
						 * The shared card — the only copy this view prints now.
						 * It used to print two: this one for the sidebar and a
						 * second, phone-only card further down inside <main>,
						 * with the entry loop written out twice. Below 992px
						 * blog.css § 17 lifts this one out of the sidebar and
						 * pins it, which is what the second copy was for.
						 *
						 * sub_level indents the per-platform entries under the
						 * page section they belong to, but only when the list
						 * actually mixes the two levels.
						 */
						if (count($alt['toc']) > 1) {
							get_template_part('template-parts/toc', '', array(
								'items' => $alt['toc'],
								'title' => __('On this page', 'botphonic'),
								'close_label' => __('Unpin the page navigation', 'botphonic'),
								'nav_id' => 'bpg-alt-toc-nav',
								'sub_level' => $alt_toc_mixed ? 3 : 0,
							));
						}
						?>

						<div class="bpg-side-cta">
							<p class="bpg-side-cta__eyebrow"><?php esc_html_e('Free 14-day trial', 'botphonic'); ?></p>
							<p class="bpg-side-cta__title">
								<?php
								printf(
									/* translators: %s: brand name. */
									esc_html__('See why teams switch to %s', 'botphonic'),
									esc_html($alt_brand)
								);
								?>
							</p>
							<ul class="bpg-side-cta__list">
								<li>Answers in under 300&nbsp;ms, 24/7</li>
								<li>Live in days, not quarters</li>
								<li>HIPAA &amp; PCI&nbsp;DSS ready</li>
							</ul>
							<a class="bpg-btn bpg-btn--coral" href="<?php echo esc_url($alt['cta_url']); ?>" <?php echo botphonic_alt_link_target($alt['cta_url']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string. ?>>
								<?php echo esc_html($alt['cta_text']); ?>
							</a>
							<p class="bpg-side-cta__note"><?php esc_html_e('No seat licences. Cancel anytime.', 'botphonic'); ?></p>
						</div>

					</div>
				</aside>

				<main class="site-main bpg-main" id="main">

					<!-- Opening content -->
					<?php if (!empty($alt['intro'])): ?>
						<section class="bpg-alt-sec bpg-alt-sec--lead">
							<div class="bpg-alt-prose">
								<?php echo botphonic_alt_prose($alt['intro']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Why look for an alternative -->
					<?php if (!empty($alt['why'])): ?>
						<section class="bpg-alt-sec" aria-labelledby="bpg-alt-why">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-why"><?php echo esc_html($alt['why_title']); ?></h2>
							<div class="bpg-alt-prose">
								<?php echo botphonic_alt_prose($alt['why']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Methodology -->
					<?php if (!empty($alt['method'])): ?>
						<section class="bpg-alt-sec" aria-labelledby="bpg-alt-method">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-method">
								<?php echo esc_html('' !== $alt['method_title'] ? $alt['method_title'] : __('How we analyse', 'botphonic')); ?>
							</h2>
							<div class="bpg-alt-prose">
								<?php echo botphonic_alt_prose($alt['method']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- ── Comparison matrix ─────────────────────────────── -->
					<?php if ($alt['has_compare']): ?>
						<?php $alt_cols = $alt['matrix']; ?>
						<section class="bpg-alt-sec bpg-alt-compare" aria-labelledby="bpg-alt-compare">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-compare"><?php echo esc_html($alt['compare_title']); ?></h2>

							<?php if (!empty($alt['compare_intro'])): ?>
								<div class="bpg-alt-prose bpg-alt-compare__intro">
									<?php echo botphonic_alt_prose($alt['compare_intro']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
								</div>
							<?php endif; ?>

							<?php
							/*
							 * The grouped feature rows are CSS grid, not table rows:
							 * a whole category lives inside one colspan'd cell so it
							 * can collapse as a single element. --bpg-alt-cols is
							 * what keeps those grid rows aligned with the <colgroup>
							 * widths of the summary rows above them.
							 */
							?>
							<div class="bpg-alt-matrix-wrap" style="--bpg-alt-cols:<?php echo esc_attr($alt_cols['template']); ?>" tabindex="0" role="region" aria-label="<?php esc_attr_e('Feature comparison table', 'botphonic'); ?>">
								<table class="bpg-alt-matrix">
									<colgroup>
										<col style="width:<?php echo esc_attr($alt_cols['label_pct']); ?>%">
										<?php for ($alt_c = 0; $alt_c < $alt_cols['products']; $alt_c++): ?>
											<col style="width:<?php echo esc_attr($alt_cols['product_pct']); ?>%">
										<?php endfor; ?>
									</colgroup>

									<thead>
										<tr class="bpg-alt-matrix__head">
											<th scope="col"><span class="screen-reader-text"><?php esc_html_e('Feature', 'botphonic'); ?></span></th>
											<th scope="col" class="is-brand">
												<?php echo botphonic_alt_column_head($alt_brand, 0, true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_column_head(). ?>
											</th>
											<?php foreach ($alt['competitors'] as $alt_competitor): ?>
												<th scope="col">
													<?php
													echo botphonic_alt_column_head( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_column_head().
														isset($alt_competitor['name']) ? $alt_competitor['name'] : '',
														isset($alt_competitor['logo']) ? $alt_competitor['logo'] : 0
													);
													?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>

									<tbody>

										<?php
										// Always-visible summary rows.
										foreach ($alt['glance_rows'] as $alt_row):
											$alt_row_values = isset($alt_row['competitor_values']) && is_array($alt_row['competitor_values']) ? array_values($alt_row['competitor_values']) : array();
											$alt_row_type = isset($alt_row['value_type']) ? $alt_row['value_type'] : 'text';
											?>
											<tr class="bpg-alt-matrix__glance">
												<th scope="row"><?php echo esc_html(isset($alt_row['label']) ? $alt_row['label'] : ''); ?></th>
												<td class="is-brand">
													<?php echo botphonic_alt_glance_value(isset($alt_row['botphonic_value']) ? $alt_row['botphonic_value'] : '', $alt_row_type); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_glance_value(). ?>
												</td>
												<?php
												// Indexed rather than keyed: a row supplies its values in
												// the Competitors order, and a short row must still print
												// an empty cell for every remaining column or the table
												// would lose its alignment.
												for ($alt_i = 0; $alt_i < $alt_competitor_count; $alt_i++):
													?>
													<td>
														<?php echo botphonic_alt_glance_value(isset($alt_row_values[$alt_i]['value']) ? $alt_row_values[$alt_i]['value'] : '', $alt_row_type); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_glance_value(). ?>
													</td>
												<?php endfor; ?>
											</tr>
										<?php endforeach; ?>

										<?php
										// Collapsible feature groups. All start
										// closed, so the table opens at a readable
										// height however many features it carries.
										// Featureless categories were already dropped by
										// botphonic_alt_data(), so every row here has
										// something to show.
										foreach ($alt['categories'] as $alt_cat_i => $alt_cat):
											$alt_features = array_values($alt_cat['features']);
											$alt_panel_id = 'bpg-alt-group-' . (int) $alt_cat_i;
											?>
											<tr class="bpg-alt-acc">
												<td class="bpg-alt-acc__cell" colspan="<?php echo esc_attr($alt_cols['total']); ?>">
													<button class="bpg-alt-acc__btn" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr($alt_panel_id); ?>">
														<span class="bpg-alt-acc__label"><?php echo esc_html(isset($alt_cat['category_name']) ? $alt_cat['category_name'] : ''); ?></span>
														<span class="bpg-alt-acc__count">
															<?php
															printf(
																/* translators: %s: number of features. */
																esc_html(_n('%s feature', '%s features', count($alt_features), 'botphonic')),
																esc_html(number_format_i18n(count($alt_features)))
															);
															?>
														</span>
														<span class="bpg-alt-acc__chev" aria-hidden="true"><?php echo botphonic_alt_icon('chevron-down'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
													</button>
												</td>
											</tr>
											<tr class="bpg-alt-acc-panel" id="<?php echo esc_attr($alt_panel_id); ?>">
												<td class="bpg-alt-acc-panel__cell" colspan="<?php echo esc_attr($alt_cols['total']); ?>">
													<div class="bpg-alt-collapse">
														<div class="bpg-alt-collapse__inner">
															<?php
															foreach ($alt_features as $alt_feature):
																$alt_f_values = (isset($alt_feature['competitor_values']) && is_array($alt_feature['competitor_values'])) ? array_values($alt_feature['competitor_values']) : array();
																?>
																<div class="bpg-alt-grid-row">
																	<div class="bpg-alt-grid-cell bpg-alt-grid-cell--label"><?php echo esc_html(isset($alt_feature['feature_name']) ? $alt_feature['feature_name'] : ''); ?></div>
																	<div class="bpg-alt-grid-cell is-brand">
																		<?php echo botphonic_alt_cell(isset($alt_feature['botphonic_value']) ? $alt_feature['botphonic_value'] : ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_cell(). ?>
																	</div>
																	<?php for ($alt_i = 0; $alt_i < $alt_competitor_count; $alt_i++): ?>
																		<div class="bpg-alt-grid-cell">
																			<?php echo botphonic_alt_cell(isset($alt_f_values[$alt_i]['value']) ? $alt_f_values[$alt_i]['value'] : ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_cell(). ?>
																		</div>
																	<?php endfor; ?>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>

										<tr class="bpg-alt-matrix__cta">
											<td></td>
											<td class="is-brand">
												<a class="bpg-alt-matrix__btn" href="<?php echo esc_url($alt['cta_url']); ?>" <?php echo botphonic_alt_link_target($alt['cta_url']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string. ?>>
													<?php echo esc_html($alt['compare_cta']); ?>
												</a>
											</td>
											<?php for ($alt_i = 0; $alt_i < $alt_competitor_count; $alt_i++): ?>
												<td></td>
											<?php endfor; ?>
										</tr>

									</tbody>
								</table>
							</div>

							<?php if (!empty($alt['compare_note'])): ?>
								<div class="bpg-alt-prose bpg-alt-compare__note">
									<?php echo botphonic_alt_prose($alt['compare_note']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
								</div>
							<?php endif; ?>
						</section>
					<?php endif; ?>

					<!-- ── Platform write-ups ────────────────────────────── -->
					<?php if ($alt['profiles']): ?>
						<section class="bpg-alt-profiles" <?php echo ('' !== $alt['profiles_heading']) ? ' aria-labelledby="bpg-alt-profiles"' : ''; ?>>

							<?php if ('' !== $alt['profiles_heading']): ?>
								<h2 class="bpg-alt-sec__title bpg-alt-profiles__title" id="bpg-alt-profiles"><?php echo esc_html($alt['profiles_heading']); ?></h2>
							<?php endif; ?>

							<?php foreach ($alt['profiles'] as $alt_i => $alt_profile): ?>
								<?php
								$alt_profile_name = trim((string) (isset($alt_profile['name']) ? $alt_profile['name'] : ''));
								$alt_profile_id = 'bpg-alt-profile-' . (int) $alt_i;
								$alt_profile_rating = isset($alt_profile['rating_value']) ? trim((string) $alt_profile['rating_value']) : '';
								$alt_profile_source = isset($alt_profile['rating_source']) ? trim((string) $alt_profile['rating_source']) : '';
								$alt_pros = (isset($alt_profile['pros']) && is_array($alt_profile['pros'])) ? $alt_profile['pros'] : array();
								$alt_cons = (isset($alt_profile['cons']) && is_array($alt_profile['cons'])) ? $alt_profile['cons'] : array();
								$alt_is_brand = (0 === $alt_i);
								?>
								<article class="bpg-alt-profile<?php echo $alt_is_brand ? ' bpg-alt-profile--brand' : ''; ?>" id="<?php echo esc_attr($alt_profile_id); ?>" aria-labelledby="<?php echo esc_attr($alt_profile_id . '-name'); ?>">

									<?php
									// Plain heading text, "1. Botphonic", in the same type
									// as every other heading on the page. The number used
									// to be a tinted badge span held next to the name by
									// flex; it read as a UI chip rather than part of the
									// heading, and it was the one heading here that did
									// not match the rest.
									//
									// It is also exactly what the table of contents prints
									// for this section (botphonic_alt_toc() builds its
									// label as "N. Name"), so the two now agree — and
									// because the number is no longer aria-hidden, the
									// article's accessible name via aria-labelledby below
									// matches the link a reader followed to get here.
									?>
									<h3 class="bpg-alt-profile__title" id="<?php echo esc_attr($alt_profile_id . '-name'); ?>">
										<?php
										printf(
											/* translators: 1: position in the list, 2: platform name. */
											esc_html__('%1$s. %2$s', 'botphonic'),
											esc_html(number_format_i18n($alt_i + 1)),
											esc_html($alt_profile_name)
										);
										?>
									</h3>

									<div class="bpg-alt-prose bpg-alt-profile__body">
										<?php
										/*
										 * The write-up is one wysiwyg field, so the prose,
										 * its subheadings and its lists arrive as ready
										 * markup and their order is whatever the writer
										 * typed. Only the three generated blocks are
										 * rendered here; the content positions them with a
										 * token. The html parts are already through
										 * wp_kses_post().
										 *
										 * One wrapper around the whole body, and the html
										 * parts echoed bare inside it. That is what lets a
										 * token sit *inside* an authored box — the rating
										 * inside "Best for" being the case this was written
										 * for. Wrapping each part individually, as this used
										 * to, cut the author's <div> in half: the opening tag
										 * landed in one wrapper and the closing tag in
										 * another, so the generated block ended up as a
										 * sibling of the box instead of a child. Echoed bare
										 * and concatenated, the string reassembles exactly as
										 * authored with the block dropped in at the token.
										 */
										$alt_parts = botphonic_alt_profile_parts(botphonic_alt_prose(isset($alt_profile['content']) ? $alt_profile['content'] : ''));

										foreach ($alt_parts as $alt_part):
											$alt_block = ('block' === $alt_part['type']) ? $alt_part['value'] : '';
											?>

											<?php if ('html' === $alt_part['type']): ?>
												<?php echo $alt_part['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose() before splitting. ?>

											<?php elseif ('screenshot' === $alt_block && !empty($alt_profile['screenshot'])): ?>
												<figure class="bpg-alt-profile__shot">
													<?php
													echo wp_get_attachment_image(
														(int) $alt_profile['screenshot'],
														'large',
														false,
														array(
															'alt' => sprintf(
																/* translators: %s: platform name. */
																esc_attr__('%s product interface', 'botphonic'),
																$alt_profile_name
															),
															'loading' => 'lazy',
															'decoding' => 'async',
														)
													);
													?>
												</figure>

											<?php elseif ('rating' === $alt_block && '' !== $alt_profile_rating): ?>
												<p class="bpg-alt-profile__rating">
													<strong class="bpg-alt-profile__rating-label"><?php esc_html_e('Rating:', 'botphonic'); ?></strong>
													<?php echo botphonic_alt_stars((float) $alt_profile_rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_stars(). ?>
													<span>
														<?php
														if ('' !== $alt_profile_source) {
															printf(
																/* translators: 1: rating out of five, 2: review source, e.g. G2. */
																esc_html__('%1$s/5 on %2$s', 'botphonic'),
																esc_html($alt_profile_rating),
																esc_html($alt_profile_source)
															);
														} else {
															printf(
																/* translators: %s: rating out of five. */
																esc_html__('%s/5', 'botphonic'),
																esc_html($alt_profile_rating)
															);
														}
														?>
													</span>
												</p>

											<?php elseif ('pros_cons' === $alt_block && ($alt_pros || $alt_cons)): ?>
												<div class="bpg-alt-pc">

													<?php if ($alt_pros): ?>
														<div class="bpg-alt-pc__box bpg-alt-pc__box--pros">
															<div class="bpg-alt-pc__head">
																<span class="bpg-alt-pc__icon" aria-hidden="true"><?php echo botphonic_alt_icon('plus-circle'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
																<h4 class="bpg-alt-pc__title"><?php esc_html_e('Pros', 'botphonic'); ?></h4>
															</div>
															<ul class="bpg-alt-pc__list">
																<?php foreach ($alt_pros as $alt_pro): ?>
																	<li>
																		<?php if (!empty($alt_pro['title'])): ?>
																			<strong><?php echo esc_html($alt_pro['title']); ?></strong><?php echo !empty($alt_pro['text']) ? ': ' : ''; ?>
																		<?php endif; ?>
																		<?php echo botphonic_alt_inline(isset($alt_pro['text']) ? $alt_pro['text'] : ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_inline(). ?>
																	</li>
																<?php endforeach; ?>
															</ul>

															<?php if (!empty($alt_profile['pros_review_enable']) && !empty($alt_profile['pros_review_quote'])): ?>
																<?php
																/*
																 * Reviewer name and score are both
																 * optional: a source document sometimes
																 * carries an unattributed note. The meta
																 * row is skipped entirely when there is
																 * neither, rather than defaulting the
																 * stars to five and inventing a score.
																 */
																$alt_pros_stars = isset($alt_profile['pros_review_rating']) ? trim((string) $alt_profile['pros_review_rating']) : '';
																$alt_pros_author = isset($alt_profile['pros_review_author']) ? trim((string) $alt_profile['pros_review_author']) : '';
																$alt_pros_label = botphonic_alt_review_label($alt_profile, 'pros');
																?>
																<figure class="bpg-alt-review">
																	<?php if ('' !== $alt_pros_author || '' !== $alt_pros_stars): ?>
																		<div class="bpg-alt-review__meta">
																			<?php if ('' !== $alt_pros_author): ?>
																				<p class="bpg-alt-review__author"><?php echo esc_html($alt_pros_author); ?></p>
																			<?php endif; ?>
																			<?php if ('' !== $alt_pros_stars): ?>
																				<?php echo botphonic_alt_stars((float) $alt_pros_stars); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_stars(). ?>
																			<?php endif; ?>
																		</div>
																	<?php endif; ?>
																	<blockquote class="bpg-alt-review__quote">
																		<p><?php echo botphonic_alt_inline($alt_profile['pros_review_quote']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_inline(). ?></p>
																	</blockquote>
																	<figcaption class="bpg-alt-review__source">
																		<?php if (!empty($alt_profile['pros_review_url'])): ?>
																			<a href="<?php echo esc_url($alt_profile['pros_review_url']); ?>" target="_blank" rel="noopener noreferrer nofollow"><?php echo esc_html($alt_pros_label); ?></a>
																		<?php else: ?>
																			<?php echo esc_html($alt_pros_label); ?>
																		<?php endif; ?>
																	</figcaption>
																</figure>
															<?php endif; ?>
														</div>
													<?php endif; ?>

													<?php if ($alt_cons): ?>
														<div class="bpg-alt-pc__box bpg-alt-pc__box--cons">
															<div class="bpg-alt-pc__head">
																<span class="bpg-alt-pc__icon" aria-hidden="true"><?php echo botphonic_alt_icon('minus-circle'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
																<h4 class="bpg-alt-pc__title"><?php esc_html_e('Cons', 'botphonic'); ?></h4>
															</div>
															<ul class="bpg-alt-pc__list">
																<?php foreach ($alt_cons as $alt_con): ?>
																	<li>
																		<?php if (!empty($alt_con['title'])): ?>
																			<strong><?php echo esc_html($alt_con['title']); ?></strong><?php echo !empty($alt_con['text']) ? ': ' : ''; ?>
																		<?php endif; ?>
																		<?php echo botphonic_alt_inline(isset($alt_con['text']) ? $alt_con['text'] : ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_inline(). ?>
																	</li>
																<?php endforeach; ?>
															</ul>

															<?php if (!empty($alt_profile['cons_review_enable']) && !empty($alt_profile['cons_review_quote'])): ?>
																<?php
																$alt_cons_stars = isset($alt_profile['cons_review_rating']) ? trim((string) $alt_profile['cons_review_rating']) : '';
																$alt_cons_author = isset($alt_profile['cons_review_author']) ? trim((string) $alt_profile['cons_review_author']) : '';
																$alt_cons_label = botphonic_alt_review_label($alt_profile, 'cons');
																?>
																<figure class="bpg-alt-review">
																	<?php if ('' !== $alt_cons_author || '' !== $alt_cons_stars): ?>
																		<div class="bpg-alt-review__meta">
																			<?php if ('' !== $alt_cons_author): ?>
																				<p class="bpg-alt-review__author"><?php echo esc_html($alt_cons_author); ?></p>
																			<?php endif; ?>
																			<?php if ('' !== $alt_cons_stars): ?>
																				<?php echo botphonic_alt_stars((float) $alt_cons_stars); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_stars(). ?>
																			<?php endif; ?>
																		</div>
																	<?php endif; ?>
																	<blockquote class="bpg-alt-review__quote">
																		<p><?php echo botphonic_alt_inline($alt_profile['cons_review_quote']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_inline(). ?></p>
																	</blockquote>
																	<figcaption class="bpg-alt-review__source">
																		<?php if (!empty($alt_profile['cons_review_url'])): ?>
																			<a href="<?php echo esc_url($alt_profile['cons_review_url']); ?>" target="_blank" rel="noopener noreferrer nofollow"><?php echo esc_html($alt_cons_label); ?></a>
																		<?php else: ?>
																			<?php echo esc_html($alt_cons_label); ?>
																		<?php endif; ?>
																	</figcaption>
																</figure>
															<?php endif; ?>
														</div>
													<?php endif; ?>

												</div>
											<?php endif; ?>

										<?php endforeach; ?>
									</div><!-- .bpg-alt-profile__body -->

								</article>

								<?php
								// Inline CTA, straight after our own write-up, while the
								// reader is on the strongest part of the page.
								if (0 === $alt_i) {
									echo botphonic_alt_cta($alt['inline_cta']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_cta().
								}
								?>
							<?php endforeach; ?>

						</section>
					<?php endif; ?>

					<!-- How to choose -->
					<?php if (!empty($alt['choose'])): ?>
						<section class="bpg-alt-sec" aria-labelledby="bpg-alt-choose">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-choose"><?php echo esc_html($alt['choose_title']); ?></h2>
							<div class="bpg-alt-prose">
								<?php echo botphonic_alt_prose($alt['choose']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Final verdict -->
					<?php if (!empty($alt['verdict'])): ?>
						<section class="bpg-alt-sec bpg-alt-verdict" aria-labelledby="bpg-alt-verdict">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-verdict"><?php echo esc_html($alt['verdict_title']); ?></h2>
							<div class="bpg-alt-prose">
								<?php echo botphonic_alt_prose($alt['verdict']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised in botphonic_alt_prose(). ?>
							</div>
						</section>
					<?php endif; ?>

					<?php
					// Anything typed into the editor. Kept after the structured
					// sections so the ACF narrative stays the spine of the page.
					if ($alt_body):
						?>
						<section class="bpg-alt-sec">
							<div class="bpg-alt-prose">
								<?php echo $alt_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already through the_content filters. ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- End-of-article CTA -->
					<?php
					echo botphonic_alt_cta($alt['mid_cta']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_cta().
					?>

					<!-- FAQs -->
					<?php if ($alt['faqs'] && function_exists('botphonic_render_faq_group')): ?>
						<section class="bpg-alt-faqs" aria-labelledby="bpg-alt-faq">
							<h2 class="bpg-alt-sec__title" id="bpg-alt-faq"><?php esc_html_e('Frequently asked questions', 'botphonic'); ?></h2>
							<?php
							$alt_faq_rows = array_map(
								function ($alt_faq) {
									return array(
										'question' => isset($alt_faq['question']) ? $alt_faq['question'] : '',
										'answer' => botphonic_alt_prose(isset($alt_faq['answer']) ? $alt_faq['answer'] : ''),
									);
								},
								$alt['faqs']
							);
							echo botphonic_render_faq_group($alt_faq_rows); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer.
							unset($alt_faq_rows);
							?>
						</section>
					<?php endif; ?>

					<!-- Author -->
					<?php if ($alt_author_name): ?>
						<section class="bpg-authorbox" aria-labelledby="bpg-alt-author">
							<div class="bpg-authorbox__head">
								<div class="bpg-authorbox__avatar">
									<?php echo get_avatar($alt_author_id, 168, '', esc_attr($alt_author_name), array('loading' => 'lazy')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_avatar() returns escaped markup. ?>
								</div>
								<div class="bpg-authorbox__ident">
									<p class="bpg-authorbox__label"><?php esc_html_e('Reviewed by', 'botphonic'); ?></p>
									<h2 class="bpg-authorbox__name" id="bpg-alt-author"><?php echo esc_html($alt_author_name); ?></h2>
								</div>
							</div>

							<?php if ($alt_author_bio): ?>
								<p class="bpg-authorbox__desc"><?php echo wp_kses_post(nl2br($alt_author_bio)); ?></p>
							<?php endif; ?>

							<a class="bpg-authorbox__link" href="<?php echo esc_url(get_author_posts_url($alt_author_id)); ?>">
								<?php
								printf(
									/* translators: %s: author display name. */
									esc_html__('View all posts by %s', 'botphonic'),
									esc_html($alt_author_name)
								);
								?>
								<?php echo botphonic_alt_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
							</a>
						</section>
					<?php endif; ?>

					<?php if ($alt_archive_url): ?>
						<p class="bpg-alt-backlink">
							<a href="<?php echo esc_url($alt_archive_url); ?>">
								<?php esc_html_e('See all comparison guides', 'botphonic'); ?>
								<?php echo botphonic_alt_icon('arrow-right'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
							</a>
						</p>
					<?php endif; ?>

				</main>
			</div>
		</div>
	</div>

</div><!-- #single-wrapper -->

<?php get_footer(); ?>