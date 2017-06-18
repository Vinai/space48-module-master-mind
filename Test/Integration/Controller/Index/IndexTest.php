<?php

declare(strict_types=1);

namespace Space48\MasterMind\Test\Integration\Controller\Index;

use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\AbstractController;
use Space48\MasterMind\Block\MasterMindBlock;

/**
 * @covers \Space48\MasterMind\Controller\Index\Index
 */
class IndexTest extends AbstractController
{
    private function getLayout(): LayoutInterface
    {
        return ObjectManager::getInstance()->get(LayoutInterface::class);
    }

    public function testReturnsPageResult()
    {
        $this->dispatch('/mastermind');
        $this->assertSelectCount(
            '#mastermind',
            1,
            $this->getResponse()->getBody(),
            "Mastermind section on result page not found"
        );
        $this->assertInstanceOf(MasterMindBlock::class, $this->getLayout()->getBlock('space48.mastermind'));
    }
}
