<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Model\CalendarGroup;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class CalendarGroupTable extends TableBase
{

    /**
     * @var CalendarGroupManagerInterface
     */
    private CalendarGroupManagerInterface $calendarGroupManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    public function __construct(
        CalendarGroupManagerInterface $calendarGroupManager,
        CalendarManagerInterface $calendarManager,
        DateTimeToolsInterface $dateTimeTools
    ) {
        $this->calendarGroupManager = $calendarGroupManager;
        $this->calendarManager      = $calendarManager;
        $this->dateTimeTools        = $dateTimeTools;

        parent::__construct(
            __("Groups", TLBM_TEXT_DOMAIN), __("Group", TLBM_TEXT_DOMAIN), 10, __("You haven't created any groups yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @param CalendarGroup $item
     */
    public function column_booking_distribution($item)
    {
        if ($item->booking_distribution == TLBM_BOOKING_DISTRIBUTION_EVENLY) {
            echo "Evenly";
        } elseif ($item->booking_distribution == TLBM_BOOKING_DISTRIBUTION_FILL_ONE) {
            echo "Fill One First";
        }
    }

    /**
     * @param CalendarGroup $item
     */
    public function column_selected_calendars($item)
    {
        $selection = $item->calendar_selection;
        if ($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            echo __("All", TLBM_TEXT_DOMAIN);
        } elseif ($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            foreach ($selection->selected_calendar_ids as $key => $id) {
                $cal  = $this->calendarManager->getCalendar($id);
                $link = get_edit_post_link($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'>" . $cal->getTitle() . "</a>";
            }
        } elseif ($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach ($selection->selected_calendar_ids as $key => $id) {
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
     * @param CalendarGroup $item
     */
    public function column_title($item)
    {
        $link = get_edit_post_link($item->wp_post_id);
        if ( !empty($item->title)) {
            echo "<strong><a href='" . $link . "'>" . $item->title . "</a></strong>";
        } else {
            echo "<strong><a href='" . $link . "'>" . $item->wp_post_id . "</a></strong>";
        }
    }

    /**
     * @param CalendarGroup $item
     */
    public function column_datetime($item)
    {
        $p = get_post($item->wp_post_id);
        echo $this->dateTimeTools->formatWithTime(strtotime($p->post_date));
    }

    protected function ProcessBuldActions()
    {
        if (isset($_REQUEST['wp_post_ids'])) {
            $ids    = $_REQUEST['wp_post_ids'];
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
        $pt_args = array();
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            $pt_args = array("post_status" => "trash");
        }

        return $this->calendarGroupManager->getAllGroups($pt_args, $orderby, $order);
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
            "cb"                   => "<input type='checkbox' />",
            "title"                => __('Title', TLBM_TEXT_DOMAIN),
            "selected_calendars"   => __('Selected Calendars', TLBM_TEXT_DOMAIN),
            "booking_distribution" => __('Booking Distribution', TLBM_TEXT_DOMAIN),
            "datetime"             => __('Date', TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetSortableColumns(): array
    {
        return array(
            'title'    => array('title', true),
            'datetime' => array('datetime', true)
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

    protected function GetItemId($item): int
    {
        return $item->wp_post_id;
    }

    /**
     * @return int
     */
    protected function GetTotalItemsCount(): int
    {
        return $this->calendarGroupManager->getAllGroupsCount();
    }
}