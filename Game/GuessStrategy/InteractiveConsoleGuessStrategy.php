<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Config\HumanReadableCheckResultMessage;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InteractiveConsoleGuessStrategy implements GuessStrategyInterface
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Colors
     */
    private $colors;

    /**
     * @var Command
     */
    private $command;

    /**
     * @var HumanReadableCheckResultMessage
     */
    private $humanReadableCheckResultMessage;

    public function __construct(
        Command $command,
        InputInterface $input,
        OutputInterface $output,
        Colors $colors,
        HumanReadableCheckResultMessage $humanReadableCheckResultMessage
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->colors = $colors;
        $this->command = $command;
        $this->humanReadableCheckResultMessage = $humanReadableCheckResultMessage;
    }

    /**
     * Return an array of color strings representing a player guess.
     *
     * @return string[]
     */
    public function getGuess()
    {
        $this->output->writeln('<option=bold>Next guess:</>');
        $this->output->writeln('Available colors:');
        $this->outputAvailableColors();

        return [
            $this->readColor((string) __('First color:')),
            $this->readColor((string) __('Second color:')),
        ];
    }

    private function outputAvailableColors()
    {
        $formattedColors = array_map(function ($color) {
            return "<fg=$color>$color</>";
        }, $this->colors->asArray());
        $this->output->writeln(' ' . implode('   ', $formattedColors) . "\n");
    }

    private function readColor(string $questionText): string
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

        return $this->command->getHelper('question')->ask($this->input, $this->output, $question);
    }

    /**
     * Receives one of the following GuessCheckerInterface constant values in regards to the previous getGuess() call:
     *
     * GuessCheckerInterface::PERFECT
     * GuessCheckerInterface::ONE_CORRECT_POSITION
     * GuessCheckerInterface::ONE_WRONG_POSITION
     * GuessCheckerInterface::NO_MATCH
     *
     * The guess count for the game is also passed as a second parameter for convenience.
     *
     * @param string $guessResult
     * @param int $guessCount
     */
    public function guessResult($guessResult, $guessCount)
    {
        $resultMessage = $this->humanReadableCheckResultMessage->get($guessResult);
        $outputMessage = __('Guess #%1:', $guessCount) . ' <options=bold>' . $resultMessage . '</>';
        $this->output->writeln($outputMessage . "\n");
    }
}
