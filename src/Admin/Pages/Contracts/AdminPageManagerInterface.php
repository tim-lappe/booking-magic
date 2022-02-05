<?php

namespace TLBM\Admin\Pages\Contracts;

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
     * @param object $page
     *
     * @return void
     */
    public function registerPage(object $page);
}