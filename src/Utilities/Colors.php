<?php


namespace TLBM\Utilities;


use TLBM\Utilities\Contracts\ColorsInterface;

class Colors implements ColorsInterface
{

    public function __construct()
    {
    }

    public function getRgbFromHex($hex)
    {
        return sscanf($hex, "#%02x%02x%02x");
    }
}