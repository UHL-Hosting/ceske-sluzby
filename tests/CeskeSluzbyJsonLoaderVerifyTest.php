<?php

use PHPUnit\Framework\TestCase;

class CeskeSluzbyJsonLoaderVerifyTest extends TestCase {

    /**
     * @var Ceske_Sluzby_Json_Loader
     */
    private $loader;

    protected function setUp(): void {
        parent::setUp();
        $this->loader = new Ceske_Sluzby_Json_Loader();
    }

    public function test_verify_throws_exception_on_wp_error() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( 'Ceske_Sluzby_Json_Loader Failed: Nepodařilo se získat obsah z URL adresy.' );

        $result = new WP_Error( 'http_request_failed', 'A valid URL was not provided.' );
        $this->loader->verify( $result );
    }

    public function test_verify_throws_exception_on_non_200_response_code() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( 'Ceske_Sluzby_Json_Loader Failed: Neplatná HTTP reakce ze serveru - 404' );

        $result = array(
            'response' => array(
                'code' => 404,
            ),
        );
        $this->loader->verify( $result );
    }

    public function test_verify_throws_exception_on_empty_body() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( 'Ceske_Sluzby_Json_Loader Failed: Json je prázdný.' );

        $result = array(
            'response' => array(
                'code' => 200,
            ),
            'body' => '',
        );
        $this->loader->verify( $result );
    }

    public function test_verify_returns_body_on_success() {
        $json_string = '{"key":"value"}';
        $result = array(
            'response' => array(
                'code' => 200,
            ),
            'body' => $json_string,
        );

        $body = $this->loader->verify( $result );
        $this->assertEquals( $json_string, $body );
    }
}
