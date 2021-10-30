<?php


namespace TLBM\Utilities;


class Colors {

	public static function GetRgbFromHex($hex) {
		return sscanf($hex, "#%02x%02x%02x");
	}
}