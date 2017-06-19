<?php

declare(strict_types=1);

namespace Space48\MasterMind\Controller\Evaluate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as JsonResultFactory;
use Space48\MasterMind\Model\MasterMindInterface;

class Index implements ActionInterface
{
    private $defaultGuess = ['', ''];

    /**
     * @var JsonResultFactory
     */
    private $jsonResultFactory;

    /**
     * @var MasterMindInterface
     */
    private $masterMind;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        JsonResultFactory $jsonResultFactory,
        RequestInterface $request,
        MasterMindInterface $masterMind
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->request = $request;
        $this->masterMind = $masterMind;
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
        $checkResult = $this->masterMind->playerGuesses($colors);
        
        $message = $checkResult[MasterMindInterface::KEY_CHECK_RESULT];
        $guesses = '(#' . $checkResult[MasterMindInterface::KEY_GUESS_COUNT] . ')';
        
        return $message . ' ' . $guesses;
    }
}
