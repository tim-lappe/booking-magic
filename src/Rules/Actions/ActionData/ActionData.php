<?php

namespace TLBM\Rules\Actions\ActionData;

abstract class ActionData
{

    /**
     * @var mixed
     */
    protected $mixedActionData;

    /**
     * @param mixed $mixedActionData
     */
    public function __construct($mixedActionData)
    {
        $this->mixedActionData = $mixedActionData;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->mixedActionData;
    }
}