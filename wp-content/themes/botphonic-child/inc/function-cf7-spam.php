<?php

/**
 * CF7 — FAKE / BOT SIGN-UP PROTECTION
 * Loaded from functions.php via $botphonic_includes.
 *
 * Rules were tuned on the October 2026 lead report (146 entries).
 *
 * Spam layers (submission is marked spam: no mail sent, nothing saved,
 * wpcf7_before_send_mail never fires):
 *   A. Honeypot field          — hidden input only bots fill in.
 *   B. Signed time trap        — rejects direct POSTs and posts under 3 seconds.
 *   C. Missing page URL        — the full_url hidden field is filled by
 *                                JavaScript in the browser (it becomes the
 *                                "Form URL" column in the report); bots posting
 *                                directly leave it empty.
 *   D. Spam text in name       — links, "$3,222 payment available", "* * *".
 *   E. Per-IP rate limit       — max 3 submissions per IP per 10 minutes.
 *
 * Validation layer (visitor sees an error under the email field):
 *   F. Email check             — disposable domains, no MX record, random strings.
 *      Runs at priority 20, before the duplicate-email guard (priority 25).
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('BOTPHONIC_FAKE_EMAIL_MESSAGE')) {
	define('BOTPHONIC_FAKE_EMAIL_MESSAGE', 'Please enter a valid email address.');
}
if (!defined('BOTPHONIC_MIN_FILL_SECONDS')) {
	define('BOTPHONIC_MIN_FILL_SECONDS', 3);
}
if (!defined('BOTPHONIC_BLOCK_RANDOM_EMAILS')) {
	define('BOTPHONIC_BLOCK_RANDOM_EMAILS', true);
}
/* Set to false if the page-URL field is NOT filled by JavaScript in the browser. */
if (!defined('BOTPHONIC_REQUIRE_FORM_URL')) {
	define('BOTPHONIC_REQUIRE_FORM_URL', true);
}

/**
 * Hidden fields that hold the page URL. The Sign UP form uses [hidden full_url id:full_url].
 * The check runs only on forms that contain one of these fields.
 */
function botphonic_form_url_fields()
{
	return array('full_url', 'form-url');
}
if (!defined('BOTPHONIC_NAME_FIELD')) {
	define('BOTPHONIC_NAME_FIELD', 'your-name');
}
if (!defined('BOTPHONIC_RATE_LIMIT')) {
	define('BOTPHONIC_RATE_LIMIT', 3);
}
if (!defined('BOTPHONIC_RATE_WINDOW')) {
	define('BOTPHONIC_RATE_WINDOW', 10 * MINUTE_IN_SECONDS);
}

/** Forms to protect. An empty array means all forms. */
function botphonic_spam_form_ids()
{
	return array();
}

function botphonic_spam_applies_to($form_id)
{
	$ids = botphonic_spam_form_ids();
	return empty($ids) || in_array((int) $form_id, $ids, true);
}

/** Disposable / spam domains. Add more here or via the botphonic_blocked_email_domains filter. */
function botphonic_blocked_email_domains()
{
	$domains = array(
		// Seen in the October 2026 report.
		'maxxspace.com',
		'uberip.com',
		'stroysfera.com',
		'any.pink',
		'merepost.com',
		'forliion.com',
		// Common disposable providers.
		'mailinator.com',
		'yopmail.com',
		'guerrillamail.com',
		'sharklasers.com',
		'grr.la',
		'tempmail.com',
		'temp-mail.org',
		'10minutemail.com',
		'trashmail.com',
		'getnada.com',
		'dispostable.com',
		'maildrop.cc',
		'mohmal.com',
		'emailondeck.com',
		'fakeinbox.com',
		'throwawaymail.com',
	);
	return array_map('strtolower', (array) apply_filters('botphonic_blocked_email_domains', $domains));
}

function botphonic_spam_posted($name)
{
	return isset($_POST[$name]) ? trim(wp_unslash((string) $_POST[$name])) : '';
}

/** Cloudflare edge ranges (https://www.cloudflare.com/ips/). */
function botphonic_cloudflare_ranges()
{
	return array(
		'173.245.48.0/20',
		'103.21.244.0/22',
		'103.22.200.0/22',
		'103.31.4.0/22',
		'141.101.64.0/18',
		'108.162.192.0/18',
		'190.93.240.0/20',
		'188.114.96.0/20',
		'197.234.240.0/22',
		'198.41.128.0/17',
		'162.158.0.0/15',
		'104.16.0.0/13',
		'104.24.0.0/14',
		'172.64.0.0/13',
		'131.0.72.0/22',
		'2400:cb00::/32',
		'2606:4700::/32',
		'2803:f800::/32',
		'2405:b500::/32',
		'2405:8100::/32',
		'2a06:98c0::/29',
		'2c0f:f248::/32',
	);
}

