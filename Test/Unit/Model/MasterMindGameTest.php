<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Unit\Model;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GameStateInterface;
use Space48\MasterMind\Model\GuessCheckerInterface;
use Space48\MasterMind\Model\MasterMindGame;
use Space48\MasterMind\Model\MasterMindGameInterface;

/**
 * @covers \Space48\MasterMind\Model\MasterMindGame
 */
class MasterMindGameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuessCheckerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockChecker;

    /**
     * @var GameStateInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockGameState;

    /**
     * @var Colors|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockColors;

    private function createGameInstance(): MasterMindGame
    {
        return new MasterMindGame($this->mockChecker, $this->mockGameState, $this->mockColors);
    }

    protected function setUp()
    {
        $this->mockChecker = $this->getMock(GuessCheckerInterface::class);
        $this->mockGameState = $this->getMock(GameStateInterface::class);
        $this->mockColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
    }

    public function testImplementsTheMasterMindGameInterface()
    {
        $this->assertInstanceOf(MasterMindGameInterface::class, $this->createGameInstance());
    }

    public function testPicksTargetColorsIfNoneAreSet()
    {
        $this->mockGameState->method('getTargetColors')->willReturn([]);
        $this->mockColors->method('pick')->willReturn([]);
        $this->mockGameState->expects($this->once())->method('setTargetColors');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createGameInstance()->playerGuesses([]);
    }

    public function testPicksNoTargetColorsIfTheyAlreadyAreSet()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockGameState->expects($this->never())->method('setTargetColors');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createGameInstance()->playerGuesses([]);
    }

    /**
     * @param string $checkerResult
     * @dataProvider checkerResultDataProvider
     */
    public function testReturnsMapValueForGuessCheckResult(string $checkerResult)
    {
        $this->mockColors->method('pick')->willReturn([]);
        $this->mockChecker->method('check')->willReturn($checkerResult);
        $this->mockGameState->method('getTargetColors')->willReturn(['frob', 'zab']);
        
        $expected = MasterMindGameInterface::RESULT_MESSAGE_MAP[$checkerResult];
        
        $result = $this->createGameInstance()->playerGuesses(['foo', 'bar']);
        $this->assertSame($expected, $result[MasterMindGameInterface::KEY_CHECK_RESULT]);
    }

    public function checkerResultDataProvider()
    {
        return [
            [GuessCheckerInterface::PERFECT],
            [GuessCheckerInterface::ONE_CORRECT_POSITION],
            [GuessCheckerInterface::ONE_WRONG_POSITION],
            [GuessCheckerInterface::NO_MATCH],
        ];
    }

    public function testIncludesTheGuessCountInTheResult()
    {
        $testGuessCount = 42;
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->mockGameState->method('getTargetColors')->willReturn(['frob', 'zab']);
        $this->mockGameState->method('getGuessCount')->willReturn($testGuessCount);
        
        $result = $this->createGameInstance()->playerGuesses(['foo', 'bar']);
        $this->assertSame($testGuessCount, $result[MasterMindGameInterface::KEY_GUESS_COUNT]);
    }

    public function testIncrementsGuessCount()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->mockGameState->expects($this->once())->method('incrementGuessCount');
        $this->createGameInstance()->playerGuesses([]);
    }

    public function testResetsGameIfGuessIsSuccessful()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockGameState->expects($this->once())->method('reset');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::PERFECT);
        $this->createGameInstance()->playerGuesses([]);
    }

    public function testDelegatesPickingNewColorsToColorsInstance()
    {
        $this->mockGameState->method('getTargetColors')->willReturn([]);
        $this->mockColors->expects($this->once())->method('pick')->willReturn(['foo', 'bar']);
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createGameInstance()->playerGuesses([]);
    }
}
