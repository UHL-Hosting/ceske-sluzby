<?php

use PHPUnit\Framework\TestCase;

class SledovaniZasilekTest extends TestCase {

    public function test_dostupni_dopravci_vraci_pole() {
        $vysledek = ceske_sluzby_sledovani_zasilek_dostupni_dopravci('CZ');
        $this->assertIsArray($vysledek);
        $this->assertNotEmpty($vysledek);
        $this->assertArrayHasKey('CPOST', $vysledek);
        $this->assertArrayHasKey('nazev', $vysledek['CPOST']);
        $this->assertArrayHasKey('url', $vysledek['CPOST']);
    }

    public function test_dostupni_dopravci_ceska_republika() {
        $vysledek = ceske_sluzby_sledovani_zasilek_dostupni_dopravci('CZ');

        $this->assertEquals('https://www.postaonline.cz/cs/trackandtrace/-/zasilka/cislo?parcelNumbers=%ID%', $vysledek['CPOST']['url']);
        $this->assertEquals('https://www.zasilkovna.cz/vyhledavani/?det=%ID%', $vysledek['Zasilkovna']['url']);
        $this->assertEquals('http://www.dhl.cz/content/cz/cs/express/sledovani_zasilek.shtml?brand=DHL&AWB=%ID%', $vysledek['DHL']['url']);
    }

    public function test_dostupni_dopravci_slovensko() {
        $vysledek = ceske_sluzby_sledovani_zasilek_dostupni_dopravci('SK');

        // CPOST is CZ only, but returns its original URL because it's a string, not an array
        $this->assertEquals('https://www.postaonline.cz/cs/trackandtrace/-/zasilka/cislo?parcelNumbers=%ID%', $vysledek['CPOST']['url']);

        // Zasilkovna and DHL are arrays, should return SK URLs
        $this->assertEquals('https://www.zasielkovna.sk/vyhladavanie/?det=%ID%', $vysledek['Zasilkovna']['url']);
        $this->assertEquals('http://www.dhl.sk/content/sk/sk/express/sledovanie_zasielky.shtml?brand=DHL&AWB=%ID%', $vysledek['DHL']['url']);
    }

    public function test_dostupni_dopravci_neznama_zeme_fallback() {
        // 'DE' is not defined for Zasilkovna, it should fall back to 'CZ'
        $vysledek = ceske_sluzby_sledovani_zasilek_dostupni_dopravci('DE');

        $this->assertEquals('https://www.zasilkovna.cz/vyhledavani/?det=%ID%', $vysledek['Zasilkovna']['url']);
        $this->assertEquals('http://www.dhl.cz/content/cz/cs/express/sledovani_zasilek.shtml?brand=DHL&AWB=%ID%', $vysledek['DHL']['url']);
    }

    public function test_dostupni_dopravci_bez_parametru() {
        // Testing the default parameter value
        $vysledek = ceske_sluzby_sledovani_zasilek_dostupni_dopravci();

        $this->assertIsArray($vysledek);
        $this->assertEquals('https://www.zasilkovna.cz/vyhledavani/?det=%ID%', $vysledek['Zasilkovna']['url']);
    }
}
