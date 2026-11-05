<?php
/**
 * Update the Yoast SEO fields of the Integrations hub and the five integration pages on the LIVE site.
 *
 * - Values for the five pages come from the local copy, where they were applied and tested.
 * - Touches only these Yoast fields: focus keyphrase, SEO title, meta description,
 *   Facebook (Open Graph) title and description, X title and description. Each is
 *   stored in wp_postmeta and mirrored in wp_yoast_indexable, which Yoast reads on the
 *   front end, so both are updated.
 * - Default run is read-only and prints what would change.
 *   --apply           saves a backup, then writes everything in one transaction.
 *   --rollback=<file> restores a backup.
 * Live database credentials are read from the salvadore tool's .env and never printed.
 */
error_reporting(E_ERROR | E_PARSE);
$apply = in_array('--apply', $argv, true);
$rollback = '';
foreach ($argv as $arg) {
	if (0 === strpos($arg, '--rollback=')) {
		$rollback = substr($arg, 11);
	}
}
$backup_dir = 'd:/xampp/htdocs/botphonic/.kiro/backups/live-yoast-2026-11-03';

$keys = array(
	'_yoast_wpseo_focuskw' => 'primary_focus_keyword',
	'_yoast_wpseo_title' => 'title',
	'_yoast_wpseo_metadesc' => 'description',
	'_yoast_wpseo_opengraph-title' => 'open_graph_title',
	'_yoast_wpseo_opengraph-description' => 'open_graph_description',
	'_yoast_wpseo_twitter-title' => 'twitter_title',
	'_yoast_wpseo_twitter-description' => 'twitter_description',
);
$slugs = array('integrations', 'hubspot-integration', 'salesforce-integration', 'zapier-integration', 'zoho-integration', 'whatsapp-integration');

// New values for the hub, written for its new content.
$values = array(
	'integrations' => array(
		'_yoast_wpseo_focuskw' => 'Botphonic integrations',
		'_yoast_wpseo_title' => 'Botphonic Integrations: HubSpot, Salesforce, Zapier & More',
		'_yoast_wpseo_metadesc' => 'Botphonic integrations connect your AI voice agent to HubSpot, Salesforce, Zoho CRM, Cal.com, Calendly, Twilio and Zapier. See setup guides for all 15.',
		'_yoast_wpseo_opengraph-title' => 'Connect Botphonic to Your CRM, Calendar and Phone System',
		'_yoast_wpseo_opengraph-description' => 'Let your AI voice agent look up callers, book meetings and log call results in the tools your team already uses: HubSpot, Salesforce, Zapier and more.',
		'_yoast_wpseo_twitter-title' => 'Botphonic Integrations: 15 Tools for Your AI Voice Agent',
		'_yoast_wpseo_twitter-description' => 'HubSpot, Salesforce, Zoho CRM, Zapier, Cal.com, Calendly, Twilio, Telnyx and more. See how each one connects to Botphonic.',
	),
);

// The five pages: the exact values applied and tested on the local copy.
$_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'] = '192.168.0.223';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_URI'] = '/botphonic/';
define('WP_USE_THEMES', false);
ob_start();
require 'd:/xampp/htdocs/botphonic/wp-load.php';
ob_end_clean();
foreach (array_slice($slugs, 1) as $slug) {
	$local = get_page_by_path('integrations/' . $slug);
	if (!$local) {
		exit("Local page integrations/{$slug} not found.\n");
	}
	foreach (array_keys($keys) as $key) {
		$values[$slug][$key] = (string) get_post_meta($local->ID, $key, true);
		if ('' === trim($values[$slug][$key])) {
			exit("Local value missing: {$slug} {$key}\n");
		}
	}
}

// Live database (credentials from .env, never printed).
require 'D:/xampp/htdocs/salvadore/tools/botphonic/vendor/autoload.php';
$env = Dotenv\Dotenv::createArrayBacked('D:/xampp/htdocs/salvadore/tools/botphonic')->load();
mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME'], (int) $env['DB_PORT']);
unset($env);
if ($db->connect_errno) {
	exit("Could not connect to the live database (error {$db->connect_errno}).\n");
}
$db->set_charset('utf8mb4');

