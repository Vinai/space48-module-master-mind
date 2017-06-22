<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Integration;

use Magento\TestFramework\ObjectManager;
use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Game\GameRunner;
use Space48\MasterMind\Model\GuessStrategyInterface;
use Space48\MasterMind\Model\MasterMindGameInterface;

/**
 * @coversNothing 
 */
class GamePlayTest extends \PHPUnit_Framework_TestCase
{
    public function testPlaysAGame()
    {
        /** @var Colors $colors */
        $colors = ObjectManager::getInstance()->create(Colors::class);
        /** @var MasterMindGameInterface $game */
        $game = ObjectManager::getInstance()->create(MasterMindGameInterface::class);
        
        /** @var GuessStrategyInterface|\PHPUnit_Framework_MockObject_MockObject $randomGuessStrategy */
        $randomGuessStrategy = $this->getMock(GuessStrategyInterface::class);
        $randomGuessStrategy->method('getGuess')->willReturnCallback(function () use ($colors) {
            return $colors->pick(2);
        });

        $result = (new GameRunner($game))->play($randomGuessStrategy);
        $this->assertGreaterThan(0, $result);

        printf("Target colors found: %s\n", implode(', ', $result[GameRunner::KEY_COLORS]));
        printf("Final guess count: %d\n", $result[GameRunner::KEY_GUESS_COUNT]);
    }
}
