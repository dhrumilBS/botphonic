<?php

/**
 * Botphonic child theme — "Reviewed by" for blog posts.
 *
 * Adds a sidebar box to the blog post and Alternatives editors where an
 * editor picks the person who reviewed the article (any WordPress user) and,
 * optionally, the review date. single.php and single-alternatives.php show
 * the reviewer in the hero byline only (template-parts/byline-people.php),
 * and the reviewer is added to Yoast's WebPage schema as `reviewedBy`.
 *
 * The reviewer's name, photo, job title ("user_position") and bio come from
 * their user profile, the same fields the author uses, so there is one place
 * to edit them.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

/**
 * Register the field group (needs ACF).
 */
function botphonic_blog_review_register_fields()
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key' => 'group_botphonic_blog_review',
			'title' => __('Reviewed by', 'botphonic'),
			'fields' => array(
				array(
					'key' => 'field_bpblog_reviewed_by',
					'label' => __('Reviewer', 'botphonic'),
					'name' => 'reviewed_by',
					'type' => 'user',
					'instructions' => __('The expert who checked this article. Their name, photo, job title and bio come from their user profile. Leave empty to hide the review.', 'botphonic'),
					'allow_null' => 1,
					'multiple' => 0,
					'return_format' => 'id',
				),
				array(
					'key' => 'field_bpblog_reviewed_on',
					'label' => __('Review date', 'botphonic'),
					'name' => 'reviewed_on',
					'type' => 'date_picker',
					'instructions' => __('Optional. Shown as "Reviewed on …".', 'botphonic'),
					'display_format' => 'F j, Y',
					'return_format' => 'Ymd',
					'first_day' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					),
				),
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives',
					),
				),
			),
			'position' => 'side',
			'style' => 'default',
			'menu_order' => 5,
			'active' => true,
		)
	);
}
add_action('acf/init', 'botphonic_blog_review_register_fields');

/**
 * The reviewer of a post, ready for the template.
 *
 * Returns null when no reviewer is set, the user no longer exists, or the
 * reviewer is the author (a self-review adds nothing for the reader).
 *
 * @param int|WP_Post|null $post Post. Defaults to the current post.
 * @return array{id:int,name:string,role:string,bio:string,url:string,date:string,date_iso:string}|null
 */
function botphonic_blog_reviewer($post = null)
{
	$post = get_post($post);
	if (!$post instanceof WP_Post) {
		return null;
	}

	$user_id = (int) get_post_meta($post->ID, 'reviewed_by', true);
	if (!$user_id || $user_id === (int) $post->post_author) {
		return null;
	}

	$user = get_userdata($user_id);
	if (!$user) {
		return null;
	}

	$name = $user->display_name ? $user->display_name : $user->nickname;

	$date = '';
	$date_iso = '';
	$raw_date = (string) get_post_meta($post->ID, 'reviewed_on', true);
	if (preg_match('/^\d{8}$/', $raw_date)) {
		// Read the date in the site's timezone so the shown day and the
		// schema day are the same calendar date.
		$day = DateTimeImmutable::createFromFormat('!Ymd', $raw_date, wp_timezone());
		if ($day) {
			$date = wp_date('F j, Y', $day->getTimestamp());
			$date_iso = $day->format('Y-m-d');
		}
	}

	return array(
		'id' => $user_id,
		'name' => (string) $name,
		'role' => trim((string) get_user_meta($user_id, 'user_position', true)),
		'bio' => trim((string) $user->description),
		'url' => get_author_posts_url($user_id),
		'date' => $date,
		'date_iso' => $date_iso,
	);
}

/**
 * Add the reviewer to Yoast's WebPage schema as `reviewedBy`.
 *
 * @param array $data WebPage schema piece.
 * @return array
 */
function botphonic_blog_review_schema($data)
{
	if (!is_singular(array('post', defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives'))) {
		return $data;
	}

	$reviewer = botphonic_blog_reviewer();
	if (!$reviewer) {
		return $data;
	}

	$person = array(
		'@type' => 'Person',
		'name' => $reviewer['name'],
		'url' => $reviewer['url'],
	);
	if ('' !== $reviewer['role']) {
		$person['jobTitle'] = $reviewer['role'];
	}

	$data['reviewedBy'] = $person;
	if ('' !== $reviewer['date_iso']) {
		$data['lastReviewed'] = $reviewer['date_iso'];
	}

	return $data;
}
add_filter('wpseo_schema_webpage', 'botphonic_blog_review_schema');
