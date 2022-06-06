<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendars")
 */
class Calendar extends ManageableEntity implements JsonSerializable
{
    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false, unique=true)
     */
    protected string $title = "";

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity=CalendarCategory::class, inversedBy="calendars", cascade={"all"})
     */
    protected Collection $categories;

    /**
     * @param string $title
     */
    public function __construct(string $title = "")
    {
        parent::__construct();
        $this->title = $title;
        $this->categories = new ArrayCollection();
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
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "title" => $this->title
        ];
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param CalendarCategory[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories->clear();
        foreach ($categories as $category) {
            $category->addCalendar($this);
            $this->categories->add($category);
        }
    }

    /**
     * @param CalendarCategory $category
     * @return void
     */
    public function removeCategory(CalendarCategory $category)
    {
        if($this->categories->contains($category)) {
            $this->categories->remove($category);
        }
    }

    /**
     * @param CalendarCategory $category
     * @return void
     */
    public function addCategory(CalendarCategory $category)
    {
        if(!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }
}