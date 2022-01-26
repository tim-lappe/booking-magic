<?php


namespace TLBM\Admin\WpForm;


use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Entity\RulePeriod;
use TLBM\Entity\TimeSlot;

if ( ! defined('ABSPATH')) {
    return;
}

class PeriodEditorField extends FormFieldBase implements FormFieldReadVarsInterface
{
    public function displayContent(): void
    {
        /**
         * @var RulePeriod[] $periods
         */
        $periods = $this->value;
        if ( ! is_array($periods)) {
            $periods = array();
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <div class="tlbm-period-select-field" data-name="<?php
                echo $this->name ?>" data-json="<?php
                echo urlencode(json_encode($periods)) ?>"></div>
            </td>
        </tr>
        <?php
    }


    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return array
     */
    public function readFromVars(string $name, $vars): array
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json        = json_decode($decoded_var);
            $periods     = array();

            if (is_array($json)) {
                foreach ($json as $key => $period_obj) {
                    $period = new RulePeriod();
                    $period->SetFromTstamp($period_obj->from_tstamp);
                    $period->SetFromTimeset((bool)$period_obj->from_timeset);
                    $period->SetToTstamp($period_obj->to_tstamp);
                    $period->SetToTimeset((bool)$period_obj->to_timeset);

                    if ($period_obj->id > 0 && is_numeric($period_obj->id)) {
                        $period->SetId($period_obj->id);
                    }

                    foreach ($period_obj->daily_time_ranges as $time_range_obj) {
                        $time_range = new TimeSlot();
                        $time_range->SetFromHour($time_range_obj->from_hour);
                        $time_range->SetToHour($time_range_obj->to_hour);
                        $time_range->SetFromMin($time_range_obj->from_min);
                        $time_range->SetToMin($time_range_obj->to_min);
                        $period->AddTimeSlot($time_range);
                    }

                    $periods[] = $period;
                }
            }

            return $periods;
        }

        return array();
    }
}