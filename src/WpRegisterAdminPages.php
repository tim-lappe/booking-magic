<?php


namespace TLBM;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\BookingMagicRoot;
use TLBM\Admin\Pages\SinglePages\BookingsPage;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarPage;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Admin\Pages\SinglePages\FormPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Pages\SinglePages\RulesPage;
use TLBM\Admin\Pages\SinglePages\SettingsPage;

class WpRegisterAdminPages
{
    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container) {
        $adminPageManager = $container->get(AdminPageManagerInterface::class);

        $adminPageManager->registerPage($container->get(BookingMagicRoot::class));
        $adminPageManager->registerPage($container->get(BookingsPage::class));
        $adminPageManager->registerPage($container->get(CalendarPage::class));
        $adminPageManager->registerPage($container->get(CalendarEditPage::class));
        $adminPageManager->registerPage($container->get(RulesPage::class));
        $adminPageManager->registerPage($container->get(RuleEditPage::class));
        $adminPageManager->registerPage($container->get(FormPage::class));
        $adminPageManager->registerPage($container->get(FormEditPage::class));
        $adminPageManager->registerPage($container->get(SettingsPage::class));
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