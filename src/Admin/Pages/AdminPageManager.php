<?php


namespace TLBM\Admin\Pages;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\PageBase;
use TLBM\ApiUtils\Contracts\AdminPagesInterface;
use TLBM\MainFactory;

class AdminPageManager implements AdminPageManagerInterface
{

    /**
     * @var class-string<PageBase>[]
     */
    public array $pages = [];

    /**
     * @var PageBase[]
     */
    public array $pageInstances = [];

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
        foreach ($this->pageInstances as $page) {
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
        foreach ($this->pages as $pageClass) {

            /**
             * @var PageBase $page
             */
            $page = MainFactory::create($pageClass);
            $page->setAdminPageManager($this);

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

            $this->pageInstances[] = $page;
        }
    }

    /**
     * @param class-string<PageBase> $page
     *
     * @return void
     */
    public function registerPage(string $page)
    {
        if (!isset($this->pages[$page])) {
            $this->pages[$page] = $page;
        }
    }
}