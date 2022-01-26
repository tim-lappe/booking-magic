<?php

namespace TLBM\Ajax;

use DateTime;
use TLBM\Booking\BookableSlot;
use TLBM\Rules\RuleActions\ActionsMerging;
use TLBM\Rules\RulesQuery;

class GetBookingOptions extends AjaxBase
{

    /**
     * @inheritDoc
     */
    public function registerAjaxAction()
    {
        $this->addAjaxAction("getBookingOptions");
    }

    /**
     * @inheritDoc
     */
    public function apiRequest($data)
    {
        $query = new RulesQuery();

        if (isset($data->action_type)) {
            if (is_array($data->action_type)) {
                $query->setActionTypes($data->action_type);
            } else {
                $query->setActionTypes(array($data->action_type));
            }
        }

        if (isset($data->from_tstamp) && isset($data->to_tstamp)) {
            $from = new DateTime();
            $from->setTimestamp($data->from_tstamp);
            $to = new DateTime();
            $to->setTimestamp($data->to_tstamp);
            $query->setDateTimeRange($from, $to);
        } elseif (isset($data->options) && isset($data->options->focused_tstamp)) {
            $dt = new DateTime();
            $dt->setTimestamp($data->options->focused_tstamp);
            $query->setDateTime($dt);
        } else {
            return array(
                "error" => true
            );
        }


        $result         = array();
        $actions_reader = new ActionsMerging($query);
        $result         = $actions_reader->getRuleActionsMerged();

        return array(
            "merged_actions" => $result,
            "bookable_slots" => array(
                new BookableSlot(1642501787),
                new BookableSlot(1642503787)
            )
        );
    }
}