<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

interface GuessCheckerInterface
{
    const PERFECT = 'Perfect';
    const ONE_CORRECT_POSITION = 'OneColorAtCorrectPosition';
    const ONE_WRONG_POSITION = 'OneColorAtWrongPosition';
    const NO_MATCH = 'NoMatch';

    /**
     * Compares the guess colors with the target colors and returns the value of one of the constants:
     * 
     * 1. self::PERFECT if all colors and positions match in both arrays.
     * 2. self::ONE_CORRECT_POSITION if one of the guess colors matches the position in the target colors.
     * 3. self::ONE_WRONG_POSITION if one of the guess colors matches but not in the same position.
     * 4. self::NO_MATCH if no guess color matches a target color.
     * 
     * @param string[] $targetColors
     * @param string[] $guessColors
     * @return string
     */
    public function check(array $targetColors, array $guessColors);
}
