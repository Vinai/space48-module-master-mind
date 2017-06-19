<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Unit\Model;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GameStateInterface;
use Space48\MasterMind\Model\GuessCheckerInterface;
use Space48\MasterMind\Model\MasterMind;
use Space48\MasterMind\Model\MasterMindInterface;

/**
 * @covers \Space48\MasterMind\Model\MasterMind
 */
class MasterMindTest extends \PHPUnit_Framework_TestCase
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

    private function createMasterMindInstance(): MasterMind
    {
        return new MasterMind($this->mockChecker, $this->mockGameState, $this->mockColors);
    }

    protected function setUp()
    {
        $this->mockChecker = $this->getMock(GuessCheckerInterface::class);
        $this->mockGameState = $this->getMock(GameStateInterface::class);
        $this->mockColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
    }

    public function testImplementsTheMasterMindInterface()
    {
        $this->assertInstanceOf(MasterMindInterface::class, $this->createMasterMindInstance());
    }

    public function testPicksTargetColorsIfNoneAreSet()
    {
        $this->mockGameState->method('getTargetColors')->willReturn([]);
        $this->mockColors->method('pick')->willReturn([]);
        $this->mockGameState->expects($this->once())->method('setTargetColors');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    public function testPicksNoTargetColorsIfTheyAlreadyAreSet()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockGameState->expects($this->never())->method('setTargetColors');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
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
        
        $expected = MasterMindInterface::RESULT_MESSAGE_MAP[$checkerResult];
        
        $result = $this->createMasterMindInstance()->playerGuesses(['foo', 'bar']);
        $this->assertSame($expected, $result[MasterMindInterface::KEY_CHECK_RESULT]);
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
        
        $result = $this->createMasterMindInstance()->playerGuesses(['foo', 'bar']);
        $this->assertSame($testGuessCount, $result[MasterMindInterface::KEY_GUESS_COUNT]);
    }

    public function testIncrementsGuessCount()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->mockGameState->expects($this->once())->method('incrementGuessCount');
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    public function testResetsGameIfGuessIsSuccessful()
    {
        $this->mockGameState->method('getTargetColors')->willReturn(['foo', 'bar']);
        $this->mockGameState->expects($this->once())->method('reset');
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::PERFECT);
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    public function testDelegatesPickingNewColorsToColorsInstance()
    {
        $this->mockGameState->method('getTargetColors')->willReturn([]);
        $this->mockColors->expects($this->once())->method('pick')->willReturn(['foo', 'bar']);
        $this->mockChecker->method('check')->willReturn(GuessCheckerInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
    }
}
