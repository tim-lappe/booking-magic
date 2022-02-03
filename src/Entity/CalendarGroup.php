<?php


namespace TLBM\Entity;

const TLBM_BOOKING_DISTRIBUTION_EVENLY = "evenly";

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendar_groups")
 */
class CalendarGroup
{

    use IndexedTable;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, unique=true)
     */
    protected string $title = "";

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $bookingDisitribution = TLBM_BOOKING_DISTRIBUTION_EVENLY;


    /**
     * @var CalendarSelection
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true, cascade={"all"})
     */
    protected CalendarSelection $calendarSelection;

    /**
     * @return CalendarSelection
     */
    public function getCalendarSelection(): CalendarSelection
    {
        return $this->calendarSelection;
    }

    /**
     * @param CalendarSelection $calendarSelection
     */
    public function setCalendarSelection(CalendarSelection $calendarSelection): void
    {
        $this->calendarSelection = $calendarSelection;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBookingDisitribution(): string
    {
        return $this->bookingDisitribution;
    }

    /**
     * @param string $bookingDisitribution
     */
    public function setBookingDisitribution(string $bookingDisitribution): void
    {
        $this->bookingDisitribution = $bookingDisitribution;
    }
}