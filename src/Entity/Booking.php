<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

const BOOKING_INTERNAL_STATE_RESERVED = "RESERVED";
const BOOKING_INTERNAL_STATE_COMPLETED = "COMPLETED";

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="bookings")
 */
class Booking
{

    use IndexedTable;

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
     */
    protected ?Form $form;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $tstampCreated;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $internalState = BOOKING_INTERNAL_STATE_RESERVED;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $state = "New";

    public function __construct()
    {
        $this->bookingValues    = new ArrayCollection();
        $this->calendarBookings = new ArrayCollection();
        $this->tstampCreated = time();
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
     * @return ArrayCollection|CalendarBooking[]
     */
    public function getCalendarBookings(): ArrayCollection
    {
        return $this->calendarBookings;
    }

    /**
     * @param BookingValue $booking_value
     *
     * @return BookingValue
     */
    public function addBookingValue(BookingValue $booking_value): BookingValue
    {
        if ( !$this->bookingValues->contains($booking_value)) {
            $this->bookingValues[] = $booking_value;
            $booking_value->setBooking($this);
        }

        return $booking_value;
    }

    /**
     * @param BookingValue $booking_value
     *
     * @return BookingValue
     */
    public function removeBookingValue(BookingValue $booking_value): BookingValue
    {
        if ($this->bookingValues->contains($booking_value)) {
            $this->bookingValues->removeElement($booking_value);
        }

        return $booking_value;
    }

    /**
     * @return mixed
     */
    public function getBookingValues()
    {
        return $this->getBookingValues();
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
}