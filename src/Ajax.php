<?php


namespace TLBM;

use TLBM\Ajax\GetBookingOptions;
use TLBM\Ajax\PingPong;

if ( ! defined('ABSPATH')) {
    return;
}

class Ajax
{

    public function __construct()
    {
        $this->enqueueAjaxEndpoints();
    }

    public function enqueueAjaxEndpoints()
    {
        if (WP_DEBUG) {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
        }

        new PingPong();
        new GetBookingOptions();
    }
}
