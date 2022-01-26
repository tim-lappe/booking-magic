<?php

namespace TLBM\Output\Contracts;

interface FormPrintInterface
{

    /**
     * @param $id
     *
     * @return string
     */
    public function printForm($id): string;
}