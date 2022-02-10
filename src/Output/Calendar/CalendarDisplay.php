<?php


namespace TLBM\Output\Calendar;


use JsonSerializable;

class CalendarDisplay implements JsonSerializable
{
    /**
     * @var ?array
     */
    private ?array $calendarIds = [];

    /**
     * @var ?array
     */
    private ?array $groupIds = [];

    /**
     * @var string
     */
    private string $view = "no-view";

    /**
     * @var ?object
     */
    private ?object $viewSettings = null;

    /**
     * @var string
     */
    private string $inputName = "calendar";

    /**
     * @var bool
     */
    private bool $readonly = false;

    public function __construct()
    {

    }

    public function assignFromAssoc(array $data) {
        foreach ($data as $key => $value) {
            if($key == "viewSettings") {
                $this->viewSettings = (object)$value;
            } else {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array|null
     */
    public function getCalendarIds(): ?array
    {
        return $this->calendarIds;
    }

    /**
     * @param array|null $calendarIds
     */
    public function setCalendarIds(?array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
    }

    /**
     * @return array|null
     */
    public function getGroupIds(): ?array
    {
        return $this->groupIds;
    }

    /**
     * @param array|null $groupIds
     */
    public function setGroupIds(?array $groupIds): void
    {
        $this->groupIds = $groupIds;
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * @param string $view
     */
    public function setView(string $view): void
    {
        $this->view = $view;
    }

    /**
     * @return object|null
     */
    public function getViewSettings(): ?object
    {
        return $this->viewSettings;
    }

    /**
     * @param object|null $viewSettings
     */
    public function setViewSettings(?object $viewSettings): void
    {
        $this->viewSettings = $viewSettings;
    }

    /**
     * @return string
     */
    public function getInputName(): string
    {
        return $this->inputName;
    }

    /**
     * @param string $inputName
     */
    public function setInputName(string $inputName): void
    {
        $this->inputName = $inputName;
    }

    /**
     * @return bool
     */
    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    /**
     * @param bool $readonly
     */
    public function setReadonly(bool $readonly): void
    {
        $this->readonly = $readonly;
    }


    /**
     * @return string
     */
    public function getDisplayContent(): string
    {
        $jsonData = urlencode(json_encode($this));
        return sprintf('<div class="tlbm-calendar-container" data-json=\'%s\'></div>', $jsonData);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "calendarIds" => $this->calendarIds,
            "groupIds" => $this->groupIds,
            "view" => $this->view,
            "viewSettings" => $this->viewSettings,
            "inputName" => $this->inputName,
            "readonly" => $this->readonly
        ];
    }
}