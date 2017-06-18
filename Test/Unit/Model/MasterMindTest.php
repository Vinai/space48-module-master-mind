<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Unit\Model;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GuessEvaluatorInterface;
use Space48\MasterMind\Model\MasterMind;
use Space48\MasterMind\Model\MasterMindInterface;
use Space48\MasterMind\Session\ColorStorage;

/**
 * @covers \Space48\MasterMind\Model\MasterMind
 */
class MasterMindTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuessEvaluatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockEvaluator;

    /**
     * @var ColorStorage|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockColorStorage;

    /**
     * @var Colors|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockColors;

    private function createMasterMindInstance(): MasterMind
    {
        return new MasterMind($this->mockEvaluator, $this->mockColorStorage, $this->mockColors);
    }

    protected function setUp()
    {
        $this->mockEvaluator = $this->getMock(GuessEvaluatorInterface::class);
        $this->mockColorStorage = $this->getMockBuilder(ColorStorage::class)->disableOriginalConstructor()->getMock();
        $this->mockColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $this->mockColors->method('asArray')->willReturn(['bar', 'baz', 'qux']);
    }

    public function testImplementsTheMasterMindInterface()
    {
        $this->assertInstanceOf(MasterMindInterface::class, $this->createMasterMindInstance());
    }

    public function testPicksTargetColorsIfNoneAreSet()
    {
        $this->mockColorStorage->method('getColors')->willReturn([]);
        $this->mockColors->method('pick')->willReturn([]);
        $this->mockColorStorage->expects($this->once())->method('setColors');
        $this->mockEvaluator->method('evaluate')->willReturn(GuessEvaluatorInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    public function testPicksNoTargetColorsIfTheyAlreadyAreSet()
    {
        $this->mockColorStorage->method('getColors')->willReturn(['foo', 'bar']);
        $this->mockColorStorage->expects($this->never())->method('setColors');
        $this->mockEvaluator->method('evaluate')->willReturn(GuessEvaluatorInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    /**
     * @param string $evaluatorResult
     * @dataProvider evaluatorResultDataProvider
     */
    public function testReturnsMapValueForPerfectGuess(string $evaluatorResult)
    {
        $this->mockColors->method('pick')->willReturn([]);
        $this->mockEvaluator->method('evaluate')->willReturn($evaluatorResult);
        $this->mockColorStorage->method('getColors')->willReturn(['frob', 'zab']);
        
        $expected = MasterMindInterface::RESULT_MESSAGE_MAP[$evaluatorResult];
        
        $result = $this->createMasterMindInstance()->playerGuesses(['foo', 'bar']);
        $this->assertSame($expected, $result);
    }

    public function evaluatorResultDataProvider()
    {
        return [
            [GuessEvaluatorInterface::PERFECT],
            [GuessEvaluatorInterface::ONE_CORRECT_POSITION],
            [GuessEvaluatorInterface::ONE_WRONG_POSITION],
            [GuessEvaluatorInterface::NO_MATCH],
        ];
    }

    public function testPicksNewTargetColorsIfGuessIsSuccessful()
    {
        $this->mockColorStorage->method('getColors')->willReturn(['foo', 'bar']);
        $this->mockColors->method('pick')->willReturn(['bar', 'baz']);
        $this->mockColorStorage->expects($this->once())->method('setColors')->with(['bar', 'baz']);
        $this->mockEvaluator->method('evaluate')->willReturn(GuessEvaluatorInterface::PERFECT);
        
        $this->createMasterMindInstance()->playerGuesses([]);
    }

    public function testDelegatesPickingNewColorsToColorsInstance()
    {
        $this->mockColors->expects($this->once())->method('pick')->willReturn(['foo', 'bar']);
        $this->mockColorStorage->method('getColors')->willReturn([]);
        $this->mockEvaluator->method('evaluate')->willReturn(GuessEvaluatorInterface::NO_MATCH);
        $this->createMasterMindInstance()->playerGuesses([]);
    }
}
