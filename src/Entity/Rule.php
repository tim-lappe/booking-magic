<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;


/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rules")
 */
class Rule extends ManageableEntity implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    public string $title = "";

    /**
     * @var CalendarSelection
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true, cascade={"all"})
     */
    protected CalendarSelection $calendarSelection;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=false)
     */
    protected int $priority = 0;

    /**
     * @var Collection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=RuleAction::class, mappedBy="rule", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $actions;

    /**
     * @var Collection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity=RulePeriod::class, mappedBy="rule", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $periods;

    /**
     * @param string $title
     * @param int $priority
     * @param CalendarSelection|null $calendarSelection
     * @param array|null $actions
     * @param array|null $periods
     */
    public function __construct(string $title = "", int $priority = 0, ?CalendarSelection $calendarSelection = null, ?array $actions = null, ?array $periods = null)
    {
        parent::__construct();
        $this->title = $title;
        $this->priority = $priority;

        if($calendarSelection == null) {
            $this->calendarSelection = new CalendarSelection();
        } else {
            $this->calendarSelection = $calendarSelection;
        }

        if($actions == null) {
            $this->actions = new ArrayCollection();
        } else {
            $this->actions = new ArrayCollection($actions);
        }

        if($periods == null) {
            $this->periods = new ArrayCollection();
        } else {
            $this->periods = new ArrayCollection($periods);
        }
    }

    /**
     * @param RuleAction $action
     *
     * @return RuleAction
     */
    public function addAction(RuleAction $action): RuleAction
    {
        if ( !$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setRule($this);
        }

        return $action;
    }

    public function removeAction(RuleAction $action): RuleAction
    {
        if ($this->actions->contains($action)) {
            $this->actions->removeElement($action);
            $action->setRule(null);
        }

        return $action;
    }

    public function clearActions()
    {
        foreach ($this->actions as $action) {
            $action->SetRule(null);
        }

        $this->actions->clear();
    }

    /**
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    /**
     * @return Collection
     */
    public function getPeriods(): Collection
    {
        return $this->periods;
    }

    /**
     * @param RulePeriod $period
     *
     * @return RulePeriod
     */
    public function addPeriod(RulePeriod $period): RulePeriod
    {
        if ( !$this->periods->contains($period)) {
            $this->periods[] = $period;
            $period->setRule($this);
        }

        return $period;
    }

    /**
     * @param RulePeriod $period
     *
     * @return RulePeriod
     */
    public function removePeriod(RulePeriod $period): RulePeriod
    {
        if ($this->periods->contains($period)) {
            $this->periods->removeElement($period);
            $period->setRule(null);
        }

        return $period;
    }

    public function clearPeriods()
    {
        foreach ($this->periods as $period) {
            $period->SetRule(null);
        }

        $this->periods->clear();
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
     * @return CalendarSelection
     */
    public function getCalendarSelection(): CalendarSelection
    {
        return $this->calendarSelection;
    }

    /**
     * @param CalendarSelection $calendarSelection
     */
    public function setCalendarSelection(CalendarSelection $calendarSelection): void
    {
        $this->calendarSelection = $calendarSelection;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function jsonSerialize(): array
    {
        return array(
            "id"                 => $this->id,
            "title"              => $this->title,
            "timestamp_created"  => $this->timestampCreated,
            "calendar_selection" => $this->calendarSelection,
            "priority"           => $this->priority,
            "actions"            => $this->actions->toArray(),
            "periods"            => $this->periods->toArray()
        );
    }
}