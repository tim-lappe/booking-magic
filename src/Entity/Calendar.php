<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="calendars")
 */
class Calendar {

	use IndexedTable;

	/**
	 * @var string
	 * @OrmMapping\Column(type="string", nullable=false, unique=true)
	 */
	protected string $title;

	/**
	 * @var int
	 * @OrmMapping\Column(type="bigint", nullable=false)
	 */
	protected int $timestamp_created;

	/**
	 * @var ArrayCollection|CalendarSelection[]
	 * @OrmMapping\ManyToMany(targetEntity=CalendarSelection::class, mappedBy="calendar")
	 */
	protected ArrayCollection $calendar_selections;

	/**
	 * @return string
	 */
	public function GetTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function SetTitle( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @return Collection|CalendarSelection[]
	 */
	public function GetCalendarSelections(): Collection {
		return $this->calendar_selections;
	}

	public function AddCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection {
		if(!$this->calendar_selections->contains($calendar_selection)) {
			$this->calendar_selections[] = $calendar_selection;
			$calendar_selection->AddCalendar($this);
		}

		return $calendar_selection;
	}

	public function RemoveCalendarSelection(CalendarSelection $calendar_selection): CalendarSelection {
		if($this->calendar_selections->contains($calendar_selection)) {
			$this->calendar_selections->removeElement($calendar_selection);
			$calendar_selection->RemoveCalendar($this);
		}

		return $calendar_selection;
	}

	public function __construct() {
		$this->calendar_selections = new ArrayCollection();
	}
}