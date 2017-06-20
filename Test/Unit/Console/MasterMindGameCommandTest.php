<?php

declare(strict_types=1);

namespace Space48\MasterMind\Console;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GameStateInterface;
use Space48\MasterMind\Model\MasterMindGameInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MasterMindGameCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MasterMindGameInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockGame;

    /**
     * @var Colors|\PHPUnit_Framework_MockObject_MockObject
     */
    private $stubColors;

    /**
     * @var GameStateInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockGameState;

    /**
     * @var InputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyInput;

    /**
     * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dummyOutput;

    private function createCommandInstance(): MasterMindGameCommand
    {
        $command = new MasterMindGameCommand($this->mockGame, $this->stubColors, $this->mockGameState);
        $dummyHelper = $this->getMockBuilder(QuestionHelper::class)->disableOriginalConstructor()->getMock();
        $command->setHelperSet(new HelperSet(['question' => $dummyHelper]));

        return $command;
    }
    
    protected function setUp()
    {
        $this->mockGame = $this->getMock(MasterMindGameInterface::class);
        $this->stubColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $this->stubColors->method('asArray')->willReturn(['red', 'green', 'blue', 'yellow']);
        $this->mockGameState = $this->getMock(GameStateInterface::class);
        
        $this->dummyInput = $this->getMock(InputInterface::class);
        $this->dummyOutput = $this->getMock(OutputInterface::class);
    }
    
    /**
     * @covers \Space48\MasterMind\Console\MasterMindGameCommand::execute
     */
    public function testGameLoopExitsWhenTheGameStateIsReset()
    {
        $this->mockGameState->method('getGuessCount')->willReturnOnConsecutiveCalls(0, 1, 2, 0);
        
        $this->mockGame->expects($this->exactly(3))->method('playerGuesses')->willReturn([
            MasterMindGameInterface::KEY_CHECK_RESULT => '',
            MasterMindGameInterface::KEY_GUESS_COUNT => 0,
        ]);
        
        $command = $this->createCommandInstance();
        $command->run($this->dummyInput, $this->dummyOutput);
    }
    
    /**
     * @covers \Space48\MasterMind\Console\MasterMindGameCommand::execute
     */
    public function testGameLoopExitsIfTheFirstGuessIsCorrect()
    {
        $this->mockGameState->method('getGuessCount')->willReturnOnConsecutiveCalls(0, 0);
        
        $this->mockGame->expects($this->once())->method('playerGuesses')->willReturn([
            MasterMindGameInterface::KEY_CHECK_RESULT => '',
            MasterMindGameInterface::KEY_GUESS_COUNT => 0,
        ]);
        
        $command = $this->createCommandInstance();
        $command->run($this->dummyInput, $this->dummyOutput);
    }
}
