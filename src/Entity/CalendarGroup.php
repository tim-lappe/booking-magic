<?php


namespace TLBM\Entity;


/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendar_groups")
 */
class CalendarGroup extends ManageableEntity
{

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
     * @var ?CalendarSelection
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true, cascade={"all"})
     */
    protected ?CalendarSelection $calendarSelection = null;

    /**
     * @param string $title
     * @param string $bookingDisitribution
     * @param CalendarSelection|null $selection
     */
    public function __construct(string $title = "", string $bookingDisitribution = TLBM_BOOKING_DISTRIBUTION_EVENLY, ?CalendarSelection $selection = null)
    {
        parent::__construct();

        $this->title = $title;
        $this->bookingDisitribution = $bookingDisitribution;
        $this->calendarSelection = $selection;
    }

    /**
     * @return ?CalendarSelection
     */
    public function getCalendarSelection(): ?CalendarSelection
    {
        return $this->calendarSelection;
    }

    /**
     * @param ?CalendarSelection $calendarSelection
     */
    public function setCalendarSelection(?CalendarSelection $calendarSelection): void
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