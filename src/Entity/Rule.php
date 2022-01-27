<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;


/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rules")
 */
class Rule implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    public string $title = "";

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $timestamp_created = 0;

    /**
     * @var CalendarSelection
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=CalendarSelection::class, orphanRemoval=true, cascade={"all"})
     */
    protected CalendarSelection $calendar_selection;

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

    public function __construct()
    {
        $this->actions            = new ArrayCollection();
        $this->periods            = new ArrayCollection();
        $this->timestamp_created  = time();
        $this->calendar_selection = new CalendarSelection();
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
        return $this->calendar_selection;
    }

    /**
     * @param CalendarSelection $calendar_selection
     */
    public function setCalendarSelection(CalendarSelection $calendar_selection): void
    {
        $this->calendar_selection = $calendar_selection;
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
            "timestamp_created"  => $this->timestamp_created,
            "calendar_selection" => $this->calendar_selection,
            "priority"           => $this->priority,
            "actions"            => $this->actions->toArray(),
            "periods"            => $this->periods->toArray()
        );
    }
}