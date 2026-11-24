<?php
namespace NinjaFileManager\File_manager;

defined('ABSPATH') || exit;

/**
 * elFinder session wrapper backed by a per-login transient instead of PHP sessions,
 * so the CSRF token no longer depends on the PHPSESSID cookie round-trip.
 * Logged-out requests get no storage (nothing persists, so CSRF validation fails).
 */
class WpSession implements \elFinderSessionInterface
{
    public function start()
    {
        return $this;
    }

    public function close()
    {
        return $this;
    }

    public function get($key, $empty = null)
    {
        $data = $this->load();
        return array_key_exists($key, $data) && $data[$key] !== null ? $data[$key] : $empty;
    }

    public function set($key, $data)
    {
        $all = $this->load();
        $all[$key] = $data;
        $this->save($all);
        return $this;
    }

    public function remove($key)
    {
        $all = $this->load();
        unset($all[$key]);
        $this->save($all);
        return $this;
    }

    private function transientKey()
    {
        $uid = get_current_user_id();
        if (!$uid) {
            return '';
        }
        return 'njt_fs_sess_' . md5($uid . '|' . wp_get_session_token());
    }

    private function load()
    {
        $k = $this->transientKey();
        $data = $k ? get_transient($k) : false;
        return is_array($data) ? $data : array();
    }

    private function save(array $data)
    {
        $k = $this->transientKey();
        if ($k) {
            set_transient($k, $data, DAY_IN_SECONDS);
        }
    }
}
