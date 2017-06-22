<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

interface GuessStrategyInterface
{
    /**
     * Return an array of color strings representing a player guess.
     * 
     * @return string[]
     */
    public function getGuess();

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
     * @return void
     */
    public function guessResult($guessResult, $guessCount);
}
