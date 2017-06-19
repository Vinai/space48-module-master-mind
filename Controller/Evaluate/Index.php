<?php

declare(strict_types=1);

namespace Space48\MasterMind\Controller\Evaluate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as JsonResultFactory;
use Space48\MasterMind\Model\MasterMindGameInterface;

class Index implements ActionInterface
{
    private $defaultGuess = ['', ''];

    /**
     * @var JsonResultFactory
     */
    private $jsonResultFactory;

    /**
     * @var MasterMindGameInterface
     */
    private $game;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        JsonResultFactory $jsonResultFactory,
        RequestInterface $request,
        MasterMindGameInterface $game
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->request = $request;
        $this->game = $game;
    }

    public function execute()
    {
        $jsonResult = $this->jsonResultFactory->create();
        $colors = $this->request->getParam('guess', $this->defaultGuess);
        $responseMessage = $this->buildResponseMessage($colors);
        $jsonResult->setData($responseMessage);

        return $jsonResult;
    }

    private function buildResponseMessage($colors): string
    {
        $guessResult = $this->game->playerGuesses($colors);
        
        $message = $guessResult[MasterMindGameInterface::KEY_CHECK_RESULT];
        $guesses = '(#' . $guessResult[MasterMindGameInterface::KEY_GUESS_COUNT] . ')';
        
        return $message . ' ' . $guesses;
    }
}
