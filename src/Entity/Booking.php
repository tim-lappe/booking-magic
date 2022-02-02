<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=BookingValue::class, mappedBy="booking", orphanRemoval=true)
     */
    protected Collection $bookingValues;

    /**
     * @var ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=CalendarSlot::class, mappedBy="booking", orphanRemoval=true)
     */
    protected Collection $calendarSlots;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $tstampCreated;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $state;

    public function __construct()
    {
        $this->bookingValues = new ArrayCollection();
        $this->calendarSlots = new ArrayCollection();
    }

    /**
     * @param CalendarSlot $calendar_slot
     *
     * @return CalendarSlot
     */
    public function addCalendarSlot(CalendarSlot $calendar_slot): CalendarSlot
    {
        if ( !$this->calendarSlots->contains($calendar_slot)) {
            $this->calendarSlots[] = $calendar_slot;
            $calendar_slot->setBooking($this);
        }

        return $calendar_slot;
    }

    /**
     * @param CalendarSlot $calendar_slot
     *
     * @return CalendarSlot
     */
    public function removeCalendarSlot(CalendarSlot $calendar_slot): CalendarSlot
    {
        if ($this->calendarSlots->contains($calendar_slot)) {
            $this->calendarSlots->removeElement($calendar_slot);
        }

        return $calendar_slot;
    }

    /**
     * @return ArrayCollection|CalendarSlot[]
     */
    public function getCalendarSlots(): ArrayCollection
    {
        return $this->calendarSlots;
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
}