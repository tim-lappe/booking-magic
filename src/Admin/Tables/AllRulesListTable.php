<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Rule;
use TLBM\Entity\RulePeriod;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Utilities\ExtendedDateTime;

class AllRulesListTable extends TableBase
{

    /**
     * @var RulesManagerInterface
     */
    private RulesManagerInterface $rulesManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(RulesManagerInterface $rulesManager, CalendarManagerInterface $calendarManager, AdminPageManagerInterface $adminPageManager, SettingsManagerInterface $settingsManager)
    {
        $this->rulesManager     = $rulesManager;
        $this->calendarManager  = $calendarManager;
        $this->adminPageManager = $adminPageManager;
        $this->settingsManager = $settingsManager;

        parent::__construct(
            __("Rules", TLBM_TEXT_DOMAIN), __("Rule", TLBM_TEXT_DOMAIN), 10, __("You haven't created any rules yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Rule $item
     */
    public function column_calendars(Rule $item)
    {
        $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
        $selection = $item->getCalendarSelection();
        if ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            echo __("All", TLBM_TEXT_DOMAIN);
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarManager->getCalendar($id);
                $link = "";
                if($calendarEditPage instanceof CalendarEditPage) {
                    $link = $calendarEditPage->getEditLink($id);
                }

                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'>" . $cal->getTitle() . "</a>";
            }
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarManager->getCalendar($id);
                $link = "";
                if($calendarEditPage instanceof CalendarEditPage) {
                    $link = $calendarEditPage->getEditLink($id);
                }
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'><s>" . $cal->getTitle() . "</s></a>";
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Rule $item
     */
    public function column_title(Rule $item)
    {
        $ruleEditPage = $this->adminPageManager->getPage(RuleEditPage::class);
        if ($ruleEditPage instanceof RuleEditPage) {
            $link = $ruleEditPage->getEditLink($item->getId());
            if ( !empty($item->getTitle())) {
                echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
            } else {
                echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
            }
        }
    }

    /**
     * @param Rule $item
     * @SuppressWarnings(PHPMD)
     * @return string
     */
    public function column_priority(Rule $item): string
    {
        $levels = $this->settingsManager->getValue(PriorityLevels::class);
        return $levels[$item->getPriority()];
    }

    /**
     * @param Rule $rule
     *
     * @return string
     */
    public function column_periods(Rule $rule): string
    {
        $periods = $rule->getPeriods();
        $htmlarr = array();
        /**
         * @var RulePeriod $period
         */
        foreach ($periods as $period) {
            $html = "";
            $fromDt = new ExtendedDateTime($period->getFromTimestamp());
            $fromDt->setFullDay(!$period->isFromTimeset());

            if($period->getToTimestamp() != null) {
                $toDt = new ExtendedDateTime($period->getToTimestamp());
                $toDt->setFullDay(!$period->isToTimeset());

                if($fromDt->isSameDate($toDt)) {
                    $html .= sprintf(__("Only on <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format());
                } else {
                    $html .= sprintf(__("From <b>%s</b><br />Until <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format(), $toDt->format());
                }
            } else {
                $html .= sprintf(__("From <b>%s</b>", TLBM_TEXT_DOMAIN), $fromDt->format());
            }

            $htmlarr[] = $html;
        }

        if(count($htmlarr) == 0) {
            $htmlarr[] = __("Always", TLBM_TEXT_DOMAIN);
        }

        return implode("<br /><br />", $htmlarr);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    protected function processBuldActions()
    {

    }

    /**
     * @param $orderby
     * @param $order
     *
     * @return array
     */
    protected function getItems($orderby, $order): array
    {
        return $this->rulesManager->getAllRules(array(), $orderby, $order);
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
        return array(
            "cb"        => "<input type='checkbox' />",
            "title"     => __('Title', TLBM_TEXT_DOMAIN),
            "calendars" => __('Calendars', TLBM_TEXT_DOMAIN),
            "priority"  => __('Priority', TLBM_TEXT_DOMAIN),
            "periods" => __('Periods', TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @return array[]
     */
    protected function getSortableColumns(): array
    {
        return array(
            'title'    => array('title', true),
            'priority' => array('priority', true)
        );
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
     * @param Rule $item
     *
     * @return int
     */
    protected function getItemId($item): int
    {
        return $item->getId();
    }

    /**
     * @return int
     */
    protected function getTotalItemsCount(): int
    {
        return $this->rulesManager->getAllRulesCount();
    }
}