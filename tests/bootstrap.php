<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Stub WP_Error
if (!class_exists('WP_Error')) {
    class WP_Error {
        public $errors = array();
        public $error_data = array();

        public function __construct($code = '', $message = '', $data = '') {
            if (empty($code)) {
                return;
            }
            $this->errors[$code][] = $message;
            if (!empty($data)) {
                $this->error_data[$code] = $data;
            }
        }
    }
}

// Stub is_wp_error
if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) {
        return ($thing instanceof WP_Error);
    }
}

// Stub wp_remote_retrieve_response_code
if (!function_exists('wp_remote_retrieve_response_code')) {
    function wp_remote_retrieve_response_code($response) {
        if (!is_array($response) || !isset($response['response']) || !is_array($response['response'])) {
            return '';
        }
        return isset($response['response']['code']) ? $response['response']['code'] : '';
    }
}

// Stub wp_remote_retrieve_body
if (!function_exists('wp_remote_retrieve_body')) {
    function wp_remote_retrieve_body($response) {
        if (!is_array($response) || !isset($response['body'])) {
            return '';
        }
        return $response['body'];
    }
}

// Load the class to test
require_once dirname(__DIR__) . '/includes/class-ceske-sluzby-json-loader.php';
