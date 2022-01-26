<?php

namespace TLBM\Admin\Pages\Contracts;

use TLBM\Admin\Pages\SinglePages\PageBase;

interface AdminPageManagerInterface
{
    /**
     * @param string $class_name
     *
     * @return PageBase|null
     */
    public function getPage(string $class_name): ?PageBase;

    /**
     * @return void
     */
    public function loadMenuPages();

    /**
     * @param object $page
     *
     * @return void
     */
    public function registerPage(object $page);
}