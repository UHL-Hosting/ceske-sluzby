<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/src/SortUTF.php';

class SortUTFTest extends TestCase
{
    public function testSortReturnsFalseForNonArray()
    {
        $this->assertFalse(SortUTF::sort('not an array'));
        $this->assertFalse(SortUTF::sort(123));
        $this->assertFalse(SortUTF::sort(null));
        $this->assertFalse(SortUTF::sort(new stdClass()));
    }

    public function testSortEmptyArray()
    {
        $this->assertSame([], SortUTF::sort([]));
    }

    public function testSortStandardCzechCharacters()
    {
        $input = ['z', 'á', 'c', 'č', 'a', 'b'];
        $expected = ['a', 'á', 'b', 'c', 'č', 'z'];
        $this->assertSame($expected, SortUTF::sort($input));
    }

    public function testSortWithChCharacterAndUseChTrue()
    {
        $input = ['d', 'ch', 'c', 'h', 'i'];
        $expected = ['c', 'd', 'h', 'ch', 'i'];
        $this->assertSame($expected, SortUTF::sort($input, true));
    }

    public function testSortWithChCharacterAndUseChFalse()
    {
        $input = ['d', 'ch', 'c', 'h', 'i'];
        // If useCh is false, 'ch' is treated as 'c' then 'h'.
        // 'c' comes before 'd', 'h' comes after 'd'.
        // 'ch' would be sorted based on 'c' then 'h'.
        $expected = ['c', 'ch', 'd', 'h', 'i'];
        $this->assertSame($expected, SortUTF::sort($input, false));
    }

    public function testSortWithChInWordAndUseChTrue()
    {
        $input = ['ahoj', 'chata', 'cesta', 'chyba'];
        $expected = ['ahoj', 'cesta', 'chata', 'chyba'];
        $this->assertSame($expected, SortUTF::sort($input, true));
    }
}
