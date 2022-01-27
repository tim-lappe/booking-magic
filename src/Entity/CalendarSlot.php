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

    public function getBooking(): Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return Calendar
     */
    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getNameFromForm(): string
    {
        return $this->name_from_form;
    }

    /**
     * @param string $name_from_form
     */
    public function setNameFromForm(string $name_from_form): void
    {
        $this->name_from_form = $name_from_form;
    }

    /**
     * @return string
     */
    public function getTitleFromForm(): string
    {
        return $this->title_from_form;
    }

    /**
     * @param string $title_from_form
     */
    public function setTitleFromForm(string $title_from_form): void
    {
        $this->title_from_form = $title_from_form;
    }
}