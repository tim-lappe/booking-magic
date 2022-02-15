<?php

namespace TLBM\Admin\FormEditor\Contracts;

use TLBM\Admin\FormEditor\LinkedFormData;

interface AdminElementInterface
{
    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return mixed
     */
    public function getAdminContent(LinkedFormData $linkedFormData, callable $displayChildren = null);
}