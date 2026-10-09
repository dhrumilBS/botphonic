<?php

/**
 * The template for displaying the author pages
 *
 * @package Understrap
 * Child theme: botphonic-child
 */

defined('ABSPATH') || exit;

get_header();

$container   = get_theme_mod('understrap_container_type');
$author      = get_queried_object();
$author_id   = $author->ID;
$author_position = get_user_meta($author_id, 'user_position', true);
$author_bio       = get_user_meta($author_id, 'user_description', true);
$linkedin = get_the_author_meta('linkedin', $author_id);
$youtube  = get_the_author_meta('youtube', $author_id);
$twitter  = get_the_author_meta('twitter', $author_id);
$email    = get_the_author_meta('user_email', $author_id);
$post_count = count_user_posts($author_id, 'post', true);
$first_post = get_posts([
	'author'         => $author_id,
	'posts_per_page' => 1,
	'orderby'        => 'date',
	'order'          => 'ASC',
	'post_status'    => 'publish',
	'fields'         => 'ids',
]);
$since_year = ! empty($first_post) ? get_the_date('Y', $first_post[0]) : gmdate('Y');
$author_post_ids = get_posts([
	'author'         => $author_id,
	'posts_per_page' => 100,
	'post_status'    => 'publish',
	'fields'         => 'ids',
]);
$author_categories = ! empty($author_post_ids)
	? get_categories(['hide_empty' => true, 'object_ids' => $author_post_ids])
	: [];

