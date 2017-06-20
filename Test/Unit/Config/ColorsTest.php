<?php

declare(strict_types=1);

namespace Space48\MasterMind\Config;

/**
 * @covers \Space48\MasterMind\Config\Colors
 */
class ColorsTest extends \PHPUnit_Framework_TestCase
{
    public function testIsJsonSerializable()
    {
        $this->assertInstanceOf(\JsonSerializable::class, new Colors(['foo']));
    }

    public function testThrowsExceptionIfColorsArrayIsEmpty()
    {
        $this->setExpectedException(\RangeException::class);
        new Colors([]);
    }
    
    public function testReturnsColorsAsJsonArray()
    {
        $testColors = [
            'a' => 'red',
            'b' => 'blue',
            'c' => 'green',
        ];
        $json = json_encode(new Colors($testColors));
        $this->assertSame(array_values($testColors), json_decode($json, true));
    }

    /**
     * @param int $numberOfColorsToPick
     * @dataProvider numberOfColorsToPickDataProvider
     */
    public function testPicksTheGivenNumberOfColors($numberOfColorsToPick)
    {
        $testColors = [
            'a' => 'red',
            'b' => 'blue',
            'c' => 'green',
        ];
        $pick = (new Colors($testColors))->pick($numberOfColorsToPick);
        $this->assertInternalType('array', $pick);
        $this->assertCount($numberOfColorsToPick, $pick);
    }

    public function numberOfColorsToPickDataProvider()
    {
        return [[0], [1], [2], [3], [4]];
    }

    public function testReturnsTheInjectedColors()
    {
        $testColors = [
            'a' => 'red',
            'b' => 'blue',
            'c' => 'green',
        ];
        $colors = new Colors($testColors);
        $this->assertSame(array_values($testColors), $colors->asArray());
    }
}
