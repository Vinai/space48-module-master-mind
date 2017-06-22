<?php

declare(strict_types=1);

namespace Space48\MasterMind\Console;

use Space48\MasterMind\Game\GameRunner;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Space48\MasterMind\Console\MasterMindGameCommand
 */
class MasterMindGameCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyInput;

    /**
     * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyOutput;

    /**
     * @var GameRunner|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockGameRunner;

    /**
     * @var ConsoleIntractiveGuessStrategyFactory
     */
    private $mockGuessStrategyFactory;

    private function createCommandInstance(): MasterMindGameCommand
    {
        $command = new MasterMindGameCommand($this->mockGameRunner, $this->mockGuessStrategyFactory);
        $stubHelper = $this->getMockBuilder(QuestionHelper::class)->disableOriginalConstructor()->getMock();
        $stubHelper->method('ask')->willReturn('');
        $command->setHelperSet(new HelperSet(['question' => $stubHelper]));

        return $command;
    }

    protected function setUp()
    {
        $this->mockGameRunner = $this->getMockBuilder(GameRunner::class)->disableOriginalConstructor()->getMock();
        $this->mockGuessStrategyFactory = $this->getMockBuilder(ConsoleIntractiveGuessStrategyFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockGuessStrategyFactory->method('create')
            ->willReturn($this->getMock(GuessStrategyInterface::class));

        $this->dummyInput = $this->getMock(InputInterface::class);
        $this->dummyOutput = $this->getMock(OutputInterface::class);
    }

    public function testPlaysGameWithInteractiveGuessStrategy()
    {
        $this->mockGameRunner->expects($this->once())->method('play')
            ->with($this->mockGuessStrategyFactory->create())
            ->willReturn([
                GameRunner::KEY_COLORS      => ['foo', 'bar'],
                GameRunner::KEY_GUESS_COUNT => 1,
            ]);
        
        $this->createCommandInstance()->run($this->dummyInput, $this->dummyOutput);
    }
}
