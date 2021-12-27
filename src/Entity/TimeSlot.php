<?php


namespace TLBM\Entity;

use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="rule_period_time_slots")
 */
class TimeSlot {

	use IndexedTable;

    /**
     * @var RulePeriod
     * @OrmMapping\ManyToOne (targetEntity=Rule::class)
     */
    protected RulePeriod $rule_period;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_hour;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_min;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $to_hour;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $to_min;

    /**
     * @return RulePeriod
     */
    public function GetRulePeriod(): RulePeriod {
        return $this->rule_period;
    }

    /**
     * @param RulePeriod $rule_period
     */
    public function SetRulePeriod(RulePeriod $rule_period): void {
        $this->rule_period = $rule_period;
    }

    /**
     * @return int
     */
    public function GetFromHour(): int {
        return $this->from_hour;
    }

    /**
     * @param int $from_hour
     */
    public function SetFromHour(int $from_hour): void {
        $this->from_hour = $from_hour;
    }

    /**
     * @return int
     */
    public function GetFromMin(): int {
        return $this->from_min;
    }

    /**
     * @param int $from_min
     */
    public function SetFromMin(int $from_min): void {
        $this->from_min = $from_min;
    }

    /**
     * @return int
     */
    public function GetToHour(): int {
        return $this->to_hour;
    }

    /**
     * @param int $to_hour
     */
    public function SetToHour(int $to_hour): void {
        $this->to_hour = $to_hour;
    }

    /**
     * @return int
     */
    public function GetToMin(): int {
        return $this->to_min;
    }

    /**
     * @param int $to_min
     */
    public function SetToMin(int $to_min): void {
        $this->to_min = $to_min;
    }


}