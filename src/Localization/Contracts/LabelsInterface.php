<?php

namespace TLBM\Localization\Contracts;

interface LabelsInterface
{

    public function getMonthLabels(): array;

    public function getWeekdayLabels(): array;
}