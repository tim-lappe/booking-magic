<?php

namespace TLBM\Utilities\Contracts;

interface DateTimeToolsInterface
{
    /**
     * @param $timestamp
     *
     * @return string
     */
    public function format($timestamp): string;

    /**
     * @param $timestamp
     *
     * @return string
     */
    public function formatWithTime($timestamp): string;

    /**
     * @return mixed
     */
    public function getDateFormat(): string;

    /**
     * @return mixed
     */
    public function getTimeFormat();

    /**
     * @param int $years
     * @param int $days
     * @param int $hours
     * @param int $minutes
     *
     * @return mixed
     */
    public function fromTimepartsToMinutes(int $years = 0, int $days = 0, int $hours = 0, int $minutes = 0);

    /**
     * @param $minutes
     *
     * @return array
     */
    public function fromMinutesToTimeparts($minutes): array;
}