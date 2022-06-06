<?php

namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;


/**
 * @ORM\Entity()
 * @ORM\Table("calendar_tags")
 */
class CalendarCategory extends ManageableEntity implements JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected string $title = "";

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected string $description = "";

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity=Calendar::class, mappedBy="categories")
     */
    protected Collection $calendars;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->calendars = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return CalendarCategory
     */
    public function setDescription(string $description): CalendarCategory
    {
        $this->description = $description;

        return $this;
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
     *
     * @return CalendarCategory
     */
    public function setTitle(string $title): CalendarCategory
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    /**
     * @param Calendar[] $calendars
     *
     * @return void
     */
    public function setCalendars(array $calendars)
    {
        $this->calendars->clear();
        foreach ($calendars as $calendar) {
            $calendar->addCategory($this);
            $this->calendars->add($calendar);
        }
    }

    public function addCalendar(Calendar $calendar)
    {
        if(!$this->calendars->contains($calendar)) {
            $calendar->addCategory($this);
            $this->calendars->add($calendar);
        }
    }

    public function removeCalendar(Calendar $calendar)
    {
        if($this->calendars->contains($calendar)) {
            $this->calendars->remove($calendar);
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "description" => $this->getDescription()
        ];
    }
}