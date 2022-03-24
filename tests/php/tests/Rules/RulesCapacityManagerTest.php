<?php

namespace php\tests\Rules;

use PHPUnit\Framework\TestCase;
use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Utilities\ExtendedDateTime;

class RulesCapacityManagerTest extends TestCase
{
    /**
     * @return Calendar[]
     */
    public function getTestCalendars(): array
    {
        $entityRepository = MainFactory::get(EntityRepositoryInterface::class);
        return [
            "Alice" => $entityRepository->getEntity(Calendar::class, 1),
            "Bob" => $entityRepository->getEntity(Calendar::class, 2),
            "Carl" => $entityRepository->getEntity(Calendar::class, 3),
            "Dan" => $entityRepository->getEntity(Calendar::class, 4),
            "Emil" => $entityRepository->getEntity(Calendar::class, 5),
            "Fabian" => $entityRepository->getEntity(Calendar::class, 6),
            "Gerd" => $entityRepository->getEntity(Calendar::class, 7),
            "ABC" => $entityRepository->getEntity(Calendar::class, 8),
        ];
    }

    public function testGetOriginalCapacity()
    {
        $rulesCapacity = MainFactory::get(RulesCapacityManagerInterface::class);
        $entityRepository = MainFactory::get(EntityRepositoryInterface::class);
        $allCalendars = $this->getTestCalendars();
        $allCalendarIds = [];

        foreach ($allCalendars as $name => $calendar) {
            $allCalendarIds[$name] = $calendar->getId();
        }

        foreach ($allCalendars as $calendar) {
            /**
             * First 8 Test cases of TestCaseSpreadsheet
             */
            $capacity = $rulesCapacity->getCapacityResult([$calendar->getId()], new ExtendedDateTime(1648805642, true));
            $this->assertEquals(10, $capacity);

            /**
             * 8-16 Test cases of TestCaseSpreadsheet
             */
            $capacity = $rulesCapacity->getCapacityResult([$calendar->getId()], new ExtendedDateTime(1648880528, true));
            $this->assertEquals(5, $capacity);
        }

        /**
         * Test Rule 3, No Capacity from 01.03. to 10.03. for Alice and Fabian
         */
        $this->assertEquals(0, $rulesCapacity->getCapacityResult([$allCalendarIds["Alice"]], new ExtendedDateTime(1646292128, true)));
        $this->assertEquals(0, $rulesCapacity->getCapacityResult([$allCalendarIds["Fabian"]], new ExtendedDateTime(1646292128, true)));

        /**
         * But still have Capacity for others
         */
        $this->assertEquals(10, $rulesCapacity->getCapacityResult([$allCalendarIds["ABC"]], new ExtendedDateTime(1646292128, true)));

        /**
         * Test summed up capacities for some dates
         */
        $this->assertEquals(80, $rulesCapacity->getCapacityResult($allCalendarIds, new ExtendedDateTime(1648805642, true)));
        $this->assertEquals(40, $rulesCapacity->getCapacityResult($allCalendarIds, new ExtendedDateTime(1648880528, true)));
        $this->assertEquals(60, $rulesCapacity->getCapacityResult($allCalendarIds, new ExtendedDateTime(1646292128, true)));
    }
}