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
	    error_reporting(E_ERROR);
	    ini_set("display_errors", 1);

	    TLBookingMagic::MakeInstance(AjaxLoadCalendar::class);
    }
}
