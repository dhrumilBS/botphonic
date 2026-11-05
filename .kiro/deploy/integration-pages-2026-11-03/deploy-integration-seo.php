<?php
/**
 * Integration pages: page titles, Yoast SEO fields and share images.
 *
 * The page copy, design and code ship with the botphonic-child theme. This
 * script applies the database side for the five pages under /integrations/:
 * - page titles with the right brand spelling (HubSpot, WhatsApp);
 * - Yoast SEO title, meta description, focus keyphrase, Facebook and X fields;
 * - a 1200x630 share image (og/*.png) as each page's featured image, which
 *   Yoast uses for og:image and the schema's primary image.
 * Then it rebuilds the Yoast indexables so the new values show at once.
 *
 * Usage, from the WordPress root, after the theme files are deployed:
 *   wp eval-file path/to/deploy-integration-seo.php                  dry run, writes nothing
 *   wp eval-file path/to/deploy-integration-seo.php apply            apply the changes
 *   wp eval-file path/to/deploy-integration-seo.php rollback <file>  restore a backup
 * Locally without WP-CLI: php deploy-integration-seo.php [apply | rollback <file>]
 *
 * "apply" first saves every value it changes to backup-<host>-<time>.json in
 * this folder. Keep the og/ folder next to this file. Safe to run twice.
 *
 * @package Botphonic
 */

// Standalone run on the local XAMPP copy: load WordPress from the site root.
if (!defined('ABSPATH')) {
	if ('cli' !== PHP_SAPI) {
		exit;
	}
	error_reporting(E_ERROR | E_PARSE);
	$_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] ?? '192.168.0.223';
	$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
	$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';
	define('WP_USE_THEMES', false);
	require dirname(__DIR__, 3) . '/wp-load.php';
}

// Yoast reads each page's stored content through the_content, and Elementor prints that content's
// CSS when WordPress shuts down. Discard it (after WordPress flushes its buffers) to keep the console clean.
add_action('shutdown', static function () {
	ob_start(static function () {
		return '';
	});
}, 2);

$bp_args = isset($args) && is_array($args) ? array_values($args) : array_slice((array) ($argv ?? array()), 1);
$bp_mode = in_array('apply', $bp_args, true) ? 'apply' : (in_array('rollback', $bp_args, true) ? 'rollback' : 'dry-run');

