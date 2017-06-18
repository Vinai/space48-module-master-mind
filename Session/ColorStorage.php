<?php

declare(strict_types=1);

namespace Space48\MasterMind\Session;

use Magento\Framework\Session\SessionManager;

class ColorStorage extends SessionManager
{
    /**
     * @param string[] $colors
     * @return void
     */
    public function setColors(array $colors)
    {
        $this->setColorDataInStorage($colors);
    }

    /**
     * @return string[]
     */
    public function getColors()
    {
        return (array) $this->getColorDataInStorage();
    }

    /**
     * @return void
     */
    public function unsetColors()
    {
        $this->unsColorDataInStorage();
    }
}
