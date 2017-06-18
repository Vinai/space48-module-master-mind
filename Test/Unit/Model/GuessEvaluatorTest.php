<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Unit\Model;

use Space48\MasterMind\Model\GuessEvaluator;
use Space48\MasterMind\Model\GuessEvaluatorInterface;

/**
 * @covers \Space48\MasterMind\Model\GuessEvaluator
 */
class GuessEvaluatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $expected
     * @param string[] $targetColors
     * @param string[] $guessColors
     */
    private function assertEvaluateGuessResult(string $expected, array $targetColors, array $guessColors)
    {
        $this->assertSame($expected, (new GuessEvaluator())->evaluate($targetColors, $guessColors));
    }

    public function testImplementsTheGuessEvaluatorInterface()
    {
        $this->assertInstanceOf(GuessEvaluatorInterface::class, new GuessEvaluator());
    }

    public function testReturnsPerfectIfGuessMatchesTargetColors()
    {
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::PERFECT, ['foo', 'bar'], ['foo', 'bar']);
    }

    public function testReturnsOneColorInCorrectPositionIfGuessMatchesOneTargetColor()
    {
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::ONE_CORRECT_POSITION, ['foo', 'foo'], ['foo', 'bar']);
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::ONE_CORRECT_POSITION, ['foo', 'foo'], ['bar', 'foo']);
    }

    public function testReturnsOneColorInWrongPositionIfGuessMatchesColorButNotPosition()
    {
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::ONE_WRONG_POSITION, ['foo', 'bar'], ['bar', 'baz']);
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::ONE_WRONG_POSITION, ['foo', 'bar'], ['bar', 'foo']);
    }

    public function testReturnsNoMatchIfGuessMatchesNoTargetColors()
    {
        $this->assertEvaluateGuessResult(GuessEvaluatorInterface::NO_MATCH, ['foo', 'bar'], ['baz', 'qux']);
    }
}
