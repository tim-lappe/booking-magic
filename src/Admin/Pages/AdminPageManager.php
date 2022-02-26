<?php


namespace TLBM\Admin\Pages;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\PageBase;
use TLBM\CMS\Contracts\AdminPagesInterface;

class AdminPageManager implements AdminPageManagerInterface
{

    /**
     * @var PageBase[]
     */
    public array $pages = array();

    /**
     * @var AdminPagesInterface
     */
    private AdminPagesInterface $adminPages;

    public function __construct(AdminPagesInterface $adminPages)
    {
        $this->adminPages = $adminPages;
    }

    /**
     * @template T of PageBase
     * @param class-string<T> $class
     *
     * @return ?T
     */
    public function getPage(string $class)
    {
        foreach ($this->pages as $page) {
            if ($page instanceof $class) {
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
            if (empty($page->parentSlug)) {
                if ($page->showInMenu) {
                    $this->adminPages->addMenuPage($page->menuTitle, $page->menuTitle, $page->capabilities, $page->menuSlug, array(
                        $page,
                        "display"
                    ), $page->icon, 10);
                    if ( !empty($page->menuSecondaryTitle)) {
                        $this->adminPages->addSubmenuPage($page->menuSlug, $page->menuSecondaryTitle, $page->menuSecondaryTitle, $page->capabilities, $page->menuSlug, array(
                                                             $page,
                                                             "display"
                                                         ));
                    }
                } else {
                    $this->adminPages->addSubmenuPage(null, $page->menuTitle, $page->menuTitle, $page->capabilities, $page->menuSlug, array(
                                             $page,
                                             "display"
                                         ));
                }
            } else {
                $this->adminPages->addSubmenuPage($page->parentSlug, $page->menuTitle, $page->menuTitle, $page->capabilities, $page->menuSlug, array(
                                                       $page,
                                                       "display"
                                                   ));
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
        if ( !isset($this->pages[get_class($page)]) && $page instanceof PageBase) {
            $this->pages[get_class($page)] = $page;
            $page->setAdminPageManager($this);
        }
    }
}