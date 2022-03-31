<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;


/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="bookings")
 */
class Booking extends ManageableEntity implements JsonSerializable
{

    /**
     * @var ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=BookingValue::class, mappedBy="booking", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $bookingValues;

    /**
     * @var ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=CalendarBooking::class, mappedBy="booking", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $calendarBookings;

    /**
     * @var ?Form
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Form::class)
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="SET NULL")
     */
    protected ?Form $form;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="text", nullable=false)
     */
    protected string $notes = "";

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $internalState = TLBM_BOOKING_INTERNAL_STATE_PENDING;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $state = "New";

    /**
     * @param string $state
     * @param Form|null $form
     * @param array|null $bookingValues
     * @param array|null $calendarBookings
     */
    public function __construct(string $state = "New", ?Form $form = null, ?array $bookingValues = null, ?array $calendarBookings = null)
    {
        parent::__construct();
        $this->state = $state;
        $this->form = $form;

        if($bookingValues == null) {
            $this->bookingValues = new ArrayCollection();
        } else {
            $this->bookingValues = new ArrayCollection($bookingValues);
        }

        if($calendarBookings == null) {
            $this->calendarBookings = new ArrayCollection();
        } else {
            $this->calendarBookings = new ArrayCollection($calendarBookings);
        }
    }

    /**
     * @param CalendarBooking $calendar_slot
     *
     * @return CalendarBooking
     */
    public function addCalendarBooking(CalendarBooking $calendar_slot): CalendarBooking
    {
        if ( !$this->calendarBookings->contains($calendar_slot)) {
            $this->calendarBookings[] = $calendar_slot;
            $calendar_slot->setBooking($this);
        }

        return $calendar_slot;
    }

    /**
     * @param CalendarBooking $calendar_slot
     *
     * @return CalendarBooking
     */
    public function removeCalendarBooking(CalendarBooking $calendar_slot): CalendarBooking
    {
        if ($this->calendarBookings->contains($calendar_slot)) {
            $this->calendarBookings->removeElement($calendar_slot);
        }

        return $calendar_slot;
    }

    /**
     * @return Collection|CalendarBooking[]
     */
    public function getCalendarBookings(): Collection
    {
        return $this->calendarBookings;
    }

    /**
     * @param string $name
     *
     * @return ?CalendarBooking
     */
    public function getCalendarBookingByName(string $name): ?CalendarBooking
    {
        foreach ($this->getCalendarBookings() as $calendarBooking) {
            if($calendarBooking->getNameFromForm() == $name) {
                return $calendarBooking;
            }
        }

        return null;
    }

    /**
     * @param BookingValue $bookingValue
     *
     * @return BookingValue
     */
    public function addBookingValue(BookingValue $bookingValue): BookingValue
    {
        if ( !$this->bookingValues->contains($bookingValue)) {
            $this->bookingValues[] = $bookingValue;
            $bookingValue->setBooking($this);
        }

        return $bookingValue;
    }

    /**
     * @param BookingValue $bookingValue
     *
     * @return BookingValue
     */
    public function removeBookingValue(BookingValue $bookingValue): BookingValue
    {
        if ($this->bookingValues->contains($bookingValue)) {
            $this->bookingValues->removeElement($bookingValue);
        }

        return $bookingValue;
    }

    public function removeBookingValueByName($name) {

        /**
         * @var BookingValue $value
         */
        foreach ($this->bookingValues as $value) {
            if($value->getName() == $name) {
                $this->removeBookingValue($value);
            }
        }
    }

    /**
     * @return Collection<BookingValue>
     */
    public function getBookingValues(): Collection
    {
        return $this->bookingValues;
    }

    /**
     * @return array
     */
    public function getBookingKeyValuesPairs(): array
    {
        $values = [];

        /**
         * @var BookingValue $bookingValue
         */
        foreach($this->getBookingValues() as $bookingValue) {
            $values[$bookingValue->getName()] = $bookingValue->getValue();
        }

        return $values;
    }

    /**
     * @return string
     */
    public function getInternalState(): string
    {
        return $this->internalState;
    }

    /**
     * @param string $internalState
     */
    public function setInternalState(string $internalState): void
    {
        $this->internalState = $internalState;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return ?Form
     */
    public function getForm(): ?Form
    {
        return $this->form;
    }

    /**
     * @param ?Form $form
     */
    public function setForm(?Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ["bookingValues" => $this->bookingValues->toArray(),
            "calendarBookings" => $this->calendarBookings->toArray(),
            "timestampCreated" => $this->timestampCreated,
            "id" => $this->id,
            "formId" => $this->form->getId()
        ];
    }

}