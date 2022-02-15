<?php

namespace TLBM\Booking\Semantic;

class PredefinedValueFieldsCollection
{

    /**
     * @var PredefinedValueField[]
     */
    private array $predefinedValues;


    public function __construct()
    {

    }

    public function addField($name, $title, $description)
    {
        $pfiled = new PredefinedValueField();
        $pfiled->setTitle($title);
        $pfiled->setName($name);
        $pfiled->setDescription($description);
        $this->predefinedValues[$name] = $pfiled;
    }

    /**
     * @return PredefinedValueField[]
     */
    public function getPredefinedValues(): array
    {
        return $this->predefinedValues;
    }
}