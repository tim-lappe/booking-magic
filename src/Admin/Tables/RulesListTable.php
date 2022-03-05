<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\Admin\Tables\DisplayHelper\DisplayPeriods;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Rule;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\BaseQuery;
use TLBM\Repository\Query\ManageableEntityQuery;

class RulesListTable extends ManagableEntityTable
{

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(EntityRepositoryInterface $entityRepository, AdminPageManagerInterface $adminPageManager, SettingsManagerInterface $settingsManager, LocalizationInterface $localization)
    {
        $this->entityRepository     = $entityRepository;
        $this->adminPageManager = $adminPageManager;
        $this->settingsManager = $settingsManager;

        parent::__construct(
            Rule::class, $localization->__("Rules", TLBM_TEXT_DOMAIN), $localization->__("Rule", TLBM_TEXT_DOMAIN), 10, $localization->__("You haven't created any rules yet", TLBM_TEXT_DOMAIN)
        );
    }

    protected function getQuery(?string $orderby, ?string $order, ?int $page): ManageableEntityQuery
    {
        $query = parent::getQuery($orderby, $order, $page);
        if($orderby == "title") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".title", $order]]);
        } elseif ($orderby == "priority") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".priority", $order]]);
        }

        return $query;
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = parent::getColumns();

        array_splice($columns, 1, 0, [
            new Column("title", $this->localization->__("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
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
            new Column("calendars", $this->localization->__("Calendars", TLBM_TEXT_DOMAIN), false, function ($item) {
                $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                $selection = $item->getCalendarSelection();
                $selectionDisplay = MainFactory::create(DisplayCalendarSelection::class);
                $selectionDisplay->setCalendarSelection($selection);
                $selectionDisplay->display();

            }),
            new Column("priority", $this->localization->__("Priority", TLBM_TEXT_DOMAIN), true, function ($item) {
                $levels = $this->settingsManager->getValue(PriorityLevels::class);
                echo $levels[$item->getPriority()];

            }),
            new Column("periods", $this->localization->__("Periods", TLBM_TEXT_DOMAIN), false, function ($item) {
                $periods = $item->getPeriods();
                $periodsDisplay = MainFactory::create(DisplayPeriods::class);
                $periodsDisplay->setRulePeriods($periods);
                $periodsDisplay->display();
            })
        ]);

        return $columns;
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