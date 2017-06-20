<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model\State;

use Space48\MasterMind\Model\GameStateInterface;

class InMemoryGameState implements GameStateInterface
{
    private $targetColors = [];
    
    private $guessCount = 0;
    
    public function setTargetColors(array $colors)
    {
        $this->targetColors = $colors;
    }

    public function getTargetColors()
    {
        return $this->targetColors;
    }

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
