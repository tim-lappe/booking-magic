<?php


namespace TLBM\Admin\WpForm\RuleActionFields;


if (!defined('ABSPATH')) {
    return;
}

abstract class RuleActionFieldBase {

    /**
     * @var int
     */
    public $key;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $tsClass;

    /**
     * @var string
     */
    public $formHtml;

    public function __construct($key, $title, $tsClass = "") {
        $this->key = $key;
        $this->title = $title;
        $this->tsClass = $tsClass;
    }
}