<?php


namespace TLBM\Booking;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\Model\Booking;
use TLBM\Model\BookingValue;
use TLBM\Model\CalendarSlot;
use TLBM\Model\Form;
use TLBM\Model\FormElementsDataPack;

if ( ! defined('ABSPATH')) {
    return;
}

class BookingProcessing
{


    /**
     * @var array
     */
    private $input_vars;

    /**
     * @var Form
     */
    private $form;

    public function __construct($input_vars, $form)
    {
        $this->input_vars = $this->EscapeInputVars($input_vars);
        $this->form = $form;
    }

    public function EscapeInputVars($input_vars): array
    {
        $escaped = array();
        foreach ($input_vars as $key => $input_var) {
            $escaped[$key] = strip_tags($input_var);
        }

        return $escaped;
    }

    /**
     * @return FormElementsDataPack[]
     */
    public function Validate(): array
    {
        $form_data = $this->form->GetFormData();

        $elements       = $this->ParseFormDataToFormElements($form_data, $this->input_vars);
        $dps_not_filled = array();

        foreach ($elements as $dataPack) {
            if ( ! $dataPack->Validate() && $dataPack->GetSettingValue("required") == "yes") {
                $dps_not_filled[] = $dataPack;
            }
        }

        return $dps_not_filled;
    }

    /**
     * @param       $form_data
     * @param array $input_vars
     *
     * @return FormElementsDataPack[]
     */
    public function ParseFormDataToFormElements($form_data, array $input_vars = array()): array
    {
        $it       = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($form_data, RecursiveArrayIterator::CHILD_ARRAYS_ONLY),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $elements = array();
        foreach ($it as $key => $value) {
            if (is_array($value)) {
                if (isset($value['unique_name']) && ! empty($value['unique_name'])) {
                    $elem = FormElementsCollection::GetElemByUniqueName($value['unique_name']);
                    if ($elem != null) {
                        if ($elem->has_user_input) {
                            $dp               = new FormElementsDataPack();
                            $dp->form_element = $elem;
                            $dp->input_values = $input_vars;
                            $ed               = $value;
                            unset($ed['unique_name']);
                            unset($ed['children']);
                            $dp->element_data = $ed;
                            $elements[]       = $dp;
                        }
                    }
                }
            }
        }

        return $elements;
    }

    /**
     * @return Booking
     */
    public function GetProcessedBooking(): Booking
    {
        $booking                 = new Booking();
        $booking->booking_values = $this->ReadBookingValues();

        $form_datapacks = $this->ParseFormDataToFormElements($this->form->GetFormData(), $this->input_vars);
        foreach ($form_datapacks as $form_datapack) {
            if ($form_datapack->form_element instanceof CalendarElem) {
                $selected_calendar_or_group_id = $form_datapack->GetSettingValue('selected_calendar');
                $inputdata                     = $form_datapack->GetInputValue($form_datapack->GetSettingValue("name"));
                if ( ! empty($inputdata)) {
                    $calendar_slot                        = new CalendarSlot();
                    $calendar_slot->calendar_selection_id = intval($selected_calendar_or_group_id);
                    $calendar_slot->timestamp             = intval($inputdata);
                    $calendar_slot->name                  = $form_datapack->GetSettingValue("name");
                    $calendar_slot->form_id               = $this->form->wp_post_id;
                    $calendar_slot->title                 = $form_datapack->GetSettingValue("title");

                    $booked_cid = BookingCapacities::PreBookCalendarSeat($calendar_slot);
                    if ($booked_cid) {
                        $calendar_slot->booked_calendar_id = $booked_cid;
                        $booking->calendar_slots[]         = $calendar_slot;
                    }
                }
            }
        }

        return $booking;
    }

    /**
     * @return BookingValue[]
     */
    public function ReadBookingValues(): array
    {
        $form_data = $this->form->GetFormData();
        $elements  = $this->ParseFormDataToFormElements($form_data, $this->input_vars);

        $booking_values = array();

        foreach ($this->input_vars as $key => $input_var) {
            foreach ($elements as $dataPack) {
                if ( ! ($dataPack->form_element instanceof CalendarElem)) {
                    if ($dataPack->GetSettingValue("name") == $key) {
                        $bv        = new BookingValue();
                        $bv->key   = $key;
                        $bv->title = $dataPack->GetSettingValue("title");
                        $bv->value = $input_var;

                        $booking_values[$key] = $bv;
                    }
                }
            }
        }

        return $booking_values;
    }
}