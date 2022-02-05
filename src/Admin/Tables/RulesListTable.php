<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\Admin\Tables\DisplayHelper\DisplayPeriods;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\RulesRepositoryInterface;
use TLBM\Repository\Query\RulesQuery;

class RulesListTable extends TableBase
{

    /**
     * @var RulesRepositoryInterface
     */
    private RulesRepositoryInterface $rulesManager;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(RulesRepositoryInterface $rulesManager, AdminPageManagerInterface $adminPageManager, SettingsManagerInterface $settingsManager)
    {
        $this->rulesManager     = $rulesManager;
        $this->adminPageManager = $adminPageManager;
        $this->settingsManager = $settingsManager;

        parent::__construct(
            __("Rules", TLBM_TEXT_DOMAIN), __("Rule", TLBM_TEXT_DOMAIN), 10, __("You haven't created any rules yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    protected function processBuldActions()
    {
        //TODO: Bulk Actions für Rules Tabelle implementieren

    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     */
    protected function getItems(string $orderby, string $order, int $page): array
    {
        $rulesQuery = MainFactory::create(RulesQuery::class);

        if($orderby == "title") {
            $rulesQuery->setOrderBy([[TLBM_RULE_QUERY_ALIAS . ".title", $order]]);
        } elseif ($orderby == "priority") {
            $rulesQuery->setOrderBy([[TLBM_RULE_QUERY_ALIAS . ".priority", $order]]);
        } else {
            $rulesQuery->setOrderBy([[TLBM_RULE_QUERY_ALIAS . ".priority", "desc"]]);
        }

        return iterator_to_array($rulesQuery->getResult());
    }

    /**
     * @return array
     */
    protected function getViews(): array
    {
        return array(
            "all"     => __("All", TLBM_TEXT_DOMAIN),
            "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            }),
            new Column("title", __("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
                $ruleEditPage = $this->adminPageManager->getPage(RuleEditPage::class);
                if ($ruleEditPage instanceof RuleEditPage) {
                    $link = $ruleEditPage->getEditLink($item->getId());
                    if ( !empty($item->getTitle())) {
                        echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                    } else {
                        echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                    }
                }
            }),
            new Column("calendars", __("Calendars", TLBM_TEXT_DOMAIN), false, function ($item) {
                $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                $selection = $item->getCalendarSelection();
                $selectionDisplay = MainFactory::create(DisplayCalendarSelection::class);
                $selectionDisplay->setCalendarSelection($selection);
                $selectionDisplay->display();

            }),
            new Column("priority", __("Priority", TLBM_TEXT_DOMAIN), true, function ($item) {
                $levels = $this->settingsManager->getValue(PriorityLevels::class);
                echo $levels[$item->getPriority()];

            }),
            new Column("periods", __("Periods", TLBM_TEXT_DOMAIN), false, function ($item) {
                $periods = $item->getPeriods();
                $periodsDisplay = MainFactory::create(DisplayPeriods::class);
                $periodsDisplay->setRulePeriods($periods);
                $periodsDisplay->display();
            }),
        ];
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    protected function getBulkActions(): array
    {
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            return array(
                'delete_permanently' => __('Delete permanently', TLBM_TEXT_DOMAIN),
                'restore'            => __('Restore', TLBM_TEXT_DOMAIN)
            );
        } else {
            return array(
                'delete' => __('Move to trash', TLBM_TEXT_DOMAIN)
            );
        }
    }

    /**
     * @return int
     */
    protected function getTotalItemsCount(): int
    {
        return $this->rulesManager->getAllRulesCount();
    }

    /**
     * @param string $which
     *
     * @return void
     */
    protected function tableNav(string $which): void
    {
        // TODO: Implement tableNav() method.
    }
}