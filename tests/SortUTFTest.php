<?php

use PHPUnit\Framework\TestCase;

class SortUTFTest extends TestCase
{
    public function testSplitEmptyString()
    {
        $this->assertEquals([], SortUTF::split('', false));
        $this->assertEquals([], SortUTF::split('', true));
    }

    public function testSplitOnlyC()
    {
        $this->assertEquals(['c'], SortUTF::split('c', false));
        $this->assertEquals(['c'], SortUTF::split('c', true));
        $this->assertEquals(['C'], SortUTF::split('C', false));
        $this->assertEquals(['C'], SortUTF::split('C', true));
    }

    public function testSplitOnlyH()
    {
        $this->assertEquals(['h'], SortUTF::split('h', false));
        $this->assertEquals(['h'], SortUTF::split('h', true));
        $this->assertEquals(['H'], SortUTF::split('H', false));
        $this->assertEquals(['H'], SortUTF::split('H', true));
    }

    public function testSplitCh()
    {
        // When $useCh is true, the 'c' gets modified to include the following 'h',
        // but the original 'h' still remains in its position.
        $this->assertEquals(['ch', 'h'], SortUTF::split('ch', true));
        $this->assertEquals(['Ch', 'h'], SortUTF::split('Ch', true));
        $this->assertEquals(['CH', 'H'], SortUTF::split('CH', true));

        $this->assertEquals(['a', 'ch', 'h'], SortUTF::split('ach', true));

        // When $useCh is false, they stay separate
        $this->assertEquals(['c', 'h'], SortUTF::split('ch', false));
        $this->assertEquals(['C', 'h'], SortUTF::split('Ch', false));
        $this->assertEquals(['C', 'H'], SortUTF::split('CH', false));

        $this->assertEquals(['a', 'c', 'h'], SortUTF::split('ach', false));
    }

    public function testSplitHc()
    {
        $this->assertEquals(['h', 'c'], SortUTF::split('hc', true));
        $this->assertEquals(['h', 'c'], SortUTF::split('hc', false));
    }
}
