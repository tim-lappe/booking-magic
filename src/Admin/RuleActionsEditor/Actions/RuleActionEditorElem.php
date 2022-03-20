<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;


use JsonSerializable;

abstract class RuleActionEditorElem implements JsonSerializable
{
    /**
     * @var string
     */
    private string $name = "";

    /**
     * @var string
     */
    private string $title = "";

    /**
     * @var string
     */
    private string $category = "";

    /**
     * @var string
     */
    private string $description = "";


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
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
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
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {
        return ["title" => $this->title,
            "name" => $this->name,
            "description" => $this->description,
            "category" => $this->category
        ];
    }
}