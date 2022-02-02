<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendars")
 */
class Calendar
{

    use IndexedTable;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, unique=true)
     */
    protected string $title = "";

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $tstampCreated = 0;

    /**
     * @var Collection|CalendarSelection[]
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity=CalendarSelection::class)
     */
    protected Collection $calendarSelections;

    public function __construct()
    {
        $this->calendarSelections = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getTstampCreated(): int
    {
        return $this->tstampCreated;
    }

    /**
     * @param int $tstampCreated
     */
    public function setTstampCreated(int $tstampCreated): void
    {
        $this->tstampCreated = $tstampCreated;
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
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Collection|CalendarSelection[]
     */
    public function getCalendarSelections(): Collection
    {
        return $this->calendarSelections;
    }

    public function addCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection
    {
        if ( !$this->calendarSelections->contains($calendar_selection)) {
            $this->calendarSelections[] = $calendar_selection;
            $calendar_selection->addCalendar($this);
        }

        return $calendar_selection;
    }

    public function removeCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection
    {
        if ($this->calendarSelections->contains($calendar_selection)) {
            $this->calendarSelections->removeElement($calendar_selection);
            $calendar_selection->removeCalendar($this);
        }

        return $calendar_selection;
    }
}