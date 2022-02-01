<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;

abstract class PageBase
{

    public string $menu_title;

    public string $menu_secondary_title;

    public string $capabilities = "manage_options";

    public string $menu_slug = "";

    public string $icon = "dashicons-calendar";

    public string $parent_slug = "";

    public bool $show_in_menu = true;

    public bool $display_default_head = true;

    public string $display_default_head_title = "";

    /**
     * @var AdminPageManagerInterface
     */
    protected AdminPageManagerInterface $adminPageManager;


    /**
     * @param string $menuTitle
     * @param string $menuSlug
     * @param bool $showInMenu
     * @param bool $displayDefaultHead
     * @param string $defaultHeadTitle
     */
    public function __construct(
        string $menuTitle,
        string $menuSlug,
        bool $showInMenu = true,
        bool $displayDefaultHead = true,
        string $defaultHeadTitle = ""
    ) {
        $this->menu_title           = $menuTitle;
        $this->menu_slug            = $menuSlug;
        $this->show_in_menu         = $showInMenu;
        $this->display_default_head = $displayDefaultHead;

        if (empty($defaultHeadTitle)) {
            $this->display_default_head_title = $menuTitle;
        }

        global $plugin_page;
        if ($plugin_page == $menuSlug) {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }


    /**
     * @return AdminPageManagerInterface
     */
    public function getAdminPageManager(): AdminPageManagerInterface
    {
        return $this->adminPageManager;
    }

    /**
     * @param AdminPageManagerInterface $adminPageManager
     */
    public function setAdminPageManager(AdminPageManagerInterface $adminPageManager): void
    {
        $this->adminPageManager = $adminPageManager;
    }

    public function display()
    {
        if ($this->display_default_head) {
            $this->displayDefaultHead();
        }

        echo "<h1></h1>";

        $this->displayPageBody();
    }

    public function displayDefaultHead()
    {
        ?>
        <div class="tlbm-admin-page-head">
            <span class="tlbm-admin-page-head-title"><?php
                echo $this->getHeadTitle() ?></span>
            <div class="tlbm-admin-page-head-bar">
                <?php
                $this->displayDefaultHeadBar() ?>
            </div>
        </div>
        <?php
    }

    protected function getHeadTitle(): string
    {
        return $this->display_default_head_title;
    }

    public function displayDefaultHeadBar()
    {
    }

    abstract public function displayPageBody();
}