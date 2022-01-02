<?php


namespace TLBM\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Calendar\CalendarManager;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use function Symfony\Component\String\s;

class CalendarPickerField extends FormFieldBase {



	public function __construct( $name, $title, $value = null, $show_calendar_groups = true ) {
	    if(!$value) {
            $value = new CalendarSelection();
        }

		parent::__construct( $name, $title, $value );
	}

	function OutputHtml() {
        $cals = CalendarManager::GetAllCalendars();
        $calendars = array();
        foreach ($cals as $cal) {
            $calendars[$cal->GetId()] = $cal->GetTitle() ?? $cal->GetId();
        }

		?>
		<tr>
			<th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
			<td>
                <div
                    data-json="<?php echo urlencode(json_encode($this->value)) ?>"
                    data-calendars="<?php echo urlencode(json_encode($calendars)) ?>"
                    data-name="<?php echo $this->name ?>"
                    class="tlbm-calendar-picker"></div>
			</td>
		</tr>
		<?php
	}

    /**
     * @param $name
     * @param $vars
     *
     * @return CalendarSelection
     */
	public static function ReadFromVars($name, $vars): CalendarSelection {
	    if(isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json = json_decode($decoded_var);
            if($json) {
                if(isset($json->calendar_ids) && isset($json->selection_mode)) {
                    $selection = new CalendarSelection();
                    if($selection->SetSelectionMode($json->selection_mode)) {
                        foreach ($json->calendar_ids as $calendar_id) {
                            $calendar = CalendarManager::GetCalendar(intval($calendar_id));
                            if($calendar) {
                                $selection->AddCalendar($calendar);
                            }
                        }
                    }

                    return $selection;
                }
            }
	    }

	    return new CalendarSelection();
    }
}