$one = static function ($sql, $types = '', ...$params) use ($db) {
	$stmt = $db->prepare($sql);
	if (!$stmt) {
		exit('Query failed to prepare: ' . $db->error . "\n");
	}
	if ($types) {
		$stmt->bind_param($types, ...$params);
	}
	$stmt->execute();
	$res = $stmt->get_result();
	return $res ? $res->fetch_all(MYSQLI_ASSOC) : array();
};

// Safety: make sure this is the live Botphonic site.
$home = $one("SELECT option_value FROM wp_options WHERE option_name = 'home'")[0]['option_value'] ?? '';
echo 'Live site: ' . $home . "\n";
if ('https://botphonic.ai' !== rtrim($home, '/')) {
	exit("Not the expected site. Nothing was changed.\n");
}
$tables = $one("SHOW TABLE STATUS WHERE Name IN ('wp_posts','wp_postmeta','wp_yoast_indexable')");
echo 'Tables: ' . implode(', ', array_map(static function ($t) { return $t['Name'] . ' (' . $t['Engine'] . ')'; }, $tables)) . "\n";
if (3 !== count($tables)) {
	exit("Expected tables not found. Nothing was changed.\n");
}

// Find the six pages by slug and parent.
$hub = $one("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish' AND post_parent = 0 AND post_name = 'integrations'");
if (1 !== count($hub)) {
	exit('Expected one published Integrations page, found ' . count($hub) . ". Nothing was changed.\n");
}
$hub_id = (int) $hub[0]['ID'];
$pages = array('integrations' => array('ID' => $hub_id, 'title' => $hub[0]['post_title']));
foreach (array_slice($slugs, 1) as $slug) {
	$row = $one("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish' AND post_parent = ? AND post_name = ?", 'is', $hub_id, $slug);
	if (1 !== count($row)) {
		exit("Expected one published page /integrations/{$slug}/, found " . count($row) . ". Nothing was changed.\n");
	}
	$pages[$slug] = array('ID' => (int) $row[0]['ID'], 'title' => $row[0]['post_title']);
}

// Current values.
$cols = implode(', ', array_values($keys));
foreach ($pages as $slug => &$page) {
	$page['meta'] = array();
	foreach (array_keys($keys) as $key) {
		$page['meta'][$key] = $one('SELECT meta_id, meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ? ORDER BY meta_id', 'is', $page['ID'], $key);
	}
	$idx = $one("SELECT id, {$cols} FROM wp_yoast_indexable WHERE object_type = 'post' AND object_id = ?", 'i', $page['ID']);
	$page['indexable'] = $idx ? $idx[0] : null;
}
unset($page);

if ('' !== $rollback) {
	$saved = json_decode((string) @file_get_contents($rollback), true);
	if (!is_array($saved) || empty($saved['pages'])) {
		exit("Backup file not readable: {$rollback}\n");
	}
	$db->begin_transaction();
	foreach ($saved['pages'] as $slug => $old) {
		foreach ($old['meta'] as $key => $rows) {
			$one('DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', 'is', $old['ID'], $key);
			foreach ($rows as $r) {
				$one('INSERT INTO wp_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (?, ?, ?, ?)', 'iiss', (int) $r['meta_id'], $old['ID'], $key, $r['meta_value']);
			}
		}
		if ($old['indexable']) {
			$set = implode(', ', array_map(static function ($c) { return "{$c} = ?"; }, array_values($keys)));
			$args = array_map(static function ($c) use ($old) { return $old['indexable'][$c]; }, array_values($keys));
			$one("UPDATE wp_yoast_indexable SET {$set} WHERE id = ?", str_repeat('s', count($args)) . 'i', ...array_merge($args, array((int) $old['indexable']['id'])));
		}
		echo "{$slug}: restored\n";
	}
	$db->commit();
	exit("Rollback committed.\n");
}

