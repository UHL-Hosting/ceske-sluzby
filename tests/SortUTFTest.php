<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/SortUTF.php';

class SortUTFTest extends TestCase
{
    public function testSplitEmptyString()
    {
        $this->assertEquals([], SortUTF::split('', true));
        $this->assertEquals([], SortUTF::split('', false));
    }

    public function testSplitSingleCharC()
    {
        $this->assertEquals(['C'], SortUTF::split('C', true));
        $this->assertEquals(['c'], SortUTF::split('c', true));
        $this->assertEquals(['C'], SortUTF::split('C', false));
        $this->assertEquals(['c'], SortUTF::split('c', false));
    }

    public function testSplitSingleCharH()
    {
        $this->assertEquals(['H'], SortUTF::split('H', true));
        $this->assertEquals(['h'], SortUTF::split('h', true));
        $this->assertEquals(['H'], SortUTF::split('H', false));
        $this->assertEquals(['h'], SortUTF::split('h', false));
    }

    public function testSplitChWithUseChEnabled()
    {
        // Notice the expected behavior of current implementation.
        // Array indexes might be a bit weird, since if "h" follows "c" it merges, but keeps the original index logic
        $result = SortUTF::split('Ch', true);
        // The implementation does:
        // $array[0] = 'C';
        // $array[1] = 'h'; then $array[0] = 'C' . 'h' => 'Ch'
        $this->assertEquals([0 => 'Ch', 1 => 'h'], $result);

        $result2 = SortUTF::split('ch', true);
        $this->assertEquals([0 => 'ch', 1 => 'h'], $result2);

        $result3 = SortUTF::split('CH', true);
        $this->assertEquals([0 => 'CH', 1 => 'H'], $result3);
    }

    public function testSplitChWithUseChDisabled()
    {
        $this->assertEquals(['C', 'h'], SortUTF::split('Ch', false));
        $this->assertEquals(['c', 'h'], SortUTF::split('ch', false));
        $this->assertEquals(['C', 'H'], SortUTF::split('CH', false));
    }

    public function testSplitNormalString()
    {
        $this->assertEquals(['A', 'b', 'c', 'd'], SortUTF::split('Abcd', false));
    }
}
