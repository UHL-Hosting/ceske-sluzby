<?php

use PHPUnit\Framework\TestCase;

class CeskeSluzbyJsonLoaderTest extends TestCase {
    private $loader;

    protected function setUp(): void {
        parent::setUp();
        $this->loader = new Ceske_Sluzby_Json_Loader();
    }

    public function test_parse_valid_json() {
        $json_string = '{"status":"success","data":{"id":123,"name":"Test Branch"}}';
        $result = $this->loader->parse($json_string);

        $this->assertIsObject($result);
        $this->assertEquals('success', $result->status);
        $this->assertEquals(123, $result->data->id);
        $this->assertEquals('Test Branch', $result->data->name);
    }

    public function test_parse_invalid_json_throws_exception() {
        $invalid_json_string = '{"status":"success","data":{"id":123,"name":"Test Branch"}'; // Missing closing brace

        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Ceske_Sluzby_Json_Loader Failed: Neplatný Json získaný ze serveru - \d+/');

        $this->loader->parse($invalid_json_string);
    }
}
