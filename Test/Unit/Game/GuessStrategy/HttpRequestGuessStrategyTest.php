<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Magento\Framework\App\RequestInterface;
use Space48\MasterMind\Model\GuessStrategyInterface;

/**
 * @covers \Space48\MasterMind\Game\GuessStrategy\HttpRequestGuessStrategy
 */
class HttpRequestGuessStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockRequest;

    protected function setUp()
    {
        $this->mockRequest = $this->getMock(RequestInterface::class);
    }
    
    public function testImplementsTheGuessStrategyInterface()
    {
        $this->assertInstanceOf(GuessStrategyInterface::class, new HttpRequestGuessStrategy($this->mockRequest));
    }

    public function testReturnsEmptyDefaultGuess()
    {
        $expectedDefault = ['', ''];
        $this->mockRequest->expects($this->once())->method('getParam')->with('guess', $expectedDefault);
        (new HttpRequestGuessStrategy($this->mockRequest))->getGuess();
    }

    public function testReturnsGuessRequestParam()
    {
        $this->mockRequest->method('getParam')->willReturn(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], (new HttpRequestGuessStrategy($this->mockRequest))->getGuess());
    }
}
