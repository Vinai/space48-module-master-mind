<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model\State;

use Space48\MasterMind\Model\GameStateInterface;

/**
 * @covers \Space48\MasterMind\Model\State\InMemoryGameState
 */
class InMemoryGameStateTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsGameStateInterface()
    {
        $this->assertInstanceOf(GameStateInterface::class, new InMemoryGameState());
    }

    public function testReturnsTheSetTargetColors()
    {
        $state = new InMemoryGameState();
        $state->setTargetColors(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], $state->getTargetColors());
    }

    public function testGuessCountStartsAtZero()
    {
        $this->assertSame(0, (new InMemoryGameState())->getGuessCount());
    }

    public function testIncrementsTheGuessCount()
    {
        $state = new InMemoryGameState();
        $state->incrementGuessCount();
        $state->incrementGuessCount();
        $this->assertSame(2, $state->getGuessCount());
    }

    public function testResetsTheTargetColorsAndTheGuessCount()
    {
        $state = new InMemoryGameState();
        $state->incrementGuessCount();
        $state->setTargetColors(['bar', 'baz']);
        $state->reset();
        $this->assertSame([], $state->getTargetColors());
        $this->assertSame(0, $state->getGuessCount());
    }
}