$recent_posts_query = new WP_Query([
	'author'         => $author_id,
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
]);
?>
<style>
	.author-template .container { max-width: 1040px !important; }
	/* ── Hero ───────────────────────────────────────────────── */
	.author-hero { padding: 50px 0; }
	.author-hero .author-section { display: flex; flex-direction: column; align-items: center; gap: 24px; }
	.author-hero .author-section .author { display: flex; max-width: 90%; padding: 12px; background: #fff; border-radius: 8px; box-shadow: rgba(17, 12, 46, 0.15) 0px 48px 100px 0px; flex-wrap: wrap; gap: 16px; width: 100%; }
	.author-hero .author-section .author .author-profile { flex-shrink: 0; }
	.author-hero .author-section .author .author-profile img { border-radius: 10px; width: 150px; height: auto; }
	.author-hero .author-section .author .about-author { display: flex; flex-direction: column; }
	.author-hero .author-section .author .about-author .author-name { font-size: 28px; margin: 0; }
	.author-hero .author-section .author .about-author .author-position { color: var(--p-color); font-size: 16px; margin: 4px 0 0; }
	.author-hero .author-section .author .about-author .author-follow { flex-grow: 1; align-content: flex-end; }
	.author-hero .author-follow li { color: var(--p-color); }
	.author-hero .author-follow .social-media-list { display: flex; list-style: none; padding: 0; margin: 12px 0 0; gap: 16px; }
	.author-hero .author-follow .social-media-list li a { display: flex; justify-content: center; align-items: center; padding: 12px; border-radius: 8px; background-color: rgb(var(--primary-rgb), 0.12); transition: background-color 0.2s ease, color 0.2s ease; }
	.author-hero .author-follow .social-media-list li a:hover { color: #fff; background: var(--secondary); }
	.author-hero .author-follow .social-media-list li a svg { width: 24px; height: 24px; }
	/* ── Stats bar ──────────────────────────────────────────── */
	.author-stats-bar { width: 100%; max-width: 90%; }
	.author-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: rgba(var(--primary-rgb), 0.12); border-radius: 8px; overflow: hidden; }
	.author-stat { background: #fff; padding: 16px 20px; text-align: center; }
	.author-stat__value { display: block; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
	.author-stat__label { display: block; font-size: 11px; color: var(--p-color); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500; }
	/* ── Bio content ────────────────────────────────────────── */
	.author-content .box-container { padding-bottom: 30px; }
	.author-content .title { margin-bottom: 0.45em; }
	.author-content ul { list-style-type: none; padding-left: 0; }
	.author-content li { color: var(--p-color); position: relative; margin-bottom: 8px; padding-left: 20px; }
	.author-content li::before { content: ''; position: absolute; left: 0; top: 6px; height: 100%; width: 12px; background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12ZM4.73731 12C4.73731 16.0111 7.98893 19.2627 12 19.2627C16.0111 19.2627 19.2627 16.0111 19.2627 12C19.2627 7.98893 16.0111 4.73731 12 4.73731C7.98893 4.73731 4.73731 7.98893 4.73731 12Z' fill='%236d28d9'/%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; }
	.author-content .theme-btn { margin-top: 20px; }
	.author-bio-empty { color: var(--p-color); font-style: italic; padding: 24px 0; }
	/* ── Recent articles ────────────────────────────────────── */
	.author-recent-posts { padding: 30px 0 60px; background: #fafafa; }
	.author-recent-title { font-size: 24px; margin-bottom: 24px; }
	.author-recent-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
	.author-recent-card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: rgba(17, 12, 46, 0.08) 0px 12px 40px 0px; transition: transform 0.2s ease, box-shadow 0.2s ease; display: flex; flex-direction: column; }
	.author-recent-card:hover { transform: translateY(-4px); box-shadow: rgba(17, 12, 46, 0.14) 0px 20px 50px 0px; }
	.author-recent-thumb-link { display: block; overflow: hidden; }
	.author-recent-thumb { width: 100%; transition: transform 0.4s ease; }
	.author-recent-card:hover .author-recent-thumb { transform: scale(1.04); }
	.author-recent-body { padding: 18px 20px 20px; display: flex; flex-direction: column; flex: 1; }
	.author-recent-cat { display: inline-block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text); background: rgba(var(--primary-rgb), 0.1); border-radius: 4px; padding: 4px 8px; margin-bottom: 8px; align-self: flex-start; }
	.author-recent-card-title { font-size: 16px; margin: 0 0 8px; line-height: 1.4; }
	.author-recent-card-title a { color: var(--dark); text-decoration: none; font-weight: 600; }
	/* Was var(--theme-color), which nothing declares — the whole declaration was
	   therefore invalid at computed-value time and collapsed to `unset`, so the
	   card title had no hover colour at all. --accent is the site's link colour
	   and matches how the blog cards signal the same thing. */
	.author-recent-card-title a:hover { color: var(--accent); }
	.author-recent-excerpt { font-size: 13px; color: var(--p-color); line-height: 1.6; flex: 1; margin: 0 0 14px; }
	.author-recent-date { font-size: 12px; color: var(--p-color); font-weight: 500; border-top: 1px solid rgba(0, 0, 0, 0.06); padding-top: 12px; margin-top: auto; }
	/* ── Responsive ─────────────────────────────────────────── */
	@media (max-width: 768px) {
		.author-recent-grid { grid-template-columns: repeat(2, 1fr); }
		.author-stats-grid { grid-template-columns: repeat(3, 1fr); }
	}

	@media (max-width: 576px) {
		.author-hero .author-section .author { flex-direction: column; align-items: center; text-align: center; }
		.author-follow .social-media-list { justify-content: center; }
		.author-stats-grid { grid-template-columns: 1fr; }
		.author-recent-grid { grid-template-columns: 1fr; }
	}
</style>
<div class="wrapper" id="author-wrapper">
	<div class="author-template">
		<section class="author-hero">
			<div class="container">
				<div class="author-section">
					<div class="author">
						<div class="author-profile">
							<?= get_avatar($author_id, 250, '', esc_attr($author->display_name)); ?>
						</div>

						<div class="about-author">
							<h1 class="author-name"><?= esc_html($author->display_name); ?></h1>
							<?php if (! empty($author_position)) : ?>
							<p class="author-position"><?= esc_html($author_position); ?></p>
							<?php endif; ?>

							<div class="author-follow">
								<ul class="social-media-list">
									<?php if (! empty($email)) : ?>
									<li>
										<a href="mailto:<?= esc_attr($email); ?>" aria-label="Email <?= esc_attr($author->display_name); ?>" rel="nofollow noopener">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentcolor" viewBox="0 0 512 512">
												<path d="m62.843 98.364 138.32 138.38c30.168 30.11 79.482 30.136 109.675 0l138.32-138.38a3.144 3.144 0 0 0-.426-4.814c-14.108-9.839-31.273-15.672-49.763-15.672H113.033c-18.491 0-35.656 5.834-49.764 15.672a3.144 3.144 0 0 0-.426 4.814zm-36.964 66.667a86.483 86.483 0 0 1 9.955-40.353 3.144 3.144 0 0 1 5.019-.762l136.569 136.569c43.247 43.31 113.885 43.335 157.158 0l136.569-136.569a3.144 3.144 0 0 1 5.019.762 86.498 86.498 0 0 1 9.955 40.353v181.937c0 48.093-39.121 87.154-87.154 87.154H113.033c-48.032 0-87.154-39.061-87.154-87.154z"></path>
											</svg>
										</a>
									</li>
									<?php endif; ?>

									<?php if ($linkedin) : ?>
									<li>
										<a href="<?= esc_url($linkedin); ?>" target="_blank" aria-label="LinkedIn profile of <?= esc_attr($author->display_name); ?>" rel="nofollow noopener noreferrer">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentcolor" viewBox="0 0 448 512">
												<path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1a53.79 53.79 0 1 1 53.79-53.79 53.79 53.79 0 0 1-53.79 53.79zM447.9 448h-92.4V302.4c0-34.7-.7-79.3-48.3-79.3-48.3 0-55.7 37.7-55.7 76.7V448h-92.4V148.9h88.7v40.8h1.3c12.4-23.5 42.7-48.3 87.8-48.3 93.9 0 111.2 61.8 111.2 142.3V448z" />
											</svg>
										</a>
									</li>
									<?php endif; ?>

									<?php if ($youtube) : ?>
									<li>
										<a href="<?= esc_url($youtube); ?>" target="_blank" aria-label="YouTube channel of <?= esc_attr($author->display_name); ?>" rel="nofollow noopener noreferrer">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentcolor" viewBox="0 0 50 50">
												<path d="M 44.898438 14.5 C 44.5 12.300781 42.601563 10.699219 40.398438 10.199219 C 37.101563 9.5 31 9 24.398438 9 C 17.800781 9 11.601563 9.5 8.300781 10.199219 C 6.101563 10.699219 4.199219 12.199219 3.800781 14.5 C 3.398438 17 3 20.5 3 25 C 3 29.5 3.398438 33 3.898438 35.5 C 4.300781 37.699219 6.199219 39.300781 8.398438 39.800781 C 11.898438 40.5 17.898438 41 24.5 41 C 31.101563 41 37.101563 40.5 40.601563 39.800781 C 42.800781 39.300781 44.699219 37.800781 45.101563 35.5 C 45.5 33 46 29.398438 46.101563 25 C 45.898438 20.5 45.398438 17 44.898438 14.5 Z M 19 32 L 19 18 L 31.199219 25 Z" />
											</svg>
										</a>
									</li>
									<?php endif; ?>

									<?php if ($twitter) : ?>
									<li>
										<a href="<?= esc_url($twitter); ?>" target="_blank" aria-label="Twitter/X profile of <?= esc_attr($author->display_name); ?>" rel="nofollow noopener noreferrer">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentcolor" viewBox="0 0 24 24">
												<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
											</svg>
										</a>
									</li>
									<?php endif; ?>

								</ul>
							</div>
						</div>
					</div>

					<?php if ($post_count > 0) : ?>
					<div class="author-stats-bar">
						<div class="author-stats-grid">
							<div class="author-stat">
								<span class="author-stat__value"><?= absint($post_count); ?>+</span>
								<span class="author-stat__label">Articles Published</span>
							</div>
							<div class="author-stat">
								<span class="author-stat__value"><?= esc_html($since_year); ?></span>
								<span class="author-stat__label">Writing Since</span>
							</div>
							<div class="author-stat">
								<span class="author-stat__value"><?= count($author_categories) ? absint(count($author_categories)) : '—'; ?></span>
								<span class="author-stat__label">Topics Covered</span>
							</div>
						</div>
					</div>
					<?php endif; ?>

				</div>
			</div>
		</section>

		<section class="author-content">
			<div class="container" itemscope itemtype="https://schema.org/Person">
				<meta itemprop="name" content="<?= esc_attr($author->display_name); ?>">
				<?php if (! empty($author_position)) : ?>
				<meta itemprop="jobTitle" content="<?= esc_attr($author_position); ?>">
				<?php endif; ?>
				<?php if ($linkedin) : ?>
				<link itemprop="sameAs" href="<?= esc_url($linkedin); ?>">
				<?php endif; ?>

				<div itemprop="description">
					<?php if (! empty($author_bio)) : ?>
					<?= wp_kses_post(wpautop($author_bio)); ?>
					<?php else : ?>
					<p class="author-bio-empty"><?= esc_html($author->display_name); ?> is a contributing author at Botphonic. Check back soon for their full bio. </p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ($recent_posts_query->have_posts()) : ?>
		<section class="author-recent-posts">
			<div class="container">
				<h2 class="author-recent-title">Recent Articles by <?= esc_html($author->display_name); ?></h2>
				<div class="author-recent-grid">
					<?php while ($recent_posts_query->have_posts()) : $recent_posts_query->the_post(); ?>
					<article class="author-recent-card">

						<?php if (has_post_thumbnail()) : ?>
						<a href="<?php the_permalink(); ?>" class="author-recent-thumb-link" tabindex="-1" aria-hidden="true">
							<?php the_post_thumbnail('medium', ['class' => 'author-recent-thumb', 'loading' => 'lazy']); ?>
						</a>
						<?php endif; ?>

						<div class="author-recent-body">
							<?php
							$cats = get_the_category();
							if ($cats) : ?>
							<a href="<?= esc_url(get_category_link($cats[0]->term_id)); ?>" class="author-recent-cat"><?= esc_html($cats[0]->name); ?></a>
							<?php endif; ?>

							<h3 class="author-recent-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="author-recent-excerpt"><?= wp_trim_words(get_the_excerpt(), 12, '…'); ?></p>
							<time class="author-recent-date" datetime="<?= esc_attr(get_the_date('c')); ?>"><?= esc_html(get_the_date('M j, Y')); ?></time>
						</div>
					</article>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
		<?php endif; ?>

	</div>
</div><!-- #author-wrapper -->

<?php get_footer(); ?>