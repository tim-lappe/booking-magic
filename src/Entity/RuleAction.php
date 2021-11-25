<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class RuleAction
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="rule_actions")
 */
class RuleAction {

	use IndexedTable;

	/**
	 * @var Rule
	 * @OrmMapping\OneToOne (targetEntity=Rule::class, inversedBy="rule_action")
	 */
	protected Rule $rule;

	/**
	 * @var string
	 * @OrmMapping\Column (type="string", nullable=false)
	 */
	protected string $action_type;

	/**
	 * @var string
	 * @OrmMapping\Column (type="string")
	 */
	protected string $weekdays;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $time_hour;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $time_min;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $priority;

	/**
	 * @var ArrayCollection
	 */
	protected ArrayCollection $values;

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
	 * @return string
	 */
	public function GetActionType(): string {
		return $this->action_type;
	}

	/**
	 * @param string $action_type
	 */
	public function SetActionType( string $action_type ): void {
		$this->action_type = $action_type;
	}

	/**
	 * @return string
	 */
	public function GetWeekdays(): string {
		return $this->weekdays;
	}

	/**
	 * @param string $weekdays
	 */
	public function SetWeekdays( string $weekdays ): void {
		$this->weekdays = $weekdays;
	}

	/**
	 * @return int
	 */
	public function GetTimeHour(): int {
		return $this->time_hour;
	}

	/**
	 * @param int $time_hour
	 */
	public function SetTimeHour( int $time_hour ): void {
		$this->time_hour = $time_hour;
	}

	/**
	 * @return int
	 */
	public function GetTimeMin(): int {
		return $this->time_min;
	}

	/**
	 * @param int $time_min
	 */
	public function SetTimeMin( int $time_min ): void {
		$this->time_min = $time_min;
	}

	/**
	 * @return int
	 */
	public function GetPriority(): int {
		return $this->priority;
	}

	/**
	 * @param int $priority
	 */
	public function SetPriority( int $priority ): void {
		$this->priority = $priority;
	}

	public function __construct() {
		$this->values = new ArrayCollection();
	}
}