<?php

use PHPUnit\Framework\TestCase;

class CeskeSluzbyJsonLoaderVerifyTest extends TestCase
{
    private $loader;

    protected function setUp(): void
    {
        $this->loader = new Ceske_Sluzby_Json_Loader();
    }

    public function testVerifyThrowsExceptionWhenGivenWpError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Ceske_Sluzby_Json_Loader Failed: Nepodařilo se získat obsah z URL adresy.');

        $errorResult = new WP_Error('http_request_failed', 'A valid URL was not provided.');
        $this->loader->verify($errorResult);
    }

    public function testVerifyThrowsExceptionOnNon200ResponseCode()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Ceske_Sluzby_Json_Loader Failed: Neplatná HTTP reakce ze serveru - 404');

        $response = array(
            'response' => array(
                'code' => 404,
                'message' => 'Not Found'
            )
        );

        $this->loader->verify($response);
    }

    public function testVerifyThrowsExceptionOnEmptyBody()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Ceske_Sluzby_Json_Loader Failed: Json je prázdný.');

        $response = array(
            'response' => array(
                'code' => 200,
                'message' => 'OK'
            ),
            'body' => ''
        );

        $this->loader->verify($response);
    }

    public function testVerifyReturnsBodyOnSuccess()
    {
        $expectedBody = '{"status":"ok"}';
        $response = array(
            'response' => array(
                'code' => 200,
                'message' => 'OK'
            ),
            'body' => $expectedBody
        );

        $result = $this->loader->verify($response);
        $this->assertSame($expectedBody, $result);
    }
}
