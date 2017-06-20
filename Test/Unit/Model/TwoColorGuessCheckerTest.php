<?php

declare(strict_types=1);

namespace Space48\MasterMind\Model;

/**
 * @covers \Space48\MasterMind\Model\TwoColorGuessChecker
 */
class TwoColorGuessCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $expected
     * @param string[] $targetColors
     * @param string[] $guessColors
     */
    private function assertCheckGuessResult(string $expected, array $targetColors, array $guessColors)
    {
        $this->assertSame($expected, (new TwoColorGuessChecker())->check($targetColors, $guessColors));
    }

    public function testImplementsTheGuessCheckerInterface()
    {
        $this->assertInstanceOf(GuessCheckerInterface::class, new TwoColorGuessChecker());
    }

    public function testReturnsPerfectIfGuessMatchesTargetColors()
    {
        $this->assertCheckGuessResult(GuessCheckerInterface::PERFECT, ['foo', 'bar'], ['foo', 'bar']);
    }

    public function testReturnsOneColorInCorrectPositionIfGuessMatchesOneTargetColor()
    {
        $this->assertCheckGuessResult(GuessCheckerInterface::ONE_CORRECT_POSITION, ['foo', 'foo'], ['foo', 'bar']);
        $this->assertCheckGuessResult(GuessCheckerInterface::ONE_CORRECT_POSITION, ['foo', 'foo'], ['bar', 'foo']);
    }

    public function testReturnsOneColorInWrongPositionIfGuessMatchesColorButNotPosition()
    {
        $this->assertCheckGuessResult(GuessCheckerInterface::ONE_WRONG_POSITION, ['foo', 'bar'], ['bar', 'baz']);
        $this->assertCheckGuessResult(GuessCheckerInterface::ONE_WRONG_POSITION, ['foo', 'bar'], ['bar', 'foo']);
    }

    public function testReturnsNoMatchIfGuessMatchesNoTargetColors()
    {
        $this->assertCheckGuessResult(GuessCheckerInterface::NO_MATCH, ['foo', 'bar'], ['baz', 'qux']);
    }
}
