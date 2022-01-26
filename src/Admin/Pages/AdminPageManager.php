<?php


namespace TLBM\Admin\Pages;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\PageBase;

class AdminPageManager implements AdminPageManagerInterface
{

    /**
     * @var PageBase[]
     */
    public array $pages = array();


    public function __construct()
    {
    }

    /**
     * @param string $class_name
     *
     * @return PageBase|null
     */
    public function getPage(string $class_name): ?PageBase
    {
        foreach ($this->pages as $page) {
            if ($page instanceof $class_name) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function loadMenuPages()
    {
        foreach ($this->pages as $page) {
            if (empty($page->parent_slug)) {
                if ($page->show_in_menu) {
                    add_menu_page($page->menu_title, $page->menu_title, $page->capabilities, $page->menu_slug, array(
                        $page,
                        "display"
                    ), $page->icon, 10);
                    if ( ! empty($page->menu_secondary_title)) {
                        add_submenu_page(
                            $page->menu_slug,
                            $page->menu_secondary_title,
                            $page->menu_secondary_title,
                            $page->capabilities,
                            $page->menu_slug,
                            array(
                                $page,
                                "display"
                            )
                        );
                    }
                } else {
                    add_submenu_page(
                        null,
                        $page->menu_title,
                        $page->menu_title,
                        $page->capabilities,
                        $page->menu_slug,
                        array(
                            $page,
                            "display"
                        )
                    );
                }
            } else {
                add_submenu_page(
                    $page->parent_slug,
                    $page->menu_title,
                    $page->menu_title,
                    $page->capabilities,
                    $page->menu_slug,
                    array(
                        $page,
                        "display"
                    )
                );
            }
        }
    }

    /**
     * @param object $page
     *
     * @return void
     */
    public function registerPage(object $page)
    {
        if ( ! isset($this->pages[get_class($page)]) && $page instanceof PageBase) {
            $this->pages[get_class($page)] = $page;
        }
    }
}