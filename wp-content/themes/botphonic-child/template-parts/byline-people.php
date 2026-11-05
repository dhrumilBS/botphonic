<?php

/**
 * Hero byline people: the author and, when one is set, the reviewer.
 *
 * Shared by single.php (blog) and single-alternatives.php. Styles live in
 * assets/css/blog.css (.bpg-byline-people, .bpg-author-inline), which both
 * views load. Each name links to that person's profile page.
 *
 * Accepted $args:
 *   'author_id'   int         Author user ID.
 *   'author_name' string      Author display name.
 *   'author_role' string      Author job title (user_position). Optional.
 *   'author_url'  string      Author archive URL.
 *   'reviewer'    array|null  botphonic_blog_reviewer() result.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bl = wp_parse_args(
	isset($args) && is_array($args) ? $args : array(),
	array(
		'author_id' => 0,
		'author_name' => '',
		'author_role' => '',
		'author_url' => '',
		'reviewer' => null,
	)
);
$bl_reviewer = is_array($bl['reviewer']) ? $bl['reviewer'] : null;
?>
<div class="bpg-byline-people<?php echo $bl_reviewer ? ' bpg-byline-people--reviewed' : ''; ?>">
	<?php if ('' !== $bl['author_name']): ?>
		<div class="bpg-author-inline">
			<?php echo get_avatar((int) $bl['author_id'], 88, '', esc_attr($bl['author_name']), array('loading' => 'lazy')); ?>
			<span class="bpg-author-inline__text">
				<?php if ($bl_reviewer): ?>
					<span class="bpg-author-inline__label"><?php esc_html_e('Written by', 'botphonic'); ?></span>
				<?php endif; ?>
				<a class="bpg-author-inline__name" href="<?php echo esc_url($bl['author_url']); ?>" rel="author">
					<?php echo esc_html($bl['author_name']); ?>
				</a>
				<?php if ('' !== (string) $bl['author_role']): ?>
					<span class="bpg-author-inline__role"><?php echo esc_html($bl['author_role']); ?></span>
				<?php endif; ?>
			</span>
		</div>
	<?php endif; ?>

	<?php if ($bl_reviewer): ?>
		<div class="bpg-author-inline bpg-author-inline--reviewer">
			<span class="bpg-author-inline__avatar">
				<?php echo get_avatar($bl_reviewer['id'], 88, '', esc_attr($bl_reviewer['name']), array('loading' => 'lazy')); ?>
				<span class="bpg-author-inline__badge" aria-hidden="true"><?php echo botphonic_blog_icon('shield-check'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed SVG. ?></span>
			</span>
			<span class="bpg-author-inline__text">
				<span class="bpg-author-inline__label"><?php esc_html_e('Reviewed by', 'botphonic'); ?></span>
				<a class="bpg-author-inline__name" href="<?php echo esc_url($bl_reviewer['url']); ?>"><?php echo esc_html($bl_reviewer['name']); ?></a>
				<?php if ('' !== $bl_reviewer['role']): ?>
					<span class="bpg-author-inline__role"><?php echo esc_html($bl_reviewer['role']); ?></span>
				<?php endif; ?>
			</span>
		</div>
	<?php endif; ?>
</div>
