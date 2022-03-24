<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\TimeSlot;
use TLBM\MainFactory;
use TLBM\Validation\Contracts\TimeSlotEntityValidatorInterface;

class TimeSlotEntityValidator implements TimeSlotEntityValidatorInterface
{
    /**
     * @var TimeSlot
     */
    private TimeSlot $timeSlot;

    /**
     * @param TimeSlot $timeSlot
     */
    public function __construct(TimeSlot $timeSlot)
    {
        $this->timeSlot = $timeSlot;
    }

    /**
     * @return array
     */
    public function validateFrom(): array
    {
        $errors = array();

        $fromHour = $this->timeSlot->getFromHour();
        $fromMinute = $this->timeSlot->getFromMin();
        $localization = MainFactory::get(LocalizationInterface::class);

        if(!$this->isValidHour($fromHour)) {
            $errors[] = sprintf($localization->getText("Invalid period time slot. From hour: %s", TLBM_TEXT_DOMAIN), $fromHour);
        }

        if(!$this->isValidMinute($fromMinute)) {
            $errors[] = sprintf($localization->getText("Invalid period time slot. From minute: %s", TLBM_TEXT_DOMAIN), $fromMinute);
        }

        return $errors;
    }
    /**
     * @return array
     */
    public function validateTo(): array
    {
        $errors = array();

        $toHour = $this->timeSlot->getToHour();
        $toMinute = $this->timeSlot->getToMin();
        $localization = MainFactory::get(LocalizationInterface::class);

        if(!$this->isValidHour($toHour)) {
            $errors[] = sprintf($localization->getText("Invalid period time slot. To hour: %s", TLBM_TEXT_DOMAIN), $toHour);
        }

        if(!$this->isValidMinute($toMinute)) {
            $errors[] = sprintf($localization->getText("Invalid period time slot. To minute: %s", TLBM_TEXT_DOMAIN), $toMinute);
        }

        return $errors;
    }

    /**
     * @param int $hour
     *
     * @return bool
     */
    private function isValidHour(int $hour): bool
    {
        return ($hour >= 0 && $hour < 24);
    }

    /**
     * @param int $minute
     *
     * @return bool
     */
    private function isValidMinute(int $minute): bool
    {
        return ($minute >= 0 && $minute < 60);
    }


    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->validateFrom(),
            $this->validateTo()
        );
    }
}