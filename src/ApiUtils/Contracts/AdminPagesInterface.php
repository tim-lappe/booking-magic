<?php

namespace TLBM\ApiUtils\Contracts;

interface AdminPagesInterface
{
    /**
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param callable $callback
     * @param string $icon
     * @param int $pos
     *
     * @return void
     */
    public function addMenuPage(string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback, string $icon, int $pos): void;

    /**
     * @param string|null $parentSlug
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param callable $callback
     *
     * @return void
     */
    public function addSubmenuPage(?string $parentSlug, string $pageTitle, string $menuTitle, string $capability, string $menuSlug, callable $callback): void;

}