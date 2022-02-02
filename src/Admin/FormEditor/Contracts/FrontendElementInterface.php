<?php

namespace TLBM\Admin\FormEditor\Contracts;

use TLBM\Admin\FormEditor\LinkedFormData;

interface FrontendElementInterface
{
    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return mixed
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null);
}