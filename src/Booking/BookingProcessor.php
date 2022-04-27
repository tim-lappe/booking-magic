<?php

namespace TLBM\Booking;

use InvalidArgumentException;
use TLBM\Admin\FormEditor\CalendarElementSettingHelper;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Entity\Booking;
use TLBM\Entity\BookingValue;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Entity\CalendarGroup;
use TLBM\Entity\Form;
use TLBM\MainFactory;
use TLBM\Repository\CacheManager;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Utilities\ExtendedDateTime;


class BookingProcessor
{
    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var mixed
     */
    private $vars = null;

    /**
     * @var ?Form
     */
    private ?Form $form = null;

    /**
     * @var BookingValueSemantic
     */
    private BookingValueSemantic $semantic;

    /**
     * @var LinkedFormData[]
     */
    private array $linkedFormDataFields;

    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    /**
     * @var CacheManager
     */
    private CacheManager $cacheManager;

    /**
     * @var ?Booking
     */
    private ?Booking $pendingBooking;

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        CalendarBookingManagerInterface $calendarBookingManager,
        CacheManager $cacheManager
    )
    {
        $this->entityRepository       = $entityRepository;
        $this->calendarBookingManager = $calendarBookingManager;
        $this->cacheManager           = $cacheManager;
    }

    /**
     * @param class-string|null $exclude
     *
     * @return LinkedFormData[]
     */
    public function validateVars(string $exclude = null): array
    {
        $formData = $this->form->getFormData();
        $formWalker = FormDataWalker::createFromData($formData);

        $invalidFields = [];
        foreach($formWalker->walkLinkedElements($this->getVars()) as $linkedFormData) {
            if(!$linkedFormData->validateInput() && !($exclude != null && $linkedFormData->getFormElement() instanceof $exclude)) {
                $invalidFields[] = $linkedFormData;
            }

            $this->linkedFormDataFields[] = $linkedFormData;
        }

        return $invalidFields;
    }

    /**
     * @param array $vars
     *
     * @return void
     */
    public function setVars(array $vars)
    {
        $form = $this->getForm() ?? (isset($vars['form']) ? $this->entityRepository->getEntity(Form::class, $vars['form']) : null);

        if($form) {
            $this->vars = $this->escapeVars($vars);
            $this->form = $form;
            $this->semantic = MainFactory::create(BookingValueSemantic::class);
            $this->semantic->setValues($vars);
            return;
        }

        throw new InvalidArgumentException("No form assigned");
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
            if(is_string($value) && is_string($key)) {
                $key   = htmlspecialchars($key);
                $value = htmlspecialchars($value);
            }

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
        $booking->setInternalState(TLBM_BOOKING_INTERNAL_STATE_PENDING);
        $booking->setForm($this->getForm());
        $bookingValues = $this->createBookingValues();
        $calendarBookings = $this->createCalendarBookings();

        if($calendarBookings !== null) {
            foreach ($calendarBookings as $calendarBooking) {
                $booking->addCalendarBooking($calendarBooking);
            }

            foreach ($bookingValues as $value) {
                $booking->addBookingValue($value);
            }

            if ($this->entityRepository->saveEntity($booking)) {
                $this->pendingBooking = $booking;

                return $booking;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function completeBooking(): bool
    {
        if(!$this->pendingBooking) {
            $this->reserveBooking();
        }

        if($this->pendingBooking) {
            $this->pendingBooking->setInternalState(TLBM_BOOKING_INTERNAL_STATE_COMPLETED);
            return $this->entityRepository->saveEntity($this->pendingBooking);
        }

        return false;
    }

    /**
     * @return ?CalendarBooking[]
     */
    public function createCalendarBookings(): ?array
    {
        $calendarBookings = [];
        foreach ($this->getLinkedFormDataFields() as $linkedFormData) {
            if ($linkedFormData->getFormElement() instanceof CalendarElem) {
                $lsettings       = $linkedFormData->getLinkedSettings();
                $title           = $lsettings->getValue("title");
                $name            = $lsettings->getValue("name");
                $value           = $linkedFormData->getInputVarByName($name);
                $value           = json_decode(urldecode($value), JSON_OBJECT_AS_ARRAY);

                $helper = MainFactory::create(CalendarElementSettingHelper::class);
                $helper->setSelectedCalendarSetting($lsettings->getValue("sourceId"));
                $selectedEntity =  $helper->getSelected();

                $nextCalendarBooking = null;
                if($selectedEntity instanceof Calendar) {
                    $nextCalendarBooking = $this->createSingleCalendarBooking($selectedEntity, $name, $title, $value);
                } elseif ($selectedEntity instanceof CalendarGroup) {
                    $nextCalendarBooking = $this->createNextCalendarBookingInGroup($selectedEntity, $name, $title, $value);
                }

                if($nextCalendarBooking) {
                   $calendarBookings[] = $nextCalendarBooking;
                } else {
                    return null;
                }
            }
        }

        return $calendarBookings;
    }

    /**
     * @param CalendarGroup $calendarGroup
     * @param string $name
     * @param string $title
     * @param mixed $value
     *
     * @return ?CalendarBooking
     */
    public function createNextCalendarBookingInGroup(CalendarGroup $calendarGroup, string $name, string $title, $value): ?CalendarBooking
    {
        $selectionHandler = MainFactory::create(CalendarSelectionHandler::class);
        $calendars = $selectionHandler->getSelectedCalendarList($calendarGroup->getCalendarSelection());

        $dateTime = new ExtendedDateTime();
        $dateTime->setFromObject($value);

        $evenlySlots = 0;
        $evenlyCalendar = null;
        $fillOneSlots = PHP_INT_MAX;
        $fillOneCalendar = null;

        foreach ($calendars as $calendar) {
            $slots = $this->calendarBookingManager->getRemainingSlots([ $calendar->getId() ], $dateTime);

            if($slots >= $evenlySlots && $slots > 0) {
                $evenlySlots = $slots;
                $evenlyCalendar = $calendar;
            }

            if($slots <= $fillOneSlots && $slots > 0) {
                $fillOneSlots = $slots;
                $fillOneCalendar = $calendar;
            }
        }

        if($calendarGroup->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_EVENLY && $evenlyCalendar != null) {
            return $this->createSingleCalendarBooking($evenlyCalendar, $name, $title, $value);
        } elseif ($calendarGroup->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_FILL_ONE && $fillOneCalendar != null) {
            return $this->createSingleCalendarBooking($fillOneCalendar, $name, $title, $value);
        }

        return null;
    }

    /**
     * @param Calendar $calendar
     * @param string $name
     * @param string $title
     * @param mixed $value
     *
     * @return ?CalendarBooking
     */
    private function createSingleCalendarBooking(Calendar $calendar, string $name, string $title, $value): ?CalendarBooking
    {
        $calendarBooking = new CalendarBooking();
        $dateTime = new ExtendedDateTime();
        $dateTime->setFromObject($value);

        $remaining = $this->calendarBookingManager->getRemainingSlots([ $calendar->getId() ], $dateTime);

        //Todo: muss noch auf Slots geprüft werden und nicht nur, ob noch mindestens ein Slot passen würde
        if(!$dateTime->isInvalid() && $remaining > 0) {
            $calendarBooking->setFromTimestamp($dateTime->getTimestamp());
            $calendarBooking->setToTimestamp($dateTime->getTimestamp());
            $calendarBooking->setFromFullDay($dateTime->isFullDay());
            $calendarBooking->setToFullDay($dateTime->isFullDay());

            $calendarBooking->setCalendar($calendar);
            $calendarBooking->setNameFromForm($name);
            $calendarBooking->setTitleFromForm($title);

            return $calendarBooking;
        }

        return null;
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
     * @return ?Form
     */
    public function getForm(): ?Form
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
    public function getPendingBooking(): ?Booking
    {
        return $this->pendingBooking;
    }

    public function setFromPendingBooking(Booking $pendingBooking)
    {
        $this->pendingBooking = $pendingBooking;
        $this->setForm($pendingBooking->getForm());
        $this->setVars($pendingBooking->getBookingKeyValuesPairs());
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
    }
}