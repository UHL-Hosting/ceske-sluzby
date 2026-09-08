<?php
// Simple bootstrap for PHPUnit tests
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/includes/class-ceske-sluzby-json-loader.php';

// Stub WP functions if needed by the class
if (!function_exists('get_transient')) {
    function get_transient($transient) { return false; }
}
if (!function_exists('set_transient')) {
    function set_transient($transient, $value, $expiration = 0) { return true; }
}
if (!function_exists('wp_remote_get')) {
    function wp_remote_get($url, $args = array()) { return array(); }
}
if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) { return false; }
}
if (!function_exists('wp_remote_retrieve_response_code')) {
    function wp_remote_retrieve_response_code($response) { return 200; }
}
if (!function_exists('wp_remote_retrieve_body')) {
    function wp_remote_retrieve_body($response) { return ''; }
}
if (!function_exists('home_url')) {
    function home_url($path = '') { return 'http://localhost' . $path; }
}
if (!defined('CS_VERSION')) {
    define('CS_VERSION', 'test');
}
if (!function_exists('WC')) {
    function WC() {
        return new class {
            public $shipping;
            public function __construct() {
                $this->shipping = new class {
                    public function load_shipping_methods() {
                        return array();
                    }
                };
            }
        };
    }
}
