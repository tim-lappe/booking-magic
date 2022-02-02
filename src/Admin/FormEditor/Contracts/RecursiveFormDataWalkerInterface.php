<?php

namespace TLBM\Admin\FormEditor\Contracts;

use TLBM\Admin\FormEditor\Elements\FormElem;

interface RecursiveFormDataWalkerInterface
{
    /**
     * @param array $formNode
     * @param ?FormElem $element
     * @param array $children
     * @param callable|null $childCallback
     *
     * @return mixed
     */
    public function walk(array $formNode, ?FormElem $element, array $children, callable $childCallback = null);
}