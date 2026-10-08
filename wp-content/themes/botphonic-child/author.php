<?php

/**
 * Author archive: the author's profile plus every article they have published.
 *
 * Built on the blog listing system, the same .bpg-archive / .bpg-arc-hero /
 * .bpg-grid / .bpg-pagination-wrap / .bpg-band skeleton as archive.php and
 * archive-alternatives.php, so an author page reads as part of the same family
 * as the blog and the comparison guides. The profile pieces (avatar, social
 * links, stats, topics) are the "Author archive" block at the end of
 * assets/css/blog.css, which author pages load as a blog view.
 *
 * The listing is the main query limited to the author's 3 latest posts
 * (botphonic_blog_author_per_page()), with no pagination.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

get_header();

$bpg_author = get_queried_object();
$bpg_author_id = $bpg_author instanceof WP_User ? (int) $bpg_author->ID : 0;
$bpg_name = $bpg_author instanceof WP_User ? $bpg_author->display_name : '';
$bpg_position = (string) get_user_meta($bpg_author_id, 'user_position', true);
$bpg_email = (string) get_the_author_meta('user_email', $bpg_author_id);

$bpg_posts_page_id = (int) get_option('page_for_posts');

// Profile links, in the order they print. Empty ones are dropped.
$bpg_social = array();
foreach (
	array(
		'linkedin' => array('meta' => 'linkedin', 'label' => 'LinkedIn'),
		'x' => array('meta' => 'twitter', 'label' => 'X'),
		'youtube' => array('meta' => 'youtube', 'label' => 'YouTube'),
	) as $bpg_icon => $bpg_link
) {
	$bpg_url = trim((string) get_the_author_meta($bpg_link['meta'], $bpg_author_id));
	if ('' !== $bpg_url) {
		$bpg_social[] = array('icon' => $bpg_icon, 'label' => $bpg_link['label'], 'url' => $bpg_url);
	}
}

/*
 * Stats and topics come from the author's published posts only, so every
 * visitor sees the same numbers (the main query also counts private posts for
 * logged-in editors). Ordered oldest first, which makes the first ID the
 * "writing since" post. One terms query covers every post's categories.
 */
$bpg_post_ids = $bpg_author_id
	? get_posts(
		array(
			'author' => $bpg_author_id,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'ASC',
			'fields' => 'ids',
			'no_found_rows' => true,
		)
	)
	: array();
$bpg_post_count = count($bpg_post_ids);
$bpg_since = $bpg_post_ids ? get_the_date('Y', $bpg_post_ids[0]) : '';

$bpg_topics = array();
if ($bpg_post_ids) {
	$bpg_terms = wp_get_object_terms($bpg_post_ids, 'category', array('fields' => 'all_with_object_id'));

	if (!is_wp_error($bpg_terms)) {
		foreach ($bpg_terms as $bpg_term) {
			if (!isset($bpg_topics[$bpg_term->term_id])) {
				$bpg_topics[$bpg_term->term_id] = array('term' => $bpg_term, 'count' => 0);
			}
			$bpg_topics[$bpg_term->term_id]['count']++;
		}
	}

	// Most-written first; alphabetical within a tie so the order is stable.
	uasort(
		$bpg_topics,
		static function ($a, $b) {
			if ($a['count'] !== $b['count']) {
				return $b['count'] - $a['count'];
			}
			return strnatcasecmp($a['term']->name, $b['term']->name);
		}
	);
}
$bpg_topic_count = count($bpg_topics);
$bpg_topics_shown = array_slice($bpg_topics, 0, 8);
?>

