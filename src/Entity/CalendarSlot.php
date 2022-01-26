<?php


namespace TLBM\Entity;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendar_booking_slots")
 */
class CalendarSlot
{

    use IndexedTable;

    /**
     * @var Booking
     * @Doctrine\ORM\Mapping\OneToOne (targetEntity=Booking::class)
     */
    public Booking $booking;
    /**
     * @var Calendar
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Calendar::class)
     */
    public Calendar $calendar;
    /**
     * @var Form
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Form::class)
     */
    public Form $form;
    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    public string $name_from_form = "";
    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    public string $title_from_form = "";
    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $timestamp = 0;

    public function GetBooking(): Booking
    {
        return $this->booking;
    }

    public function SetBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return int
     */
    public function GetTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function SetTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return Calendar
     */
    public function GetCalendar(): Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function SetCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    /**
     * @return Form
     */
    public function GetForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function SetForm(Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function GetNameFromForm(): string
    {
        return $this->name_from_form;
    }

    /**
     * @param string $name_from_form
     */
    public function SetNameFromForm(string $name_from_form): void
    {
        $this->name_from_form = $name_from_form;
    }

    /**
     * @return string
     */
    public function GetTitleFromForm(): string
    {
        return $this->title_from_form;
    }

    /**
     * @param string $title_from_form
     */
    public function SetTitleFromForm(string $title_from_form): void
    {
        $this->title_from_form = $title_from_form;
    }
}