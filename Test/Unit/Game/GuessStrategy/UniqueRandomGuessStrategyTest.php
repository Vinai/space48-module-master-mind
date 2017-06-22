<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Space48\MasterMind\Config\Colors;
use Space48\MasterMind\Model\GuessStrategyInterface;

/**
 * @covers \Space48\MasterMind\Game\GuessStrategy\UniqueRandomGuessStrategy
 */
class UniqueRandomGuessStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsGuessStrategyInterface()
    {
        /** @var Colors $dummyColors */
        $dummyColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(GuessStrategyInterface::class, new UniqueRandomGuessStrategy($dummyColors));
    }

    public function testPicksTwoColors()
    {
        /** @var Colors|\PHPUnit_Framework_MockObject_MockObject $stubColors */
        $stubColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $stubColors->method('pick')->with(2)->willReturn(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], (new UniqueRandomGuessStrategy($stubColors))->getGuess());
    }

    public function testSkipsAlreadyGuessedCombinationsOnConsequtiveCalls()
    {
        /** @var Colors|\PHPUnit_Framework_MockObject_MockObject $stubColors */
        $stubColors = $this->getMockBuilder(Colors::class)->disableOriginalConstructor()->getMock();
        $stubColors->method('pick')->with(2)->willReturnOnConsecutiveCalls(
            ['foo', 'bar'],
            ['bar', 'baz'],
            ['foo', 'bar'],
            ['foo', 'qux']
        );
        $guessStrategy = new UniqueRandomGuessStrategy($stubColors);
        $this->assertSame(['foo', 'bar'], $guessStrategy->getGuess());
        $this->assertSame(['bar', 'baz'], $guessStrategy->getGuess());
        $this->assertSame(['foo', 'qux'], $guessStrategy->getGuess());
    }
}
