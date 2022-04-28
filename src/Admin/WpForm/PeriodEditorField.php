<?php


namespace TLBM\Admin\WpForm;


use Throwable;
use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Entity\RulePeriod;
use TLBM\Utilities\ExtendedDateTime;

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
            <th scope="row"><label for="<?php echo $this->escaping->escAttr($this->name) ?>"><?php echo $this->escaping->escHtml($this->title) ?></label></th>
            <td>
                <div class="tlbm-period-select-field"
                     data-name="<?php echo $this->escaping->escAttr($this->name) ?>"
                     data-json="<?php echo $this->escaping->escAttr(urlencode(json_encode($periods))); ?>"></div>
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
                        $fromDateTime = new ExtendedDateTime();
                        $fromDateTime->setFromObject($periodObj->fromDateTime);

                        $period->setFromFullDay($fromDateTime->isFullDay());
                        $period->setFromTimestamp($fromDateTime->getTimestamp());
                    } catch (Throwable $exception) {
                        continue;
                    }

                    if(isset($periodObj->toDateTime)) {
                        try {
                            $toDateTime = new ExtendedDateTime();
                            $toDateTime->setFromObject($periodObj->toDateTime);

                            $period->setToFullDay($toDateTime->isFullDay());
                            $period->setToTimestamp($toDateTime->getTimestamp());
                        } catch (Throwable $exception) {
                            $period->setToTimestamp(null);
                            $period->setToFullDay(true);
                        }
                    }

                    if ($periodObj->id > 0 && is_numeric($periodObj->id)) {
                        $period->setId($periodObj->id);
                    }

                    $periods[] = $period;
                }
            }

            return $periods;
        }

        return array();
    }
}