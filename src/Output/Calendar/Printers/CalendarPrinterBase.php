<?php


namespace TLBM\Output\Calendar\Printers;


use TLBM\Model\Calendar;
use TLBM\Output\Calendar\Modules\ICalendarPrintModule;

abstract class CalendarPrinterBase {

    /**
     * @var ICalendarPrintModule[] $modules ;
     */
    private $modules;

    public function __construct() {

    }

    /**
     * @param Calendar $calendar
     *
     * @return bool
     */
    public abstract function CanPrintCalendar(Calendar $calendar): bool;

    /**
     * @param Calendar $calendar
     *
     * @return string
     */
    public abstract function GetTsClass(Calendar $calendar): string;

    /**
     * @param array $data
     *
     */
    public function ProcessData(array &$data) {
        if(!isset($data['screen'])) {
            $data['screen'] = "default";
        }
    }

    /**
     * @param string               $screen
     * @param ICalendarPrintModule $module
     */
    public function AddModule(string $screen, ICalendarPrintModule $module) {
        if($this->modules != null && isset($this->modules[$screen]) && !is_array($this->modules[$screen])) {
            $this->modules[$screen] = array($module);
        } else {
            $this->modules[$screen][] = $module;
        }
    }

    /**
     * @param array $data
     * @param bool  $process_data
     *
     * @return string
     */
    public function GetOutput(array &$data, $process_data = true): string {
        $html = "";

        if($process_data) {
            $this->ProcessData($data);
        }

        $screen = $data['screen'];
        if(!$screen) {
            $screen = "default";
        }

        foreach ($this->modules[$screen] as $module) {
            $html .= $module->GetOutput($data);
        }
        return $html;
    }
}