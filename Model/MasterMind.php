<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Session\ColorStorage;

class MasterMind implements MasterMindInterface
{
    private $numberOfColorsToPick = 2;

    /**
     * @var GuessEvaluatorInterface
     */
    private $guessEvaluator;

    /**
     * @var ColorStorage
     */
    private $colorStorage;

    /**
     * @var Colors
     */
    private $colors;

    public function __construct(GuessEvaluatorInterface $guessEvaluator, ColorStorage $colorStorage, Colors $colors)
    {
        $this->guessEvaluator = $guessEvaluator;
        $this->colorStorage = $colorStorage;
        $this->colors = $colors;
    }

    /**
     * @param string[] $colors
     * @return string
     */
    public function playerGuesses(array $colors)
    {
        $result = $this->guessEvaluator->evaluate($this->getTargetColors(), $colors);
        if (GuessEvaluatorInterface::PERFECT === $result) {
            $this->pickNewTargetColors();
        }

        return self::RESULT_MESSAGE_MAP[$result];
    }

    /**
     * @return string[]
     */
    private function getTargetColors()
    {
        if (! $this->colorStorage->getColors()) {
            $this->pickNewTargetColors();
        }

        return $this->colorStorage->getColors();
    }

    private function pickNewTargetColors()
    {
        $targetColors = $this->colors->pick($this->numberOfColorsToPick);
        $this->colorStorage->setColors($targetColors);
    }
}
