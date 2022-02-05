<?php


namespace TLBM\Admin\WpForm;


use DateTime;
use Throwable;
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
                foreach ($json as $periodObj) {
                    $period = new RulePeriod();
                    try {
                        $fromDateTime = new DateTime();
                        $fromDateTime->setTimezone(wp_timezone());
                        $fromDateTime->setDate(
                                $periodObj->fromDateTime->year,
                                $periodObj->fromDateTime->month,
                                $periodObj->fromDateTime->day);
                        $fromDateTime->setTime(
                            $periodObj->fromDateTime->hour,
                            $periodObj->fromDateTime->minute,
                            $periodObj->fromDateTime->seconds);

                        $period->setFromTimestamp($fromDateTime->getTimestamp());
                        $period->setFromFullDay(!(bool) $periodObj->fromTimeset);

                    } catch (Throwable $exception) {
                        continue;
                    }

                    if(isset($periodObj->toDateTime)) {
                        try {
                            $toDateTime = new DateTime();
                            $toDateTime->setTimezone(wp_timezone());
                            $toDateTime->setDate(
                                $periodObj->toDateTime->year, $periodObj->toDateTime->month, $periodObj->toDateTime->day
                            );
                            $toDateTime->setTime(
                                $periodObj->toDateTime->hour, $periodObj->toDateTime->minute, $periodObj->toDateTime->seconds
                            );

                            if(isset($periodObj->toTimeset)) {
                                $period->setToFullDay(!(bool) $periodObj->toTimeset);
                            }

                            $period->setToTimestamp($toDateTime->getTimestamp());
                        } catch (Throwable $exception) {
                            $period->setToTimestamp(null);
                            $period->setToFullDay(true);
                        }
                    }

                    if ($periodObj->id > 0 && is_numeric($periodObj->id)) {
                        $period->setId($periodObj->id);
                    }

                    foreach ($periodObj->dailyTimeRanges as $timeRangeObj) {
                        $time_range = new TimeSlot();
                        $time_range->setFromHour($timeRangeObj->from_hour);
                        $time_range->setToHour($timeRangeObj->to_hour);
                        $time_range->setFromMin($timeRangeObj->from_min);
                        $time_range->setToMin($timeRangeObj->to_min);
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