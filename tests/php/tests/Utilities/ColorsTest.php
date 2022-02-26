<?php

namespace php\tests\Utilities;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TLBM\MainFactory;
use TLBM\Utilities\Contracts\ColorsInterface;


class ColorsTest extends TestCase
{
    public function badRgbFromHexValues(): array
    {
        return [
             [""],["#"],["#11"],["#ffff"],["#34874"],["45"]
        ];
    }

    /**
     * @dataProvider badRgbFromHexValues
     *
     * @param mixed $value
     *
     * @return void
     */
    public function testRgbFromHexException($value): void
    {
        $colors = MainFactory::get(ColorsInterface::class);
        $this->expectException(InvalidArgumentException::class);

        $result = $colors->getRgbFromHex($value);
    }

    public function testRgbFromHex(): void
    {
        $colors = MainFactory::get(ColorsInterface::class);
        $this->assertEquals([217, 198, 145], $colors->getRgbFromHex("#d9c691"));
        $this->assertEquals([120, 102, 52], $colors->getRgbFromHex("#786634"));
        $this->assertEquals([89, 0, 255], $colors->getRgbFromHex("#5900ff"));
    }
}