<?php


namespace TLBM\Model;

if (!defined('ABSPATH')) {
    return;
}

define("TLBM_CALENDAR_SELECTION_TYPE_ALL", "all");
define("TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT", "all_but");
define("TLBM_CALENDAR_SELECTION_TYPE_ONLY", "only");


class CalendarSelection {

    public static array $select_types = array( TLBM_CALENDAR_SELECTION_TYPE_ALL, TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT, TLBM_CALENDAR_SELECTION_TYPE_ONLY );

    public array $selected_calendar_ids = array();

    public string $selection_type = "all";
}