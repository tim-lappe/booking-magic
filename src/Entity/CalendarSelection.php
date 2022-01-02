<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as OrmMapping;

define("TLBM_CALENDAR_SELECTION_TYPE_ALL", "all");
define("TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT", "all_but");
define("TLBM_CALENDAR_SELECTION_TYPE_ONLY", "only");


/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="calendar_selections")
 */
class CalendarSelection implements \JsonSerializable {

	use IndexedTable;

	/**
	 * @OrmMapping\ManyToMany(targetEntity=Calendar::class)
	 * @OrmMapping\JoinTable(
	 *     name="calendar_selection_calendar_mapping",
	 *     joinColumns={
	 *          @OrmMapping\JoinColumn(name="calendar_selection_id", referencedColumnName="id"),
	 *     },
	 *     inverseJoinColumns={
	 *          @OrmMapping\JoinColumn(name="calendar_id", referencedColumnName="id")
	 *     }
	 * )
	 * @var Collection|Calendar[]
	 */
	protected Collection $calendars;


	/**
	 * @var string
	 * @OrmMapping\Column(type="string", nullable=false)
	 */
	protected string $selection_mode = TLBM_CALENDAR_SELECTION_TYPE_ALL;

    /**
     * @return string
     */
    public function GetSelectionMode(): string {
        return $this->selection_mode;
    }

    /**
     * @param string $selection_mode
     * @return bool
     */
    public function SetSelectionMode(string $selection_mode): bool {
        if(self::IsValidSelectionMode($selection_mode)) {
            $this->selection_mode = $selection_mode;
            return true;
        }
        return false;
    }


	/**
	 * @return Collection|Calendar[]
	 */
	public function GetCalendars(): Collection {
		return $this->calendars;
	}

    /**
     * @return array
     */
    public function GetCalendarIds(): array {
        $calendars = $this->GetCalendars();
        $ids = array();
        foreach ($calendars as $calendar) {
            $ids[] = $calendar->GetId();
        }
        return $ids;
    }

	/**
	 * @param Calendar $calendar
	 *
	 * @return Calendar
	 */
	public function RemoveCalendar(Calendar $calendar): Calendar {
		if ($this->calendars->contains($calendar)) {
			$this->calendars->removeElement($calendar);
			$calendar->RemoveCalendarSelection($this);
		}

		return $calendar;
	}

	/**
	 * @param Calendar $calendar
	 *
	 * @return Calendar
	 */
	public function AddCalendar(Calendar $calendar): Calendar {
		if (!$this->calendars->contains($calendar)) {
			$this->calendars[] = $calendar;
			$calendar->AddCalendarSelection($this);
		}

		return $calendar;
	}

	public function __construct() {
		$this->calendars = new ArrayCollection();
	}

    public function jsonSerialize(): array {
        $calendars = $this->GetCalendars()->toArray();
        $cal_ids = array();
        foreach ($this->calendars as $cal) {
            $cal_ids[] = $cal->GetId();
        }

        return array(
            "selection_mode" => $this->GetSelectionMode(),
            "calendar_ids" => $cal_ids
        );
    }

    public static function IsValidSelectionMode($selection_mode): bool {
        return in_array($selection_mode, array(
            TLBM_CALENDAR_SELECTION_TYPE_ALL,
            TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT,
            TLBM_CALENDAR_SELECTION_TYPE_ONLY
        ));
    }
}