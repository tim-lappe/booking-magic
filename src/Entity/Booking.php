<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="bookings")
 */
class Booking {

	use IndexedTable;

	/**
	 * @var ArrayCollection
	 * @OrmMapping\OneToMany(targetEntity=BookingValue::class, mappedBy="booking", orphanRemoval=true)
	 */
	protected ArrayCollection $booking_values;

	/**
	 * @var ArrayCollection
	 * @OrmMapping\OneToMany(targetEntity=CalendarSlot::class, mappedBy="booking", orphanRemoval=true)
	 */
	protected ArrayCollection $calendar_slots;

	/**
	 * @var int
	 * @OrmMapping\Column(type="bigint", nullable=false)
	 */
	protected int $timestamp_created;

	/**
	 * @var string
	 * @OrmMapping\Column(type="string", nullable=false)
	 */
	protected string $state;

	/**
	 * @param CalendarSlot $calendar_slot
	 *
	 * @return CalendarSlot
	 */
	public function AddCalendarSlot(CalendarSlot $calendar_slot): CalendarSlot {
		if(!$this->calendar_slots->contains($calendar_slot)) {
			$this->calendar_slots[] = $calendar_slot;
			$calendar_slot->SetBooking($this);
		}

		return $calendar_slot;
	}

	/**
	 * @param CalendarSlot $calendar_slot
	 *
	 * @return CalendarSlot
	 */
	public function RemoveCalendarSlot(CalendarSlot $calendar_slot): CalendarSlot {
		if($this->calendar_slots->contains($calendar_slot)) {
			$this->calendar_slots->removeElement($calendar_slot);
		}

		return $calendar_slot;
	}

	/**
	 * @return ArrayCollection|CalendarSlot[]
	 */
	public function GetCalendarSlots(): ArrayCollection {
		return $this->calendar_slots;
	}

	/**
	 * @param BookingValue $booking_value
	 *
	 * @return BookingValue
	 */
	public function AddBookingValue(BookingValue $booking_value): BookingValue {
		if(!$this->booking_values->contains($booking_value)) {
			$this->booking_values[] = $booking_value;
			$booking_value->SetBooking($this);
		}

		return $booking_value;
	}

	/**
	 * @param BookingValue $booking_value
	 *
	 * @return BookingValue
	 */
	public function RemoveBookingValue(BookingValue  $booking_value): BookingValue {
		if($this->booking_values->contains($booking_value)) {
			$this->booking_values->removeElement($booking_value);
		}

		return $booking_value;
	}

	/**
	 * @return mixed
	 */
	public function GetBookingValues() {
		return $this->GetBookingValues();
	}

	public function __construct() {
		$this->booking_values = new ArrayCollection();
		$this->calendar_slots = new ArrayCollection();
	}
}