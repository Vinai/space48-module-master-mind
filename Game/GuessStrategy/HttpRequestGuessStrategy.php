<?php

declare(strict_types=1);

namespace Space48\MasterMind\Game\GuessStrategy;

use Magento\Framework\App\RequestInterface;
use Space48\MasterMind\Model\GuessStrategyInterface;

class HttpRequestGuessStrategy implements GuessStrategyInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    private $defaultGuess = ['', ''];

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getGuess()
    {
        return $this->request->getParam('guess', $this->defaultGuess);
    }

    public function guessResult($guessResult, $guessCount)
    {
        // This guess strategy does nothing with the guess result.
    }
}
