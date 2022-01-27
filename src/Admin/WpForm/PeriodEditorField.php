<?php


namespace TLBM\Admin\WpForm;


use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Entity\RulePeriod;
use TLBM\Entity\TimeSlot;

if ( !defined('ABSPATH')) {
    return;
}

class PeriodEditorField extends FormFieldBase implements FormFieldReadVarsInterface
{
    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        /**
         * @var RulePeriod[] $periods
         */
        $periods = $value;
        if ( !is_array($periods)) {
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
                    $period->setFromTstamp($period_obj->from_tstamp);
                    $period->setFromTimeset((bool) $period_obj->from_timeset);
                    $period->setToTstamp($period_obj->to_tstamp);
                    $period->setToTimeset((bool) $period_obj->to_timeset);

                    if ($period_obj->id > 0 && is_numeric($period_obj->id)) {
                        $period->setId($period_obj->id);
                    }

                    foreach ($period_obj->daily_time_ranges as $time_range_obj) {
                        $time_range = new TimeSlot();
                        $time_range->setFromHour($time_range_obj->from_hour);
                        $time_range->setToHour($time_range_obj->to_hour);
                        $time_range->setFromMin($time_range_obj->from_min);
                        $time_range->setToMin($time_range_obj->to_min);
                        $period->addTimeSlot($time_range);
                    }

                    $periods[] = $period;
                }
            }

            return $periods;
        }

        return array();
    }
}