<?php


namespace TLBM\Utilities;


use InvalidArgumentException;
use TLBM\Utilities\Contracts\ColorsInterface;

class Colors implements ColorsInterface
{
    /**
     * @param string $hex
     *
     * @return array
     */
    public function getRgbFromHex(string $hex): array
    {
        if(strlen($hex) == 7) {
            $rgbaArr = sscanf($hex, "#%02x%02x%02x");
            if ($rgbaArr != null) {
                if (count($rgbaArr) == 3 && !in_array(null, $rgbaArr, true)) {
                    return $rgbaArr;
                }
            }
        }

        throw new InvalidArgumentException("Invalid hex format. Exmaple: #5900ff");
    }
}