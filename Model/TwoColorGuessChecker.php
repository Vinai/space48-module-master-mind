<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

class TwoColorGuessChecker implements GuessCheckerInterface
{
    /**
     * @param string[] $targetColors
     * @param string[] $guessColors
     * @return string
     */
    public function check(array $targetColors, array $guessColors)
    {
        if ($targetColors[0] == $guessColors[0] && $targetColors[1] == $guessColors[1]) {
            return self::PERFECT;
        }
        if ($targetColors[0] == $guessColors[0] || $targetColors[1] == $guessColors[1]) {
            return self::ONE_CORRECT_POSITION;
        }
        if ($targetColors[0] == $guessColors[1] || $targetColors[1] == $guessColors[0]) {
            return self::ONE_WRONG_POSITION;
        }
        return self::NO_MATCH;
    }
}
