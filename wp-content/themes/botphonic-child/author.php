<?php

/**
 * Author archive.
 *
 * Built on the blog design system — blog.css plus the global --bpg-* tokens in
 * style.css — so a profile reads like the listing it belongs to; the cards,
 * chips, buttons, pagination and CTA band are the blog's own. assets/css/author.css
 * only adds the profile layer (hero identity, stats, jump links, About).
 *
 * Layout: profile hero → About → latest articles → comparison guides →
 *         customer stories → CTA band. Each content row shows the latest
 *         BOTPHONIC_AUTHOR_CARDS (3) items — one clean row — with a link to
 *         the full listing; there is no featured card and no pagination.
 *
 * The articles row reads the main query (posts by this author); comparison
 * guides and customer stories are secondary queries, shown only when the
 * author has any.
 *
 * Data helpers live in inc/function-author.php.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_AUTHOR_CARDS')) {
	define('BOTPHONIC_AUTHOR_CARDS', 3);
}

$bpa_user = get_queried_object();

if (!$bpa_user instanceof WP_User) {
	$bpa_user = get_userdata((int) get_query_var('author'));
}

if (!$bpa_user instanceof WP_User) {
	get_template_part('404'); // 404.php prints its own header and footer.
	return;
}

get_header();

$bpa_id = (int) $bpa_user->ID;
$bpa_name = $bpa_user->display_name;
$bpa_paged = max(1, (int) get_query_var('paged'));
$bpa_first = (1 === $bpa_paged);
$bpa_pages = (int) $GLOBALS['wp_query']->max_num_pages;

$bpa_profile = botphonic_author_profile($bpa_user);
$bpa_types = botphonic_author_content_types();
$bpa_counts = botphonic_author_counts($bpa_id);
$bpa_topics = botphonic_author_topics($bpa_id, 8);
$bpa_range = botphonic_author_date_range($bpa_id);
$bpa_total = array_sum($bpa_counts);

// Secondary content types (everything except posts) with something to show.
$bpa_extra = array();
foreach ($bpa_types as $bpa_type => $bpa_meta) {
	if ('post' !== $bpa_type && !empty($bpa_counts[$bpa_type])) {
		$bpa_extra[$bpa_type] = $bpa_meta;
	}
}

// In-page jump links (page 1 only).
$bpa_jumps = array();
if ($bpa_first) {
	if ($bpa_profile['about']) {
		$bpa_jumps['about'] = sprintf(__('About %s', 'botphonic'), $bpa_user->first_name ?: $bpa_name);
	}
	foreach ($bpa_types as $bpa_type => $bpa_meta) {
		if (!empty($bpa_counts[$bpa_type])) {
			$bpa_jumps[$bpa_meta['anchor']] = $bpa_meta['label'];
		}
	}
}

$bpa_posts_page_id = (int) get_option('page_for_posts');
$bpa_blog_url = $bpa_posts_page_id ? get_permalink($bpa_posts_page_id) : home_url('/');

botphonic_author_schema($bpa_user, $bpa_profile);
?>

<div class="bpg-archive bpa" id="author-wrapper">

	<!-- ── Profile hero ─────────────────────────────────────────────── -->
	<header class="bpg-arc-hero bpa-hero<?php echo $bpa_first ? '' : ' bpa-hero--compact'; ?>">
		<div class="bpg-shell">

			<?php
			if (function_exists('wpdev_breadcrumbs')) {
				wpdev_breadcrumbs(array('show_paged' => false));
			}
			?>

			<div class="bpa-hero__grid">

				<div class="bpa-hero__avatar">
					<?php echo get_avatar($bpa_id, $bpa_first ? 240 : 128, '', esc_attr($bpa_name), array('loading' => 'eager', 'class' => 'bpa-hero__img')); ?>
				</div>

				<div class="bpa-hero__body">
					<p class="bpg-eyebrow"><?php esc_html_e('Author', 'botphonic'); ?></p>
					<h1 class="bpa-hero__name"><?php echo esc_html($bpa_name); ?></h1>

					<?php if ($bpa_profile['role']) : ?>
					<p class="bpa-hero__role"><?php echo esc_html($bpa_profile['role']); ?> <span>· Botphonic</span></p>
					<?php endif; ?>

					<?php if ($bpa_first && $bpa_profile['lede']) : ?>
					<p class="bpa-hero__lede"><?php echo esc_html($bpa_profile['lede']); ?></p>
					<?php endif; ?>

					<?php if (!empty($bpa_profile['links'])) : ?>
					<ul class="bpa-social" aria-label="<?php echo esc_attr(sprintf(__('%s online', 'botphonic'), $bpa_name)); ?>">
						<?php foreach ($bpa_profile['links'] as $bpa_link) :
							$bpa_is_mail = ('mail' === $bpa_link['network']);
						?>
						<li>
							<a class="bpa-social__link bpa-social__link--<?php echo esc_attr($bpa_link['network']); ?>"
								href="<?php echo $bpa_is_mail ? esc_attr($bpa_link['url']) : esc_url($bpa_link['url']); ?>"
								<?php echo $bpa_is_mail ? 'rel="nofollow"' : 'target="_blank" rel="me noopener noreferrer"'; ?>
								aria-label="<?php echo esc_attr(sprintf('%1$s — %2$s', $bpa_link['label'], $bpa_name)); ?>">
								<?php echo botphonic_author_icon($bpa_link['network']); // phpcs:ignore WordPress.Security.EscapeOutput -- whitelisted SVG. ?>
								<span><?php echo esc_html($bpa_link['label']); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>

			</div>

			<?php if ($bpa_first && $bpa_total) : ?>
			<dl class="bpa-stats">
				<?php foreach ($bpa_types as $bpa_type => $bpa_meta) :
					if (empty($bpa_counts[$bpa_type])) {
						continue;
					}
				?>
				<div class="bpa-stat">
					<dt><?php echo esc_html($bpa_meta['label']); ?></dt>
					<dd><?php echo esc_html(number_format_i18n($bpa_counts[$bpa_type])); ?></dd>
				</div>
				<?php endforeach; ?>

				<?php if ($bpa_topics) : ?>
				<div class="bpa-stat">
					<dt><?php esc_html_e('Topics covered', 'botphonic'); ?></dt>
					<dd><?php echo esc_html(number_format_i18n(count($bpa_topics))); ?></dd>
				</div>
				<?php endif; ?>

				<?php if ($bpa_range['first']) : ?>
				<div class="bpa-stat">
					<dt><?php esc_html_e('Writing since', 'botphonic'); ?></dt>
					<dd><?php echo esc_html(mysql2date('M Y', $bpa_range['first'])); ?></dd>
				</div>
				<?php endif; ?>

				<?php if ($bpa_range['latest']) : ?>
				<div class="bpa-stat">
					<dt><?php esc_html_e('Last published', 'botphonic'); ?></dt>
					<dd><?php echo esc_html(mysql2date('M j, Y', $bpa_range['latest'])); ?></dd>
				</div>
				<?php endif; ?>
			</dl>
			<?php endif; ?>

			<?php if ($bpa_first && $bpa_topics) : ?>
			<div class="bpa-topics">
				<p class="bpa-topics__label"><?php esc_html_e('Writes about', 'botphonic'); ?></p>
				<div class="bpa-topics__list">
					<?php foreach ($bpa_topics as $bpa_topic) : ?>
					<a class="bpg-filter" href="<?php echo esc_url(get_category_link($bpa_topic['term']->term_id)); ?>">
						<?php echo esc_html($bpa_topic['term']->name); ?>
						<span class="bpg-filter__count"><?php echo esc_html(number_format_i18n($bpa_topic['count'])); ?></span>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if (count($bpa_jumps) > 1) : ?>
			<nav class="bpa-jump" aria-label="<?php esc_attr_e('On this page', 'botphonic'); ?>">
				<?php foreach ($bpa_jumps as $bpa_anchor => $bpa_label) : ?>
				<a class="bpa-jump__link" href="#<?php echo esc_attr($bpa_anchor); ?>"><?php echo esc_html($bpa_label); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>

		</div>
	</header>

	<div class="bpg-arc-body bpa-body" id="content" tabindex="-1">
		<div class="bpg-shell">
			<main class="site-main" id="main">

				<!-- ── About ──────────────────────────────────────────────── -->
				<?php if ($bpa_first && $bpa_profile['about']) : ?>
				<section class="bpa-section bpa-about" id="about" aria-labelledby="bpa-about-title">
					<div class="bpa-about__grid">
						<div class="bpa-about__prose">
							<p class="bpg-eyebrow"><?php esc_html_e('About the author', 'botphonic'); ?></p>
							<h2 class="bpa-about__title" id="bpa-about-title"><?php echo esc_html(sprintf(__('About %s', 'botphonic'), $bpa_name)); ?></h2>
							<div class="bpa-about__content">
								<?php echo wp_kses_post(wpautop($bpa_profile['about'])); ?>
							</div>
						</div>

						<aside class="bpa-card" aria-label="<?php esc_attr_e('At a glance', 'botphonic'); ?>">
							<div class="bpa-card__head">
								<?php echo get_avatar($bpa_id, 112, '', esc_attr($bpa_name), array('loading' => 'lazy')); ?>
								<div>
									<p class="bpa-card__name"><?php echo esc_html($bpa_name); ?></p>
									<?php if ($bpa_profile['role']) : ?>
									<p class="bpa-card__role"><?php echo esc_html($bpa_profile['role']); ?></p>
									<?php endif; ?>
								</div>
							</div>

							<?php if ($bpa_topics) : ?>
							<p class="bpa-card__label"><?php esc_html_e('Expertise', 'botphonic'); ?></p>
							<ul class="bpa-card__chips">
								<?php foreach (array_slice($bpa_topics, 0, 6) as $bpa_topic) : ?>
								<li><a class="bpg-chip" href="<?php echo esc_url(get_category_link($bpa_topic['term']->term_id)); ?>"><?php echo esc_html($bpa_topic['term']->name); ?></a></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>

							<?php
							$bpa_primary = null;
							foreach ($bpa_profile['links'] as $bpa_link) {
								if ('mail' !== $bpa_link['network']) {
									$bpa_primary = $bpa_link;
									break;
								}
							}
							if ($bpa_primary) :
							?>
							<a class="bpg-btn bpg-btn--ghost bpa-card__cta" href="<?php echo esc_url($bpa_primary['url']); ?>" target="_blank" rel="me noopener noreferrer">
								<?php echo botphonic_author_icon($bpa_primary['network']); // phpcs:ignore ?>
								<?php echo esc_html(sprintf(__('Follow on %s', 'botphonic'), $bpa_primary['label'])); ?>
							</a>
							<?php endif; ?>
						</aside>
					</div>
				</section>
				<?php endif; ?>


				<!-- ── Articles (main query) ──────────────────────────────── -->
				<?php if (have_posts()) : ?>
				<section class="bpa-section" id="articles" aria-labelledby="bpa-articles-title">
					<div class="bpg-sec-head">
						<h2 class="bpg-sec-head__title" id="bpa-articles-title">
							<?php
							echo esc_html(
								sprintf(__('Latest articles by %s', 'botphonic'), $bpa_name)
							);
							?>
						</h2>
						<a class="bpg-sec-head__link" href="<?php echo esc_url($bpa_blog_url); ?>">
							<?php esc_html_e('All articles', 'botphonic'); ?> <?php echo botphonic_blog_icon('arrow-right'); // phpcs:ignore ?>
						</a>
					</div>

					<div class="bpg-grid">
						<?php
						$bpa_index = 0;
						while (have_posts() && $bpa_index < BOTPHONIC_AUTHOR_CARDS) :
							the_post();
							$bpa_index++;
							get_template_part('loop-templates/content', 'bpgcard');
						endwhile;
						?>
					</div>
				</section>
				<?php endif; ?>

				<!-- ── Comparison guides / customer stories ───────────────── -->
				<?php
				if ($bpa_first) :
					foreach ($bpa_extra as $bpa_type => $bpa_meta) :
						$bpa_query = new WP_Query(
							array(
								'author' => $bpa_id,
								'post_type' => $bpa_type,
								'post_status' => 'publish',
								'posts_per_page' => BOTPHONIC_AUTHOR_CARDS,
								'ignore_sticky_posts' => true,
								'no_found_rows' => true,
							)
						);

						if (!$bpa_query->have_posts()) {
							continue;
						}

						$bpa_is_alt = ('stories' !== $bpa_meta['anchor']);
						$bpa_archive = get_post_type_archive_link($bpa_type);
				?>
				<section class="bpa-section <?php echo $bpa_is_alt ? 'bpg-alt' : 'bpg-story'; ?>" id="<?php echo esc_attr($bpa_meta['anchor']); ?>" aria-labelledby="bpa-<?php echo esc_attr($bpa_meta['anchor']); ?>-title">
					<div class="bpg-sec-head">
						<h2 class="bpg-sec-head__title" id="bpa-<?php echo esc_attr($bpa_meta['anchor']); ?>-title">
							<?php echo esc_html(sprintf('%1$s by %2$s', $bpa_meta['label'], $bpa_name)); ?>
						</h2>
						<?php if ($bpa_archive) : ?>
						<a class="bpg-sec-head__link" href="<?php echo esc_url($bpa_archive); ?>">
							<?php echo esc_html(sprintf(__('All %s', 'botphonic'), strtolower($bpa_meta['label']))); ?> <?php echo botphonic_blog_icon('arrow-right'); // phpcs:ignore ?>
						</a>
						<?php endif; ?>
					</div>

					<div class="bpg-grid">
						<?php
						while ($bpa_query->have_posts()) :
							$bpa_query->the_post();
							get_template_part('loop-templates/content', $bpa_is_alt ? 'altcard' : 'storycard');
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
				<?php
					endforeach;
				endif;
				?>

				<!-- ── Nothing published yet ──────────────────────────────── -->
				<?php if (!have_posts() && empty($bpa_extra)) : ?>
				<div class="bpg-empty">
					<h2 class="bpg-empty__title"><?php esc_html_e('No articles yet', 'botphonic'); ?></h2>
					<p><?php echo esc_html(sprintf(__('%s hasn\'t published anything yet. In the meantime, browse the latest from the Botphonic blog.', 'botphonic'), $bpa_name)); ?></p>
					<p class="bpg-empty__action">
						<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url($bpa_blog_url); ?>"><?php esc_html_e('Browse all articles', 'botphonic'); ?></a>
					</p>
				</div>
				<?php endif; ?>

			</main>
		</div>
	</div>

	<!-- ── CTA band (shared with the blog archives) ─────────────────── -->
	<section class="bpg-band" aria-labelledby="bpg-band-title">
		<div class="bpg-shell">
			<div class="bpg-band__inner">
				<div class="bpg-band__copy">
					<p class="bpg-eyebrow"><?php esc_html_e('See it on a live call', 'botphonic'); ?></p>
					<h2 class="bpg-band__title" id="bpg-band-title"><?php esc_html_e('Put what you read to work with your own AI voice agent', 'botphonic'); ?></h2>
					<p class="bpg-band__text">
						<?php esc_html_e('Spin up an agent, point it at a number, and let it answer, qualify and book — 24/7, in 50+ languages.', 'botphonic'); ?>
					</p>
				</div>
				<div class="bpg-band__row">
					<a class="bpg-btn bpg-btn--coral" href="https://app.botphonic.ai/register/" rel="noopener"><?php esc_html_e('Start free trial', 'botphonic'); ?></a>
					<a class="bpg-btn bpg-btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book a demo', 'botphonic'); ?></a>
				</div>
			</div>
		</div>
	</section>

</div><!-- #author-wrapper -->

<?php get_footer(); ?>
