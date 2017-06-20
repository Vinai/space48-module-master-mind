<?php

declare(strict_types=1);

namespace Space48\MasterMind\Console;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GameStateInterface;
use Space48\MasterMind\Model\MasterMindGameInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MasterMindGameCommand extends Command
{
    /**
     * @var MasterMindGameInterface
     */
    private $game;

    /**
     * @var Colors
     */
    private $colors;

    /**
     * @var GameStateInterface
     */
    private $gameState;

    public function __construct(MasterMindGameInterface $game, Colors $colors, GameStateInterface $gameState)
    {
        parent::__construct('play:mastermind');
        $this->game = $game;
        $this->colors = $colors;
        $this->gameState = $gameState;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<options=bold>' . __('Welcome!') . "</>\n");
        $result = null;

        while ($this->gameState->getGuessCount() > 0 || null === $result) {
            $result = $this->game->playerGuesses($this->readGuess($input, $output));
            $output->writeln("\n<option=bold>" . $this->buildGuessResultOutput($result) . "</>\n");
        };
    }

    private function readGuess(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<option=bold>Your guess:</>');
        $output->writeln('Available colors:');
        $this->outputAvailableColors($output);

        return [
            $this->readColor($input, $output, (string) __('First color:')),
            $this->readColor($input, $output, (string) __('Second color:')),
        ];
    }

    private function outputAvailableColors(OutputInterface $output)
    {
        $formattedColors = array_map(function ($color) {
            return "<fg=$color>$color</>";
        }, $this->colors->asArray());
        $output->writeln(' ' . implode('   ', $formattedColors) . "\n");
    }

    private function readColor(InputInterface $input, OutputInterface $output, string $questionText)
    {
        $availableColors = $this->colors->asArray();
        
        $question = new Question($questionText . ' ');
        $question->setAutocompleterValues($availableColors);
        $question->setValidator(function ($color) use ($availableColors) {
            if (! in_array($color, $availableColors)) {
                throw new \OutOfBoundsException((string) __('Please enter a valid color.'));
            }
            return $color;
        });
        return $this->getHelper('question')->ask($input, $output, $question);
    }

    private function buildGuessResultOutput(array $result): string
    {
        $guessNumber = __('Guess #%1:', $result[MasterMindGameInterface::KEY_GUESS_COUNT]);

        return $guessNumber . ' ' . $result[MasterMindGameInterface::KEY_CHECK_RESULT];
    }
}
