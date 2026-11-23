<?php
/**
 * Hub page layout, shared by the Products, Solutions and Industries hubs.
 *
 * Loaded by page-product.php, page-solution.php and template-industries.php
 * with $args['page'] from botphonic_hub_boot() (inc/function-hub-pages.php).
 * A section is skipped when the hub has no data for it.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bphub = isset($args['page']) && is_array($args['page']) ? $args['page'] : botphonic_hub_page_data();
if (!$bphub) {
	return;
}

$bphub_hero = (array) $bphub['hero'];
$bphub_list = (array) $bphub['list'];
$bphub_guide = (array) $bphub['guide'];
$bphub_features = (array) $bphub['features'];
$bphub_related = (array) $bphub['related'];
$bphub_faq = (array) $bphub['faq'];
$bphub_cta = (array) $bphub['cta'];
$bphub_cards = (array) $bphub['cards'];
$bphub_count = count($bphub_cards);
$bphub_noun = $bphub['noun'];
$bphub_arrow = botphonic_hub_icon('arrow', 16);
$bphub_group_counts = array_count_values(array_filter(wp_list_pluck($bphub_cards, 'group')));
$bphub_post = $bphub['hub_id'] ? get_post($bphub['hub_id']) : null;

// The hub page's featured image is the hero visual (and the share image).
// It is the likely LCP element, so it loads eagerly at high priority.
$bphub_thumb = $bphub['hub_id'] ? (int) get_post_thumbnail_id($bphub['hub_id']) : 0;
$bphub_image = $bphub_thumb ? wp_get_attachment_image(
	$bphub_thumb,
	'full',
	false,
	array(
		'class' => 'bphub-hero__img',
		'loading' => 'eager',
		'fetchpriority' => 'high',
		'decoding' => 'async',
		'sizes' => '(min-width: 1360px) 560px, (min-width: 1024px) 41vw, (min-width: 700px) 620px, calc(100vw - 32px)',
	)
) : '';
?>
<div id="bp-hub" class="bphub bphub--<?php echo esc_attr($bphub['key']); ?> no-js" data-bphub>
	<main id="main" class="bphub-main">

		<section class="bphub-hero<?php echo $bphub_image ? ' bphub-hero--media' : ''; ?>" aria-labelledby="bphub-title">
			<div class="bphub-wrap">
				<div class="bphub-hero__copy">
					<nav class="bphub-breadcrumb" aria-label="Breadcrumb">
						<a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
						<span aria-hidden="true">/</span>
						<span aria-current="page"><?php echo esc_html($bphub['label']); ?></span>
					</nav>
					<?php if (!empty($bphub_hero['kicker'])) : ?>
						<p class="bphub-kicker"><?php echo esc_html($bphub_hero['kicker']); ?></p>
					<?php endif; ?>
					<h1 id="bphub-title"><?php echo esc_html($bphub_hero['title']); ?></h1>
					<?php if (!empty($bphub_hero['lede'])) : ?>
						<p class="bphub-lede"><?php echo esc_html($bphub_hero['lede']); ?></p>
					<?php endif; ?>
					<div class="bphub-actions">
						<a class="bphub-btn bphub-btn--coral" href="<?php echo esc_url($bphub['register_url']); ?>">Start 14-day free trial<?php echo $bphub_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
						<a class="bphub-btn bphub-btn--outline" href="<?php echo esc_url($bphub['demo_url']); ?>">Book a 20-minute demo</a>
					</div>
					<p class="bphub-note">20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime</p>
					<?php if (!empty($bphub_hero['proof'])) : ?>
						<ul class="bphub-proof" role="list" aria-label="Botphonic at a glance">
							<?php foreach ((array) $bphub_hero['proof'] as $bphub_proof) : ?>
								<li><strong><?php echo esc_html($bphub_proof[0]); ?></strong> <?php echo esc_html($bphub_proof[1]); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<?php if ($bphub_image) : ?>
					<figure class="bphub-hero__media"><?php echo $bphub_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() output. ?></figure>
				<?php endif; ?>
			</div>
		</section>

		<section id="all" class="bphub-section bphub-list" aria-labelledby="bphub-list-title">
			<div class="bphub-wrap">
				<div class="bphub-list__head">
					<div class="bphub-stack">
						<?php if (!empty($bphub_list['kicker'])) : ?>
							<p class="bphub-kicker"><?php echo esc_html($bphub_list['kicker']); ?></p>
						<?php endif; ?>
						<h2 id="bphub-list-title"><?php echo esc_html($bphub_list['title'] ?? $bphub['label']); ?></h2>
						<?php if (!empty($bphub_list['lede'])) : ?>
							<p class="bphub-list__lede"><?php echo esc_html($bphub_list['lede']); ?></p>
						<?php endif; ?>
					</div>
					<?php if (count($bphub_group_counts) > 1) : ?>
						<div class="bphub-filters" role="group" aria-label="<?php echo esc_attr('Filter ' . $bphub_noun[1]); ?>" data-bphub-filters>
							<button type="button" class="bphub-filter" data-bphub-filter="all" aria-pressed="true">All <span class="bphub-filter__count"><?php echo esc_html(number_format_i18n($bphub_count)); ?></span></button>
							<?php foreach ((array) $bphub['groups'] as $bphub_group_id => $bphub_group_label) : ?>
								<?php if (!empty($bphub_group_counts[$bphub_group_id])) : ?>
									<button type="button" class="bphub-filter" data-bphub-filter="<?php echo esc_attr($bphub_group_id); ?>" aria-pressed="false"><?php echo esc_html($bphub_group_label); ?> <span class="bphub-filter__count"><?php echo esc_html(number_format_i18n($bphub_group_counts[$bphub_group_id])); ?></span></button>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ($bphub_cards) : ?>
					<p class="bphub-count" aria-live="polite" data-bphub-count data-one="<?php echo esc_attr($bphub_noun[0]); ?>" data-many="<?php echo esc_attr($bphub_noun[1]); ?>"><?php echo esc_html(number_format_i18n($bphub_count) . ' ' . (1 === $bphub_count ? $bphub_noun[0] : $bphub_noun[1])); ?></p>
					<ul class="bphub-grid" role="list">
						<?php foreach ($bphub_cards as $bphub_card) : ?>
							<li class="bphub-grid__item" data-bphub-group="<?php echo esc_attr($bphub_card['group']); ?>">
								<article class="bphub-card">
									<div class="bphub-card__top">
										<span class="bphub-card__icon"><?php echo botphonic_hub_icon($bphub_card['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
										<?php if ('' !== $bphub_card['group_label']) : ?>
											<span class="bphub-card__group"><?php echo esc_html($bphub_card['group_label']); ?></span>
										<?php endif; ?>
									</div>
									<h3 class="bphub-card__title"><a class="bphub-card__link" href="<?php echo esc_url($bphub_card['url']); ?>"><?php echo esc_html($bphub_card['title']); ?></a></h3>
									<?php if ('' !== $bphub_card['line']) : ?>
										<p class="bphub-card__line"><?php echo esc_html($bphub_card['line']); ?></p>
									<?php endif; ?>
									<?php if ($bphub_card['tasks']) : ?>
										<ul class="bphub-card__tasks" role="list">
											<?php foreach ($bphub_card['tasks'] as $bphub_task) : ?>
												<li><?php echo esc_html($bphub_task); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
									<span class="bphub-card__cta" aria-hidden="true">Explore <?php echo esc_html($bphub_card['title']); ?><?php echo $bphub_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
								</article>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p class="bphub-empty"><?php echo esc_html(ucfirst($bphub_noun[1]) . ' will appear here once their pages are published.'); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<?php if (!empty($bphub['guide_rows'])) : ?>
			<?php $bphub_columns = array_values((array) ($bphub_guide['columns'] ?? array())); ?>
			<section id="guide" class="bphub-section bphub-section--tint" aria-labelledby="bphub-guide-title">
				<div class="bphub-wrap">
					<header class="bphub-heading">
						<?php if (!empty($bphub_guide['kicker'])) : ?>
							<p class="bphub-kicker"><?php echo esc_html($bphub_guide['kicker']); ?></p>
						<?php endif; ?>
						<h2 id="bphub-guide-title"><?php echo esc_html($bphub_guide['title']); ?></h2>
						<?php if (!empty($bphub_guide['lede'])) : ?>
							<p class="bphub-heading__lede"><?php echo esc_html($bphub_guide['lede']); ?></p>
						<?php endif; ?>
					</header>
					<div class="bphub-table">
						<table role="table">
							<?php if (!empty($bphub_guide['caption'])) : ?>
								<caption class="bphub-sr"><?php echo esc_html($bphub_guide['caption']); ?></caption>
							<?php endif; ?>
							<?php if ($bphub_columns) : ?>
								<thead role="rowgroup">
									<tr role="row">
										<?php foreach ($bphub_columns as $bphub_column) : ?>
											<th scope="col" role="columnheader"><?php echo esc_html($bphub_column); ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
							<?php endif; ?>
							<tbody role="rowgroup">
								<?php foreach ($bphub['guide_rows'] as $bphub_row) : ?>
									<tr role="row">
										<?php foreach ($bphub_row as $bphub_i => $bphub_cell) : ?>
											<?php $bphub_label = isset($bphub_columns[$bphub_i]) ? $bphub_columns[$bphub_i] : ''; ?>
											<?php if (0 === $bphub_i) : ?>
												<th scope="row" role="rowheader" data-label="<?php echo esc_attr($bphub_label); ?>"><?php echo $bphub_cell; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_hub_cell_html(). ?></th>
											<?php else : ?>
												<td role="cell" data-label="<?php echo esc_attr($bphub_label); ?>"><?php echo $bphub_cell; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_hub_cell_html(). ?></td>
											<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if (!empty($bphub_features['items'])) : ?>
			<?php
			$bphub_steps = !empty($bphub_features['numbered']);
			$bphub_tag = $bphub_steps ? 'ol' : 'ul';
			?>
			<section class="bphub-section" aria-labelledby="bphub-features-title">
				<div class="bphub-wrap">
					<header class="bphub-heading">
						<?php if (!empty($bphub_features['kicker'])) : ?>
							<p class="bphub-kicker"><?php echo esc_html($bphub_features['kicker']); ?></p>
						<?php endif; ?>
						<h2 id="bphub-features-title"><?php echo esc_html($bphub_features['title']); ?></h2>
						<?php if (!empty($bphub_features['lede'])) : ?>
							<p class="bphub-heading__lede"><?php echo esc_html($bphub_features['lede']); ?></p>
						<?php endif; ?>
					</header>
					<<?php echo $bphub_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ol or ul. ?> class="bphub-features<?php echo $bphub_steps ? ' bphub-features--steps' : ''; ?>" role="list">
						<?php foreach (array_values((array) $bphub_features['items']) as $bphub_n => $bphub_feature) : ?>
							<li class="bphub-feature">
								<?php if ($bphub_steps) : ?>
									<span class="bphub-feature__step"><?php echo esc_html(str_pad((string) ($bphub_n + 1), 2, '0', STR_PAD_LEFT)); ?></span>
								<?php else : ?>
									<span class="bphub-feature__icon"><?php echo botphonic_hub_icon($bphub_feature['icon'] ?? 'check', 22); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
								<?php endif; ?>
								<h3><?php echo esc_html($bphub_feature['title']); ?></h3>
								<p><?php echo esc_html($bphub_feature['text']); ?></p>
							</li>
						<?php endforeach; ?>
					</<?php echo $bphub_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ol or ul. ?>>
				</div>
			</section>
		<?php endif; ?>

		<?php if (!empty($bphub_related['items'])) : ?>
			<section class="bphub-section bphub-section--tint" aria-labelledby="bphub-related-title">
				<div class="bphub-wrap">
					<header class="bphub-heading">
						<?php if (!empty($bphub_related['kicker'])) : ?>
							<p class="bphub-kicker"><?php echo esc_html($bphub_related['kicker']); ?></p>
						<?php endif; ?>
						<h2 id="bphub-related-title"><?php echo esc_html($bphub_related['title']); ?></h2>
					</header>
					<ul class="bphub-related" role="list">
						<?php foreach ((array) $bphub_related['items'] as $bphub_item) : ?>
							<li>
								<article class="bphub-related__card">
									<span class="bphub-related__icon"><?php echo botphonic_hub_icon($bphub_item['icon'] ?? 'grid', 22); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
									<h3><a class="bphub-related__link" href="<?php echo esc_url($bphub_item['url']); ?>"><?php echo esc_html($bphub_item['title']); ?></a></h3>
									<p><?php echo esc_html($bphub_item['text']); ?></p>
									<span class="bphub-related__cta" aria-hidden="true"><?php echo esc_html($bphub_item['cta'] ?? 'Explore'); ?><?php echo $bphub_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
								</article>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ($bphub_post instanceof WP_Post && '' !== trim((string) $bphub_post->post_content)) : ?>
			<section class="bphub-section" aria-label="<?php echo esc_attr('More about ' . $bphub_noun[1]); ?>">
				<div class="bphub-wrap">
					<div class="bphub-prose">
						<?php
						// Optional copy added in the editor, including shortcodes and blocks.
						rewind_posts();
						while (have_posts()) {
							the_post();
							the_content();
						}
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if (!empty($bphub['faqs']) && function_exists('botphonic_render_faq_group')) : ?>
			<section id="faq" class="bphub-section" aria-labelledby="bphub-faq-title">
				<div class="bphub-wrap bphub-faq">
					<div class="bphub-faq__intro">
						<p class="bphub-kicker">FAQ</p>
						<h2 id="bphub-faq-title"><?php echo esc_html($bphub_faq['title'] ?? 'Frequently asked questions'); ?></h2>
						<?php if (!empty($bphub_faq['lede'])) : ?>
							<p class="bphub-faq__lede"><?php echo esc_html($bphub_faq['lede']); ?></p>
						<?php endif; ?>
						<a class="bphub-btn bphub-btn--outline" href="<?php echo esc_url($bphub['demo_url']); ?>">Book a 20-minute demo</a>
					</div>
					<div class="bphub-faq__list">
						<?php
						// With Yoast active the questions go into its graph (inc/function-hub-pages.php),
						// so the plugin's standalone FAQPage output stays off to avoid a duplicate.
						echo botphonic_render_faq_group($bphub['faqs'], array('schema' => !defined('WPSEO_VERSION'))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer.
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if (!empty($bphub_cta['title'])) : ?>
			<section class="bphub-section bphub-section--cta" aria-labelledby="bphub-cta-title">
				<div class="bphub-wrap">
					<div class="bphub-cta">
						<div>
							<h2 id="bphub-cta-title"><?php echo esc_html($bphub_cta['title']); ?></h2>
							<?php if (!empty($bphub_cta['text'])) : ?>
								<p><?php echo esc_html($bphub_cta['text']); ?></p>
							<?php endif; ?>
						</div>
						<div class="bphub-actions">
							<?php foreach (array('primary' => 'coral', 'secondary' => 'ghost') as $bphub_slot => $bphub_tone) : ?>
								<?php if (!empty($bphub_cta[$bphub_slot]['url'])) : ?>
									<a class="bphub-btn bphub-btn--<?php echo esc_attr($bphub_tone); ?>" href="<?php echo esc_url($bphub_cta[$bphub_slot]['url']); ?>"><?php echo esc_html($bphub_cta[$bphub_slot]['label']); ?><?php echo 'primary' === $bphub_slot ? $bphub_arrow : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

	</main>
</div>
<?php
unset(
	$bphub,
	$bphub_hero,
	$bphub_list,
	$bphub_guide,
	$bphub_features,
	$bphub_related,
	$bphub_faq,
	$bphub_cta,
	$bphub_cards,
	$bphub_count,
	$bphub_noun,
	$bphub_arrow,
	$bphub_group_counts,
	$bphub_post,
	$bphub_thumb,
	$bphub_image
);
