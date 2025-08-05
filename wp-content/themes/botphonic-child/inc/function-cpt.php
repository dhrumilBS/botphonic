<?php
function wpdocs_codex_customer_story_init()
{
	$labels = array(
		'name'                  => _x('Customer Stories', 'Post type general name', 'textdomain'),
		'singular_name'         => _x('Customer Stories', 'Post type singular name', 'textdomain'),
		'menu_name'             => _x('Customer Stories', 'Admin Menu text', 'textdomain'),
		'all_items'             => __('All Customer Stories', 'textdomain'),
		'search_items'          => __('Search Customer Stories', 'textdomain'),
		'parent_item_colon'     => __('Parent Customer Stories:', 'textdomain'),
		'not_found'             => __('No customer stories found.', 'textdomain'),
		'not_found_in_trash'    => __('No customer stories found in Trash.', 'textdomain'),
		'featured_image'        => _x('Customer Stories Cover Image', 'Overrides the “Featured Image” phrase', 'textdomain'),
		'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase', 'textdomain'),
		'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase', 'textdomain'),
		'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase', 'textdomain'),
		'archives'              => _x('Customer Stories Archives', 'Post type archive label', 'textdomain'),
		'insert_into_item'      => _x('Insert into customer story', 'Used when inserting media', 'textdomain'),
		'uploaded_to_this_item' => _x('Uploaded to this customer story', 'Media attachment phrase', 'textdomain'),
		'filter_items_list'     => _x('Filter customer stories list', 'Filter text', 'textdomain'),
		'items_list_navigation' => _x('Customer stories list navigation', 'Pagination label', 'textdomain'),
		'items_list'            => _x('Customer stories list', 'Items list label', 'textdomain'),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'description' 			=> 'Don’t believe what we say, check out our results and gauge their transformational journey.',
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
	);

	register_post_type(CUSTOME_STORY_SLUG , $args);
	//	flush_rewrite_rules(); // Only keep this line temporarily

}
add_action('init', 'wpdocs_codex_customer_story_init');