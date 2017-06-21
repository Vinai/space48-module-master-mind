<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model\State;

use Space48\MasterMind\Model\GameStateInterface;

class InMemoryGameState implements GameStateInterface
{
    private $targetColors = [];
    
    private $guessCount = 0;

    /**
     * @param string[] $colors
     */
    public function setTargetColors(array $colors)
    {
        $this->targetColors = $colors;
    }

    /**
     * @return string[]
     */
    public function getTargetColors()
    {
        return $this->targetColors;
    }

    /**
     * @return int
     */
    public function getGuessCount()
    {
        return $this->guessCount;
    }

    public function incrementGuessCount()
    {
        $this->guessCount++;
    }

    public function reset()
    {
        $this->targetColors = [];
        $this->guessCount = 0;
    }
}
