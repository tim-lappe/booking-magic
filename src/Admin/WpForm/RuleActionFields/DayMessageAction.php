<?php


namespace TLBM\Admin\WpForm\RuleActionFields;


if ( ! defined('ABSPATH')) {
    return;
}

class DayMessageAction extends RuleActionFieldBase
{

    public function __construct()
    {
        parent::__construct("day-message", __("Add Day Message", TLBM_TEXT_DOMAIN), "");

        $this->formHtml = '<textarea style="width: 100%" name="message" placeholder="' . __(
                "Enter Message",
                TLBM_TEXT_DOMAIN
            ) . '"></textarea>';
    }
}