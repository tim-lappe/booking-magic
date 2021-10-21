<?php


namespace TLBM\Model;


if (!defined('ABSPATH')) {
    return;
}

class CapacitySetting {

    /**
     * @var string
     */
    public $mode = "set";

    /**
     * @var int
     */
    public $capacity = 0;
}