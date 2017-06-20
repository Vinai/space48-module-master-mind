<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

/**
 * @covers \Space48\MasterMind\Model\HumanCheckResultGameDecorator
 */
class HumanCheckResultGameDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MasterMindGameInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockDelegate;

    protected function setUp()
    {
        $this->mockDelegate = $this->getMock(MasterMindGameInterface::class);
    }

    public function testImplementsMasterMindGameInterface()
    {
        $this->assertInstanceOf(MasterMindGameInterface::class, new HumanCheckResultGameDecorator($this->mockDelegate));
    }

    public function testDelegatesProcessingAGuess()
    {
        $testGuessCount = 42;
        $this->mockDelegate->expects($this->once())->method('playerGuesses')->with(['foo', 'bar'])->willReturn([
            MasterMindGameInterface::KEY_CHECK_RESULT => GuessCheckerInterface::NO_MATCH,
            MasterMindGameInterface::KEY_GUESS_COUNT => $testGuessCount
        ]);
        (new HumanCheckResultGameDecorator($this->mockDelegate))->playerGuesses(['foo', 'bar']);
    }

    /**
     * @dataProvider checkResultDataProvider
     */
    public function testReturnsTheCheckResultMessageFromTheMessageMap(string $checkResult)
    {
        $testGuessCount = 1;
        $this->mockDelegate->method('playerGuesses')->willReturn([
            MasterMindGameInterface::KEY_CHECK_RESULT => $checkResult,
            MasterMindGameInterface::KEY_GUESS_COUNT  => $testGuessCount,
        ]);
        
        $result = (new HumanCheckResultGameDecorator($this->mockDelegate))->playerGuesses(['foo', 'bar']);
        $this->assertSame(
            HumanCheckResultGameDecorator::RESULT_MESSAGE_MAP[$checkResult],
            $result[MasterMindGameInterface::KEY_CHECK_RESULT]    
        );
    }

    public function checkResultDataProvider(): array
    {
        return [
            [GuessCheckerInterface::PERFECT],
            [GuessCheckerInterface::ONE_CORRECT_POSITION],
            [GuessCheckerInterface::ONE_WRONG_POSITION],
            [GuessCheckerInterface::NO_MATCH],
        ];
    }
}
