<?php


namespace TL_Booking;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TL_Booking\Ajax\AjaxLoadCalendar;
use TL_Booking_Magic;

class Ajax {

    public function __construct() {
        $this->EnqueueAjaxEndpoints();
    }

    public function EnqueueAjaxEndpoints() {
        TL_Booking_Magic::MakeInstance(AjaxLoadCalendar::class);
    }
}
