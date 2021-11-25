<?php


namespace TLBM\Entity;

use Doctrine\ORM\Mapping as OrmMapping;
use phpDocumentor\Reflection\Types\This;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="calendar_booking_slots")
 */
class CalendarSlot {

	use IndexedTable;

	/**
	 * @var int
	 * @OrmMapping\Column (type="bigint", nullable=false)
	 */
	protected int $timestamp = 0;

	/**
	 * @var Booking
	 * @OrmMapping\OneToOne (targetEntity=Booking::class, inversedBy="calendar_slot")
	 */
	public Booking $booking;

	/**
	 * @var Calendar
	 * @OrmMapping\ManyToOne (targetEntity=Calendar::class, inversedBy="calendar_slot")
	 */
	public Calendar $calendar;

	/**
	 * @var Form
	 * @OrmMapping\ManyToOne (targetEntity=Form::class, inversedBy="calendar_slot")
	 */
	public Form $form;

	/**
	 * @var string
	 * @OrmMapping\Column (type="string", nullable=false)
	 */
	public string $name_from_form = "";

	/**
	 * @var string
	 * @OrmMapping\Column (type="string", nullable=false)
	 */
	public string $title_from_form = "";


	public function GetBooking(): Booking {
		return $this->booking;
	}

	/**
	 * @return int
	 */
	public function GetTimestamp(): int {
		return $this->timestamp;
	}

	/**
	 * @param int $timestamp
	 */
	public function SetTimestamp( int $timestamp ): void {
		$this->timestamp = $timestamp;
	}

	/**
	 * @return Calendar
	 */
	public function GetCalendar(): Calendar {
		return $this->calendar;
	}

	/**
	 * @param Calendar $calendar
	 */
	public function SetCalendar( Calendar $calendar ): void {
		$this->calendar = $calendar;
	}

	/**
	 * @return Form
	 */
	public function GetForm(): Form {
		return $this->form;
	}

	/**
	 * @param Form $form
	 */
	public function SetForm( Form $form ): void {
		$this->form = $form;
	}

	/**
	 * @return string
	 */
	public function GetNameFromForm(): string {
		return $this->name_from_form;
	}

	/**
	 * @param string $name_from_form
	 */
	public function SetNameFromForm( string $name_from_form ): void {
		$this->name_from_form = $name_from_form;
	}

	/**
	 * @return string
	 */
	public function GetTitleFromForm(): string {
		return $this->title_from_form;
	}

	/**
	 * @param string $title_from_form
	 */
	public function SetTitleFromForm( string $title_from_form ): void {
		$this->title_from_form = $title_from_form;
	}
}