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
    protected int $timestamp_created = 0;
    /**
     * @var Collection|CalendarSelection[]
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity=CalendarSelection::class)
     */
    protected Collection $calendar_selections;

    public function __construct()
    {
        $this->calendar_selections = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getTimestampCreated(): int
    {
        return $this->timestamp_created;
    }

    /**
     * @param int $timestamp_created
     */
    public function setTimestampCreated(int $timestamp_created): void
    {
        $this->timestamp_created = $timestamp_created;
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
     * @return Collection|CalendarSelection[]
     */
    public function getCalendarSelections(): Collection
    {
        return $this->calendar_selections;
    }

    public function addCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection
    {
        if ( !$this->calendar_selections->contains($calendar_selection)) {
            $this->calendar_selections[] = $calendar_selection;
            $calendar_selection->addCalendar($this);
        }

        return $calendar_selection;
    }

    public function removeCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection
    {
        if ($this->calendar_selections->contains($calendar_selection)) {
            $this->calendar_selections->removeElement($calendar_selection);
            $calendar_selection->removeCalendar($this);
        }

        return $calendar_selection;
    }
}