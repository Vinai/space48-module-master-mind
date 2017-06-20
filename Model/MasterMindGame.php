<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

use Space48\MasterMind\Config\Colors;

class MasterMindGame implements MasterMindGameInterface
{
    private $numberOfColorsToPick = 2;

    /**
     * @var GuessCheckerInterface
     */
    private $guessChecker;

    /**
     * @var GameStateInterface
     */
    private $gameState;

    /**
     * @var Colors
     */
    private $colors;

    public function __construct(GuessCheckerInterface $guessChecker, GameStateInterface $gameState, Colors $colors)
    {
        $this->guessChecker = $guessChecker;
        $this->gameState = $gameState;
        $this->colors = $colors;
    }

    /**
     * @param string[] $colors
     * @return mixed[]
     */
    public function playerGuesses(array $colors)
    {
        $this->gameState->incrementGuessCount();
        $checkResult = $this->guessChecker->check($this->getTargetColors(), $colors);
        
        $result = $this->buildResultArray($checkResult);

        if (GuessCheckerInterface::PERFECT === $checkResult) {
            $this->gameState->reset();
        }
        
        return $result;
    }

    /**
     * @return string[]
     */
    private function getTargetColors()
    {
        if (empty($this->gameState->getTargetColors())) {
            $this->pickNewTargetColors();
        }

        return $this->gameState->getTargetColors();
    }

    private function pickNewTargetColors()
    {
        $targetColors = $this->colors->pick($this->numberOfColorsToPick);
        $this->gameState->setTargetColors($targetColors);
    }

    private function buildResultArray($checkResult): array
    {
        return [
            self::KEY_CHECK_RESULT => $checkResult,
            self::KEY_GUESS_COUNT  => $this->gameState->getGuessCount(),
        ];
    }
}
