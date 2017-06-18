<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Integration\Session;

use Magento\Framework\Session\SessionManager;
use Magento\TestFramework\ObjectManager;
use Space48\MasterMind\Session\ColorStorage;

/**
 * @covers \Space48\MasterMind\Session\ColorStorage
 */
class ColorStorageTest extends \PHPUnit_Framework_TestCase
{
    private function createSession(): ColorStorage
    {
        return ObjectManager::getInstance()->create(ColorStorage::class);
    }

    public function testExtendsSessionManagement()
    {
        $this->assertInstanceOf(SessionManager::class, $this->createSession());
    }

    public function testReturnsTheSetColorTuple()
    {
        $testColors = ['red', 'green'];
        $this->createSession()->setColors($testColors);
        $this->assertSame($testColors, $this->createSession()->getColors());
    }

    public function testUnsetsTheSetColorTuple()
    {
        $testColors = ['red', 'green'];
        $this->createSession()->setColors($testColors);
        $this->createSession()->unsetColors();
        $this->assertSame([], $this->createSession()->getColors());
    }
}
