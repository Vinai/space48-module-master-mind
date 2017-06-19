<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Integration\Session;

use Magento\Framework\Session\SessionManager;
use Magento\TestFramework\ObjectManager;
use Space48\MasterMind\Session\GameState;

/**
 * @covers \Space48\MasterMind\Session\GameState
 * @magentoAppIsolation enabled
 */
class GameStateTest extends \PHPUnit_Framework_TestCase
{
    private function createGameState(): GameState
    {
        return ObjectManager::getInstance()->create(GameState::class);
    }

    public function testExtendsSessionManagement()
    {
        $this->assertInstanceOf(SessionManager::class, $this->createGameState());
    }

    public function testStarstWithGuessCountOfZero()
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
