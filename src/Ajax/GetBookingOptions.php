<?php

namespace TLBM\Ajax;

use TLBM\Booking\BookableSlot;
use TLBM\Entity\RuleAction;
use TLBM\Rules\ActionsReader;
use TLBM\Rules\RulesQuery;

class GetBookingOptions extends AjaxBase {

    /**
     * @inheritDoc
     */
    function RegisterAjaxAction() {
        $this->AddAjaxAction("getBookingOptions");
    }

    /**
     * @inheritDoc
     */
    function ApiRequest($data) {
        $query = new RulesQuery();

        if(isset($data->action_type)) {
            if(is_array($data->action_type)) {
                $query->setActionTypes($data->action_type);
            } else {
                $query->setActionTypes(array($data->action_type));
            }
        }

        if(isset($data->from_tstamp) && isset($data->to_tstamp)) {
            $from = new \DateTime();
            $from->setTimestamp($data->from_tstamp);
            $to = new \DateTime();
            $to->setTimestamp($data->to_tstamp);
            $query->setDateTimeRange($from, $to);

        } else if(isset($data->options) && isset($data->options->focused_tstamp)) {
            $dt = new \DateTime();
            $dt->setTimestamp($data->options->focused_tstamp);
            $query->setDateTime($dt);

        } else {
            return array(
                "error" => true
            );
        }


        $result = array();
        $actions_reader = new ActionsReader($query);
        $result = $actions_reader->getRuleActionsMerged();

        return array(
            "merged_actions" => $result,
            "bookable_slots" => array(
                new BookableSlot(1642501787),
                new BookableSlot(1642503787)
            )
        );
    }
}