$bp_pages = array(
	'hubspot' => array(
		'path' => 'integrations/hubspot-integration',
		'post_title' => 'HubSpot Integration',
		'image_alt' => 'Botphonic HubSpot integration: call new contacts and log every outcome',
		'meta' => array(
			'_yoast_wpseo_title' => 'HubSpot Integration: AI Calls for New Contacts | Botphonic',
			'_yoast_wpseo_metadesc' => 'The Botphonic HubSpot integration has an AI voice agent call new contacts, then writes the lead status, lifecycle stage and a call note back to HubSpot.',
			'_yoast_wpseo_focuskw' => 'HubSpot integration',
			'_yoast_wpseo_opengraph-title' => 'Botphonic + HubSpot: Call New Contacts Automatically',
			'_yoast_wpseo_opengraph-description' => "Connect HubSpot in a few clicks. Botphonic's AI voice agent calls new contacts and writes the lead status, lifecycle stage and a call note back to HubSpot.",
			'_yoast_wpseo_twitter-title' => 'HubSpot Integration: AI Calls With Automatic CRM Updates',
			'_yoast_wpseo_twitter-description' => 'Botphonic calls new HubSpot contacts, or contacts that reach a lifecycle stage you pick, then updates the record for you. No code needed.',
		),
	),
	'salesforce' => array(
		'path' => 'integrations/salesforce-integration',
		'post_title' => 'Salesforce Integration',
		'image_alt' => 'Botphonic Salesforce integration: AI calls that know the record',
		'meta' => array(
			'_yoast_wpseo_title' => 'Salesforce Integration for AI Voice Agents | Botphonic',
			'_yoast_wpseo_metadesc' => "The Botphonic Salesforce integration looks up each caller's record before the agent answers, updates fields mid-call and logs the call as a task.",
			'_yoast_wpseo_focuskw' => 'Salesforce integration',
			'_yoast_wpseo_opengraph-title' => 'Botphonic + Salesforce: AI Calls That Know the Record',
			'_yoast_wpseo_opengraph-description' => "Botphonic's AI voice agent can look up the Salesforce lead or contact before it answers, update records mid-call and log the outcome as a task.",
			'_yoast_wpseo_twitter-title' => 'Salesforce Integration: AI Calls With Your CRM Data',
			'_yoast_wpseo_twitter-description' => 'Greet returning callers by name, update Salesforce records during the call and log calls as tasks, all through the Salesforce REST API.',
		),
	),
	'zapier' => array(
		'path' => 'integrations/zapier-integration',
		'post_title' => 'Zapier Integration',
		'image_alt' => 'Botphonic Zapier integration: start AI phone calls from any Zap',
		'meta' => array(
			'_yoast_wpseo_title' => 'Zapier Integration: AI Phone Calls From Any Zap | Botphonic',
			'_yoast_wpseo_metadesc' => 'Turn form fills and ad leads into AI phone calls. The Botphonic Zapier integration runs on Webhooks by Zapier and passes call results to other apps.',
			'_yoast_wpseo_focuskw' => 'Zapier integration',
			'_yoast_wpseo_opengraph-title' => 'Botphonic + Zapier: Start AI Phone Calls From Any Zap',
			'_yoast_wpseo_opengraph-description' => 'Send leads from forms, ads, sheets and booking tools to Botphonic with Webhooks by Zapier. An AI voice agent calls them and results flow back to your apps.',
			'_yoast_wpseo_twitter-title' => 'Zapier Integration: Turn Any Trigger Into an AI Call',
			'_yoast_wpseo_twitter-description' => "Add a lead in any Zapier app and Botphonic's AI voice agent calls them. Then send call results to Slack, Google Sheets or your CRM with a second Zap.",
		),
	),
	'zoho' => array(
		'path' => 'integrations/zoho-integration',
		'post_title' => 'Zoho Integration',
		'image_alt' => 'Botphonic Zoho CRM integration: AI calls that keep Zoho CRM up to date',
		'meta' => array(
			'_yoast_wpseo_title' => 'Zoho Integration: AI Calls That Update Zoho CRM | Botphonic',
			'_yoast_wpseo_metadesc' => 'The Botphonic Zoho integration recognizes callers in Zoho CRM, creates or updates leads from the conversation and logs calls in the Calls module.',
			'_yoast_wpseo_focuskw' => 'Zoho integration',
			'_yoast_wpseo_opengraph-title' => 'Botphonic + Zoho CRM: AI Calls That Keep Records Current',
			'_yoast_wpseo_opengraph-description' => 'Recognize callers from Zoho CRM, create or update leads from the conversation and log calls in the Calls module. Zoho workflow rules can start calls too.',
			'_yoast_wpseo_twitter-title' => 'Zoho Integration: AI Calls Logged in Zoho CRM',
			'_yoast_wpseo_twitter-description' => "Botphonic's AI voice agent knows returning callers, upserts leads without duplicates and logs calls in Zoho CRM. A workflow rule can trigger the call.",
		),
	),
	'whatsapp' => array(
		'path' => 'integrations/whatsapp-integration',
		'post_title' => 'WhatsApp Integration',
		'image_alt' => 'Botphonic WhatsApp integration: automated messages for every new lead',
		'meta' => array(
			'_yoast_wpseo_title' => 'WhatsApp Integration: Automated Lead Follow-Up | Botphonic',
			'_yoast_wpseo_metadesc' => 'Send an automated WhatsApp message the moment a lead submits your form, then let a Botphonic workflow handle the follow-up. No numbers copied by hand.',
			'_yoast_wpseo_focuskw' => 'WhatsApp integration',
			'_yoast_wpseo_opengraph-title' => 'Botphonic + WhatsApp: Message Every New Lead Automatically',
			'_yoast_wpseo_opengraph-description' => 'When someone fills out your form, Botphonic sends them a WhatsApp message and runs your follow-up workflow, so every lead hears from you fast.',
			'_yoast_wpseo_twitter-title' => 'WhatsApp Integration: Automated Messages for New Leads',
			'_yoast_wpseo_twitter-description' => 'Botphonic messages new leads on WhatsApp as soon as they submit your form, then follows up through your workflow.',
		),
	),
);

/**
 * Rebuild the Yoast indexable of a post so stored titles, descriptions and
 * images match the post and its meta.
 */
$bp_rebuild = static function ($post_id) {
	if (!function_exists('YoastSEO')) {
		return 'Yoast not active';
	}
	// Yoast reads the post content through the_content, where Elementor prints its CSS: keep that out of the console.
	ob_start();
	try {
		$repo = YoastSEO()->classes->get(\Yoast\WP\SEO\Repositories\Indexable_Repository::class);
		$builder = YoastSEO()->classes->get(\Yoast\WP\SEO\Builders\Indexable_Builder::class);
		$indexable = $repo->find_by_id_and_type($post_id, 'post', false);
		$builder->build_for_id_and_type($post_id, 'post', $indexable ? $indexable : false);
		$result = 'indexable rebuilt';
	} catch (\Throwable $e) {
		$result = 'indexable rebuild failed: ' . $e->getMessage();
	}
	ob_end_clean();

	return $result;
};

