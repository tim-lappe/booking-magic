<?php


namespace TL_Booking\Output\Calendar\Modules;


interface ICalendarPrintModule {

    /**
     * @param $data
     *
     * @return string
     */
    public function GetOutput($data): string;
}