function botphonic_ip_in_cidr($ip, $cidr)
{
	list($subnet, $bits) = explode('/', $cidr);
	$ip_bin = @inet_pton($ip);
	$net_bin = @inet_pton($subnet);
	if ($ip_bin === false || $net_bin === false || strlen($ip_bin) !== strlen($net_bin)) {
		return false;
	}
	$bits = (int) $bits;
	$bytes = intdiv($bits, 8);
	if (substr($ip_bin, 0, $bytes) !== substr($net_bin, 0, $bytes)) {
		return false;
	}
	$rem = $bits % 8;
	if ($rem === 0) {
		return true;
	}
	$mask = chr((0xFF << (8 - $rem)) & 0xFF);
	return (($ip_bin[$bytes] & $mask) === ($net_bin[$bytes] & $mask));
}

/**
 * Visitor IP. The site sits behind Cloudflare, so REMOTE_ADDR is a Cloudflare
 * edge IP shared by many visitors. When the request really comes from
 * Cloudflare, trust its CF-Connecting-IP header instead.
 */
function botphonic_visitor_ip()
{
	$remote = isset($_SERVER['REMOTE_ADDR']) ? trim((string) $_SERVER['REMOTE_ADDR']) : '';
	$cf_ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? trim((string) $_SERVER['HTTP_CF_CONNECTING_IP']) : '';
	if ($remote !== '' && $cf_ip !== '' && filter_var($cf_ip, FILTER_VALIDATE_IP)) {
		foreach (botphonic_cloudflare_ranges() as $range) {
			if (botphonic_ip_in_cidr($remote, $range)) {
				return $cf_ip;
			}
		}
	}
	return $remote;
}

/* ---------------------------------------------------------------
 * A + B. Honeypot and signed timestamp, injected into protected forms.
 * ------------------------------------------------------------- */
add_filter('wpcf7_form_hidden_fields', function ($fields) {
	$form = class_exists('WPCF7_ContactForm') ? WPCF7_ContactForm::get_current() : null;
	if (!$form || !botphonic_spam_applies_to($form->id())) {
		return $fields;
	}
	$ts = (string) time();
	$fields['_bp_ts'] = $ts . '.' . wp_hash('bp_ts_' . $ts);
	return $fields;
});

add_filter('wpcf7_form_elements', function ($html) {
	$form = class_exists('WPCF7_ContactForm') ? WPCF7_ContactForm::get_current() : null;
	if (!$form || !botphonic_spam_applies_to($form->id())) {
		return $html;
	}
	$honeypot = '<span aria-hidden="true" style="position:absolute!important;left:-9999px!important;width:1px;height:1px;overflow:hidden;">'
		. '<label>Leave this field empty<input type="text" name="_bp_website" value="" tabindex="-1" autocomplete="off"></label>'
		. '</span>';
	return $honeypot . $html;
});

/* ---------------------------------------------------------------
 * A–E. Spam decision.
 * ------------------------------------------------------------- */
