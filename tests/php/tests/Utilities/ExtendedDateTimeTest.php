<?php

namespace php\tests\Utilities;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TLBM\Utilities\ExtendedDateTime;

class ExtendedDateTimeTest extends TestCase
{
    public function getTimestampDataProvier(): array
    {
        return [
            [1645700182, new ExtendedDateTime(1645700182)],
            [1614160582, new ExtendedDateTime(1614160582)],
            [1614506182, new ExtendedDateTime(1614506182)],
            [1582970182, new ExtendedDateTime(1582970182)],
            [1425117382, new ExtendedDateTime(1425117382)],
            [1362056182, new ExtendedDateTime(1362056182)],
            [1362006000, new ExtendedDateTime(1362006000)],
            [TLBM_TEST_TIMESTAMP, new ExtendedDateTime()]
        ];
    }

    /**
     * @dataProvider getTimestampDataProvier
     *
     * @return void
     */
    public function testGetTimestamp(int $tstamp, ExtendedDateTime $edt)
    {
        $this->assertEquals($tstamp, $edt->getTimestamp());
    }

    public function getTimestampBeginOfDayDataProvier(): array
    {
        return [
            [1645657200, new ExtendedDateTime(1645700182)],
            [1614121200, new ExtendedDateTime(1614160582)],
            [1614466800, new ExtendedDateTime(1614506182)],
            [1582930800, new ExtendedDateTime(1582970182)],
            [1425078000, new ExtendedDateTime(1425117382)],
            [1362006000, new ExtendedDateTime(1362056182)],
            [1362006000, new ExtendedDateTime(1362090239)],
        ];
    }

    /**
     * @dataProvider getTimestampBeginOfDayDataProvier
     *
     * @param int $tstamp
     * @param ExtendedDateTime $edt
     *
     * @return void
     */
    public function testGetTimestampBeginOfDay(int $tstamp, ExtendedDateTime $edt)
    {
        $this->assertEquals($tstamp, $edt->getTimestampBeginOfDay());
    }

    public function getTimestampEndOfDayDataProvier(): array
    {
        return [
            [1645743599, new ExtendedDateTime(1645700182)],
            [1614207599, new ExtendedDateTime(1614160582)],
            [1614553199, new ExtendedDateTime(1614506182)],
            [1583017199, new ExtendedDateTime(1582970182)],
            [1425164399, new ExtendedDateTime(1425117382)],
            [1362092399, new ExtendedDateTime(1362056182)],
            [1362092399, new ExtendedDateTime(1362090239)],
            [1362092399, new ExtendedDateTime(1362092399)],
        ];
    }

    /**
     * @dataProvider getTimestampEndOfDayDataProvier
     *
     * @param int $tstamp
     * @param ExtendedDateTime $edt
     *
     * @return void
     */
    public function testGetTimestampEndOfDay(int $tstamp, ExtendedDateTime $edt)
    {
        $this->assertEquals($tstamp, $edt->getTimestampEndOfDay());
    }

    public function getWeekdayDataProvier(): array
    {
        return [
            [4, new ExtendedDateTime(1645700182)],
            [3, new ExtendedDateTime(1614160582)],
            [7, new ExtendedDateTime(1614506182)],
            [6, new ExtendedDateTime(1582970182)],
            [6, new ExtendedDateTime(1425117382)],
            [4, new ExtendedDateTime(1362056182)],
            [4, new ExtendedDateTime(1362090239)],
            [1, new ExtendedDateTime(1361746800)],
            [5, new ExtendedDateTime(1361487600)],
            [4, new ExtendedDateTime(1361401200)],
            [4, new ExtendedDateTime(1362092399)],
        ];
    }

    /**
     * @dataProvider getWeekdayDataProvier
     *
     * @param int $weekday
     * @param ExtendedDateTime $edt
     *
     * @return void
     */
    public function testGetWeekday(int $weekday, ExtendedDateTime $edt)
    {
        $this->assertEquals($weekday, $edt->getWeekday());
    }

    public function getSameDateDataProvier(): array
    {
        return [
            [true, new ExtendedDateTime(1645700182, true), new ExtendedDateTime(1645739782, true)],
            [false, new ExtendedDateTime(1645700182, false), new ExtendedDateTime(1645739782, false)],
            [false, new ExtendedDateTime(1645700182, true), new ExtendedDateTime(1645739782, false)],
            [true, new ExtendedDateTime(1645700182, false), new ExtendedDateTime(1645700182, false)],
            [false, new ExtendedDateTime(1645700182, false), new ExtendedDateTime(1645700182, true)],
            [false, new ExtendedDateTime(1645657200, false), new ExtendedDateTime(1645657199, false)],
            [true, new ExtendedDateTime(1645570800, true), new ExtendedDateTime(1645657199, true)],
            [true, new ExtendedDateTime(1645657199, true), new ExtendedDateTime(1645570800, true)],
        ];
    }

    /**
     * @dataProvider getSameDateDataProvier
     *
     * @param bool $result
     * @param ExtendedDateTime $edt1
     * @param ExtendedDateTime $edt2
     *
     * @return void
     */
    public function testIsEqualToDate(bool $result, ExtendedDateTime $edt1, ExtendedDateTime $edt2)
    {
        $this->assertEquals($result, $edt1->isEqualTo($edt2));
        $this->assertEquals($result, $edt2->isEqualTo($edt1));
    }

