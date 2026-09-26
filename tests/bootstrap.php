<?php

// Mock WordPress functions
if (!function_exists('is_admin')) {
    function is_admin() {
        return true;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return $default;
    }
}

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return $text;
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text) {
        return $text;
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url) {
        return $url;
    }
}

if (!function_exists('ceske_sluzby_is_order_admin_screen')) {
    function ceske_sluzby_is_order_admin_screen($screen = null) {
        return true;
    }
}

if (!function_exists('ceske_sluzby_get_order_screen_ids')) {
    function ceske_sluzby_get_order_screen_ids() {
        return array();
    }
}

// Load the file to test
require_once __DIR__ . '/../includes/class-ceske-sluzby-sledovani-zasilek.php';
