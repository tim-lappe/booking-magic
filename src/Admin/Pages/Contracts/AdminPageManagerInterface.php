<?php

namespace TLBM\Admin\Pages\Contracts;

use TLBM\Admin\Pages\SinglePages\PageBase;

interface AdminPageManagerInterface
{
    /**
     * @template T
     * @param class-string<T> $class
     *
     * @return ?T
     */
    public function getPage(string $class);

    /**
     * @return void
     */
    public function loadMenuPages();

    /**
     * @param class-string<PageBase> $page
     *
     * @return void
     */
    public function registerPage(string $page);
}