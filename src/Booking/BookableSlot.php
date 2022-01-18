<?php

namespace TLBM\Booking;

class BookableSlot {

    /**
     * @var int
     */
    public int $tstamp = 0;

    public function __construct(int $tstamp = 0) {
        $this->tstamp = $tstamp;
    }
}