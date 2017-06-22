<?php

declare(strict_types=1);

namespace Space48\MasterMind\Controller\Evaluate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory as JsonResultFactory;
use Space48\MasterMind\Config\HumanReadableCheckResultMessage;
use Space48\MasterMind\Game\GuessStrategy\HttpRequestGuessStrategy;
use Space48\MasterMind\Game\GameRunner;

class Index implements ActionInterface
{
    /**
     * @var JsonResultFactory
     */
    private $jsonResultFactory;
    
    /**
     * @var HttpRequestGuessStrategy
     */
    private $guessStrategy;

    /**
     * @var HumanReadableCheckResultMessage
     */
    private $humanReadableCheckResultMessage;

    /**
     * @var GameRunner
     */
    private $gameRunner;

    public function __construct(
        JsonResultFactory $jsonResultFactory,
        GameRunner $gameRunner,
        HttpRequestGuessStrategy $guessStrategy,
        HumanReadableCheckResultMessage $humanReadableCheckResultMessage
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->gameRunner = $gameRunner;
        $this->guessStrategy = $guessStrategy;
        $this->humanReadableCheckResultMessage = $humanReadableCheckResultMessage;
    }

    public function execute()
    {
        $guessResult = $this->gameRunner->playRound($this->guessStrategy);
        $responseMessage = $this->buildResponseMessage($guessResult);

        return $this->createJsonResponse($responseMessage);
    }

    private function buildResponseMessage(array $guessResult): string
    {
        $message = $this->getResultMessage($guessResult[GameRunner::KEY_RESULT]);
        $guesses = '(#' . $guessResult[GameRunner::KEY_GUESS_COUNT] . ')';
        
        return $message . ' ' . $guesses;
    }

    private function getResultMessage($resultCode): string
    {
        return $this->humanReadableCheckResultMessage->get($resultCode);
    }

    private function createJsonResponse($responseMessage): Json
    {
        $jsonResult = $this->jsonResultFactory->create();
        $jsonResult->setData($responseMessage);

        return $jsonResult;
    }
}
