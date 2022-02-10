<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;

define("TLBM_CALENDAR_SELECTION_TYPE_ALL", "all");
define("TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT", "all_but");
define("TLBM_CALENDAR_SELECTION_TYPE_ONLY", "only");


/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendar_selections")
 */
class CalendarSelection implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity=Calendar::class)
     */
    protected Collection $calendars;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $selectionMode = TLBM_CALENDAR_SELECTION_TYPE_ALL;

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getCalendarIds(): array
    {
        $calendars = $this->getCalendars();
        $ids       = array();
        foreach ($calendars as $calendar) {
            $ids[] = $calendar->getId();
        }

        return $ids;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    /**
     * @param Calendar $calendar
     *
     * @return Calendar
     */
    public function removeCalendar(Calendar $calendar): Calendar
    {
        if ($this->calendars->contains($calendar)) {
            $this->calendars->removeElement($calendar);
        }

        return $calendar;
    }

    /**
     * @param Calendar $calendar
     *
     * @return Calendar
     */
    public function addCalendar(Calendar $calendar): Calendar
    {
        if ( !$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
        }

        return $calendar;
    }

    public function jsonSerialize(): array
    {
        $calendars = $this->getCalendars()->toArray();
        $cal_ids   = array();
        foreach ($this->calendars as $cal) {
            $cal_ids[] = $cal->getId();
        }

        return array(
            "selection_mode" => $this->getSelectionMode(),
            "calendar_ids"   => $cal_ids
        );
    }

    /**
     * @return string
     */
    public function getSelectionMode(): string
    {
        return $this->selectionMode;
    }

    /**
     * @param string $selectionMode
     *
     * @return bool
     */
    public function setSelectionMode(string $selectionMode): bool
    {
        if (self::isValidSelectionMode($selectionMode)) {
            $this->selectionMode = $selectionMode;

            return true;
        }

        return false;
    }

    public static function isValidSelectionMode($selection_mode): bool
    {
        return in_array($selection_mode, array(
            TLBM_CALENDAR_SELECTION_TYPE_ALL,
            TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT,
            TLBM_CALENDAR_SELECTION_TYPE_ONLY
        ));
    }
}