    public function getYearDataProvier(): array
    {
        return [
            [2022, new ExtendedDateTime(1645700182)],
            [2021, new ExtendedDateTime(1614160582)],
            [2021, new ExtendedDateTime(1614506182)],
            [2020, new ExtendedDateTime(1582970182)],
            [2015, new ExtendedDateTime(1425117382)],
            [2013, new ExtendedDateTime(1362056182)],
            [1970, new ExtendedDateTime(0)],
            [2054, new ExtendedDateTime(2662056182)],
        ];
    }

    /**
     * @dataProvider getYearDataProvier
     *
     * @param int $result
     * @param ExtendedDateTime $edt
     *
     * @return void
     */
    public function testGetYear(int $result, ExtendedDateTime $edt)
    {
        $this->assertEquals($result, $edt->getYear());
    }

    public function getSetFromObjectProvier(): array
    {
        return [
            [new ExtendedDateTime(1645702375), (new ExtendedDateTime())->setFromObject(
                [ "year" => 2022, "month" => 2, "day" => 24, "hour" => 12, "minute" => 32, "seconds" => 55])],
            [new ExtendedDateTime(1614166375), (new ExtendedDateTime())->setFromObject(
                [ "year" => 2021, "month" => 2, "day" => 24, "hour" => 12, "minute" => 32, "seconds" => 55])],
            [new ExtendedDateTime(1609459261), (new ExtendedDateTime())->setFromObject(
                [ "year" => 2021, "month" => "1", "day" => 1, "hour" => 1, "minute" => 1, "seconds" => 1])],
            [new ExtendedDateTime(944132461), (new ExtendedDateTime())->setFromObject(
                [ "year" => 1999, "month" => 12, "day" => 2, "hour" => 12, "minute" => 1, "seconds" => 1])],
            [new ExtendedDateTime(944132460), (new ExtendedDateTime())->setFromObject(
                [ "year" => 1999, "month" => 12, "day" => 2, "hour" => 12, "minute" => 1])],
            [new ExtendedDateTime(944132400), (new ExtendedDateTime())->setFromObject(
                [ "year" => 1999, "month" => 12, "day" => 2, "hour" => 12])],
            [new ExtendedDateTime(944089200, true), (new ExtendedDateTime())->setFromObject(
                [ "year" => 1999, "month" => 12, "day" => 2])],
            [new ExtendedDateTime(2944132401, true), (new ExtendedDateTime())->setFromObject(
                [ "year" => 2063, "month" => 4, "day" => 18])],
        ];
    }

    /**
     * @dataProvider getSetFromObjectProvier
     *
     * @param ExtendedDateTime $original
     * @param ExtendedDateTime $created
     *
     * @return void
     */
    public function testSetFromObject(ExtendedDateTime $original, ExtendedDateTime $created)
    {
        $this->assertTrue($original->isEqualTo($created), "Original: " . $original->getTimestamp() . ", Created: " . $created->getTimestamp());
    }

    /**
     * @return array[]
     */
    public function getInvalidSetFromObjectProvier(): array
    {
        return [
            [[ "year" => 1999, "mo2nth" => 12, "day" => 2]],
            [[ "year" => 1999, "day" => 2]],
            [[ "day" => 2]],
            [[ "year" => 1999, "month" => 12, "hour" => 12, "minute" => 1, "seconds" => 1]],
            [[]],
            [null],
            [[null, "month" => null]],
            [["month" => null]],
        ];
    }

    /**
     * @dataProvider getInvalidSetFromObjectProvier
     *
     * @param mixed $dtobj
     *
     * @return void
     */
    public function testSetFromObjectException($dtobj)
    {
        $this->expectException(InvalidArgumentException::class);
        $dt = (new ExtendedDateTime())->setFromObject($dtobj);
    }

    /**
     * @return array[]
     */
    public function getSetDatetimeBetweenProvier(): array
    {
        return [
            [944089200, 946767600, 32],
            [1645705975, 1646715975, 12],
            [1645705975, 1646766975, 13],
            [1645705069, 1740399469, 1097],
        ];
    }

    /**
     * @dataProvider getSetDatetimeBetweenProvier
     *
     * @param int $start
     * @param int $end
     * @param int $expectedDatesBetween
     *
     * @return void
     */
    public function testSetDatetimeBetween(int $start, int $end, int $expectedDatesBetween)
    {
        $dt1 = new ExtendedDateTime($start);
        $dt2 = new ExtendedDateTime($end);

        $arr = iterator_to_array($dt1->getDateTimesBetween(TLBM_EXTDATETIME_INTERVAL_DAY, $dt2));
        $this->assertNotEmpty($arr);

        $c = 0;
        /**
         * @var ExtendedDateTime $dt
         */
        foreach ($arr as $dt) {
            if($c == 0) {
                $this->assertTrue($dt1->isEqualTo($dt));
            } elseif ($c < count($arr) - 1) {
                $this->assertTrue($dt->isFullDay());
            }
            $c++;
        }

        $this->assertCount($expectedDatesBetween, iterator_to_array($dt1->getDateTimesBetween(TLBM_EXTDATETIME_INTERVAL_DAY, $dt2)));
    }
}