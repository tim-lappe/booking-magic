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
     * @param AdminPageManagerInterface $adminPageManager
     * @param string $menu_title
     * @param string $menu_slug
     * @param bool $show_in_menu
     * @param bool $display_default_head
     * @param string $display_default_head_title
     */
    public function __construct(
        AdminPageManagerInterface $adminPageManager,
        string $menu_title,
        string $menu_slug,
        bool $show_in_menu = true,
        bool $display_default_head = true,
        string $display_default_head_title = ""
    ) {
        $this->menu_title           = $menu_title;
        $this->menu_slug            = $menu_slug;
        $this->show_in_menu         = $show_in_menu;
        $this->display_default_head = $display_default_head;
        $this->adminPageManager = $adminPageManager;

        if (empty($display_default_head_title)) {
            $this->display_default_head_title = $menu_title;
        }

        global $plugin_page;
        if ($plugin_page == $menu_slug) {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
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