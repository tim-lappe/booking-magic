<?php

namespace TLBM\Admin\Tables\DisplayHelper;

use Doctrine\Common\Collections\Collection;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\Entity\RulePeriod;
use TLBM\Utilities\ExtendedDateTime;

class DisplayPeriods
{
    /**
     * @var Collection
     */
    private Collection $rulePeriods;

	/**
	 * @var LocalizationInterface
	 */
    private LocalizationInterface $localization;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @var SanitizingInterface
	 */
	protected SanitizingInterface $sanitizing;

	/**
	 * @param EscapingInterface $escaping
	 * @param SanitizingInterface $sanitizing
	 * @param LocalizationInterface $localization
	 */
    public function __construct(EscapingInterface $escaping, SanitizingInterface $sanitizing, LocalizationInterface $localization)
    {
        $this->localization = $localization;
		$this->escaping = $escaping;
		$this->sanitizing = $sanitizing;
    }

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

                if($fromDt->isEqualTo($toDt)) {
                    $html .= sprintf($this->localization->getText("Only on <b>%s</b>", TLBM_TEXT_DOMAIN),  $this->escaping->escHtml($fromDt->format()));
                } else {
                    $html .= sprintf($this->localization->getText("From <b>%s</b><br />Until <b>%s</b>", TLBM_TEXT_DOMAIN), $this->escaping->escHtml($fromDt->format()), $this->escaping->escHtml($toDt->format()));
                }
            } else {
                $html .= sprintf($this->localization->getText("From <b>%s</b>", TLBM_TEXT_DOMAIN), $this->escaping->escHtml($fromDt->format()));
            }

            $htmlarr[] = $html;
        }

        if(count($htmlarr) == 0) {
            $htmlarr[] = $this->localization->getText("Always", TLBM_TEXT_DOMAIN);
        }

        echo $this->sanitizing->ksesPost(implode("<br /><br />", $htmlarr));
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