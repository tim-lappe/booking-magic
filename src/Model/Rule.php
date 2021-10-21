<?php


namespace TLBM\Model;


if (!defined('ABSPATH')) {
    return;
}

class Rule {

    /**
     * @var int
     */
    public $wp_post_id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var CalendarSelection
     */
    public $calendar_selection;

    /**
     * @var int
     */
    public $priority;

	/**
	 * @var RuleActionCollection
	 */
    public $action;

	/**
	 * @var PeriodCollection
	 */
    public $periods;
}