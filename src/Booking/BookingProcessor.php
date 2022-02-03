<?php

namespace TLBM\Booking;

use Exception;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Booking\Contracts\BookingManagerInterface;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Booking;
use TLBM\Entity\BookingValue;
use TLBM\Entity\CalendarBooking;
use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\MainFactory;
use TLBM\Utilities\ExtendedDateTime;

use const TLBM\Entity\BOOKING_INTERNAL_STATE_COMPLETED;
use const TLBM\Entity\BOOKING_INTERNAL_STATE_RESERVED;

class BookingProcessor
{
    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var mixed
     */
    private $vars;

    /**
     * @var Form
     */
    private Form $form;

    /**
     * @var BookingValueSemantic
     */
    private BookingValueSemantic $semantic;

    /**
     * @var BookingManagerInterface
     */
    private BookingManagerInterface $bookingManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;


    /**
     * @var LinkedFormData[]
     */
    private array $linkedFormDataFields;

    /**
     * @var ?Booking
     */
    private ?Booking $reservedBooking;

    public function __construct
    (
        FormManagerInterface $formManager,
        BookingManagerInterface $bookingManager,
        CalendarManagerInterface $calendarManager
    )
    {
        $this->formManager = $formManager;
        $this->bookingManager = $bookingManager;
        $this->calendarManager = $calendarManager;
    }

    /**
     * @return LinkedFormData[]
     */
    public function validateVars(): array
    {
        $formData = $this->form->getFormData();
        $formWalker = FormDataWalker::createFromData($formData);

        $invalidFields = [];
        foreach($formWalker->walkLinkedElements($this->getVars()) as $linkedFormData) {
            if(!$linkedFormData->validateInput()) {
                $invalidFields[] = $linkedFormData;
            }

            $this->linkedFormDataFields[] = $linkedFormData;
        }

        return $invalidFields;
    }

    /**
     * @param mixed $vars
     *
     * @return void
     */
    public function setVars($vars)
    {
        if(isset($vars['form'])) {
            $form = $this->formManager->getForm($vars['form']);
            if($form) {
                $this->vars = $this->escapeVars($vars);
                $this->form = $form;
                $this->semantic = MainFactory::create(BookingValueSemantic::class);
                $this->semantic->setValues($vars);
            }
        }

        $this->vars = $vars;
    }

    /**
     * @param array $vars
     *
     * @return array
     */
    private function escapeVars(array $vars): array
    {
        $escapedVars = [];
        foreach ($vars as $key => $value) {
            $key = htmlspecialchars($key);
            $value = htmlspecialchars($value);
            $escapedVars[$key] = $value;
        }

        return $escapedVars;
    }

    /**
     * @return mixed
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return ?Booking
     */
    public function reserveBooking(): ?Booking
    {
        $booking = new Booking();
        $booking->setInternalState(BOOKING_INTERNAL_STATE_RESERVED);
        $booking->setForm($this->getForm());
        $bookingValues = $this->createBookingValues();
        $calendarBookings = $this->createCalendarBookings();

        foreach ($calendarBookings as $calendarBooking) {
            $booking->addCalendarBooking($calendarBooking);
        }
        foreach ($bookingValues as $value) {
            $booking->addBookingValue($value);
        }

        try {
            $this->bookingManager->saveBooking($booking);
            $this->reservedBooking = $booking;
            return $booking;
        } catch (Exception $e) {
            if(WP_DEBUG) {
                var_dump($e->getMessage());
            }

            return null;
        }
    }

    /**
     * @return bool
     */
    public function completeBooking(): bool
    {
        if(!$this->reservedBooking) {
            $this->reserveBooking();
        }

        if($this->reservedBooking) {
            $this->reservedBooking->setInternalState(BOOKING_INTERNAL_STATE_COMPLETED);

            try {
                $this->bookingManager->saveBooking($this->reservedBooking);
                return true;
            } catch (Exception $e) {
                if(WP_DEBUG) {
                    var_dump($e->getMessage());
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function createCalendarBookings(): array
    {
        $calendarBookings = [];
        foreach ($this->getLinkedFormDataFields() as $linkedFormData) {
            if ($linkedFormData->getFormElement() instanceof CalendarElem) {
                $lsettings    = $linkedFormData->getLinkedSettings();
                $calendarId   = $lsettings->getValue("selected_calendar");
                $calendar     = $this->calendarManager->getCalendar($calendarId);
                if($calendar != null) {
                    $calendarBooking = new CalendarBooking();
                    $title           = $lsettings->getValue("title");
                    $name            = $lsettings->getValue("name");
                    $value           = $linkedFormData->getInputVarByName($name);
                    $value           = json_decode(urldecode($value), JSON_OBJECT_AS_ARRAY);

                    $dateTime = new ExtendedDateTime();
                    $dateTime->setFromObject($value);

                    $calendarBooking->setFromTimestamp($dateTime->getTimestamp());
                    $calendarBooking->setToTimestamp($dateTime->getTimestamp());
                    $calendarBooking->setFromFullDay($dateTime->isFullDay());
                    $calendarBooking->setToFullDay($dateTime->isFullDay());

                    $calendarBooking->setCalendar($calendar);
                    $calendarBooking->setNameFromForm($name);
                    $calendarBooking->setTitleFromForm($title);
                    $calendarBookings[] = $calendarBooking;
                }
            }
        }

        return $calendarBookings;
    }

    /**
     * @return BookingValue[]
     */
    public function createBookingValues(): array
    {
        $bookingValues = [];
        foreach ($this->getLinkedFormDataFields() as $linkedFormData) {
            if(!($linkedFormData->getFormElement() instanceof CalendarElem)) {
                $bookingValue = new BookingValue();
                $lsettings    = $linkedFormData->getLinkedSettings();
                $title        = $lsettings->getValue("title");
                $name         = $lsettings->getValue("name");
                $value        = $linkedFormData->getInputVarByName($name);

                $bookingValue->setTitle($title);
                $bookingValue->setValue($value);
                $bookingValue->setName($name);
                $bookingValues[] = $bookingValue;
            }
        }

        return $bookingValues;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @return BookingValueSemantic
     */
    public function getSemantic(): BookingValueSemantic
    {
        return $this->semantic;
    }

    /**
     * @param BookingValueSemantic $semantic
     */
    public function setSemantic(BookingValueSemantic $semantic): void
    {
        $this->semantic = $semantic;
    }

    /**
     * @return LinkedFormData[]
     */
    public function getLinkedFormDataFields(): array
    {
        return $this->linkedFormDataFields;
    }

    /**
     * @return Booking|null
     */
    public function getReservedBooking(): ?Booking
    {
        return $this->reservedBooking;
    }
}