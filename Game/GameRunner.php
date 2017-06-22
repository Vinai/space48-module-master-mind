<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game;

use Space48\MasterMind\Model\GuessCheckerInterface;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Space48\MasterMind\Model\MasterMindGameInterface;

class GameRunner
{
    const KEY_COLORS = 'colors';
    const KEY_GUESS_COUNT = 'guesses';
    const KEY_RESULT = 'result';

    /**
     * @var MasterMindGameInterface
     */
    private $game;

    public function __construct(MasterMindGameInterface $game)
    {
        $this->game = $game;
    }

    /**
     * Play guess rounds until a guess is correct.
     * Returns an array containing the guess colors, the guess result and the total guess count.
     * 
     * For example:
     * [
     *     self::KEY_RESULT      => GuessCheckerInterface::NO_MATCH,
     *     self::KEY_COLORS      => ['red', 'green'],
     *     self::KEY_GUESS_COUNT => 5
     * ]
     * 
     * @param GuessStrategyInterface $guessStrategy
     * @return mixed[]
     */
    public function play(GuessStrategyInterface $guessStrategy)
    {
        do {
            $result = $this->playRound($guessStrategy);
        } while (! $this->isCorrect($result));

        return $result;
    }

    private function isCorrect(array $result): bool
    {
        return $result[self::KEY_RESULT] === GuessCheckerInterface::PERFECT;
    }

    /**
     * Read a guess from the guess strategy and pass it to the game to evaluate.
     * Calls GuessStrategyInterface::guessResult() callback with the guess result.
     * Returns an array containing the guess colors, the guess evaluation result and the total guess count.
     * 
     * @param GuessStrategyInterface $guessStrategy
     * @return mixed[]
     */
    public function playRound(GuessStrategyInterface $guessStrategy)
    {
        $guess = $guessStrategy->getGuess();
        $result = $this->game->playerGuesses($guess);

        $guessStrategy->guessResult(
            $result[MasterMindGameInterface::KEY_CHECK_RESULT],
            $result[MasterMindGameInterface::KEY_GUESS_COUNT]
        );

        return [
            self::KEY_RESULT      => $result[MasterMindGameInterface::KEY_CHECK_RESULT],
            self::KEY_COLORS      => $guess,
            self::KEY_GUESS_COUNT => $result[MasterMindGameInterface::KEY_GUESS_COUNT],
        ];
    }
}