add_filter('wpcf7_spam', function ($spam, $submission = null) {
	if ($spam || !$submission || !method_exists($submission, 'get_contact_form')) {
		return $spam;
	}
	$form = $submission->get_contact_form();
	if (!$form || !botphonic_spam_applies_to($form->id())) {
		return $spam;
	}

	$reason = '';

	// A. Honeypot filled in.
	if (botphonic_spam_posted('_bp_website') !== '') {
		$reason = 'Honeypot field was filled.';
	}

	// B. Missing, forged or too-fast timestamp.
	if ($reason === '') {
		$parts = explode('.', sanitize_text_field(botphonic_spam_posted('_bp_ts')), 2);
		if (count($parts) !== 2 || !ctype_digit($parts[0]) || !hash_equals(wp_hash('bp_ts_' . $parts[0]), $parts[1])) {
			$reason = 'Form token missing or invalid (direct POST).';
		} elseif ((time() - (int) $parts[0]) < BOTPHONIC_MIN_FILL_SECONDS) {
			$reason = 'Form submitted too fast.';
		}
	}

	// C. Page-URL field empty or from another site (only on forms that have the field).
	if ($reason === '' && BOTPHONIC_REQUIRE_FORM_URL && method_exists($form, 'scan_form_tags')) {
		$site_host = preg_replace('/^www\./i', '', (string) wp_parse_url(home_url(), PHP_URL_HOST));
		foreach (botphonic_form_url_fields() as $field) {
			if (empty($form->scan_form_tags(array('name' => $field)))) {
				continue;
			}
			$url = botphonic_spam_posted($field);
			$url_host = $url !== '' ? preg_replace('/^www\./i', '', (string) wp_parse_url($url, PHP_URL_HOST)) : '';
			if ($url_host === '' || strcasecmp($url_host, $site_host) !== 0) {
				$reason = $field . ' empty or from another site.';
			}
			break;
		}
	}

	// D. Spam text in the name field.
	if ($reason === '') {
		$name = botphonic_spam_posted(BOTPHONIC_NAME_FIELD);
		if ($name !== '' && preg_match('~https?://|www\.|\$\s?\d|\*\s\*\s\*|<[a-z/]~i', $name)) {
			$reason = 'Spam text in name field.';
		}
	}

	// E. Per-IP rate limit.
	if ($reason === '') {
		$ip = botphonic_visitor_ip();
		if ($ip !== '') {
			$key = 'botphonic_rl_' . md5($ip . '|' . $form->id());
			$count = (int) get_transient($key);
			if ($count >= BOTPHONIC_RATE_LIMIT) {
				$reason = 'Rate limit exceeded for this IP.';
			} else {
				set_transient($key, $count + 1, BOTPHONIC_RATE_WINDOW);
			}
		}
	}

	if ($reason === '') {
		return $spam;
	}

	if (method_exists($submission, 'add_spam_log')) {
		$submission->add_spam_log(array('agent' => 'botphonic', 'reason' => $reason));
	}
	if (defined('WP_DEBUG') && WP_DEBUG) {
		error_log('Botphonic CF7 spam blocked (form ' . $form->id() . '): ' . $reason);
	}
	return true;
}, 10, 2);

/* ---------------------------------------------------------------
 * F. Email validation.
 * ------------------------------------------------------------- */

/** MX lookup, cached a week per domain (failures retried hourly). */
function botphonic_domain_has_mx($domain)
{
	if (!function_exists('checkdnsrr')) {
		return true;
	}
	$key = 'botphonic_mx_' . md5($domain);
	$cached = get_transient($key);
	if ($cached !== false) {
		return $cached === 'yes';
	}
	$ok = checkdnsrr($domain, 'MX');
	set_transient($key, $ok ? 'yes' : 'no', $ok ? WEEK_IN_SECONDS : HOUR_IN_SECONDS);
	return $ok;
}

/**
 * Random bot strings like 9zfq5h9hx7q1rn or f2iwda0gcarysk:
 * 13+ letters/digits, 2+ separate digit groups and few vowels.
 * Normal addresses like rahulkumar6302903387 or madhurapawar61 pass.
 */
function botphonic_is_random_local_part($local)
{
	if (!preg_match('/^[a-z0-9]{13,}$/', $local)) {
		return false;
	}
	if (preg_match_all('/\d+/', $local) < 2) {
		return false;
	}
	$letters = preg_replace('/\d/', '', $local);
	if ($letters === '') {
		return false;
	}
	$vowels = preg_match_all('/[aeiou]/', $letters);
	return ($vowels / strlen($letters)) < 0.3;
}

add_filter('wpcf7_validate_email*', 'botphonic_validate_fake_email', 20, 2);
add_filter('wpcf7_validate_email', 'botphonic_validate_fake_email', 20, 2);

function botphonic_validate_fake_email($result, $tag)
{
	if (!class_exists('WPCF7_ContactForm')) {
		return $result;
	}
	$form = WPCF7_ContactForm::get_current();
	if (!$form || !botphonic_spam_applies_to($form->id())) {
		return $result;
	}

	$invalid = method_exists($result, 'get_invalid_fields') ? $result->get_invalid_fields() : array();
	if (isset($invalid[$tag->name])) {
		return $result;
	}

	$email = strtolower(botphonic_spam_posted($tag->name));
	if ($email === '' || !is_email($email)) {
		return $result;
	}

	list($local, $domain) = explode('@', $email, 2);

	$blocked = in_array($domain, botphonic_blocked_email_domains(), true)
		|| !botphonic_domain_has_mx($domain)
		|| (BOTPHONIC_BLOCK_RANDOM_EMAILS && botphonic_is_random_local_part($local));

	if ($blocked) {
		$result->invalidate($tag, BOTPHONIC_FAKE_EMAIL_MESSAGE);
	}

	return $result;
}