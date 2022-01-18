<?php


namespace TLBM;

use TLBM\Ajax\GetBookingOptions;
use TLBM\Ajax\PingPong;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class Ajax {


    public function __construct() {
        $this->EnqueueAjaxEndpoints();
    }

    public function EnqueueAjaxEndpoints() {
	    error_reporting(E_ERROR);
	    ini_set("display_errors", 1);

        new PingPong();
        new GetBookingOptions();
    }
}
