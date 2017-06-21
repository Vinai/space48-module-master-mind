<?php

declare(strict_types=1);

namespace Space48\MasterMind\Config;

class Colors implements \JsonSerializable
{
    /**
     * @var string[]
     */
    private $colors;

    /**
     * @param string[] $colors
     */
    public function __construct(array $colors)
    {
        if (empty($colors)) {
            throw new \RangeException('The list of available colors array must not be empty');
        }
        $this->colors = $colors;
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    /**
     * @param int $numberOfColorsToPick
     * @return string[]
     */
    public function pick($numberOfColorsToPick)
    {
        if (0 === $numberOfColorsToPick) {
            return [];
        }
        $color = $this->colors[array_rand($this->colors)];
        return array_merge($this->pick($numberOfColorsToPick - 1), [$color]);
    }

    /**
     * @return string[]
     */
    public function asArray()
    {
        return array_values($this->colors);
    }
}