// Show what changes.
$changes = 0;
foreach ($pages as $slug => $page) {
	echo "\n/integrations/" . ('integrations' === $slug ? '' : $slug . '/') . " (#{$page['ID']}, \"{$page['title']}\", indexable " . ($page['indexable'] ? '#' . $page['indexable']['id'] : 'none') . ")\n";
	foreach ($keys as $key => $col) {
		$new = $values[$slug][$key];
		$rows = $page['meta'][$key];
		$cur = $rows ? $rows[0]['meta_value'] : '(not set)';
		$idx_cur = $page['indexable'] ? (string) $page['indexable'][$col] : null;
		$meta_ok = count($rows) === 1 && $rows[0]['meta_value'] === $new;
		$idx_ok = null === $idx_cur || $idx_cur === $new;
		$label = str_replace('_yoast_wpseo_', '', $key);
		if ($meta_ok && $idx_ok) {
			echo "  = {$label}: unchanged\n";
			continue;
		}
		$changes++;
		echo "  ~ {$label} (" . mb_strlen($new) . " chars)" . (count($rows) > 1 ? ' [' . count($rows) . ' rows]' : '') . "\n      now: {$cur}\n      new: {$new}\n";
	}
}
echo "\n{$changes} field(s) to change.\n";

if (!$apply) {
	exit("Read-only run: nothing was changed.\n");
}
if (!$changes) {
	exit("Nothing to change.\n");
}

// Backup, then write in one transaction.
if (!is_dir($backup_dir)) {
	mkdir($backup_dir, 0775, true);
	file_put_contents($backup_dir . '/.htaccess', "Require all denied\n");
}
$file = $backup_dir . '/live-yoast-before-' . gmdate('Ymd-His') . '.json';
if (false === file_put_contents($file, json_encode(array('site' => $home, 'taken' => gmdate('c'), 'pages' => $pages), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))) {
	exit("Could not write the backup. Nothing was changed.\n");
}
echo "Backup: {$file}\n";

$db->begin_transaction();
foreach ($pages as $slug => $page) {
	foreach ($keys as $key => $col) {
		$new = $values[$slug][$key];
		if ($page['meta'][$key]) {
			$one('UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = ?', 'sis', $new, $page['ID'], $key);
		} else {
			$one('INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)', 'iss', $page['ID'], $key, $new);
		}
	}
	if ($page['indexable']) {
		$set = implode(', ', array_map(static function ($c) { return "{$c} = ?"; }, array_values($keys)));
		$args = array_map(static function ($k) use ($values, $slug) { return $values[$slug][$k]; }, array_keys($keys));
		$one("UPDATE wp_yoast_indexable SET {$set} WHERE id = ?", str_repeat('s', count($args)) . 'i', ...array_merge($args, array((int) $page['indexable']['id'])));
	}
}

// Verify inside the transaction before committing.
$bad = array();
foreach ($pages as $slug => $page) {
	foreach ($keys as $key => $col) {
		$rows = $one('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', 'is', $page['ID'], $key);
		foreach ($rows as $r) {
			if ($r['meta_value'] !== $values[$slug][$key]) {
				$bad[] = "{$slug} {$key}";
			}
		}
		if (!$rows) {
			$bad[] = "{$slug} {$key} missing";
		}
	}
	if ($page['indexable']) {
		$idx = $one("SELECT {$cols} FROM wp_yoast_indexable WHERE id = ?", 'i', (int) $page['indexable']['id'])[0];
		foreach ($keys as $key => $col) {
			if ((string) $idx[$col] !== $values[$slug][$key]) {
				$bad[] = "{$slug} indexable {$col}";
			}
		}
	}
}
if ($bad) {
	$db->rollback();
	exit("Verification failed, rolled back. Nothing was changed: " . implode('; ', $bad) . "\n");
}
$db->commit();
echo "Committed: {$changes} field(s) updated on 6 pages and verified.\n";
