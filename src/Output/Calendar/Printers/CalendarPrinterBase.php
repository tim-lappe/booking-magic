<?php


namespace TLBM\Output\Calendar\Printers;


use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\Modules\ICalendarPrintModule;

abstract class CalendarPrinterBase {

    /**
     * @var ICalendarPrintModule[] $modules ;
     */
    private array $modules = array();

    public function __construct() {

    }

	/**
	 * @param CalendarGroup $group
	 *
	 * @return bool
	 */
    public abstract function CanPrintGroup(CalendarGroup $group): bool;

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