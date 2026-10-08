<?php
/**
 * ====================================================================
 * BREADCRUMBS — Visual navigation only (no Microdata)
 * ====================================================================
 */

if (!function_exists('wpdev_breadcrumbs')) {
	function wpdev_breadcrumbs($args = [])
	{
		$defaults = [
			'home_label' => 'Home',
			'blog_label' => get_the_title(get_option('page_for_posts')) ?: 'Blog',
			'separator' => '', // chevrons drawn via CSS
			'wrap_before' => '<nav class="wpdev-breadcrumbs" aria-label="Breadcrumb">',
			'wrap_after' => '</nav>',
			'item_before' => '<span class="crumb">',
			'item_after' => '</span>',
			'link_before' => '<a class="crumb-link" href="%s"><span>',
			'link_after' => '</span></a>',
			'current_before' => '<span class="crumb current" aria-current="page"><span class="current-label">',
			'current_after' => '</span></span>',
			'show_on_front' => true,
			// Opt-out flags. All default to the historical behaviour, so the
			// [breadcrumbs] shortcode and any other caller are unaffected; the
			// blog templates pass the ones they need.
			'show_home' => true,   // leading "Home" crumb
			'show_current' => true,   // trailing non-linked crumb (post/page title, term name…)
			'show_paged' => true,   // trailing "Page N" crumb
		];

		$args = wp_parse_args($args, $defaults);
		$out = '';

		/**
		 * Trailing, non-linked crumb. Returns an empty string when the caller
		 * asked for the current item to be omitted.
		 *
		 * @param string $label Already-escaped label.
		 * @return string
		 */
		$current_crumb = static function ($label) use ($args) {
			if (!$args['show_current']) {
				return '';
			}

			return $args['current_before'] . $label . $args['current_after'];
		};

		$home_url = home_url('/');
		$out .= $args['wrap_before'];

		// ─── Home crumb ──────────────────────────────────────────────────
		if ($args['show_home']) {
			$out .= $args['item_before']
				. sprintf($args['link_before'], esc_url($home_url))
				. esc_html($args['home_label'])
				. $args['link_after']
				. $args['item_after'];
		}

		// ─── Front page: just show Home ──────────────────────────────────
		if (is_front_page()) {
			if (!$args['show_on_front']) {
				echo $out . $args['wrap_after'];
				return;
			}
			echo $out . $args['wrap_after'];
			return;
		}

		// ─── Blog (posts page) ───────────────────────────────────────────
		if (is_home()) {
			$out .= $current_crumb(esc_html($args['blog_label']));
			echo $out . $args['wrap_after'];
			return;
		}

		/*
		 * ─── Blog crumb on post archives ─────────────────────────────────
		 * Singular posts already link back to the posts page below. Doing the
		 * same for category / tag / date archives is what lets the trail read
		 * "Blog → Category" once the Home crumb is dropped.
		 */
		if (is_category() || is_tag() || is_date()) {
			$posts_page_id = (int) get_option('page_for_posts');

			if ($posts_page_id) {
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url(get_permalink($posts_page_id)))
					. esc_html($args['blog_label'])
					. $args['link_after']
					. $args['item_after'];
			}
		}

		// ─── Archives ────────────────────────────────────────────────────
		if (is_category()) {
			$cat = get_queried_object();
			$parents = array_reverse(get_ancestors($cat->term_id, 'category'));
			foreach ($parents as $parent_id) {
				$term = get_term($parent_id, 'category');
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url(get_term_link($term)))
					. esc_html($term->name)
					. $args['link_after']
					. $args['item_after'];
			}
			$out .= $current_crumb(esc_html(single_cat_title('', false)));

		} elseif (is_tag()) {
			$out .= $current_crumb(esc_html(single_tag_title('', false)));

		} elseif (is_tax()) {
			$term = get_queried_object();
			$post_type = get_post_type_object(get_post_type());

			if ($post_type && !is_post_type_archive() && !is_singular()) {
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url(get_post_type_archive_link($post_type->name)))
					. esc_html($post_type->labels->name)
					. $args['link_after']
					. $args['item_after'];
			}

			if ($term && $term->parent) {
				$parents = array_reverse(get_ancestors($term->term_id, $term->taxonomy));
				foreach ($parents as $parent_id) {
					$pterm = get_term($parent_id, $term->taxonomy);
					$out .= $args['item_before']
						. sprintf($args['link_before'], esc_url(get_term_link($pterm)))
						. esc_html($pterm->name)
						. $args['link_after']
						. $args['item_after'];
				}
			}
			$out .= $current_crumb(esc_html(single_term_title('', false)));

		} elseif (is_post_type_archive()) {
			$post_type = get_queried_object();
			$out .= $current_crumb(esc_html($post_type->labels->name));

		} elseif (is_author()) {
			$out .= $current_crumb(esc_html(get_the_author()));

		} elseif (is_day() || is_month() || is_year()) {
			if (is_year()) {
				$out .= $current_crumb(get_the_date('Y'));
			} elseif (is_month()) {
				$year_link = get_year_link(get_the_date('Y'));
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url($year_link))
					. get_the_date('Y')
					. $args['link_after']
					. $args['item_after'];
				$out .= $current_crumb(get_the_date('F'));
			} else { // day
				$year_link = get_year_link(get_the_date('Y'));
				$month_link = get_month_link(get_the_date('Y'), get_the_date('m'));
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url($year_link))
					. get_the_date('Y')
					. $args['link_after']
					. $args['item_after'];
				$out .= $args['item_before']
					. sprintf($args['link_before'], esc_url($month_link))
					. get_the_date('F')
					. $args['link_after']
					. $args['item_after'];
				$out .= $current_crumb(get_the_date('j'));
			}

		} elseif (is_search()) {
			$out .= $current_crumb(sprintf('Search results for "%s"', esc_html(get_search_query())));

		} elseif (is_404()) {
			$out .= $current_crumb('Not Found');
		}

		// ─── Singular (posts, pages, CPTs) ───────────────────────────────
		if (is_singular() && !is_front_page()) {
			global $post;

			if ('post' === get_post_type($post)) {
				// Link to blog page
				$posts_page_id = (int) get_option('page_for_posts');
				if ($posts_page_id) {
					$out .= $args['item_before']
						. sprintf($args['link_before'], esc_url(get_permalink($posts_page_id)))
						. esc_html($args['blog_label'])
						. $args['link_after']
						. $args['item_after'];
				}

				// Determine primary category (Yoast-aware)
				$primary = null;

				// Try Yoast's primary category first
				if (class_exists('WPSEO_Primary_Term')) {
					$wpseo_primary = new WPSEO_Primary_Term('category', $post->ID);
					$primary_term_id = $wpseo_primary->get_primary_term();
					if ($primary_term_id) {
						$primary = get_term($primary_term_id, 'category');
						if (is_wp_error($primary)) {
							$primary = null;
						}
					}
				}

				if (!$primary) {
					$cats = get_the_category($post->ID);
					$primary = $cats ? $cats[0] : null;
				}

				if ($primary) {
					$parents = array_reverse(get_ancestors($primary->term_id, 'category'));
					foreach ($parents as $parent_id) {
						$term = get_term($parent_id, 'category');
						$out .= $args['item_before']
							. sprintf($args['link_before'], esc_url(get_term_link($term)))
							. esc_html($term->name)
							. $args['link_after']
							. $args['item_after'];
					}
					$out .= $args['item_before']
						. sprintf($args['link_before'], esc_url(get_term_link($primary)))
						. esc_html($primary->name)
						. $args['link_after']
						. $args['item_after'];
				}

			} else {
				$post_type = get_post_type_object(get_post_type($post));
				if ($post_type && !empty($post_type->has_archive)) {
					$out .= $args['item_before']
						. sprintf($args['link_before'], esc_url(get_post_type_archive_link($post_type->name)))
						. esc_html($post_type->labels->name)
						. $args['link_after']
						. $args['item_after'];
				}

				if ($post_type && is_post_type_hierarchical($post_type->name) && $post->post_parent) {
					$parents = array_reverse(get_post_ancestors($post));
					foreach ($parents as $parent_id) {
						$out .= $args['item_before']
							. sprintf($args['link_before'], esc_url(get_permalink($parent_id)))
							. esc_html(get_the_title($parent_id))
							. $args['link_after']
							. $args['item_after'];
					}
				}
			}

			$out .= $current_crumb(esc_html(get_the_title($post)));
		}

		if ($args['show_paged'] && get_query_var('paged')) {
			$out .= $args['current_before'] . sprintf('Page %d', (int) get_query_var('paged')) . $args['current_after'];
		}

		// Suppressing crumbs can leave an empty trail — print nothing then.
		if (strpos($out, '<span class="crumb') === false) {
			return;
		}

		$out .= $args['wrap_after'];
		echo $out;
	}
}

add_shortcode('breadcrumbs', function ($atts) {
	ob_start();
	wpdev_breadcrumbs();
	return ob_get_clean();
});