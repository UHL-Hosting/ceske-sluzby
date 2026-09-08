<?php

// Define test environment flag
define( 'CS_TESTS', true );

// Load the class under test
require_once dirname( __DIR__ ) . '/includes/class-ceske-sluzby-json-loader.php';

// Stub WP_Error
class WP_Error {
    public $errors = array();
    public $error_data = array();

    public function __construct( $code = '', $message = '', $data = '' ) {
        if ( empty( $code ) ) {
            return;
        }

        $this->errors[ $code ][] = $message;

        if ( ! empty( $data ) ) {
            $this->error_data[ $code ] = $data;
        }
    }
}

// Stub WordPress functions used in the class
if ( ! function_exists( 'is_wp_error' ) ) {
    function is_wp_error( $thing ) {
        return ( $thing instanceof WP_Error );
    }
}

// For verify(), we will pass arrays as mocked wp_remote_get results.
if ( ! function_exists( 'wp_remote_retrieve_response_code' ) ) {
    function wp_remote_retrieve_response_code( $response ) {
        if ( ! is_array( $response ) || ! isset( $response['response'] ) || ! is_array( $response['response'] ) ) {
            return '';
        }
        return $response['response']['code'];
    }
}

if ( ! function_exists( 'wp_remote_retrieve_body' ) ) {
    function wp_remote_retrieve_body( $response ) {
        if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
            return '';
        }
        return $response['body'];
    }
}

// Additional functions needed by other methods in the class, just in case they are parsed/loaded
if ( ! function_exists( 'get_transient' ) ) {
    function get_transient( $transient ) { return false; }
}

if ( ! function_exists( 'set_transient' ) ) {
    function set_transient( $transient, $value, $expiration ) { return true; }
}

if ( ! function_exists( 'wp_remote_get' ) ) {
    function wp_remote_get( $url, $args = array() ) { return array(); }
}

if ( ! function_exists( 'home_url' ) ) {
    function home_url( $path = '', $scheme = null ) { return 'http://localhost'; }
}

if ( ! defined( 'CS_VERSION' ) ) {
    define( 'CS_VERSION', '1.0.0' );
}

if ( ! function_exists( 'WC' ) ) {
    function WC() {
        $wc = new stdClass();
        $wc->shipping = new stdClass();
        $wc->shipping->load_shipping_methods = function() { return array(); };
        return $wc;
    }
}

// We also need HOUR_IN_SECONDS defined
if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
    define( 'HOUR_IN_SECONDS', 3600 );
}
