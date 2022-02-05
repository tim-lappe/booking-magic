<?php

namespace TLBM\Admin\Tables;

class Column
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var bool
     */
    private bool $sortable;

    /**
     * @var callable
     */
    private $display;

    /**
     * @param string $name
     * @param string $title
     * @param bool $sortable
     * @param callable $display
     */
    public function __construct(string $name, string $title, bool $sortable, callable $display)
    {
        $this->name     = $name;
        $this->title    = $title;
        $this->sortable = $sortable;
        $this->display  = $display;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     */
    public function setSortable(bool $sortable): void
    {
        $this->sortable = $sortable;
    }

    /**
     * @return callable
     */
    public function getDisplay(): callable
    {
        return $this->display;
    }

    /**
     * @param callable $display
     */
    public function setDisplay(callable $display): void
    {
        $this->display = $display;
    }
}