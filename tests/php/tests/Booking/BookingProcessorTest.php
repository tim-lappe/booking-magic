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

    public function getValidateVarsProvider(): array
    {
        return [
            [ 0, [
                "time" => "%7B%22year%22%3A2022%2C%22month%22%3A2%2C%22day%22%3A22%7D", "first_name" => "Tim", "last_name" => "Lappe",
                "address" => "Musterstraße. 8", "zip" => "51234", "city" => "Musterstadt",
                "contact_email" => "info@tlappe.de",
                "form" => "1"
            ]],
            [ 1, [
                "time" => "%7B%22month%22%3A2022%2C%22month%22%3A2%2C%22day%22%3A22%7D", "first_name" => "Tim", "last_name" => "Lappe",
                "address" => "Musterstraße. 8", "zip" => "51234", "city" => "Musterstadt",
                "contact_email" => "info@tlappe.de",
                "form" => "1"
            ]],
            [ 1, [
                "time" => "null", "first_name" => "Tim", "last_name" => "Lappe",
                "address" => "Musterstraße. 8", "zip" => "51234", "city" => "Musterstadt",
                "contact_email" => "info@tlappe.de",
                "form" => "1"
            ]],
            [ 2, [
                "time" => "null", "first_name" => "", "last_name" => "Lappe",
                "address" => "Musterstraße. 8", "zip" => "51234", "city" => "Musterstadt",
                "contact_email" => "info@tlappe.de",
                "form" => "1"
            ]],
            [ 7, [
                "time" => "null", "first_name" => "", "last_name" => "",
                "address" => "", "zip" => "", "city" => "",
                "contact_email" => "",
                "form" => "1"
            ]],
            [ 7, [
                "time" => "null", "first_name" => "", "last_name" => "",
                "form" => ""
            ]],
            [ 7, [
                "time" => "null", "first_name" => "", "last_name" => ""
            ]]
        ];
    }

    /**
     * @dataProvider getValidateVarsProvider
     *
     * @param int $invalidCount
     * @param array $vars
     *
     * @return void
     */
    public function testValidateVars(int $invalidCount, array $vars)
    {
        $bookingProcessor = MainFactory::get(BookingProcessor::class);
        $bookingProcessor->setVars($vars);
        $this->assertCount($invalidCount, $bookingProcessor->validateVars());
    }
}