<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;


class CalendarListTable extends TableBase
{

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    public function __construct(CalendarManagerInterface $calendarManager, DateTimeToolsInterface $dateTimeTools)
    {
        $this->calendarManager = $calendarManager;
        $this->dateTimeTools   = $dateTimeTools;

        parent::__construct(
            __("Calendars", TLBM_TEXT_DOMAIN),
            __("Calendar", TLBM_TEXT_DOMAIN),
            10,
            __("You haven't created any calendars yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @param Calendar $item
     */
    public function column_title(Calendar $item)
    {
        $link = admin_url("admin.php?page=booking-calendar-edit&calendar_id=" . $item->GetId());

        if ( ! empty($item->GetTitle())) {
            echo "<strong><a href='" . $link . "'>" . $item->GetTitle() . "</a></strong>";
        } else {
            echo "<strong><a href='" . $link . "'>" . $item->GetId() . "</a></strong>";
        }
    }

    /**
     * @param Calendar $item
     */
    public function column_datetime(Calendar $item)
    {
        $p = get_post($item->GetId());
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

        return $this->calendarManager->getAllCalendars($pt_args, $orderby, $order);
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
            "cb"       => "<input type='checkbox' />",
            "title"    => __('Title', TLBM_TEXT_DOMAIN),
            "datetime" => __('Date', TLBM_TEXT_DOMAIN)
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

    /**
     * @param Calendar $item
     *
     * @return int
     */
    protected function GetItemId($item): int
    {
        return $item->GetId();
    }

    /**
     * @return int
     */
    protected function GetTotalItemsCount(): int
    {
        return $this->calendarManager->getAllCalendarsCount();
    }
}