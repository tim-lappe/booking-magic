<?php

namespace TLBM\Email;


abstract class EmailSemantic
{

    /**
     * @return array
     */
    abstract public function getValues(): array;
}