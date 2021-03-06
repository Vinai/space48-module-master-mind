<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model\State;

use Magento\Framework\Session\SessionManager;
use Magento\TestFramework\ObjectManager;
use Space48\MasterMind\Model\GameStateInterface;
use Space48\MasterMind\Model\State\SessionGameState;

/**
 * @covers \Space48\MasterMind\Model\State\SessionGameState
 * @magentoAppIsolation enabled
 * @magentoAppArea frontend
 */
class SessionGameStateTest extends \PHPUnit_Framework_TestCase
{
    private function createGameState(): GameStateInterface
    {
        return ObjectManager::getInstance()->create(GameStateInterface::class);
    }

    public function testExtendsSessionManagement()
    {
        $this->assertInstanceOf(SessionManager::class, $this->createGameState());
    }

    public function testStartsWithGuessCountOfZero()
    {
        $this->assertSame(0, $this->createGameState()->getGuessCount());
    }

    public function testIncrementsTheGuessCount()
    {
        $this->createGameState()->incrementGuessCount();
        $this->createGameState()->incrementGuessCount();
        $this->assertSame(2, $this->createGameState()->getGuessCount());
    }

    public function testReturnsTheSetTargetColorTuple()
    {
        $testColors = ['red', 'green'];
        $this->createGameState()->setTargetColors($testColors);
        $this->assertSame($testColors, $this->createGameState()->getTargetColors());
    }

    public function testResetsTheGuessCountAndTargetColors()
    {
        $this->createGameState()->setTargetColors(['red', 'green']);
        $this->createGameState()->incrementGuessCount();

        $this->assertSame(['red', 'green'], $this->createGameState()->getTargetColors());
        $this->assertSame(1, $this->createGameState()->getGuessCount());
        
        $this->createGameState()->reset();
        
        $this->assertSame([], $this->createGameState()->getTargetColors());
        $this->assertSame(0, $this->createGameState()->getGuessCount());
    }
}
