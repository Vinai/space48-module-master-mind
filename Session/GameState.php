<?php

declare(strict_types=1);

namespace Space48\MasterMind\Session;

use Magento\Framework\Session\SessionManager;

class GameState extends SessionManager
{
    /**
     * @param string[] $colors
     * @return void
     */
    public function setTargetColors(array $colors)
    {
        $this->setColorDataInStorage($colors);
    }

    /**
     * @return string[]
     */
    public function getTargetColors()
    {
        return (array) $this->getColorDataInStorage();
    }

    /**
     * @return int
     */
    public function getGuessCount()
    {
        return (int) $this->getGuessCountDataInStorage();
    }

    public function incrementGuessCount()
    {
        $this->setGuessCountDataInStorage($this->getGuessCount() + 1);
    }

    public function reset()
    {
        $this->unsColorDataInStorage();
        $this->unsGuessCountDataInStorage();
    }
}