/** Shared image attachment for a page, imported once and found again by a meta key. */
$bp_image = static function ($key, $post_id, $alt) {
	$found = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_botphonic_og', 'meta_value' => $key, 'fields' => 'ids', 'numberposts' => 1));
	if ($found) {
		return (int) $found[0];
	}

	$file = __DIR__ . '/og/botphonic-' . $key . '-integration.png';
	if (!is_readable($file)) {
		return new WP_Error('missing', 'Share image not found: ' . $file);
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam(basename($file));
	copy($file, $tmp);
	$id = media_handle_sideload(array('name' => basename($file), 'tmp_name' => $tmp), $post_id, $alt);
	if (is_wp_error($id)) {
		@unlink($tmp);
		return $id;
	}
	update_post_meta($id, '_botphonic_og', $key);
	update_post_meta($id, '_wp_attachment_image_alt', $alt);

	return (int) $id;
};

global $wpdb;
$bp_host = (string) wp_parse_url(home_url('/'), PHP_URL_HOST);
echo 'Site: ' . home_url('/') . ' | mode: ' . $bp_mode . "\n";

if ('rollback' === $bp_mode) {
	$file = (string) ($bp_args[array_search('rollback', $bp_args, true) + 1] ?? '');
	$path = is_readable($file) ? $file : __DIR__ . '/' . basename($file);
	$backup = is_readable($path) ? json_decode((string) file_get_contents($path), true) : null;
	if (!is_array($backup)) {
		echo "Backup file not found or unreadable: {$file}\n";
		return;
	}
	foreach ($backup as $key => $old) {
		$id = (int) $old['ID'];
		$wpdb->update($wpdb->posts, array('post_title' => $old['post_title'], 'post_modified' => $old['post_modified'], 'post_modified_gmt' => $old['post_modified_gmt']), array('ID' => $id));
		foreach ((array) $old['meta'] as $meta_key => $value) {
			'' === $value ? delete_post_meta($id, $meta_key) : update_post_meta($id, $meta_key, $value);
		}
		$old['thumbnail_id'] ? set_post_thumbnail($id, (int) $old['thumbnail_id']) : delete_post_thumbnail($id);
		clean_post_cache($id);
		echo "{$key}: restored, " . $bp_rebuild($id) . "\n";
	}
	return;
}

$bp_backup = array();
$bp_changes = 0;

foreach ($bp_pages as $key => $page) {
	$post = get_page_by_path($page['path']);
	if (!$post instanceof WP_Post) {
		echo "{$key}: page /{$page['path']}/ not found, skipped\n";
		continue;
	}
	$id = (int) $post->ID;
	$todo = array();

	if ($post->post_title !== $page['post_title']) {
		$todo[] = 'title "' . $post->post_title . '" -> "' . $page['post_title'] . '"';
	}
	foreach ($page['meta'] as $meta_key => $value) {
		if ((string) get_post_meta($id, $meta_key, true) !== $value) {
			$todo[] = $meta_key;
		}
	}
	$found = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_botphonic_og', 'meta_value' => $key, 'fields' => 'ids', 'numberposts' => 1));
	if (!$found || (int) get_post_thumbnail_id($id) !== (int) $found[0]) {
		$todo[] = 'featured image -> og/botphonic-' . $key . '-integration.png';
	}

	echo "{$key} (#{$id}): " . ($todo ? implode('; ', $todo) : 'already up to date') . "\n";
	if (!$todo || 'apply' !== $bp_mode) {
		continue;
	}

	$bp_backup[$key] = array(
		'ID' => $id,
		'post_title' => $post->post_title,
		'post_modified' => $post->post_modified,
		'post_modified_gmt' => $post->post_modified_gmt,
		'thumbnail_id' => (int) get_post_thumbnail_id($id),
		'meta' => array_combine(array_keys($page['meta']), array_map(static function ($meta_key) use ($id) {
			return (string) get_post_meta($id, $meta_key, true);
		}, array_keys($page['meta']))),
	);
}

if ('apply' !== $bp_mode) {
	echo "Dry run: nothing was changed. Run again with \"apply\" to write.\n";
	return;
}
if (!$bp_backup) {
	echo "Nothing to change.\n";
	return;
}

$bp_file = __DIR__ . '/backup-' . sanitize_file_name($bp_host) . '-' . gmdate('Ymd-His') . '.json';
if (false === file_put_contents($bp_file, wp_json_encode($bp_backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))) {
	echo "Could not write the backup to {$bp_file}. Nothing was changed.\n";
	return;
}
echo 'Backup: ' . $bp_file . "\n";

foreach ($bp_backup as $key => $old) {
	$id = (int) $old['ID'];
	$page = $bp_pages[$key];

	// Direct update: wp_update_post() would run the stored Elementor content through kses.
	$wpdb->update(
		$wpdb->posts,
		array('post_title' => $page['post_title'], 'post_modified' => current_time('mysql'), 'post_modified_gmt' => current_time('mysql', true)),
		array('ID' => $id)
	);
	foreach ($page['meta'] as $meta_key => $value) {
		update_post_meta($id, $meta_key, $value);
	}

	$image = $bp_image($key, $id, $page['image_alt']);
	$image_note = is_wp_error($image) ? 'image failed: ' . $image->get_error_message() : (set_post_thumbnail($id, $image) || (int) get_post_thumbnail_id($id) === $image ? 'image #' . $image : 'image not set');

	clean_post_cache($id);
	echo "{$key}: updated, {$image_note}, " . $bp_rebuild($id) . "\n";
	$bp_changes++;
}

echo "Done: {$bp_changes} page(s) updated. Clear any page cache for /integrations/.\n";
