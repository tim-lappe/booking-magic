<?php


namespace TLBM;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;

class WpRegisterAdminPages
{
    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;


    /**
     * @param AdminPageManagerInterface $adminPageManager
     */
    public function __construct(AdminPageManagerInterface $adminPageManager)
    {
        $this->adminPageManager = $adminPageManager;
        add_action("admin_menu", array($this, "wpRegisterAdminPages"));
    }

    /**
     * @return void
     */
    public function wpRegisterAdminPages()
    {
        $this->adminPageManager->loadMenuPages();
    }
}