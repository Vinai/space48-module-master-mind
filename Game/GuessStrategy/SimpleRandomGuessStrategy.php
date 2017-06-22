<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GuessStrategyInterface;

class SimpleRandomGuessStrategy implements GuessStrategyInterface
{
    /**
     * @var Colors
     */
    private $colors;

    public function __construct(Colors $colors)
    {
        $this->colors = $colors;
    }

    /**
     * @return string[]
     */
    public function getGuess()
    {
        return $this->colors->pick(2);
    }

    public function guessResult($guessResult, $guessCount)
    {
        // This guess strategy does nothing with the guess results
    }
}
