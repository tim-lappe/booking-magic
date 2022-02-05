<?php


namespace TLBM\Booking;

use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Entity\Calendar;
use TLBM\Model\Booking;
use TLBM\Model\BookingValue;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class MainValues
{

    private Booking $booking;

    private array $booking_values;

    /**
     * @var Calendar[]
     */
    private array $calendars = array();

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $elementsCollection;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    public function __construct(
        CalendarRepositoryInterface $calendarManager,
        FormElementsCollectionInterface $elementsCollection,
        DateTimeToolsInterface $dateTimeTools,
        Booking $booking
    ) {
        $this->booking            = $booking;
        $this->booking_values     = $booking->booking_values;
        $this->calendarManager    = $calendarManager;
        $this->elementsCollection = $elementsCollection;
        $this->dateTimeTools      = $dateTimeTools;

        foreach ($this->booking->calendar_slots as $calendar_slot) {
            $this->calendars[] = $this->calendarManager->getCalendar($calendar_slot->booked_calendar_id);
        }
    }

    public function getBookingTitle(): string
    {
        $title = "";
        if ($this->hasName()) {
            $title = $this->getFullName();
        }
        if ($this->hasCalendar()) {
            $title .= " " . $this->getCalendarTimeFormat();
        }

        return trim($title);
    }

    public function hasName(): bool
    {
        return $this->hasOne("first_name", "last_name");
    }

    public function hasOne(...$names): bool
    {
        $arr_names = (array) $names;
        foreach ($arr_names as $name) {
            if ( !empty($this->getValue($name))) {
                return true;
            }
        }

        return false;
    }

    public function getValue($name): string
    {
        if (isset($this->booking_values[$name])) {
            return $this->booking_values[$name]->value;
        }

        return "";
    }

    public function getFullName(): string
    {
        if ($this->hasAll("first_name", "last_name")) {
            return $this->getFirstName() . " " . $this->getLastName();
        } else {
            return $this->getFirstName() . $this->getLastName();
        }
    }

    public function hasAll(...$names): bool
    {
        $arr_names = (array) $names;
        foreach ($arr_names as $name) {
            if (empty($this->getValue($name))) {
                return false;
            }
        }

        return true;
    }

    public function getFirstName(): string
    {
        return $this->getValue("first_name");
    }

    public function getLastName(): string
    {
        return $this->getValue("last_name");
    }

    public function hasCalendar(): bool
    {
        return sizeof($this->booking->calendar_slots) > 0;
    }

    public function getCalendarTimeFormat($index = 0): string
    {
        if (isset($this->booking->calendar_slots[$index])) {
            return $this->dateTimeTools->formatWithTime($this->booking->calendar_slots[$index]->timestamp);
        } else {
            return "";
        }
    }

    public function hasContactEmail(): bool
    {
        return $this->hasOne("contact_email");
    }

    public function hasAddress(): bool
    {
        return $this->hasOne("address", "zip", "city");
    }

    public function hasCustomValues(): bool
    {
        return sizeof($this->getCustomValues()) > 0;
    }

    /**
     * @return BookingValue[]
     */
    public function getCustomValues(): array
    {
        $elements   = $this->elementsCollection->getRegisteredFormElements();
        $fixednames = array();
        $custom     = array();
        foreach ($elements as $elem) {
            $st = $elem->getSettingsType("name");
            if ($st && $st->readonly) {
                $fixednames[] = $st->default_value;
            }
        }

        foreach ($this->booking_values as $booking_value) {
            if ( !in_array($booking_value->key, $fixednames)) {
                $custom[] = $booking_value;
            }
        }

        return $custom;
    }

    public function getCalendarCount(): int
    {
        return sizeof($this->calendars);
    }

    public function getCalendarId($index = 0): int
    {
        if (isset($this->booking->calendar_slots[$index])) {
            return $this->booking->calendar_slots[$index]->booked_calendar_id;
        } else {
            return 0;
        }
    }

    public function getCalendarFormName($index = 0): string
    {
        if (isset($this->booking->calendar_slots[$index])) {
            return $this->booking->calendar_slots[$index]->title;
        }

        return "";
    }

    public function getCalendarName($index = 0): string
    {
        if (isset($this->calendars[$index]) && $this->calendars[$index] instanceof Calendar) {
            return $this->calendars[$index]->getTitle();
        }

        return "";
    }

    public function getAddress($formatted = true): string
    {
        $address = "";

        if ($this->hasOne("address")) {
            $address = $this->getValue("address");
        }

        if ($this->hasAll("city", "zip")) {
            if ($formatted) {
                $address .= "<br>";
            }

            $address .= $this->getValue("zip") . " " . $this->getValue("city");
        } elseif ($this->hasOne("city", "zip")) {
            if ($formatted) {
                $address .= "<br>";
            }

            $address .= $this->getValue("zip") . $this->getValue("city");
        }

        return $address;
    }

    public function getContactEmail(): string
    {
        return $this->getValue("contact_email");
    }
}