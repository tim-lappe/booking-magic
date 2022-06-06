<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Entity\Traits\IndexedEntity;
use TLBM\MainFactory;


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
	 * @Doctrine\ORM\Mapping\ManyToMany(targetEntity=CalendarCategory::class)
	 */
	protected Collection $categories;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $selectionMode = TLBM_CALENDAR_SELECTION_TYPE_ALL;

    /**
     * @param string $selectionMode
     * @param array|null $calendars
     * @param array|null $categories
     */
    public function __construct(string $selectionMode = TLBM_CALENDAR_SELECTION_TYPE_ALL, ?array $calendars = null, ?array $categories = null)
    {
        if($calendars == null) {
            $this->calendars = new ArrayCollection();
        } else {
            $this->calendars = new ArrayCollection($calendars);
        }

        if($categories == null) {
            $this->categories = new ArrayCollection();
        } else {
            $this->categories = new ArrayCollection($categories);
        }

        $this->selectionMode = $selectionMode;
    }

    /**
     * @return array
     */
    public function getCombinedCalendarIds(): array
    {
        $calendars = $this->getCalendars();
        $ids       = array();
        foreach ($calendars as $calendar) {
            $ids[] = $calendar->getId();
        }

        /**
         * @var CalendarGroup[] $groups
         */
        $groups = $this->getCategories();
        foreach ($groups as $group) {
            $selectionHandler = MainFactory::create(CalendarSelectionHandler::class);
            $calendars = $selectionHandler->getSelectedCalendarList($group->getCalendarSelection());
            foreach ($calendars as $calendar) {
                if ( ! in_array($calendar->getId(), $ids)) {
                    $ids[] = $calendar->getId();
                }
            }
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
        $calendarIds   = array();
        foreach ($this->getCalendars() as $calendar) {
            $calendarIds[] = $calendar->getId();
        }

        $categoryIds = [];
        foreach($this->getCategories() as $category) {
            $categoryIds[] = $category->getId();
        }

        return array(
            "selection_mode" => $this->getSelectionMode(),
            "calendar_ids"   => $calendarIds,
            "tag_ids"      => $categoryIds
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

    /**
     * @return Collection<CalendarCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection<CalendarCategory> $categories
     */
    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @param CalendarCategory $category
     *
     * @return CalendarCategory
     */
    public function addCalendarCategory(CalendarCategory $category): CalendarCategory
    {
        if ( !$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $category;
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