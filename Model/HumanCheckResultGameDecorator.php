<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

class HumanCheckResultGameDecorator implements MasterMindGameInterface
{
    const RESULT_MESSAGE_MAP = [
        GuessCheckerInterface::PERFECT              => 'Bingo! Your guess is correct!',
        GuessCheckerInterface::NO_MATCH             => 'No match!',
        GuessCheckerInterface::ONE_CORRECT_POSITION => 'One color is at the correct position.',
        GuessCheckerInterface::ONE_WRONG_POSITION   => 'A color matches but is at the wrong position.',
    ];
    
    /**
     * @var MasterMindGameInterface
     */
    private $delegate;

    public function __construct(MasterMindGameInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    public function playerGuesses(array $colors)
    {
        $result = $this->delegate->playerGuesses($colors);
        $humanReadableResult = self::RESULT_MESSAGE_MAP[$result[self::KEY_CHECK_RESULT]];

        return array_merge($result, [self::KEY_CHECK_RESULT => $humanReadableResult]);
    }
}
