<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Rule;
use TLBM\Rules\Contracts\RulesManagerInterface;

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

    public function __construct(RulesManagerInterface $rulesManager, CalendarManagerInterface $calendarManager, AdminPageManagerInterface $adminPageManager)
    {
        $this->rulesManager     = $rulesManager;
        $this->calendarManager  = $calendarManager;
        $this->adminPageManager = $adminPageManager;

        parent::__construct(
            __("Rules", TLBM_TEXT_DOMAIN), __("Rule", TLBM_TEXT_DOMAIN), 10, __("You haven't created any rules yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @param Rule $item
     */
    public function column_calendars(Rule $item)
    {
        $selection = $item->getCalendarSelection();
        if ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            echo __("All", TLBM_TEXT_DOMAIN);
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarManager->getCalendar($id);
                $link = get_edit_post_link($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'>" . $cal->getTitle() . "</a>";
            }
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarManager->getCalendar($id);
                $link = get_edit_post_link($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'><s>" . $cal->getTitle() . "</s></a>";
            }
        }
    }

    /**
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

    public function column_priority(Rule $item): int
    {
        return $item->getPriority();
    }

    protected function ProcessBuldActions()
    {
        if (isset($_REQUEST['wp_post_ids'])) {
            $ids = $_REQUEST['wp_post_ids'];

            $action = $this->current_action();
            foreach ($ids as $id) {
                if ($action == "delete") {
                    wp_update_post(array(
                                       "ID"          => $id,
                                       "post_status" => "trash"
                                   ));
                } elseif ($action == "delete_permanently") {
                    wp_delete_post($id);
                } elseif ($action == "restore") {
                    wp_update_post(array(
                                       "ID"          => $id,
                                       "post_status" => "publish"
                                   ));
                }
            }
        }
    }

    protected function GetItems($orderby, $order): array
    {
        $filteredbookings = array();

        return $this->rulesManager->getAllRules(array(), $orderby, $order);
    }

    protected function GetViews(): array
    {
        return array(
            "all"     => __("All", TLBM_TEXT_DOMAIN),
            "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetColumns(): array
    {
        return array(
            "cb"        => "<input type='checkbox' />",
            "title"     => __('Title', TLBM_TEXT_DOMAIN),
            "calendars" => __('Calendars', TLBM_TEXT_DOMAIN),
            "priority"  => __('Priority', TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetSortableColumns(): array
    {
        return array(
            'title'    => array('title', true),
            'priority' => array('priority', true)
        );
    }

    protected function GetBulkActions(): array
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
    protected function GetItemId($item): int
    {
        return $item->getId();
    }

    /**
     * @return int
     */
    protected function GetTotalItemsCount(): int
    {
        return $this->rulesManager->getAllRulesCount();
    }
}