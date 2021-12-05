<?php


namespace TLBM\Booking;

use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\Calendar\CalendarManager;
use TLBM\Model\Booking;
use TLBM\Model\BookingValue;
use TLBM\Entity\Calendar;
use TLBM\Utilities\DateTimeTools;

class MainValues {

	private Booking $booking;
	private array $booking_values;

	/**
	 * @var Calendar[]
	 */
	private array $calendars = array();

	public function __construct(Booking $booking) {
		$this->booking = $booking;
		$this->booking_values = $booking->booking_values;

		foreach ($this->booking->calendar_slots as $calendar_slot) {
			$this->calendars[] = CalendarManager::GetCalendar($calendar_slot->booked_calendar_id);
		}
	}

	public function GetValue($name): string {
		if(isset($this->booking_values[$name])) {
			return $this->booking_values[$name]->value;
		}

		return "";
	}


	public function HasAll(...$names): bool {
		$arr_names = (array)$names;
		foreach ($arr_names as $name) {
			if(empty($this->GetValue($name))) {
				return false;
			}
		}
		return true;
	}

	public function HasOne(...$names): bool {
		$arr_names = (array)$names;
		foreach ($arr_names as $name) {
			if(!empty($this->GetValue($name))) {
				return true;
			}
		}
		return false;
	}

	public function GetBookingTitle(): string {
		$title = "";
		if($this->HasName()) {
			$title = $this->GetFullName();
		}
		if($this->HasCalendar()) {
			$title .= " " . $this->GetCalendarTimeFormat();
		}
		return trim($title);
	}

	public function HasContactEmail(): bool {
		return $this->HasOne("contact_email");
	}

	public function HasName(): bool {
		return $this->HasOne("first_name", "last_name");
	}

	public function HasAddress(): bool {
		return $this->HasOne("address", "zip", "city");
	}

	public function HasCalendar(): bool {
		return sizeof( $this->booking->calendar_slots ) > 0;
	}

	public function HasCustomValues(): bool {
		return sizeof($this->GetCustomValues()) > 0;
	}

	/**
	 * @return BookingValue[]
	 */
	public function GetCustomValues(): array {
		$elements = FormElementsCollection::GetRegisteredFormElements();
		$fixednames = array();
		$custom = array();
		foreach ($elements as $elem) {
			$st = $elem->GetSettingsType("name");
			if($st && $st->readonly) {
				$fixednames[] = $st->default_value;
			}
		}

		foreach ($this->booking_values as $booking_value) {
			if(!in_array($booking_value->key, $fixednames)) {
				$custom[] = $booking_value;
			}
		}

		return $custom;
	}

	public function GetCalendarCount(): int {
		return sizeof($this->calendars);
	}

	public function GetCalendarId($index = 0): int {
		if(isset($this->booking->calendar_slots[$index])) {
			return $this->booking->calendar_slots[$index]->booked_calendar_id;
		} else {
			return 0;
		}
	}

	public function GetCalendarTimeFormat($index = 0): string {
		if(isset($this->booking->calendar_slots[$index])) {
			return DateTimeTools::FormatWithTime( $this->booking->calendar_slots[ $index ]->timestamp );
		} else {
			return "";
		}
	}

	public function GetCalendarFormName($index = 0): string {
		if(isset($this->booking->calendar_slots[$index])) {
			return $this->booking->calendar_slots[$index]->title;
		}
		return "";
	}

	public function GetCalendarName($index = 0): string {
		if(isset($this->calendars[$index]) && $this->calendars[$index] instanceof Calendar) {
			return $this->calendars[$index]->GetTitle();
		}

		return "";
	}

	public function GetAddress($formatted = true): string {
		$address = "";

		if($this->HasOne("address")) {
			$address = $this->GetValue("address");
		}

		if($this->HasAll("city", "zip")) {
			if($formatted) {
				$address .= "<br>";
			}

			$address .= $this->GetValue("zip") . " " . $this->GetValue("city");
		} else if($this->HasOne("city", "zip")) {
			if($formatted) {
				$address .= "<br>";
			}

			$address .= $this->GetValue("zip") . $this->GetValue("city");
		}

		return $address;
	}

	public function GetContactEmail(): string {
		return $this->GetValue("contact_email");
	}

	public function GetFullName(): string {
		if($this->HasAll("first_name", "last_name")) {
			return $this->GetFirstName() . " " . $this->GetLastName();
		} else {
			return $this->GetFirstName() . $this->GetLastName();
		}
	}

	public function GetFirstName(): string {
		return $this->GetValue("first_name");
	}

	public function GetLastName(): string {
		return $this->GetValue("last_name");
	}
}