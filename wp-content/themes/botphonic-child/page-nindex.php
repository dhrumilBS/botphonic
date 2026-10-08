<?php get_header(); ?>

<style>
	body { background: linear-gradient(135deg, #f8fafc, #eef2f7); }
	.page-header { text-align: center; margin-bottom: 40px; }
	.search-box { padding: 20px 10px; position: sticky; top: 70px; z-index: 15; width: 100%; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); margin-bottom: 40px; }
	.search-box input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; outline: none; max-width: 400px; margin: 0 auto; display: block; }
	.search-box input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
	.pages-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; }
	.glass-card { border-radius: 18px; background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); transition: all 0.35s ease; display: flex; flex-direction: column; overflow: hidden; }
	.glass-card:hover { transform: scale(1.02); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1); }
	.glass-card-img img { width: 100%; height: 160px; object-fit: cover; }
	.glass-body { padding: 18px; }
	.badge-index { background: #dcfce7; color: #16a34a; }
	.badge-noindex { background: #fee2e2; color: #dc2626; }
	.meta-info { font-size: 13px; color: #64748b; display: flex; flex-direction: column; gap: 4px; margin-bottom: 10px; }
	.meta-info code { padding: 2px 6px; background: #fcfcfc; border: 1px solid #c5d0dd; border-radius: 8px; font-family: var(--mono-font); }
	.card-excerpt { font-size: 14px; color: #64748b; }
	.no-results { text-align: center; color: #64748b; }
</style>

<?php
$args = array(
	'post_type'      => 'page',
	'posts_per_page' => -1,
);

$query = new WP_Query($args);
$total = $query->found_posts;
?>

<div class="py-5">
	<div class="container">
		<div class="page-header">
			<h1>All Pages</h1>
			<p><?php echo esc_html($total); ?> pages available</p>
		</div>
	</div>

	<div class="search-box">
		<div class="container">
			<input type="text" id="searchInput" placeholder="Search pages...">
		</div>
	</div>

	<div class="container">

		<?php if ($query->have_posts()) : ?>
			<div class="pages-grid">
				<?php while ($query->have_posts()) : $query->the_post();
					$post_id = get_the_ID();
					$noindex = (get_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', true) === '1');
					$index_status = $noindex ? 'Noindex' : 'Index';
					$slug = get_post_field('post_name', $post_id);
					$date = get_the_date('d M Y');
					$title = get_the_title();
				?>

					<div class="page-card glass-card" data-title="<?php echo esc_attr(strtolower($title)); ?>">
						<div class="glass-card-img">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['loading' => 'lazy']); ?>
							<?php else : ?>
								<img src="https://botphonic.ai/wp-content/uploads/2025/12/Botphonic-feature-placeholder.webp" loading="lazy" alt="Botphonic Page Thumbnail">
							<?php endif; ?>
						</div>
						<div class="glass-body">
							<span class="badge <?php echo $noindex ? 'badge-noindex' : 'badge-index'; ?>">
								<?php echo esc_html($index_status); ?>
							</span>
							<h4><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($title); ?></a></h4>
							<div class="meta-info">
								<code><?php echo esc_html($slug); ?></code>
								<span><?php echo esc_html($date); ?></span>
							</div>
							<div class="card-excerpt">
								<?php echo esc_html(wp_trim_words(get_the_excerpt(), 12)); ?>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="no-results">No pages found.</p>
		<?php endif; ?>
	</div>
</div>

<script>
	const searchInput = document.getElementById('searchInput');
	const cards = document.querySelectorAll('.page-card');
	searchInput.addEventListener('keyup', function() {
		const value = this.value.toLowerCase();
		cards.forEach(card => {
			const title = card.dataset.title || '';
			card.style.display = title.includes(value) ? "flex" : "none";
		});
	});
</script>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>