<?php

namespace TLBM\Utilities\Contracts;

use InvalidArgumentException;

interface ColorsInterface
{
    /**
     * @param string $hex
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function getRgbFromHex(string $hex): array;
}