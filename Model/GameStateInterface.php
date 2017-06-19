<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

interface GameStateInterface
{
    /**
     * @param string[] $colors
     * @return void
     */
    public function setTargetColors(array $colors);

    /**
     * @return string[]
     */
    public function getTargetColors();

    /**
     * @return int
     */
    public function getGuessCount();

    /**
     * @return void
     */
    public function incrementGuessCount();

    /**
     * Set the target colors to an empty array and the guess count to zero.
     * 
     * @return void
     */
    public function reset();
}
