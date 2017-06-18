<?php

declare(strict_types=1);

namespace Space48\MasterMind\Block;

use Magento\Framework\View\Element\Template;
use Space48\MasterMind\Config\Colors;

class MasterMindBlock extends Template
{
    /**
     * @var Colors
     */
    private $colors;

    public function __construct(Template\Context $context, Colors $colors, array $data = [])
    {
        parent::__construct($context, $data);
        $this->colors = $colors;
    }

    /**
     * @return string
     */
    public function getColorsJson()
    {
        return json_encode($this->colors) . PHP_EOL;
    }
}
