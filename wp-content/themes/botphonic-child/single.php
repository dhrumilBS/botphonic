<?php
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');

$author_id = $post->post_author;
$display_name = get_the_author_meta('display_name', $post->post_author);
if (empty($display_name))
	$display_name = get_the_author_meta('nickname', $post->post_author);

$user_description = get_the_author_meta('user_description', $post->post_author);
$user_website = get_the_author_meta('url', $post->post_author);
$user_posts = get_author_posts_url(get_the_author_meta('ID', $post->post_author));

$content = get_post_field('post_content', $post->ID);
$word_count = str_word_count(strip_tags($content));
$readingtime = ceil($word_count / 200) . " Min Read";
?>

<style>
	/* Hero Section */
	.hero-section { background: linear-gradient(135deg, #4facfe, #7366ff); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); margin-bottom: 24px; padding: 30px 0; }
	.hero-section .blog-category { margin-bottom: 16px; }
	.hero-section .blog-category a { background: #fff; color: #0073e6; padding: 6px 18px; border-radius: 20px; font-size: 14px; display: inline-block; box-shadow: 0px 2px 10px; }
	.hero-section .blog-category a:hover { background: #fff; color: #3185d9; box-shadow: 4px 10px 20px #0073e650, 0px -4px 10px #d1e3f550; }
	.hero-section .blog-title { font-size: 48px; font-weight: 800; margin-bottom: 25px; line-height: 1.3; max-width: 900px; }
	.author-box { display: flex; align-items: center; gap: 16px; }
	.author-box .author-meta { font-size: 14px; opacity: 0.9; }
	.author-box .author-avatar img { height: 90px; width: 90px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); }
	.author-box .author-meta .dot { margin: 0 6px; }
	@media screen and (max-width: 768px) {
		.hero-section .blog-title { font-size: 32px; }
		.author-box .author-avatar img { height: 70px; width: 70px; }
	}
</style>

<div class="main-wrapper" id="single-wrapper">
	<div class="hero-section dark-bg">
		<div class="container">
			<div class="content-inner">
				<div class="single-hero-title">
					<?php if (!empty(get_the_category())) { ?>
						<div class="blog-category">
							<a class="term-id-<?= get_the_category()[0]->term_id; ?>" href="<?= get_category_link(get_the_category()[0]->term_id); ?>" title="<?= get_the_category()[0]->cat_name; ?>">
								<span><?= get_the_category()[0]->cat_name; ?></span>
							</a>
						</div>
					<?php } ?>
					<h1 class="blog-title"><?= get_the_title(); ?></h1>
				</div>
				<div class="author-box">
					<div class="author-meta">
						<a target="_blank" class="author-avatar" href="<?= $user_posts; ?>"> <?= get_avatar(get_the_author_meta('ID')); ?> </a>
					</div>
					<div class="author-meta">
						<div class="top">
							<span class="author-by">By </span> <a target="_blank" class="author-name" href="<?= $user_posts; ?>"> <?= $display_name; ?> </a>
						</div>
						<div class="bottom">
							<span class="meta-item date"> <?= date('F j, Y ', strtotime(get_the_date())); ?> </span>
							<span class="dot">•</span>
							<span class="meta-item reading-time"> <?= $readingtime; ?> </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">
		<div class="row">
			<?php get_template_part('global-templates/left-sidebar-check'); ?>
			<main class="site-main" id="main">
				<?php
				while (have_posts()) {
					the_post();
					if (is_single() && has_category(15)) {
						wp_enqueue_style('alternative', get_template_directory_uri() . '/css/alternative.css', array(), null);
						get_template_part('loop-templates/content', 'single-alternative');
					} else
						get_template_part('loop-templates/content', 'single');
				}
				?>
			</main>
			<?php get_template_part('global-templates/right-sidebar-check'); ?>
		</div>
	</div>
</div>
<?php
get_footer();
