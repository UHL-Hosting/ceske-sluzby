<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SortUTFTest extends TestCase
{
    #[DataProvider('sortDataProvider')]
    public function testSort($input, $expected, $useCh)
    {
        $result = SortUTF::sort($input, $useCh);
        $this->assertEquals($expected, $result);
    }

    public static function sortDataProvider()
    {
        return [
            'basic_sort' => [
                ['b', 'a', 'c'],
                ['a', 'b', 'c'],
                false
            ],
            'czech_chars' => [
                ['č', 'c', 'ď', 'd'],
                ['c', 'č', 'd', 'ď'],
                false
            ],
            'with_ch_handling_true' => [
                ['h', 'ch', 'c'],
                ['c', 'h', 'ch'],
                true
            ],
            'with_ch_handling_false' => [
                ['h', 'ch', 'c'],
                ['c', 'ch', 'h'], // c, then c+h -> ch, then h
                false
            ],
            'ch_case_insensitivity_true' => [
                ['Ch', 'CH', 'h', 'c'],
                ['c', 'h', 'Ch', 'CH'],
                true
            ],
            'words_with_ch' => [
                ['chodba', 'husa', 'cesta'],
                ['cesta', 'husa', 'chodba'],
                true
            ],
            'words_without_ch_handling' => [
                ['chodba', 'husa', 'cesta'],
                ['cesta', 'chodba', 'husa'], // c comes before h
                false
            ],
            'mixed_case_czech' => [
                ['Z', 'Ž', 'z', 'ž', 'a', 'A', 'á', 'Á'],
                ['a', 'A', 'á', 'Á', 'z', 'Z', 'ž', 'Ž'],
                true
            ]
        ];
    }

    public function testSortInvalidInput()
    {
        $this->assertFalse(SortUTF::sort('not an array'));
        $this->assertFalse(SortUTF::sort(123));
        $this->assertFalse(SortUTF::sort(null));
    }

    #[DataProvider('splitDataProvider')]
    public function testSplit($string, $useCh, $expected)
    {
        $result = SortUTF::split($string, $useCh);
        $this->assertEquals($expected, $result);
    }

    public static function splitDataProvider()
    {
        return [
            'normal_split' => [
                'hello',
                false,
                ['h', 'e', 'l', 'l', 'o']
            ],
            'split_with_ch_true' => [
                'chodba',
                true,
                ['ch', 'h', 'o', 'd', 'b', 'a']
            ],
            'split_with_ch_false' => [
                'chodba',
                false,
                ['c', 'h', 'o', 'd', 'b', 'a']
            ],
            'split_with_CH' => [
                'CHodba',
                true,
                ['CH', 'H', 'o', 'd', 'b', 'a']
            ],
            'split_with_ch_at_end' => [
                'mach',
                true,
                ['m', 'a', 'ch', 'h']
            ]
        ];
    }

    public function testGiveKey()
    {
        $alphabet = explode(',', 'a,b,c,ch,d');

        // Exists in alphabet
        $this->assertEquals(0, SortUTF::giveKey('a', $alphabet));
        $this->assertEquals(3, SortUTF::giveKey('ch', $alphabet));

        // The implementation in SortUTF::giveKey actually returns `false`
        // if `array_search` doesn't find the character, because it strictly
        // checks for `=== NULL` (which PHP's array_search never returns, it returns `false`).
        $this->assertFalse(SortUTF::giveKey('z', $alphabet));
    }

    public function testCompareArray()
    {
        $alphabet = explode(',', 'a,b,c,d,e');

        // a < b => bigger should be false
        $this->assertFalse(SortUTF::compareArray(['a'], ['b'], $alphabet));

        // b > a => bigger should be true
        $this->assertTrue(SortUTF::compareArray(['b'], ['a'], $alphabet));

        // ab > a => bigger should be true
        $this->assertTrue(SortUTF::compareArray(['a', 'b'], ['a'], $alphabet));

        // a < ab => bigger should be false
        $this->assertFalse(SortUTF::compareArray(['a'], ['a', 'b'], $alphabet));
    }
}
