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
    protected string $booking_disitribution = TLBM_BOOKING_DISTRIBUTION_EVENLY;


    /**
     * @var CalendarSelection
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true)
     */
    protected CalendarSelection $calendar_selection;

    /**
     * @return CalendarSelection
     */
    public function getCalendarSelection(): CalendarSelection
    {
        return $this->calendar_selection;
    }

    /**
     * @param CalendarSelection $calendar_selection
     */
    public function setCalendarSelection(CalendarSelection $calendar_selection): void
    {
        $this->calendar_selection = $calendar_selection;
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
        return $this->booking_disitribution;
    }

    /**
     * @param string $booking_disitribution
     */
    public function setBookingDisitribution(string $booking_disitribution): void
    {
        $this->booking_disitribution = $booking_disitribution;
    }
}