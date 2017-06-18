<?php

namespace Space48\MasterMind\Model;

interface MasterMindInterface
{
    const RESULT_MESSAGE_MAP = [
        GuessEvaluatorInterface::PERFECT              => 'Bingo! Choose new colors to play again.',
        GuessEvaluatorInterface::NO_MATCH             => 'No match!',
        GuessEvaluatorInterface::ONE_CORRECT_POSITION => 'One color is at the correct position.',
        GuessEvaluatorInterface::ONE_WRONG_POSITION   => 'A color matches but is at the wrong position.',
    ];

    /**
     * Receives an array of color strings.
     * Responsible for picking the target colors if there none are set.
     * Evaluates the guess and returns the string in the result message map for the evaluation result.
     * If the evaluation result is "Perfect" reset the target colors before returning the result string.
     *
     * @param string[] $colors
     * @return string
     */
    public function playerGuesses(array $colors);
}
