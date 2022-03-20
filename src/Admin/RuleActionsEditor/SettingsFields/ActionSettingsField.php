<?php

namespace TLBM\Admin\RuleActionsEditor\SettingsFields;

use JsonSerializable;

class ActionSettingsField implements JsonSerializable
{
    /**
     * @var string
     */
    public string $name = "";

    /**
     * @var string
     */
    public string $title = "";

    /**
     * @var string
     */
    public string $defaultValue = "";

    /**
     * @var string
     */
    public string $type = "";

    /**
     * @var mixed
     */
    public $options = null;


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
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue(string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function jsonSerialize(): array
    {
        return ["name" => $this->name,
            "title" => $this->title,
            "defaultValue" => $this->defaultValue,
            "type" => $this->type,
            "options" => $this->options
        ];
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }
}