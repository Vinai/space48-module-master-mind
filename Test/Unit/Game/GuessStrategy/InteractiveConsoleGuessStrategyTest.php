<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Config\HumanReadableCheckResultMessage;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Space48\MasterMind\Game\GuessStrategy\InteractiveConsoleGuessStrategy
 */
class InteractiveConsoleGuessStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QuestionHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockCommandHelper;

    /**
     * @var HumanReadableCheckResultMessage|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubHumanReadableOutputMessage;

    /**
     * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockOutput;

    private function createInstance(): InteractiveConsoleGuessStrategy
    {
        /**
         * @var Command|\PHPUnit_Framework_MockObject_MockObject $stubCommand
         * @var InputInterface|\PHPUnit_Framework_MockObject_MockObject $dummyInput
         * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject $mockOutput
         * @var Colors|\PHPUnit_Framework_MockObject_MockObject $stubColors
         * @var HumanReadableCheckResultMessage|\PHPUnit_Framework_MockObject_MockObject $stubHumanReadableOutputMessage
         */
        $stubCommand = $this->getMockBuilder(Command::class)->disableOriginalConstructor()->getMock();
        $stubCommand->method('getHelper')->with('question')->willReturn($this->mockCommandHelper);
        $dummyInput = $this->getMock(InputInterface::class);
        $stubColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $stubColors->method('asArray')->willReturn(['foo', 'bar']);
        return new InteractiveConsoleGuessStrategy(
            $stubCommand,
            $dummyInput,
            $this->mockOutput,
            $stubColors,
            $this->stubHumanReadableOutputMessage
        );
    }

    protected function setUp()
    {
        $this->mockCommandHelper = $this->getMockBuilder(QuestionHelper::class)->disableOriginalConstructor()->getMock();
        $this->stubHumanReadableOutputMessage = $this->getMock(HumanReadableCheckResultMessage::class);
        $this->mockOutput = $this->getMock(OutputInterface::class);
    }

    public function testImplementsGuessStrategyInterface()
    {
        $this->assertInstanceOf(GuessStrategyInterface::class, $this->createInstance());
    }

    public function testAsksPlayerTwoGuessTwoColors()
    {
        $this->mockCommandHelper->expects($this->exactly(2))->method('ask')->willReturn('foo');
        
        $this->createInstance()->getGuess();
    }

    public function testDisplaysTheCheckResultAsAHumanReadableMessage()
    {
        $this->stubHumanReadableOutputMessage->method('get')->willReturn('Foo Bar Baz');
        $this->mockOutput->expects($this->once())->method('writeln')->with($this->stringContains('Foo Bar Baz'));
        $this->createInstance()->guessResult('Foo', $guessCount = 3);
    }
}
