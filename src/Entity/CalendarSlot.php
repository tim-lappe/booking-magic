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
    public string $nameFromForm = "";

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    public string $titleFromForm = "";

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $tstamp = 0;

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
    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    /**
     * @param int $tstamp
     */
    public function setTstamp(int $tstamp): void
    {
        $this->tstamp = $tstamp;
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
        return $this->nameFromForm;
    }

    /**
     * @param string $nameFromForm
     */
    public function setNameFromForm(string $nameFromForm): void
    {
        $this->nameFromForm = $nameFromForm;
    }

    /**
     * @return string
     */
    public function getTitleFromForm(): string
    {
        return $this->titleFromForm;
    }

    /**
     * @param string $titleFromForm
     */
    public function setTitleFromForm(string $titleFromForm): void
    {
        $this->titleFromForm = $titleFromForm;
    }
}