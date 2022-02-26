<?php

namespace TLBM\CMS;

class AdminPagesWrapper implements Contracts\AdminPagesInterface
{

    /**
     * @inheritDoc
     */
    public function addMenuPage(string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback, string $icon, int $pos): void
    {
        add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, $callback, $icon, $pos);
    }

    /**
     * @inheritDoc
     */
    public function addSubmenuPage(?string $parentSlug, string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback): void
    {
        add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $callback);
    }
}