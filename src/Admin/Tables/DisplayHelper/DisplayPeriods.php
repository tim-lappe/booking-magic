<?php

namespace TLBM\Admin\Tables\DisplayHelper;

use Doctrine\Common\Collections\Collection;
use TLBM\Entity\RulePeriod;
use TLBM\Utilities\ExtendedDateTime;

class DisplayPeriods
{
    /**
     * @var Collection
     */
    private Collection $rulePeriods;

    /**
     * @return void
     */
    public function display(): void
    {
        $htmlarr = array();
        $periods = $this->getRulePeriods();

        /**
         * @var RulePeriod $period
         */
        foreach ($periods as $period) {
            $html = "";
            $fromDt = new ExtendedDateTime($period->getFromTimestamp());
            $fromDt->setFullDay($period->isFromFullDay());

            if($period->getToTimestamp() != null) {
                $toDt = new ExtendedDateTime($period->getToTimestamp());
                $toDt->setFullDay($period->isToFullDay());

                if($fromDt->isSameDate($toDt)) {
                    $html .= sprintf(__("Only on <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format());
                } else {
                    $html .= sprintf(__("From <b>%s</b><br />Until <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format(), $toDt->format());
                }
            } else {
                $html .= sprintf(__("From <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format());
            }

            $htmlarr[] = $html;
        }

        if(count($htmlarr) == 0) {
            $htmlarr[] = __("Always", TLBM_TEXT_DOMAIN);
        }

        echo implode("<br /><br />", $htmlarr);
    }

    /**
     * @return Collection
     */
    public function getRulePeriods(): Collection
    {
        return $this->rulePeriods;
    }

    /**
     * @param Collection $rulePeriods
     */
    public function setRulePeriods(Collection $rulePeriods): void
    {
        $this->rulePeriods = $rulePeriods;
    }
}