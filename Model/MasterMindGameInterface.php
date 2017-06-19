<?php

namespace Space48\MasterMind\Model;

interface MasterMindGameInterface
{
    const RESULT_MESSAGE_MAP = [
        GuessCheckerInterface::PERFECT              => 'Bingo! Choose new colors to play again.',
        GuessCheckerInterface::NO_MATCH             => 'No match!',
        GuessCheckerInterface::ONE_CORRECT_POSITION => 'One color is at the correct position.',
        GuessCheckerInterface::ONE_WRONG_POSITION   => 'A color matches but is at the wrong position.',
    ];
    
    const KEY_CHECK_RESULT = 'check_result';
    const KEY_GUESS_COUNT = 'guess_count';

    /**
     * Receives an array of color strings.
     * 
     * This class is responsible for coordinating the game:
     * 
     * 1. If no target colors are set, pick the target colors.
     * 2. Check the guess, and return an array with the string in the result message map for the check result and
     *    also the guess count for the current game.
     *
     *    Example return array structure:
     *    [
     *        'check_result' => 'No match!',
     *        'guess_count'  => 5
     *    ]
     * 
     *    The return array keys are the values of the KEY_CHECK_RESULT and KEY_GUESS_COUNT constants.
     * 
     * 3. If the check result is "Perfect", reset the game state before returning.
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
