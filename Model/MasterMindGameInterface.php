<?php

namespace Space48\MasterMind\Model;

interface MasterMindGameInterface
{
    const KEY_CHECK_RESULT = 'check_result';
    const KEY_GUESS_COUNT = 'guess_count';

    /**
     * This method is responsible for coordinating the game round.
     * 
     * Receives the player guess as an array of color strings.
     * 
     * 1. If no target colors are set, pick the target colors.
     * 2. Check the guess, and return an array with the check result and also the guess count for the current game.
     *
     *    Example return array structure:
     *    [
     *        self::KEY_CHECK_RESULT => GuessCheckerInterface::NO_MATCH,
     *        self::KEY_GUESS_COUNT  => 5
     *    ]
     * 
     * 3. If the check result is GuessCheckerInterface::PERFECT, reset the game state before returning.
     * 4. Each call to playerGuesses() increments the guess count for the current game.
     * 
     * The following classes should be used as collaborators:
     * 
     * - \Space48\MasterMind\Model\GameStateInterface (target colors, guess count, reset game state)
     * - \Space48\MasterMind\Model\GuessCheckerInterface (compare guess to target colors)
     * - \Space48\MasterMind\Config\Colors (pick new target colors)
     *
     * @param string[] $colors
     * @return mixed[]
     */
    public function playerGuesses(array $colors);
}
