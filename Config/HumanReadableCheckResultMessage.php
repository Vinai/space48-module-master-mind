<?php

declare(strict_types=1);

namespace Space48\MasterMind\Config;

use Space48\MasterMind\Model\GuessCheckerInterface;

class HumanReadableCheckResultMessage
{
    const RESULT_MESSAGE_MAP = [
        GuessCheckerInterface::PERFECT              => 'Bingo! Your guess is correct!',
        GuessCheckerInterface::NO_MATCH             => 'No match!',
        GuessCheckerInterface::ONE_CORRECT_POSITION => 'One color is at the correct position.',
        GuessCheckerInterface::ONE_WRONG_POSITION   => 'A color matches but is at the wrong position.',
    ];

    /**
     * @param string $resultCode
     * @return string
     */
    public function get(string $resultCode)
    {
        return (string) __(self::RESULT_MESSAGE_MAP[$resultCode]);
    }
}
