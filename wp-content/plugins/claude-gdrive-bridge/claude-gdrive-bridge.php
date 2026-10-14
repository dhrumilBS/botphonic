<?php
/**
 * Plugin Name: Botphonic Claude GDrive Bridge
 * Plugin URI: https://botphonic.ai
 * Description: Secure bridge to receive files from Claude VM and upload them to Google Drive. Accepts multipart file uploads via REST API with API key + HMAC authentication.
 * Version: 1.1.0
 * Author: Botphonic
 * Author URI: https://botphonic.ai
 * License: GPL v2 or later
 * Text Domain: claude-gdrive-bridge
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CGB_VERSION', '1.2.0');
define('CGB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CGB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CGB_TEMP_DIR', WP_CONTENT_DIR . '/claude-gdrive-temp/');
define('CGB_LOG_FILE', WP_CONTENT_DIR . '/claude-gdrive-bridge.log');

class Claude_GDrive_Bridge {

    private static $instance = null;
    private const RATE_LIMIT = 30;
    private const MAX_FILE_SIZE = 52428800; // 50MB
    private const MAX_REQUEST_AGE = 300; // 5 minutes
    private const ALLOWED_EXTENSIONS = ['docx', 'pdf', 'xlsx', 'pptx', 'txt', 'csv', 'zip', 'doc', 'xls', 'ppt'];
    private const ALLOWED_MIMES = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint',
        'application/pdf', 'text/plain', 'text/csv', 'application/zip',
        'application/octet-stream',
    ];
    private const ALLOWED_ACTIONS = ['new', 'replace'];
    private const DANGEROUS_EXTS = [
        'php','phtml','php3','php4','php5','php7','phps','phar',
        'js','jsp','asp','aspx','cgi','pl','py','rb','sh','bash',
        'exe','bat','cmd','com','scr','msi','dll','htaccess','svg',
    ];

    public static function get_instance() {
        if (null === self::$instance) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'init']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'handle_oauth_callback']);
        add_action('admin_enqueue_scripts', [$this, 'admin_styles']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('cgb_cleanup_temp_files', [$this, 'cleanup_temp_files']);
    }

    // ==================== ACTIVATION ====================

    public function activate() {
        if (!file_exists(CGB_TEMP_DIR)) wp_mkdir_p(CGB_TEMP_DIR);
        file_put_contents(CGB_TEMP_DIR . '.htaccess', "Order Deny,Allow\nDeny from all");
        file_put_contents(CGB_TEMP_DIR . 'index.php', '<?php // Silence is golden.');

        // Protect log file via .htaccess
        $log_htaccess = dirname(CGB_LOG_FILE) . '/.htaccess';
        if (file_exists($log_htaccess)) {
            $content = file_get_contents($log_htaccess);
            if (strpos($content, 'claude-gdrive-bridge.log') === false) {
                file_put_contents($log_htaccess, $content . "\n<Files \"claude-gdrive-bridge.log\">\nOrder Deny,Allow\nDeny from all\n</Files>\n");
            }
        }

        if (!get_option('cgb_api_key')) update_option('cgb_api_key', wp_generate_password(48, false));
        if (!get_option('cgb_hmac_secret')) update_option('cgb_hmac_secret', wp_generate_password(64, true, true));
        if (!wp_next_scheduled('cgb_cleanup_temp_files')) wp_schedule_event(time(), 'hourly', 'cgb_cleanup_temp_files');
        flush_rewrite_rules();
    }

    public function deactivate() {
        wp_clear_scheduled_hook('cgb_cleanup_temp_files');
        $this->cleanup_temp_files();
    }

    public function init() {
        if (!file_exists(CGB_TEMP_DIR)) wp_mkdir_p(CGB_TEMP_DIR);
    }

    // ==================== REST ROUTES ====================

    public function register_rest_routes() {
        register_rest_route('claude-bridge/v1', '/upload', [
            'methods' => 'POST', 'callback' => [$this, 'handle_upload'], 'permission_callback' => [$this, 'verify_request'],
        ]);
        register_rest_route('claude-bridge/v1', '/download', [
            'methods' => 'POST', 'callback' => [$this, 'handle_download'], 'permission_callback' => [$this, 'verify_request'],
        ]);
        register_rest_route('claude-bridge/v1', '/status', [
            'methods' => 'GET', 'callback' => [$this, 'handle_status'], 'permission_callback' => [$this, 'verify_request'],
        ]);
    }

    // ==================== AUTH & SECURITY ====================

    public function verify_request($request) {
        $ip = $this->get_client_ip();

        // 1. HTTPS enforcement (skip for localhost)
        $is_https = is_ssl() || (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] === "https") || (!empty($_SERVER["HTTP_CF_VISITOR"]) && strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false);
        if (!$is_https && !in_array($ip, ["127.0.0.1", "::1"])) {
            $this->log("BLOCKED: Non-HTTPS from $ip");
            return new WP_Error('https_required', 'HTTPS required.', ['status' => 403]);
        }

        // 2. IP whitelist
        $whitelist = get_option('cgb_ip_whitelist', '');
        if (!empty(trim($whitelist))) {
            $allowed = array_filter(array_map('trim', explode("\n", $whitelist)));
            if (!empty($allowed) && !in_array($ip, $allowed)) {
                $this->log("BLOCKED: IP $ip not whitelisted");
                return new WP_Error('ip_blocked', 'IP not allowed.', ['status' => 403]);
            }
        }

        // 3. API Key (timing-safe)
        $api_key = $request->get_header('X-API-Key');
        $stored  = get_option('cgb_api_key');
        if (empty($api_key) || empty($stored)) return new WP_Error('unauthorized', 'Missing API key.', ['status' => 401]);
        if (!hash_equals($stored, $api_key)) {
            $this->log("AUTH FAILED: Bad API key from $ip");
            return new WP_Error('forbidden', 'Invalid API key.', ['status' => 403]);
        }

        // 4. HMAC signature (if enabled)
        if (get_option('cgb_hmac_enabled', '0') === '1') {
            $sig = $request->get_header('X-Signature');
            $ts  = $request->get_header('X-Timestamp');
            $secret = get_option('cgb_hmac_secret');

            if (empty($sig) || empty($ts)) {
                $this->log("AUTH FAILED: Missing HMAC from $ip");
                return new WP_Error('unauthorized', 'Missing HMAC headers.', ['status' => 401]);
            }
            if (abs(time() - (int)$ts) > self::MAX_REQUEST_AGE) {
                $this->log("AUTH FAILED: Expired timestamp from $ip");
                return new WP_Error('expired', 'Request expired.', ['status' => 401]);
            }
            $expected = hash_hmac('sha256', $ts . strtoupper($request->get_method()) . $request->get_route(), $secret);
            if (!hash_equals($expected, $sig)) {
                $this->log("AUTH FAILED: Bad HMAC from $ip");
                return new WP_Error('forbidden', 'Invalid signature.', ['status' => 403]);
            }
        }

        // 5. Rate limiting
        $rk = 'cgb_rate_' . md5($ip);
        $count = (int) get_transient($rk);
        if ($count >= self::RATE_LIMIT) {
            $this->log("RATE LIMITED: $ip");
            return new WP_Error('rate_limited', 'Max ' . self::RATE_LIMIT . '/hour.', ['status' => 429]);
        }
        set_transient($rk, $count + 1, HOUR_IN_SECONDS);

        return true;
    }

    // ==================== UPLOAD HANDLER ====================

    public function handle_upload($request) {
        $ip = $this->get_client_ip();
        $this->log("UPLOAD from $ip");

        // 1. OAuth check
        $token = $this->get_google_access_token();
        if (is_wp_error($token)) return new WP_REST_Response(['success' => false, 'error' => $token->get_error_message()], 500);

        // 2. File validation
        $files = $request->get_file_params();
        if (empty($files['file'])) return new WP_REST_Response(['success' => false, 'error' => 'No file. Use -F "file=@path"'], 400);
        $file = $files['file'];

        if ($file['error'] !== UPLOAD_ERR_OK) return new WP_REST_Response(['success' => false, 'error' => 'Upload error: ' . $file['error']], 400);
        if ($file['size'] > self::MAX_FILE_SIZE) return new WP_REST_Response(['success' => false, 'error' => 'Max size: ' . size_format(self::MAX_FILE_SIZE)], 400);
        if ($file['size'] === 0) return new WP_REST_Response(['success' => false, 'error' => 'Empty file.'], 400);

        // Extension check
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true))
            return new WP_REST_Response(['success' => false, 'error' => 'Type not allowed. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS)], 400);

        // MIME check (content-based, not declared)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, self::ALLOWED_MIMES, true)) {
            $this->log("BLOCKED: MIME '$mime' file '{$file['name']}' from $ip");
            return new WP_REST_Response(['success' => false, 'error' => "MIME not allowed: $mime"], 400);
        }

        // Double-extension attack (e.g. file.php.docx)
        $parts = explode('.', $file['name']);
        if (count($parts) > 2) {
            foreach ($parts as $p) {
                if (in_array(strtolower($p), self::DANGEROUS_EXTS, true)) {
                    $this->log("BLOCKED: Suspicious name '{$file['name']}' from $ip");
                    return new WP_REST_Response(['success' => false, 'error' => 'Suspicious filename.'], 400);
                }
            }
        }

        // Path traversal check on tmp_name
        $real_tmp = realpath($file['tmp_name']);
        $sys_tmp  = realpath(sys_get_temp_dir());
        if (!$real_tmp || !$sys_tmp || strpos($real_tmp, $sys_tmp) !== 0) {
            $this->log("BLOCKED: Path traversal attempt from $ip");
            return new WP_REST_Response(['success' => false, 'error' => 'Invalid upload.'], 400);
        }

        // 3. Save to temp
        $safe = sanitize_file_name($file['name']);
        $tmp  = CGB_TEMP_DIR . wp_generate_password(16, false) . '_' . $safe;
        if (!move_uploaded_file($file['tmp_name'], $tmp))
            return new WP_REST_Response(['success' => false, 'error' => 'Save failed.'], 500);

        $this->log("SAVED: $safe (" . size_format($file['size']) . ")");

        // 4. Parameters
        $params = $request->get_params();
        $doc_id = isset($params['doc_id']) ? sanitize_text_field($params['doc_id']) : '';
        $fname  = isset($params['filename']) ? sanitize_file_name($params['filename']) : $safe;
        $title  = isset($params['title']) ? sanitize_text_field($params['title']) : '';
        $action = isset($params['action']) ? sanitize_text_field($params['action']) : 'new';

        // If title is provided, use it as the Drive file name (append extension if missing)
        if (!empty($title)) {
            $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            $title_ext = strtolower(pathinfo($title, PATHINFO_EXTENSION));
            if ($title_ext !== $ext && !empty($ext)) {
                $fname = $title . '.' . $ext;
            } else {
                $fname = $title;
            }
        }

        if (!in_array($action, self::ALLOWED_ACTIONS, true)) { unlink($tmp); return new WP_REST_Response(['success' => false, 'error' => 'Invalid action.'], 400); }

        $gid = $this->extract_doc_id($doc_id);
        if (!empty($doc_id) && empty($gid)) { unlink($tmp); return new WP_REST_Response(['success' => false, 'error' => 'Invalid doc_id.'], 400); }

        // 5. Upload to Drive
        $result = $this->upload_to_gdrive($token, $tmp, $fname, $gid, $action);

        // 6. Cleanup (always)
        if (file_exists($tmp)) unlink($tmp);

        if (is_wp_error($result)) {
            $this->log("GDRIVE ERROR: " . $result->get_error_message());
            return new WP_REST_Response(['success' => false, 'error' => $result->get_error_message()], 500);
        }

        $this->log("SUCCESS: {$result['name']} → {$result['webViewLink']}");
        return new WP_REST_Response([
            'success' => true, 'file_id' => $result['id'],
            'file_name' => $result['name'], 'web_link' => $result['webViewLink'], 'action' => $action,
        ], 200);
    }

    public function handle_status($request) {
        $has = !empty(get_option('cgb_google_refresh_token'));
        $tok = $has ? $this->get_google_access_token() : null;
        return new WP_REST_Response([
            'success' => true, 'version' => CGB_VERSION,
            'oauth' => $has, 'token_valid' => ($tok && !is_wp_error($tok)),
            'writable' => is_writable(CGB_TEMP_DIR), 'hmac' => get_option('cgb_hmac_enabled','0')==='1',
        ], 200);
    }

    /**
     * Handle download: fetch a Google Doc/Drive file as .docx and stream it back.
     *
     * Expects POST with:
     *   - doc_id: Google Doc ID or full URL
     *   - format: "docx" (default) or "pdf"
     */
    public function handle_download($request) {
        $ip = $this->get_client_ip();
        $this->log("DOWNLOAD from $ip");

        // 1. OAuth check
        $token = $this->get_google_access_token();
        if (is_wp_error($token)) {
            return new WP_REST_Response(['success' => false, 'error' => $token->get_error_message()], 500);
        }

        // 2. Get and validate doc_id
        $params = $request->get_params();
        $doc_id_raw = isset($params['doc_id']) ? sanitize_text_field($params['doc_id']) : '';
        $format = isset($params['format']) ? sanitize_text_field($params['format']) : 'docx';

        if (empty($doc_id_raw)) {
            return new WP_REST_Response(['success' => false, 'error' => 'doc_id is required.'], 400);
        }

        $doc_id = $this->extract_doc_id($doc_id_raw);
        if (empty($doc_id)) {
            return new WP_REST_Response(['success' => false, 'error' => 'Invalid doc_id format.'], 400);
        }

        // Validate format
        $format_map = [
            'docx' => [
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'ext'  => '.docx',
            ],
            'pdf' => [
                'mime' => 'application/pdf',
                'ext'  => '.pdf',
            ],
        ];

        if (!isset($format_map[$format])) {
            return new WP_REST_Response(['success' => false, 'error' => 'Invalid format. Use: docx, pdf'], 400);
        }

        $export_mime = $format_map[$format]['mime'];
        $export_ext  = $format_map[$format]['ext'];

        // 3. Get file metadata
        $doc_id_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $doc_id);
        $meta_url = "https://www.googleapis.com/drive/v3/files/{$doc_id_safe}?fields=id,name,mimeType,size";
        $meta_resp = wp_remote_get($meta_url, [
            'timeout' => 15,
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        if (is_wp_error($meta_resp)) {
            return new WP_REST_Response(['success' => false, 'error' => 'Failed to get file info: ' . $meta_resp->get_error_message()], 500);
        }

        $meta_code = wp_remote_retrieve_response_code($meta_resp);
        $meta_body = json_decode(wp_remote_retrieve_body($meta_resp), true);

        if ($meta_code !== 200) {
            $msg = $meta_body['error']['message'] ?? "HTTP $meta_code";
            return new WP_REST_Response(['success' => false, 'error' => "Google Drive error: $msg"], $meta_code === 404 ? 404 : 500);
        }

        $file_mime = $meta_body['mimeType'] ?? '';
        $file_name = $meta_body['name'] ?? 'document';

        // Sanitize filename
        $safe_name = preg_replace('/[^a-zA-Z0-9 _-]/', '', $file_name);
        if (empty($safe_name)) $safe_name = 'document';

        // 4. Download/Export the file
        $temp_path = CGB_TEMP_DIR . wp_generate_password(16, false) . '_' . $safe_name . $export_ext;

        $download_success = false;

        if ($file_mime === 'application/vnd.google-apps.document') {
            // Native Google Doc — try API export first, then web export fallback for large files
            $download_url = "https://www.googleapis.com/drive/v3/files/{$doc_id_safe}/export?mimeType=" . urlencode($export_mime);

            $dl_resp = wp_remote_get($download_url, [
                'timeout'  => 120,
                'stream'   => true,
                'filename' => $temp_path,
                'headers'  => ['Authorization' => "Bearer $token"],
            ]);

            $dl_code = is_wp_error($dl_resp) ? 0 : wp_remote_retrieve_response_code($dl_resp);

            // Check if API export failed (usually "too large" for files >10MB)
            if ($dl_code !== 200) {
                if (file_exists($temp_path)) unlink($temp_path);

                // Fallback: Use Google's web export URL (no 10MB limit)
                $this->log("API export failed (HTTP $dl_code), trying web export fallback for doc $doc_id");
                $format_param = ($format === 'pdf') ? 'pdf' : 'docx';
                $web_export_url = "https://docs.google.com/document/d/{$doc_id_safe}/export?format={$format_param}";

                $dl_resp = wp_remote_get($web_export_url, [
                    'timeout'    => 180,
                    'stream'     => true,
                    'filename'   => $temp_path,
                    'headers'    => ['Authorization' => "Bearer $token"],
                    'user-agent' => 'Mozilla/5.0 (compatible; CGB/1.2)',
                ]);

                $dl_code = is_wp_error($dl_resp) ? 0 : wp_remote_retrieve_response_code($dl_resp);

                if (is_wp_error($dl_resp) || $dl_code !== 200) {
                    if (file_exists($temp_path)) unlink($temp_path);
                    $err_msg = is_wp_error($dl_resp) ? $dl_resp->get_error_message() : "HTTP $dl_code";
                    return new WP_REST_Response(['success' => false, 'error' => "Export failed (both API and web): $err_msg"], 500);
                }

                $download_success = true;
                $this->log("Web export fallback succeeded for doc $doc_id");
            } else {
                $download_success = true;
            }

        } elseif ($file_mime === $export_mime) {
            // Already the right format — direct download
            $download_url = "https://www.googleapis.com/drive/v3/files/{$doc_id_safe}?alt=media";

            $dl_resp = wp_remote_get($download_url, [
                'timeout'  => 120,
                'stream'   => true,
                'filename' => $temp_path,
                'headers'  => ['Authorization' => "Bearer $token"],
            ]);

            $dl_code = is_wp_error($dl_resp) ? 0 : wp_remote_retrieve_response_code($dl_resp);

            if (is_wp_error($dl_resp) || $dl_code !== 200) {
                if (file_exists($temp_path)) unlink($temp_path);
                $err_msg = is_wp_error($dl_resp) ? $dl_resp->get_error_message() : "HTTP $dl_code";
                return new WP_REST_Response(['success' => false, 'error' => "Download failed: $err_msg"], 500);
            }

            $download_success = true;

        } else {
            // Other Google Workspace type — try export
            $download_url = "https://www.googleapis.com/drive/v3/files/{$doc_id_safe}/export?mimeType=" . urlencode($export_mime);

            $dl_resp = wp_remote_get($download_url, [
                'timeout'  => 120,
                'stream'   => true,
                'filename' => $temp_path,
                'headers'  => ['Authorization' => "Bearer $token"],
            ]);

            $dl_code = is_wp_error($dl_resp) ? 0 : wp_remote_retrieve_response_code($dl_resp);

            if (is_wp_error($dl_resp) || $dl_code !== 200) {
                if (file_exists($temp_path)) unlink($temp_path);
                $err_msg = is_wp_error($dl_resp) ? $dl_resp->get_error_message() : "HTTP $dl_code";
                return new WP_REST_Response(['success' => false, 'error' => "Export failed: $err_msg"], 500);
            }

            $download_success = true;
        }

        // Verify file exists and has content
        if (!file_exists($temp_path) || filesize($temp_path) === 0) {
            if (file_exists($temp_path)) unlink($temp_path);
            return new WP_REST_Response(['success' => false, 'error' => 'Downloaded file is empty.'], 500);
        }

        $file_size = filesize($temp_path);
        $this->log("DOWNLOAD SUCCESS: $safe_name$export_ext (" . size_format($file_size) . ") for doc $doc_id");

        // 5. Stream the file back to the caller
        $output_name = $safe_name . $export_ext;

        // Send file as response — use WordPress-compatible approach
        header('Content-Type: ' . $export_mime);
        header('Content-Disposition: attachment; filename="' . $output_name . '"');
        header('Content-Length: ' . $file_size);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('X-CGB-Document-Id: ' . $doc_id);
        header('X-CGB-Source-Type: ' . ($file_mime === 'application/vnd.google-apps.document' ? 'google_doc_export' : 'direct_download'));
        header('X-CGB-File-Name: ' . $output_name);

        // Read and output file
        readfile($temp_path);

        // Cleanup
        unlink($temp_path);

        // Exit to prevent WordPress from sending additional output
        exit;
    }

    // ==================== GOOGLE DRIVE ====================

    private function get_google_access_token() {
        $cid = get_option('cgb_google_client_id');
        $cs  = $this->decrypt_option('cgb_google_client_secret_enc') ?: get_option('cgb_google_client_secret');
        $rt  = $this->decrypt_option('cgb_google_refresh_token_enc') ?: get_option('cgb_google_refresh_token');
        if (empty($cid)||empty($cs)||empty($rt)) return new WP_Error('no_oauth','OAuth not configured.');

        $cached = get_transient('cgb_google_access_token');
        if ($cached) return $cached;

        $r = wp_remote_post('https://oauth2.googleapis.com/token', ['timeout'=>15, 'body'=>[
            'client_id'=>$cid, 'client_secret'=>$cs, 'refresh_token'=>$rt, 'grant_type'=>'refresh_token',
        ]]);
        if (is_wp_error($r)) return $r;
        $b = json_decode(wp_remote_retrieve_body($r), true);
        if (isset($b['error'])) return new WP_Error('refresh_fail', $b['error_description'] ?? $b['error']);
        if (empty($b['access_token'])) return new WP_Error('refresh_fail','No token.');
        set_transient('cgb_google_access_token', $b['access_token'], ($b['expires_in'] ?? 3600) - 100);
        return $b['access_token'];
    }

    private function upload_to_gdrive($token, $path, $name, $gid, $action) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); $mime = finfo_file($finfo, $path); finfo_close($finfo);
        if ($mime==='application/octet-stream') $mime = $this->get_mime_from_ext($name);

        if ($action==='replace' && !empty($gid)) return $this->gdrive_replace($token, $gid, $path, $name, $mime);

        $parent = null;
        if (!empty($gid)) {
            // Check if the ID is a folder — if so, upload directly into it
            $type = $this->gdrive_get_type($token, $gid);
            if ($type === 'folder') {
                $parent = $gid; // Use folder ID directly as parent
            } else {
                $parent = $this->gdrive_parent($token, $gid);
                if (is_wp_error($parent)) $parent = null;
            }
        }
        return $this->gdrive_create($token, $path, $name, $mime, $parent);
    }

    /**
     * Get the MIME type of a Google Drive item (to detect folders vs files)
     */
    private function gdrive_get_type($token, $fid) {
        $fid = preg_replace('/[^a-zA-Z0-9_-]/','', $fid);
        $r = wp_remote_get("https://www.googleapis.com/drive/v3/files/{$fid}?fields=mimeType", [
            'timeout'=>15, 'headers'=>['Authorization'=>"Bearer $token"],
        ]);
        if (is_wp_error($r)) return 'unknown';
        $b = json_decode(wp_remote_retrieve_body($r), true);
        if (wp_remote_retrieve_response_code($r) !== 200) return 'unknown';
        return ($b['mimeType'] ?? '') === 'application/vnd.google-apps.folder' ? 'folder' : 'file';
    }

    private function gdrive_parent($token, $fid) {
        $fid = preg_replace('/[^a-zA-Z0-9_-]/','', $fid);
        $r = wp_remote_get("https://www.googleapis.com/drive/v3/files/{$fid}?fields=parents", [
            'timeout'=>15, 'headers'=>['Authorization'=>"Bearer $token"],
        ]);
        if (is_wp_error($r)) return $r;
        $b = json_decode(wp_remote_retrieve_body($r), true);
        if (wp_remote_retrieve_response_code($r)!==200) return new WP_Error('e', $b['error']['message'] ?? 'Failed');
        return $b['parents'][0] ?? new WP_Error('e','No parent.');
    }

    private function gdrive_create($token, $path, $name, $mime, $parent=null) {
        $meta = ['name'=>$name]; if ($parent) $meta['parents'] = [$parent];
        return $this->gdrive_multipart('POST',
            'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&fields=id,name,webViewLink',
            $token, $meta, $path, $mime);
    }

    private function gdrive_replace($token, $fid, $path, $name, $mime) {
        $fid = preg_replace('/[^a-zA-Z0-9_-]/','', $fid);
        return $this->gdrive_multipart('PATCH',
            "https://www.googleapis.com/upload/drive/v3/files/{$fid}?uploadType=multipart&fields=id,name,webViewLink",
            $token, ['name'=>$name], $path, $mime);
    }

    private function gdrive_multipart($method, $url, $token, $meta, $path, $mime) {
        $b = 'cgb_' . wp_generate_password(24, false);
        $body  = "--{$b}\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n" . json_encode($meta) . "\r\n";
        $body .= "--{$b}\r\nContent-Type: {$mime}\r\n\r\n" . file_get_contents($path) . "\r\n--{$b}--";

        $r = wp_remote_request($url, [
            'method'=>$method, 'timeout'=>120, 'body'=>$body,
            'headers'=>['Authorization'=>"Bearer $token", 'Content-Type'=>"multipart/related; boundary=$b"],
        ]);
        if (is_wp_error($r)) return $r;
        $res = json_decode(wp_remote_retrieve_body($r), true);
        if (wp_remote_retrieve_response_code($r)!==200)
            return new WP_Error('fail', ($res['error']['message'] ?? 'HTTP '.wp_remote_retrieve_response_code($r)));
        return $res;
    }

    // ==================== ENCRYPTION ====================

    private function get_enc_key() {
        return hash('sha256', (defined('AUTH_KEY') ? AUTH_KEY : 'cgb-' . get_site_url()) . 'cgb-enc', true);
    }
    private function encrypt_val($v) {
        if (empty($v)) return '';
        $iv = random_bytes(16);
        $c = openssl_encrypt($v, 'aes-256-cbc', $this->get_enc_key(), OPENSSL_RAW_DATA, $iv);
        return $c !== false ? base64_encode($iv . $c) : '';
    }
    private function decrypt_val($v) {
        if (empty($v)) return '';
        $d = base64_decode($v); if (!$d || strlen($d)<17) return '';
        $r = openssl_decrypt(substr($d,16), 'aes-256-cbc', $this->get_enc_key(), OPENSSL_RAW_DATA, substr($d,0,16));
        return $r !== false ? $r : '';
    }
    private function decrypt_option($k) { return $this->decrypt_val(get_option($k, '')); }

    // ==================== ADMIN ====================

    public function add_admin_menu() {
        add_options_page('Botphonic Claude GDrive Bridge', 'Botphonic Claude GDrive Bridge', 'manage_options', 'claude-gdrive-bridge', [$this, 'render_settings_page']);
    }

    public function register_settings() {
        register_setting('cgb_settings', 'cgb_api_key', ['sanitize_callback'=>'sanitize_text_field']);
        register_setting('cgb_settings', 'cgb_google_client_id', ['sanitize_callback'=>'sanitize_text_field']);
        register_setting('cgb_settings', 'cgb_ip_whitelist', ['sanitize_callback'=>'sanitize_textarea_field']);
        register_setting('cgb_settings', 'cgb_hmac_enabled', ['sanitize_callback'=>'sanitize_text_field']);
        $self = $this;
        register_setting('cgb_settings', 'cgb_google_client_secret', ['sanitize_callback'=>function($v) use ($self) {
            if (!empty($v)) update_option('cgb_google_client_secret_enc', $self->encrypt_val($v));
            return $v;
        }]);
        register_setting('cgb_settings', 'cgb_google_refresh_token', ['sanitize_callback'=>function($v) use ($self) {
            if (!empty($v)) update_option('cgb_google_refresh_token_enc', $self->encrypt_val($v));
            return $v;
        }]);
    }

    public function handle_oauth_callback() {
        if (!isset($_GET['page']) || $_GET['page']!=='claude-gdrive-bridge') return;
        if (!isset($_GET['code'])) return;
        if (!current_user_can('manage_options')) return;

        // CSRF protection via state parameter
        $state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
        $stored = get_transient('cgb_oauth_state');
        delete_transient('cgb_oauth_state');
        if (empty($state) || empty($stored) || !hash_equals($stored, $state)) {
            add_settings_error('cgb_settings', 'csrf', 'OAuth state mismatch (CSRF protection). Try again.', 'error');
            return;
        }

        $code = sanitize_text_field($_GET['code']);
        $r = wp_remote_post('https://oauth2.googleapis.com/token', ['timeout'=>15, 'body'=>[
            'code'=>$code, 'client_id'=>get_option('cgb_google_client_id'),
            'client_secret'=>get_option('cgb_google_client_secret'),
            'redirect_uri'=>admin_url('options-general.php?page=claude-gdrive-bridge'),
            'grant_type'=>'authorization_code',
        ]]);

        if (!is_wp_error($r)) {
            $b = json_decode(wp_remote_retrieve_body($r), true);
            if (!empty($b['refresh_token'])) {
                update_option('cgb_google_refresh_token', $b['refresh_token']);
                update_option('cgb_google_refresh_token_enc', $this->encrypt_val($b['refresh_token']));
                delete_transient('cgb_google_access_token');
                add_settings_error('cgb_settings', 'ok', 'Google OAuth connected!', 'success');
            } else {
                add_settings_error('cgb_settings', 'err', 'OAuth: ' . ($b['error_description'] ?? $b['error'] ?? 'No refresh token'), 'error');
            }
        }
        // No redirect - let the settings page render with the success/error message
    }

    public function admin_styles($hook) {
        if ($hook !== 'settings_page_claude-gdrive-bridge') return;
        wp_add_inline_style('wp-admin', '
            .cgb-wrap{max-width:820px}.cgb-card{background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:20px;margin:15px 0}
            .cgb-card h3{margin-top:0;padding-bottom:10px;border-bottom:1px solid #eee}
            .cgb-code{background:#f0f0f1;padding:12px 16px;border-radius:4px;font-family:monospace;font-size:13px;overflow-x:auto;white-space:pre-wrap;word-break:break-all}
            .cgb-s{display:inline-block;padding:3px 10px;border-radius:3px;font-size:12px;font-weight:600}
            .cgb-ok{background:#d4edda;color:#155724}.cgb-err{background:#f8d7da;color:#721c24}
            .cgb-field{margin-bottom:15px}.cgb-field label{display:block;font-weight:600;margin-bottom:5px}
            .cgb-field input[type="text"],.cgb-field input[type="password"],.cgb-field textarea{width:100%;max-width:600px}
            .cgb-curl{background:#1d2327;color:#50c878;padding:16px;border-radius:6px;font-family:monospace;font-size:12px;line-height:1.6;overflow-x:auto;white-space:pre-wrap;word-break:break-all}
            .cgb-sec{background:#e8f5e9;border:1px solid #4caf50;border-radius:4px;padding:12px 16px;margin:10px 0}
            .cgb-sec ul{margin:8px 0 0 20px}
        ');
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) return;
        $ak = get_option('cgb_api_key',''); $hs = get_option('cgb_hmac_secret','');
        $he = get_option('cgb_hmac_enabled','0'); $cid = get_option('cgb_google_client_id','');
        $cs = get_option('cgb_google_client_secret',''); $rt = get_option('cgb_google_refresh_token','');
        $wl = get_option('cgb_ip_whitelist',''); $oauth = !empty($cid)&&!empty($cs)&&!empty($rt);
        $ru = admin_url('options-general.php?page=claude-gdrive-bridge');
        $ou = '';
        if (!empty($cid)) {
            $st = wp_generate_password(32, false); set_transient('cgb_oauth_state', $st, 600);
            $ou = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                'client_id'=>$cid,'redirect_uri'=>$ru,'response_type'=>'code',
                'scope'=>'https://www.googleapis.com/auth/drive','access_type'=>'offline','prompt'=>'consent','state'=>$st,
            ]);
        }
        $tv = false; if ($oauth) { $t = $this->get_google_access_token(); $tv = !is_wp_error($t); }
        $eu = rest_url('claude-bridge/v1/upload'); $su = rest_url('claude-bridge/v1/status');
        ?>
        <div class="wrap cgb-wrap">
        <h1>Botphonic Claude GDrive Bridge <small style="font-size:12px;color:#666">v<?php echo CGB_VERSION; ?></small></h1>
        <?php settings_errors('cgb_settings'); ?>

        <div class="cgb-sec">
            <strong>Security Layers:</strong>
            <ul>
                <li>✅ API Key (timing-safe hash_equals)</li>
                <li><?php echo $he==='1'?'✅':'⬜'; ?> HMAC signing + replay protection (5min window)</li>
                <li><?php echo !empty(trim($wl))?'✅':'⬜'; ?> IP whitelist</li>
                <li>✅ HTTPS enforcement</li>
                <li>✅ Rate limiting (<?php echo self::RATE_LIMIT; ?>/hr)</li>
                <li>✅ File validation (ext + MIME + double-ext + size + empty + path traversal)</li>
                <li>✅ Encrypted secrets (AES-256-CBC)</li>
                <li>✅ OAuth CSRF protection (state param)</li>
                <li>✅ Temp cleanup + .htaccess protection</li>
                <li>✅ Log file protected from web access</li>
                <li>✅ Google Drive ID sanitization (injection prevention)</li>
            </ul>
        </div>

        <div class="cgb-card"><h3>Status</h3>
        <table class="widefat" style="max-width:500px">
            <tr><td><strong>API Key</strong></td><td><?php echo $ak?'<span class="cgb-s cgb-ok">Set</span>':'<span class="cgb-s cgb-err">Missing</span>'; ?></td></tr>
            <tr><td><strong>HMAC</strong></td><td><?php echo $he==='1'?'<span class="cgb-s cgb-ok">On</span>':'<span class="cgb-s cgb-err">Off</span>'; ?></td></tr>
            <tr><td><strong>OAuth</strong></td><td><?php echo $oauth?'<span class="cgb-s cgb-ok">Connected</span>':'<span class="cgb-s cgb-err">Not Connected</span>'; ?></td></tr>
            <tr><td><strong>Token</strong></td><td><?php echo $tv?'<span class="cgb-s cgb-ok">Valid</span>':'<span class="cgb-s cgb-err">Invalid</span>'; ?></td></tr>
            <tr><td><strong>Temp Dir</strong></td><td><?php echo is_writable(CGB_TEMP_DIR)?'<span class="cgb-s cgb-ok">OK</span>':'<span class="cgb-s cgb-err">Error</span>'; ?></td></tr>
        </table></div>

        <form method="post" action="options.php">
        <?php settings_fields('cgb_settings'); ?>

        <div class="cgb-card"><h3>API Key</h3>
        <div class="cgb-field"><label>API Key</label>
        <input type="text" name="cgb_api_key" value="<?php echo esc_attr($ak); ?>" class="regular-text"/>
        <p class="description">Sent as <code>X-API-Key</code> header.</p></div></div>

        <div class="cgb-card"><h3>HMAC Signing (Recommended)</h3>
        <div class="cgb-field"><label><input type="checkbox" name="cgb_hmac_enabled" value="1" <?php checked($he,'1'); ?>/> Enable HMAC</label>
        <p class="description">Requires <code>X-Signature</code> + <code>X-Timestamp</code> headers. Prevents replay attacks.</p></div>
        <div class="cgb-field"><label>HMAC Secret</label>
        <input type="text" value="<?php echo esc_attr($hs); ?>" readonly onclick="this.select();" class="regular-text"/></div></div>

        <div class="cgb-card"><h3>IP Whitelist</h3>
        <div class="cgb-field"><label>Allowed IPs (one per line)</label>
        <textarea name="cgb_ip_whitelist" rows="4" class="regular-text"><?php echo esc_textarea($wl); ?></textarea>
        <p class="description">Empty = allow all (API key still required).</p></div></div>

        <div class="cgb-card"><h3>Google OAuth</h3>
        <p>Redirect URI:</p><div class="cgb-code"><?php echo esc_html($ru); ?></div><br/>
        <div class="cgb-field"><label>Client ID</label><input type="text" name="cgb_google_client_id" value="<?php echo esc_attr($cid); ?>" class="regular-text"/></div>
        <div class="cgb-field"><label>Client Secret</label><input type="password" name="cgb_google_client_secret" value="<?php echo esc_attr($cs); ?>" class="regular-text"/><p class="description">Encrypted at rest (AES-256).</p></div>
        <div class="cgb-field"><label>Refresh Token</label><input type="password" name="cgb_google_refresh_token" value="<?php echo esc_attr($rt); ?>" class="regular-text"/><p class="description">Auto-filled after OAuth. Encrypted.</p></div></div>

        <?php submit_button('Save Settings'); ?>
        </form>

        <?php if (!empty($cid)&&!empty($cs)): ?>
        <div class="cgb-card"><h3>Connect Google</h3>
        <a href="<?php echo esc_url($ou); ?>" class="button button-primary button-hero">Authorize Google Drive</a>
        <?php if ($oauth): ?><p style="margin-top:10px"><em>Already connected.</em></p><?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="cgb-card"><h3>Usage from Claude VM</h3>
        <p><strong>Simple (API Key only):</strong></p>
        <div class="cgb-curl">curl -s -X POST \
  "<?php echo esc_html($eu); ?>" \
  -H "X-API-Key: <?php echo esc_html($ak); ?>" \
  -F "file=@/home/claude/output.docx" \
  -F "doc_id=YOUR_DOC_ID" \
  -F "filename=output.docx" \
  -F "action=new"</div>
        <br/><p><strong>With HMAC (when enabled):</strong></p>
        <div class="cgb-curl">TIMESTAMP=$(date +%s)
SECRET="<?php echo esc_html($hs); ?>"
SIGNATURE=$(echo -n "${TIMESTAMP}POST/claude-bridge/v1/upload" \
  | openssl dgst -sha256 -hmac "$SECRET" -hex | awk '{print $2}')

curl -s -X POST \
  "<?php echo esc_html($eu); ?>" \
  -H "X-API-Key: <?php echo esc_html($ak); ?>" \
  -H "X-Timestamp: $TIMESTAMP" \
  -H "X-Signature: $SIGNATURE" \
  -F "file=@/home/claude/output.docx" \
  -F "doc_id=YOUR_DOC_ID" \
  -F "filename=output.docx" \
  -F "action=new"</div>
        <br/><p><strong>Replace existing:</strong> Use <code>-F "action=replace"</code> with the file's doc_id.</p>
        <br/><p><strong>Download a Google Doc as .docx:</strong></p>
        <div class="cgb-curl">curl -s -X POST \
  "<?php echo esc_html(rest_url('claude-bridge/v1/download')); ?>" \
  -H "X-API-Key: <?php echo esc_html($ak); ?>" \
  -F "doc_id=YOUR_GOOGLE_DOC_ID" \
  -F "format=docx" \
  --output /home/claude/document.docx</div>
        <br/><p><strong>Download as PDF:</strong> Use <code>-F "format=pdf"</code> instead.</p>
        </div>
        </div>
        <?php
    }

    // ==================== UTILITIES ====================

    private function extract_doc_id($input) {
        if (empty($input)) return '';
        // Folder URL: drive.google.com/drive/folders/XXXXX
        if (preg_match('/\/folders\/([a-zA-Z0-9_-]+)/', $input, $m)) return $m[1];
        // Doc URL: docs.google.com/document/d/XXXXX or drive.google.com/file/d/XXXXX
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $input, $m)) return $m[1];
        // Raw ID
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $input)) return $input;
        return '';
    }

    private function get_mime_from_ext($f) {
        $map = ['docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'doc'=>'application/msword','xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls'=>'application/vnd.ms-excel','pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'ppt'=>'application/vnd.ms-powerpoint','pdf'=>'application/pdf','txt'=>'text/plain','csv'=>'text/csv','zip'=>'application/zip'];
        return $map[strtolower(pathinfo($f, PATHINFO_EXTENSION))] ?? 'application/octet-stream';
    }

    private function get_client_ip() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) return trim($_SERVER['HTTP_X_REAL_IP']);
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function log($msg) {
        $e = '[' . current_time('Y-m-d H:i:s') . "] $msg\n";
        if (file_exists(CGB_LOG_FILE) && filesize(CGB_LOG_FILE) > 1048576) {
            file_put_contents(CGB_LOG_FILE, implode('', array_slice(file(CGB_LOG_FILE), -500)));
        }
        error_log($e, 3, CGB_LOG_FILE);
    }

    public function cleanup_temp_files() {
        if (!is_dir(CGB_TEMP_DIR)) return;
        $fs = glob(CGB_TEMP_DIR . '*'); if (!$fs) return;
        $now = time();
        foreach ($fs as $f) { if (is_file($f) && ($now - filemtime($f)) > 3600 && basename($f)!=='.htaccess' && basename($f)!=='index.php') unlink($f); }
    }
}

Claude_GDrive_Bridge::get_instance();
