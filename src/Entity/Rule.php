<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as OrmMapping;


/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="rules")
 */
class Rule implements \JsonSerializable {

	use IndexedTable;

	/**
	 * @var string
	 * @OrmMapping\Column (type="string", nullable=false)
	 */
	public string $title = "";

	/**
	 * @var int
	 * @OrmMapping\Column(type="bigint", nullable=false)
	 */
	protected int $timestamp_created = 0;

	/**
	 * @var CalendarSelection
	 * @OrmMapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true, cascade={"all"})
	 */
	public CalendarSelection $calendar_selection;

	/**
	 * @var int
	 * @OrmMapping\Column(type="integer", nullable=false)
	 */
	public int $priority = 0;

	/**
	 * @var Collection
	 * @OrmMapping\OneToMany(targetEntity=RuleAction::class, mappedBy="rule", orphanRemoval=true, cascade={"all"})
	 */
	public Collection $actions;

	/**
	 * @var Collection
	 * @OrmMapping\OneToMany(targetEntity=RulePeriod::class, mappedBy="rule", orphanRemoval=true, cascade={"all"})
	 */
	public Collection $periods;


	/**
	 * @param RuleAction $action
	 *
	 * @return RuleAction
	 */
	public function AddAction(RuleAction $action): RuleAction {
		if(!$this->actions->contains($action)) {
			$this->actions[] = $action;
			$action->SetRule($this);
		}

		return $action;
	}

	public function RemoveAction(RuleAction $action): RuleAction {
		if($this->actions->contains($action)) {
			$this->actions->removeElement($action);
			$action->SetRule(null);
		}

		return $action;
	}

    public function ClearActions() {
        foreach ($this->actions as $action) {
            $action->SetRule(null);
        }

        $this->actions->clear();
    }

    /**
     * @return Collection
     */
    public function GetActions(): Collection {
        return $this->actions;
    }

    /**
     * @return Collection
     */
    public function GetPeriods(): Collection {
        return $this->periods;
    }

	/**
	 * @param RulePeriod $period
	 *
	 * @return RulePeriod
	 */
	public function AddPeriod(RulePeriod $period): RulePeriod {
		if(!$this->periods->contains($period)) {
			$this->periods[] = $period;
			$period->SetRule($this);
		}

		return $period;
	}

	/**
	 * @param RulePeriod $period
	 *
	 * @return RulePeriod
	 */
	public function RemovePeriod(RulePeriod $period): RulePeriod {
		if($this->periods->contains($period)) {
			$this->periods->removeElement($period);
			$period->SetRule(null);
		}

		return $period;
	}

    public function ClearPeriods() {
        foreach ($this->periods as $period) {
            $period->SetRule(null);
        }

        $this->periods->clear();
    }

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
	 * @return CalendarSelection
	 */
	public function GetCalendarSelection(): CalendarSelection {
		return $this->calendar_selection;
	}

	/**
	 * @param CalendarSelection $calendar_selection
	 */
	public function SetCalendarSelection( CalendarSelection $calendar_selection ): void {
		$this->calendar_selection = $calendar_selection;
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
		$this->actions = new ArrayCollection();
		$this->periods = new ArrayCollection();
        $this->timestamp_created = time();
        $this->calendar_selection = new CalendarSelection();
	}

    public function jsonSerialize(): array {
        return array(
            "id" => $this->id,
            "title" => $this->title,
            "timestamp_created" => $this->timestamp_created,
            "calendar_selection" => $this->calendar_selection,
            "priority" => $this->priority,
            "actions" => $this->actions->toArray(),
            "periods" => $this->periods->toArray()
        );
    }
}