<?php

namespace TLBM\Localization\Contracts;

interface LabelsInterface
{

    /**
     * @return array
     */
    public function getMonthLabels(): array;

    /**
     * @return array
     */
    public function getWeekdayLabels(): array;

    /**
     * @return array
     */
    public function getWeekdayRangeLabels(): array;
}