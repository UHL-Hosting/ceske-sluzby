<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/SortUTF.php';

class SortUTFTest extends TestCase
{
    public function testSortReturnsFalseForNonArray()
    {
        $this->assertFalse(SortUTF::sort('not-an-array'));
        $this->assertFalse(SortUTF::sort(123));
        $this->assertFalse(SortUTF::sort(null));
    }

    public function testSortStandardAlphabet()
    {
        $input = ['b', 'c', 'a', 'd'];
        $expected = ['a', 'b', 'c', 'd'];
        $this->assertSame($expected, SortUTF::sort($input));
    }

    public function testSortStandardAlphabetCaseSensitive()
    {
        $input = ['B', 'b', 'A', 'a'];
        $expected = ['a', 'A', 'b', 'B'];
        $this->assertSame($expected, SortUTF::sort($input));
    }

    public function testSortCzechAlphabetStandard()
    {
        $input = ['červená', 'cesta', 'ďábel', 'dveře'];
        $expected = ['cesta', 'červená', 'dveře', 'ďábel'];
        $this->assertSame($expected, SortUTF::sort($input));
    }

    public function testSortCzechAlphabetWithoutChHandling()
    {
        // Without useCh, 'ch' is treated as 'c' then 'h'
        $input = ['chata', 'cesta', 'cukr', 'drak'];
        $expected = ['cesta', 'chata', 'cukr', 'drak']; // Since 'c' < 'd', chata is before drak, but after cesta since 'e' < 'h'

        $this->assertSame($expected, SortUTF::sort($input));
    }

    public function testSortCzechAlphabetWithChHandling()
    {
        // With useCh = true, 'ch' is sorted after 'h'
        $input = ['chata', 'cukr', 'hromada', 'cesta'];
        // Default sort (cesta, chata, cukr, hromada) -> With CH: cesta, cukr, hromada, chata
        $expected = ['cesta', 'cukr', 'hromada', 'chata'];

        $this->assertSame($expected, SortUTF::sort($input, true));
    }

    public function testSortCzechAlphabetWithChHandlingMixedCase()
    {
        $input = ['Chobotnice', 'cesta', 'hrnec', 'CHleba', 'cukr'];
        $expected = ['cesta', 'cukr', 'hrnec', 'Chobotnice', 'CHleba'];

        $this->assertSame($expected, SortUTF::sort($input, true));
    }

    public function testSortEmptyArray()
    {
        $this->assertSame([], SortUTF::sort([]));
    }

    public function testSortCustomAlphabet()
    {
        $customChars = 'z,y,x,a,b,c';
        $input = ['a', 'c', 'z', 'b'];
        $expected = ['z', 'a', 'b', 'c']; // According to custom chars order

        $this->assertSame($expected, SortUTF::sort($input, false, $customChars));
    }

    public function testSortNumbers()
    {
        $input = ['10', '2', '1'];
        $expected = ['1', '10', '2']; // It sorts by characters (1 < 2)

        $this->assertSame($expected, SortUTF::sort($input));
    }
}
