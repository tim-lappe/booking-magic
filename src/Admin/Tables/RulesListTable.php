<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\Admin\Tables\DisplayHelper\DisplayPeriods;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\Rule;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\ManageableEntityQuery;
use TLBM\Repository\Query\RulesQuery;

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

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param EntityRepositoryInterface $entityRepository
	 * @param AdminPageManagerInterface $adminPageManager
	 * @param SettingsManagerInterface $settingsManager
	 * @param LocalizationInterface $localization
	 */
    public function __construct(EscapingInterface $escaping, EntityRepositoryInterface $entityRepository, AdminPageManagerInterface $adminPageManager, SettingsManagerInterface $settingsManager, LocalizationInterface $localization)
    {
        $this->escaping = $escaping;
        $this->entityRepository = $entityRepository;
        $this->adminPageManager = $adminPageManager;
        $this->settingsManager  = $settingsManager;

        parent::__construct(
            Rule::class, $localization->getText("Rules", TLBM_TEXT_DOMAIN), $localization->getText("Rule", TLBM_TEXT_DOMAIN), 10, $localization->getText("You haven't created any rules yet", TLBM_TEXT_DOMAIN)
        );
    }

    protected function getQueryObject(): RulesQuery
    {
        return MainFactory::create(RulesQuery::class);
    }

    protected function getQuery(?string $orderby, ?string $order, ?int $page, bool $useCustomFilters = true): ManageableEntityQuery
    {
        $query = parent::getQuery($orderby, $order, $page);
        if ($query instanceof RulesQuery) {
            if ($orderby == "title") {
                $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".title", $order]]);
            } elseif ($orderby == "priority") {
                $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".priority", $order]]);
            }

            if ($useCustomFilters) {
                if (isset($_GET['filter_calendar'])) {
                    $filterCalendar = $this->entityRepository->getEntity(Calendar::class, intval($_GET['filter_calendar']));
                    if ($filterCalendar != null) {
                        $query->setFilterCalendarsIds([intval($_GET['filter_calendar'])]);
                    }
                }
            }

            return $query;
        }

        return MainFactory::create(RulesQuery::class);
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = parent::getColumns();

        array_splice($columns, 1, 0, [new Column("title", $this->localization->getText("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
            $ruleEditPage = $this->adminPageManager->getPage(RuleEditPage::class);
            if ($ruleEditPage instanceof RuleEditPage) {
                $link = $ruleEditPage->getEditLink($item->getId());
                if ( !empty($item->getTitle())) {
                    echo "<strong><a href='" . $this->escaping->escAttr($link) . "'>" . $this->escaping->escHtml($item->getTitle()) . "</a></strong>";
                } else {
                    echo "<strong><a href='" . $this->escaping->escAttr($link) . "'>" . $this->escaping->escHtml($item->getId()) . "</a></strong>";
                }
            }
        }),
            new Column("calendars", $this->localization->getText("Calendars", TLBM_TEXT_DOMAIN), false, function ($item) {
                $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                $selection        = $item->getCalendarSelection();
                $selectionDisplay = MainFactory::create(DisplayCalendarSelection::class);
                $selectionDisplay->setCalendarSelection($selection);
                $selectionDisplay->display();
            }),
            new Column("priority", $this->localization->getText("Priority", TLBM_TEXT_DOMAIN), true, function ($item) {
                $levels = $this->settingsManager->getValue(PriorityLevels::class);
                echo $this->escaping->escHtml($levels[$item->getPriority()]);
            }),
            new Column("periods", $this->localization->getText("Periods", TLBM_TEXT_DOMAIN), false, function ($item) {
                $periods        = $item->getPeriods();
                $periodsDisplay = MainFactory::create(DisplayPeriods::class);
                $periodsDisplay->setRulePeriods($periods);
                $periodsDisplay->display();
            })
        ]);

        return $columns;
    }

    protected function tableNav(string $witch): void
    {
        if ($witch == "top") {
            /**
             * @var Calendar[] $calendars
             */
            $calendars = $this->entityRepository->getEntites(Calendar::class);
            ?>
            <div class="alignleft actions bulkactions">
                <select name="filter_calendar">
                    <option value=""><?php $this->localization->echoText("All Calendars", TLBM_TEXT_DOMAIN); ?></option>
                    <?php
                    foreach ($calendars as $calendar): ?>
                        <option <?php selected($calendar->getId(), $this->sanitizing->sanitizeKey($_GET['filter_calendar'] ?? ""), true) ?> value="<?php echo $calendar->getId() ?>"><?php echo $this->escaping->escHtml($calendar->getTitle()) ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <button class="button" type="submit">Filter</button>
            </div>
            <?php
        }
    }
}