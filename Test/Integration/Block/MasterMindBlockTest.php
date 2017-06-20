<?php

declare(strict_types=1);

namespace Space48\MasterMind\Block;

use Magento\TestFramework\ObjectManager;
use Space48\MasterMind\Block\MasterMindBlock;

/**
 * @covers \Space48\MasterMind\Block\MasterMindBlock
 */
class MasterMindBlockTest extends \PHPUnit_Framework_TestCase
{
    private function createBlock(): MasterMindBlock
    {
        return ObjectManager::getInstance()->create(MasterMindBlock::class);
    }

    public function testIsATemplateBlock()
    {
        $this->assertInstanceOf(\Magento\Framework\View\Element\Template::class, $this->createBlock());
    }

    public function testReturnsTheColorsAsJson()
    {
        $colorsJson = $this->createBlock()->getColorsJson();
        $colors = json_decode($colorsJson, true);
        $this->assertContains('red', $colors);
        $this->assertContains('green', $colors);
        $this->assertContains('yellow', $colors);
        $this->assertContains('blue', $colors);
        $this->assertContains('magenta', $colors);
        $this->assertContains('cyan', $colors);
    }
}