<div class="bpg-archive bpg-author" id="author-wrapper">

	<!-- ── Hero: who wrote it ───────────────────────────────────────── -->
	<header class="bpg-arc-hero bpg-author-hero">
		<div class="bpg-shell">
			<div class="bpg-arc-hero__inner" itemscope itemtype="https://schema.org/Person">

				<?php
				// Home → Author. No "Page N" tail: the count line says which page this is.
				if (function_exists('wpdev_breadcrumbs')) {
					wpdev_breadcrumbs(array(
						'show_paged' => false,
					));
				}
				?>

				<div class="bpg-author__avatar">
					<?php
					echo get_avatar(
						$bpg_author_id,
						240,
						'',
						esc_attr($bpg_name),
						array(
							'loading' => 'eager',
							'extra_attr' => 'itemprop="image" fetchpriority="high"',
						)
					);
					?>
				</div>

				<p class="bpg-eyebrow">Author</p>

				<h1 class="bpg-arc-hero__title" itemprop="name"><?php echo esc_html($bpg_name); ?></h1>
				<link itemprop="url" href="<?php echo esc_url(get_author_posts_url($bpg_author_id)); ?>">

				<?php if ('' !== $bpg_position) : ?>
				<p class="bpg-author__role" itemprop="jobTitle"><?php echo esc_html($bpg_position); ?></p>
				<?php endif; ?>

				<?php if ($bpg_social || '' !== $bpg_email) : ?>
				<ul class="bpg-author__social" aria-label="<?php echo esc_attr(sprintf('%s online', $bpg_name)); ?>">
					<?php foreach ($bpg_social as $bpg_link) : ?>
					<li>
						<a href="<?php echo esc_url($bpg_link['url']); ?>" target="_blank" rel="me noopener noreferrer" itemprop="sameAs" aria-label="<?php echo esc_attr(sprintf('%1$s on %2$s (opens in a new tab)', $bpg_name, $bpg_link['label'])); ?>">
							<?php echo botphonic_blog_icon($bpg_link['icon']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
						</a>
					</li>
					<?php endforeach; ?>

					<?php if ('' !== $bpg_email) : ?>
					<li>
						<?php // antispambot() entity-encodes the address so it isn't sitting in the page as plain text for scrapers. ?>
						<a href="mailto:<?php echo esc_attr(antispambot($bpg_email)); ?>" aria-label="<?php echo esc_attr(sprintf('Email %s', $bpg_name)); ?>">
							<?php echo botphonic_blog_icon('mail'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
						</a>
					</li>
					<?php endif; ?>
				</ul>
				<?php endif; ?>

				<?php if ($bpg_post_count) : ?>
				<dl class="bpg-author__stats">
					<div class="bpg-author__stat">
						<dt><?php echo esc_html(_n('Article published', 'Articles published', $bpg_post_count, 'botphonic')); ?></dt>
						<dd><?php echo esc_html(number_format_i18n($bpg_post_count)); ?></dd>
					</div>
					<div class="bpg-author__stat">
						<dt>Writing since</dt>
						<dd><?php echo esc_html($bpg_since); ?></dd>
					</div>
					<div class="bpg-author__stat">
						<dt><?php echo esc_html(_n('Topic covered', 'Topics covered', $bpg_topic_count, 'botphonic')); ?></dt>
						<dd><?php echo esc_html(number_format_i18n($bpg_topic_count)); ?></dd>
					</div>
				</dl>
				<?php endif; ?>

				<?php if ($bpg_topics_shown) : ?>
				<div class="bpg-author__topics">
					<p class="bpg-author__topics-label">Writes about</p>
					<?php
					// Same pills as the blog's topic rail (botphonic_blog_category_filter());
					// the count is how many of this author's articles sit in that topic.
					?>
					<nav class="bpg-filters" aria-label="<?php echo esc_attr(sprintf('Topics %s writes about', $bpg_name)); ?>">
						<div class="bpg-filters__track">
							<?php foreach ($bpg_topics_shown as $bpg_topic) : ?>
							<a class="bpg-filter" href="<?php echo esc_url(get_category_link($bpg_topic['term']->term_id)); ?>">
								<?php echo esc_html($bpg_topic['term']->name); ?>
								<span class="bpg-filter__count"><?php echo esc_html(number_format_i18n($bpg_topic['count'])); ?></span>
							</a>
							<?php endforeach; ?>
						</div>
					</nav>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</header>

	<!-- ── Body ─────────────────────────────────────────────────────── -->
	<div class="bpg-arc-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<section class="bpg-author__posts" aria-labelledby="bpg-author-posts-title">
					<div class="bpg-sec-head">
						<h2 class="bpg-sec-head__title" id="bpg-author-posts-title"><?php echo esc_html(sprintf('Latest articles by %s', $bpg_name)); ?></h2>
					</div>

					<?php if (have_posts()) : ?>

						<?php // The 3 latest posts (botphonic_blog_author_per_page()); deliberately no pagination. ?>
						<div class="bpg-grid">
							<?php
							while (have_posts()) :
								the_post();
								get_template_part('loop-templates/content', 'bpgcard');
							endwhile;
							?>
						</div>

					<?php else : ?>

						<div class="bpg-empty">
							<h3 class="bpg-empty__title">No articles published yet</h3>
							<p>Articles by <?php echo esc_html($bpg_name); ?> will appear here once they go live.</p>
							<?php if ($bpg_posts_page_id) : ?>
							<p class="bpg-empty__action">
								<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url(get_permalink($bpg_posts_page_id)); ?>">Browse all articles</a>
							</p>
							<?php endif; ?>
						</div>

					<?php endif; ?>
				</section>

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

</div><!-- #author-wrapper -->

<?php get_footer(); ?>
