<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GuessStrategyInterface;

class UniqueRandomGuessStrategy implements GuessStrategyInterface
{
    /**
     * @var Colors
     */
    private $colors;

    /**
     * @var array[]
     */
    private $previousGuesses = [];

    public function __construct(Colors $colors)
    {
        $this->colors = $colors;
    }

    /**
     * @return string[]
     */
    public function getGuess()
    {
        do {
            $guess = $this->colors->pick(2);
        } while (in_array($guess, $this->previousGuesses));
        $this->previousGuesses[] = $guess;
        
        return $guess;
    }

    public function guessResult($guessResult, $guessCount)
    {
        // This guess strategy does nothing with the guess results.
    }
}
