<?php

namespace TLBM\Rules\Actions\Merging\Results;

use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Rules\Actions\Merging\Contracts\CapacityMergeResultInterface;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;

class CapacityResult implements MergeResultInterface, CapacityMergeResultInterface
{
    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    public function __construct(CalendarBookingManagerInterface $calendarBookingManager)
    {
        $this->calendarBookingManager = $calendarBookingManager;
    }

    /**
     * @var int
     */
    public int $capacity = 0;

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = max(0, $capacity);
    }

    /**
     * @return int
     */
    public function getMergeResult(): int
    {


        return $this->capacity;
    }
}