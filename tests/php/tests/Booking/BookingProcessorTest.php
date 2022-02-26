<?php

namespace php\tests\Booking;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TLBM\Booking\BookingProcessor;
use TLBM\MainFactory;

class BookingProcessorTest extends TestCase
{

    public function escapeVarsProvider(): array
    {
        return [
            [
                [
                    "first_name" => "first",
                    "last_name" => "last",
                    "address" => "musterstrasse 9",
                    "city" => "Kamen"
                ]
            ],
            [
                [
                    "first_name" => "first",
                    "last_name" => "last",
                    "address" => "musterstrasse 9",
                    "city" => "Kamen",
                    "form" => -1
                ]
            ],
            [
                []
            ],
            [
                [null]
            ]
        ];
    }

    /**
     * @dataProvider escapeVarsProvider
     *
     * @return void
     */
    public function testSetVarsException(array $vars)
    {
        $this->expectException(InvalidArgumentException::class);

        $bookingProcessor = MainFactory::get(BookingProcessor::class);
        $bookingProcessor->setVars($vars);
    }
}