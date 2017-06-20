<?php

declare(strict_types=1);

namespace Space48\MasterMind\Controller\Evaluate;

use Magento\TestFramework\TestCase\AbstractController;

/**
 * @covers \Space48\MasterMind\Controller\Evaluate\Index
 */
class IndexTest extends AbstractController
{
    public function testReturnsJsonResponse()
    {
        $this->dispatch('/mastermind/evaluate');
        $contentTypeHeader = $this->getResponse()->getHeader('Content-Type');
        $this->assertNotFalse($contentTypeHeader);
        $this->assertSame('application/json', $contentTypeHeader->getFieldValue());
    }
}
