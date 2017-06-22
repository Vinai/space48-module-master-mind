<?php

declare(strict_types=1);

namespace Space48\MasterMind\Console;

use Magento\Framework\App\ObjectManager;
use Space48\MasterMind\Game\GameRunner;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MasterMindGameCommand extends Command
{
    /**
     * @var GameRunner
     */
    private $gameRunner;

    /**
     * @var ConsoleIntractiveGuessStrategyFactory
     */
    private $interactiveGuessStrategyFactory;

    public function __construct(
        GameRunner $gameRunner,
        ConsoleIntractiveGuessStrategyFactory $guessStrategyFactory
    ) {
        parent::__construct('play:mastermind');
        $this->gameRunner = $gameRunner;
        $this->interactiveGuessStrategyFactory = $guessStrategyFactory;
    }

    protected function configure()
    {
        $this->addArgument('strategy', InputArgument::OPTIONAL, 'GuessStrategy class name', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<options=bold>' . __('Welcome!') . "</>");
        $output->writeln(__('Guess 2 colors in the correct order.') . "\n");

        $result = $this->gameRunner->play($this->createGuessStrategy($input, $output));

        $targetColors = '<options=bold>' . implode(', ', $result[GameRunner::KEY_COLORS]) . '</>';
        $output->writeln((string) __('Target colors: %1', $targetColors));
        $guessCount = '<options=bold>' . $result[GameRunner::KEY_GUESS_COUNT] . '</>';
        $output->writeln((string) (__('Final guess count: %1', $guessCount)));
    }

    private function createGuessStrategy(InputInterface $input, OutputInterface $output): GuessStrategyInterface
    {
        $guessStrategyImplementation = $this->getGuessStrategyClass($input);
        return '' !== $guessStrategyImplementation ?
            ObjectManager::getInstance()->create($guessStrategyImplementation) :
            $guessStrategy = $this->interactiveGuessStrategyFactory->create([
                'command' => $this,
                'input'   => $input,
                'output'  => $output
            ]);
    }

    private function getGuessStrategyClass(InputInterface $input): string
    {
        $class = (string) $input->getArgument('strategy');
        if ('' !== $class && ! $this->isGuessStrategyClass($class)) {
            $message = sprintf('The specified class is not a GuessStrategyInterface implementation: %s', $class);
            throw new \InvalidArgumentException($message);
        }
        return $class;
    }

    private function isGuessStrategyClass(string $class): bool
    {
        return class_exists($class) && in_array(ltrim(GuessStrategyInterface::class, '\\'), class_implements($class));
    }
}
