<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as OrmMapping;
use TLBM\Entity\Form;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="rule_periods")
 */
class RulePeriod {

	use IndexedTable;

	/**
	 * @var Rule
	 * @OrmMapping\ManyToOne (targetEntity=Rule::class)
	 */
	protected Rule $rule;

	/**
	 * @var ArrayCollection
	 * @OrmMapping\OneToMany (targetEntity=TimeSlot::class, mappedBy="rule_period", orphanRemoval=true, cascade={"all"})
	 */
	protected Collection $time_slots;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_day;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_month;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $from_year;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $to_day;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $to_month;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $to_year;


	public function AddTimeSlot(TimeSlot $slot): TimeSlot {
		if(!$this->time_slots->contains($slot)) {
			$this->time_slots[] = $slot;
		}

		return $slot;
	}

	public function RemoveTimeSlot(TimeSlot $slot): TimeSlot {
		if($this->time_slots->contains($slot)) {
			$this->time_slots->removeElement($slot);
		}
		return $slot;
	}

	/**
	 * @return Rule
	 */
	public function GetRule(): Rule {
		return $this->rule;
	}

	/**
	 * @param Rule $rule
	 */
	public function SetRule( Rule $rule ): void {
		$this->rule = $rule;
	}

	/**
	 * @return int
	 */
	public function GetFromDay(): int {
		return $this->from_day;
	}

	/**
	 * @param int $from_day
	 */
	public function SetFromDay( int $from_day ): void {
		$this->from_day = $from_day;
	}

	/**
	 * @return int
	 */
	public function GetFromMonth(): int {
		return $this->from_month;
	}

	/**
	 * @param int $from_month
	 */
	public function SetFromMonth( int $from_month ): void {
		$this->from_month = $from_month;
	}

	/**
	 * @return int
	 */
	public function GetFromYear(): int {
		return $this->from_year;
	}

	/**
	 * @param int $from_year
	 */
	public function SetFromYear( int $from_year ): void {
		$this->from_year = $from_year;
	}

	/**
	 * @return int
	 */
	public function GetToDay(): int {
		return $this->to_day;
	}

	/**
	 * @param int $to_day
	 */
	public function SetToDay( int $to_day ): void {
		$this->to_day = $to_day;
	}

	/**
	 * @return int
	 */
	public function GetToMonth(): int {
		return $this->to_month;
	}

	/**
	 * @param int $to_month
	 */
	public function SetToMonth( int $to_month ): void {
		$this->to_month = $to_month;
	}

	/**
	 * @return int
	 */
	public function GetToYear(): int {
		return $this->to_year;
	}

	/**
	 * @param int $to_year
	 */
	public function SetToYear( int $to_year ): void {
		$this->to_year = $to_year;
	}


	public function __construct() {
		$this->time_slots = new ArrayCollection();
	}
}