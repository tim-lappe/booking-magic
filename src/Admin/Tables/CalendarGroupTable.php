<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\CalendarGroup;
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
     * @SuppressWarnings(PHPMD)
     * @param CalendarGroup $item
     */
    public function column_booking_distribution(CalendarGroup $item)
    {
        if ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_EVENLY) {
            echo "Evenly";
        } elseif ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_FILL_ONE) {
            echo "Fill One First";
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param CalendarGroup $item
     */
    public function column_selected_calendars(CalendarGroup $item)
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
     * @SuppressWarnings(PHPMD)
     * @param CalendarGroup $item
     */
    public function column_title(CalendarGroup $item)
    {
        $link = get_edit_post_link($item->getId());
        if ( !empty($item->getTitle())) {
            echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
        } else {
            echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param CalendarGroup $item
     */
    public function column_datetime(CalendarGroup $item)
    {
        $p = get_post($item->getId());
        echo $this->dateTimeTools->formatWithTime(strtotime($p->post_date));
    }

    protected function processBuldActions()
    {

    }

    /**
     * @param string $orderby
     * @param string $order
     * @SuppressWarnings(PHPMD)
     *
     * @return array
     */
    protected function getItems(string $orderby, string $order): array
    {
        $pt_args = array();
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            $pt_args = array("post_status" => "trash");
        }

        return $this->calendarGroupManager->getAllGroups($pt_args, $orderby, $order);
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
            "cb"                   => "<input type='checkbox' />",
            "title"                => __('Title', TLBM_TEXT_DOMAIN),
            "selected_calendars"   => __('Selected Calendars', TLBM_TEXT_DOMAIN),
            "booking_distribution" => __('Booking Distribution', TLBM_TEXT_DOMAIN),
            "datetime"             => __('Date', TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @return array[]
     */
    protected function getSortableColumns(): array
    {
        return array(
            'title'    => array('title', true),
            'datetime' => array('datetime', true)
        );
    }

    /**
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
     * @param $item
     *
     * @return int
     */
    protected function getItemId($item): int
    {
        return $item->wp_post_id;
    }

    /**
     * @return int
     */
    protected function getTotalItemsCount(): int
    {
        return $this->calendarGroupManager->getAllGroupsCount();
    }
}