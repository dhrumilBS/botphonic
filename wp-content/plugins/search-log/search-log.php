<?php
/*
Plugin Name: Search Log
Description: Logs user searches in WordPress, including search term, IP address, location, and country.
Version: 1.0.0
Author: wordpressdev bigscal
Author URI: https://bigscal.com/
Text Domain: search-log
Domain Path: /languages
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// 1. Create table on activation
function my_create_search_logs_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'search_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        search_term text NOT NULL,
        search_slug varchar(255) NOT NULL DEFAULT '',
        ip_address varchar(50) NOT NULL,
        user_id bigint(20) DEFAULT 0 NOT NULL,
        user_name varchar(255) NOT NULL DEFAULT '',
        country varchar(100) NOT NULL DEFAULT '',
        region varchar(100) NOT NULL DEFAULT '',
        city varchar(100) NOT NULL DEFAULT '',
        lat decimal(10,8) DEFAULT NULL,
        lon decimal(11,8) DEFAULT NULL,
        device_type varchar(20) NOT NULL DEFAULT '',
        browser varchar(100) NOT NULL DEFAULT '',
        os varchar(100) NOT NULL DEFAULT '',
        referer text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'my_create_search_logs_table');

// 2. Add admin menu for viewing logs

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}



// 2. Add admin menu for viewing logs
function my_register_search_logs_page()
{
    add_menu_page(
        'Search Logs',
        'Search Logs',
        'manage_options',
        'search-logs',
        'my_display_search_logs',
        'dashicons-search',
        25
    );
}
add_action('admin_menu', 'my_register_search_logs_page');


// 3. Display logs in admin page
function my_display_search_logs()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'search_logs';

    // Optional: pagination
    $per_page = 20;
    $paged    = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset   = ($paged - 1) * $per_page;

    // Total count
    $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Logs
    $logs = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset)
    );

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Search Logs</h1>';
    echo '<hr class="wp-header-end">';

    // Pagination
    $num_pages = ceil($total / $per_page);
    if ($num_pages > 1) {
        $page_links = paginate_links([
            'base'      => add_query_arg('paged', '%#%'),
            'format'    => '',
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »'),
            'total'     => $num_pages,
            'current'   => $paged,
        ]);

        if ($page_links) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . $page_links . '</div></div>';
        }
    }

    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>ID</th>';
    echo '<th>Search Term</th>';
    echo '<th>User ID</th>';
    echo '<th>User Name</th>';
    echo '<th>IP Address</th>';
    echo '<th>Country</th>';
    echo '<th>City</th>';
    echo '<th>Date</th>';
    echo '</tr></thead><tbody>';

    if ($logs) {
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . esc_html($log->id) . '</td>';
            echo '<td>' . esc_html($log->search_term) . '</td>';
            echo '<td>' . esc_html($log->user_id) . '</td>';
            echo '<td>' . esc_html($log->user_name) . '</td>';
            echo '<td>' . esc_html($log->ip_address) . '</td>';
            echo '<td>' . esc_html($log->country) . '</td>';
            echo '<td>' . esc_html($log->city) . '</td>';
            echo '<td>' . esc_html($log->created_at) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No search logs found.</td></tr>';
    }

    echo '</tbody></table>';

    // Pagination
    $num_pages = ceil($total / $per_page);
    if ($num_pages > 1) {
        $page_links = paginate_links([
            'base'      => add_query_arg('paged', '%#%'),
            'format'    => '',
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »'),
            'total'     => $num_pages,
            'current'   => $paged,
        ]);

        if ($page_links) {
            echo '<div class="tablenav"><div class="tablenav-pages">' . $page_links . '</div></div>';
        }
    }

    echo '</div>';
}

// 4. Log search data
function my_log_search_data()
{
    if (is_search() && isset($_GET['s'])) {
        global $wpdb;

        $search_term = sanitize_text_field($_GET['s']);
        $search_slug = sanitize_title($search_term);
        $ip          = $_SERVER['REMOTE_ADDR'];
        $user_id     = get_current_user_id();
        $user_name   = $user_id ? wp_get_current_user()->display_name : '';

        // Device & Browser Detection
        $user_agent  = $_SERVER['HTTP_USER_AGENT'];
        $device_type = wp_is_mobile() ? 'Mobile' : 'Desktop';
        $browser     = 'Unknown';
        $os          = 'Unknown';

        if (preg_match('/Windows/i', $user_agent)) $os = 'Windows';
        elseif (preg_match('/Mac/i', $user_agent)) $os = 'MacOS';
        elseif (preg_match('/Linux/i', $user_agent)) $os = 'Linux';
        elseif (preg_match('/Android/i', $user_agent)) $os = 'Android';
        elseif (preg_match('/iPhone|iPad/i', $user_agent)) $os = 'iOS';

        if (preg_match('/Chrome/i', $user_agent)) $browser = 'Chrome';
        elseif (preg_match('/Safari/i', $user_agent) && !preg_match('/Chrome/i', $user_agent)) $browser = 'Safari';
        elseif (preg_match('/Firefox/i', $user_agent)) $browser = 'Firefox';
        elseif (preg_match('/Edge/i', $user_agent)) $browser = 'Edge';
        elseif (preg_match('/MSIE|Trident/i', $user_agent)) $browser = 'Internet Explorer';

        // Get location
        $country = $region = $city = '';
        $lat = $lon = null;

        $location_data = wp_remote_get("http://ip-api.com/json/{$ip}");
        if (!is_wp_error($location_data)) {
            $body = json_decode(wp_remote_retrieve_body($location_data));
            if ($body && $body->status === 'success') {
                $country = sanitize_text_field($body->country);
                $region  = sanitize_text_field($body->regionName);
                $city    = sanitize_text_field($body->city);
                $lat     = floatval($body->lat);
                $lon     = floatval($body->lon);
            }
        }

        // Save
        $wpdb->insert(
            "{$wpdb->prefix}search_logs",
            array(
                'search_term' => $search_term,
                'search_slug' => $search_slug,
                'ip_address'  => $ip,
                'user_id'     => $user_id,
                'user_name'   => $user_name,
                'country'     => $country,
                'region'      => $region,
                'city'        => $city,
                'lat'         => $lat,
                'lon'         => $lon,
                'device_type' => $device_type,
                'browser'     => $browser,
                'os'          => $os,
                'referer'     => isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '',
                'created_at'  => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%f', '%f', '%s', '%s', '%s', '%s', '%s', '%s')
        );
    }
}
add_action('template_redirect', 'my_log_search_data');
