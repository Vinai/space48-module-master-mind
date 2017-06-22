<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game;

use Space48\MasterMind\Model\GuessCheckerInterface;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Space48\MasterMind\Model\MasterMindGameInterface;

/**
 * @covers \Space48\MasterMind\Game\GameRunner
 */
class GameRunnerTest extends \PHPUnit_Framework_TestCase
{
    const CHECK_RESULT = MasterMindGameInterface::KEY_CHECK_RESULT;
    const GUESS_GOUNT = MasterMindGameInterface::KEY_GUESS_COUNT;
    
    public function testPlaysUntilGuessIsCorrect()
    {
        /** @var MasterMindGameInterface|\PHPUnit_Framework_MockObject_MockObject $stubGame */
        $stubGame = $this->getMock(MasterMindGameInterface::class);
        
        /** @var GuessStrategyInterface|\PHPUnit_Framework_MockObject_MockObject $stubGuessStrategy */
        $stubGuessStrategy = $this->getMock(GuessStrategyInterface::class);
        $stubGuessStrategy->method('getGuess')->willReturnOnConsecutiveCalls(
            ['foo', 'bar'],
            ['bar', 'baz'],
            ['baz', 'qux']
        );

        $stubGame->method('playerGuesses')->willReturnOnConsecutiveCalls(
            [self::CHECK_RESULT => GuessCheckerInterface::NO_MATCH, self::GUESS_GOUNT => 1],
            [self::CHECK_RESULT => GuessCheckerInterface::NO_MATCH, self::GUESS_GOUNT => 2],
            [self::CHECK_RESULT => GuessCheckerInterface::PERFECT, self::GUESS_GOUNT => 3]
        );

        $expected = [
            GameRunner::KEY_RESULT => GuessCheckerInterface::PERFECT,
            GameRunner::KEY_COLORS => ['baz', 'qux'],
            GameRunner::KEY_GUESS_COUNT => 3
        ];
        $this->assertSame($expected, (new GameRunner($stubGame))->play($stubGuessStrategy));
    }

    public function testCallsGuessStrategyResultCallbackWithGuessResult()
    {
        /** @var MasterMindGameInterface|\PHPUnit_Framework_MockObject_MockObject $stubGame */
        $stubGame = $this->getMock(MasterMindGameInterface::class);

        /** @var GuessStrategyInterface|\PHPUnit_Framework_MockObject_MockObject $stubGuessStrategy */
        $stubGuessStrategy = $this->getMock(GuessStrategyInterface::class);
        $stubGuessStrategy->method('getGuess')->willReturn(['foo', 'bar']);

        $testGuessCount = 1;
        $stubGame->method('playerGuesses')->willReturn(
            [self::CHECK_RESULT => GuessCheckerInterface::PERFECT, self::GUESS_GOUNT => $testGuessCount]
        );
        
        $stubGuessStrategy->expects($this->once())->method('guessResult')
            ->with(GuessCheckerInterface::PERFECT, $testGuessCount);

        (new GameRunner($stubGame))->play($stubGuessStrategy);
    }
}
