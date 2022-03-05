<?php

namespace TLBMTEST\CMS;

use TLBM\ApiUtils\Contracts\AdminPagesInterface;

class AdminPagesTestWrapper implements AdminPagesInterface
{

    /**
     * @inheritDoc
     */
    public function addMenuPage(string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback, string $icon, int $pos): void
    {

    }

    /**
     * @inheritDoc
     */
    public function addSubmenuPage(?string $parentSlug, string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback): void
    {

    }
}