<?php


namespace php\includes\Utilities;


use PHPUnit\Framework\TestCase;
use TL_Booking\Utilities\DateTimeTools;

final class DateTimeToolsTest extends TestCase {

	public function testGetDateTimeFormatTest() {
		$format = DateTimeTools::GetDateFormat();
		$date = date($format, time());

		$this->assertNotFalse($date);
	}
}