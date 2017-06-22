<?php

declare(strict_types=1);

namespace Space48\MasterMind\Config;

use Space48\MasterMind\Model\GuessCheckerInterface;

/**
 * @covers \Space48\MasterMind\Config\HumanReadableCheckResultMessage
 */
class HumanReadableCheckResultMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider checkResultDataProvider
     */
    public function testMapsACheckerResultCodeToAHumanReadableMessage($checkerResult)
    {
        $this->assertSame(
            HumanReadableCheckResultMessage::RESULT_MESSAGE_MAP[$checkerResult],
            (new HumanReadableCheckResultMessage())->get($checkerResult)
        );
    }

    public function checkResultDataProvider(): array
    {
        return [
            [GuessCheckerInterface::PERFECT],
            [GuessCheckerInterface::ONE_CORRECT_POSITION],
            [GuessCheckerInterface::ONE_WRONG_POSITION],
            [GuessCheckerInterface::NO_MATCH],
        ];
    }
}
