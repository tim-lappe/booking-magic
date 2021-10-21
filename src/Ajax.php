<?php


namespace TLBM;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Ajax\AjaxLoadCalendar;
use TLBookingMagic;

class Ajax {

    public function __construct() {
        $this->EnqueueAjaxEndpoints();
    }

    public function EnqueueAjaxEndpoints() {
        TLBookingMagic::MakeInstance(AjaxLoadCalendar::class);
    }
}
