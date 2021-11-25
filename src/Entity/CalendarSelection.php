<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="calendar_selections")
 */
class CalendarSelection {

	use IndexedTable;

	/**
	 * @OrmMapping\ManyToMany(targetEntity=Calendar::class, inversedBy="calendar_selection")
	 * @OrmMapping\JoinTable(
	 *     name="calendar_selection_calendar_mapping",
	 *     joinColumns={
	 *          @OrmMapping\JoinColumn(name="calendar_selection_id", referencedColumnName="id"),
	 *     },
	 *     inverseJoinColumns={
	 *          @OrmMapping\JoinColumn(name="calendar_id", referencedColumnName="id")
	 *     }
	 * )
	 * @var ArrayCollection|Calendar[]
	 */
	protected ArrayCollection $calendars;


	/**
	 * @var string
	 * @OrmMapping\Column(type="string", nullable=false)
	 */
	protected string $selection_mode = TLBM_CALENDAR_SELECTION_TYPE_ALL;

	/**
	 * @return Collection|Calendar[]
	 */
	public function GetCalendars(): Collection {
		return $this->calendars;
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
}