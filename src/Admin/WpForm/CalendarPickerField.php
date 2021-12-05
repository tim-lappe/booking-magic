<?php


namespace TLBM\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Calendar\CalendarManager;
use TLBM\Model\CalendarSelection;

class CalendarPickerField extends FormFieldBase {



	public function __construct( $name, $title, $value = null, $show_calendar_groups = true ) {
	    if(!$value) {
            $value = new CalendarSelection();
        }

		parent::__construct( $name, $title, $value );
	}

	function OutputHtml() {
		?>
		<tr>
			<th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
			<td>
                <div class="tlbm-form-field-calendar-selector">
                    <select id="<?php echo $this->name ?>" name="<?php echo $this->name . "_select_type" ?>" value="<?php echo $this->value->selection_type ?>">
                        <option value="all" <?php selected($this->value->selection_type, "all") ?>><?php echo __("All", TLBM_TEXT_DOMAIN); ?></option>
                        <option value="all_but" <?php selected($this->value->selection_type, "all_but") ?>><?php echo __("All but these:", TLBM_TEXT_DOMAIN); ?></option>
                        <option value="only" <?php selected($this->value->selection_type, "only") ?>><?php echo __("Only these:", TLBM_TEXT_DOMAIN); ?></option>
                    </select>
                    <div class="tlbm-calendar-select-panel" <?php echo $this->value->selection_type !== "all" ? "style='display: block'" : "style='display: none'" ?>>
                        <?php
                            $cals = CalendarManager::GetAllCalendars();
                            if(sizeof($cals) > 0) {
                                foreach($cals as $calendar) {
                                    ?>
                                    <div class="tlbm-calendar-select-item">
                                    <label>
                                        <input name="<?php echo $this->name . "_selection[]"; ?>" value="<?php echo $calendar->GetId() ?>" <?php echo in_array($calendar->GetId(), $this->value->selected_calendar_ids) ? "checked='checked'" : "" ?> type="checkbox">
                                        <?php
                                        if(!empty($calendar->GetTitle())) {
                                            echo $calendar->GetTitle();
                                        } else {
                                            echo "ID: " .  $calendar->GetId() . " <span style='color: rgba(0, 0, 0, 0.5)'>(" . __("No Name", TLBM_TEXT_DOMAIN) . ")</span>";
                                        }
                                        ?>
                                    </label>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p>" . __("There are no calendars to select.", TLBM_TEXT_DOMAIN) . "<br><a href='".admin_url() . "/post-new.php?post_type=". TLBM_PT_CALENDAR ."'>".__("Create new calendar", TLBM_TEXT_DOMAIN)."</a></p>";
                            }
                        ?>
                    </div>
                </div>
			</td>
		</tr>
		<?php
	}

    /**
     * @param $name
     * @param $request_arr
     *
     * @return CalendarSelection
     */
	public static function GetCalendarSelectionFromRequest($name, $request_arr): CalendarSelection {
	    if(isset($request_arr[$name . '_selection']) && isset($request_arr[$name . '_select_type'])) {
		    $calendar_selection   = $request_arr[ $name . '_selection' ];
		    $calendar_select_type = $request_arr[ $name . '_select_type' ];

		    if ( is_array( $calendar_selection ) && in_array( $calendar_select_type, CalendarSelection::$select_types ) ) {
			    $calendar_selection_obj                        = new CalendarSelection();
			    $calendar_selection_obj->selected_calendar_ids = $calendar_selection;
			    $calendar_selection_obj->selection_type        = $calendar_select_type;

			    return $calendar_selection_obj;
		    }
	    }

	    return new CalendarSelection();
    }
}