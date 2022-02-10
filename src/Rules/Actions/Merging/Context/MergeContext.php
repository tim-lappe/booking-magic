<?php

namespace TLBM\Rules\Actions\Merging\Context;

class MergeContext
{
    /**
     * @var array
     */
    private array $values;

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $keyValues
     */
    public function setValues(array $keyValues): void
    {
        $this->values = $keyValues;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addValue(string $key, $value)
    {
        $this->values[$key] = $value;
    }
}