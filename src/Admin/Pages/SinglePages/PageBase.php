<?php


namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;

abstract class PageBase
{
    /**
     * @var string
     */
    public string $menuTitle;

    /**
     * @var string
     */
    public string $menuSecondaryTitle;

    /**
     * @var string
     */
    public string $capabilities = "manage_options";

    /**
     * @var string
     */
    public string $menuSlug = "";

    /**
     * @var string
     */
    public string $icon = "dashicons-calendar";

    /**
     * @var string
     */
    public string $parentSlug = "";

    /**
     * @var bool
     */
    public bool $showInMenu = true;

    /**
     * @var bool
     */
    public bool $displayDefaultHead = true;

    /**
     * @var string
     */
    public string $displayDefaultHeadTitle = "";

    /**
     * @var AdminPageManagerInterface
     */
    protected AdminPageManagerInterface $adminPageManager;

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

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
        $this->menuTitle    = $menuTitle;
        $this->menuSlug     = $menuSlug;
        $this->showInMenu = $showInMenu;
        $this->displayDefaultHead = $displayDefaultHead;

        $this->escaping = MainFactory::get(EscapingInterface::class);

        if (empty($defaultHeadTitle)) {
            $this->displayDefaultHeadTitle = $menuTitle;
        }

        global $plugin_page;
        if ($plugin_page == $menuSlug) {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return admin_url() . "admin.php?page=" . urlencode($this->menuSlug);
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
        if ($this->displayDefaultHead) {
            $this->displayDefaultHead();
        }

        echo "<h1></h1>";

        $this->displayPageBody();
    }

    public function displayDefaultHead()
    {
        ?>
        <div class="tlbm-admin-page-head">
            <span class="tlbm-admin-page-head-title"><?php echo $this->escaping->escHtml($this->getHeadTitle()); ?></span>
            <div class="tlbm-admin-page-head-bar">
                <?php $this->displayDefaultHeadBar() ?>
            </div>
        </div>
        <?php
    }

    protected function getHeadTitle(): string
    {
        return $this->displayDefaultHeadTitle;
    }

    public function displayDefaultHeadBar()
    {

    }

    abstract public function displayPageBody();
}