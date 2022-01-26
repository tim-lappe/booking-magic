<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;
use TLBM\Booking\BookingManager;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Model\Booking;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class BookingListTable extends TableBase
{

    /**
     * @var bool
     */
    public bool $slim = false;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    /**
     * @var ColorsInterface
     */
    private ColorsInterface $colors;

    public function __construct(
        CalendarManagerInterface $calendarManager,
        DateTimeToolsInterface $dateTimeTools
    ) {
        $this->calendarManager = $calendarManager;
        $this->dateTimeTools   = $dateTimeTools;
        $this->colors          = new Colors();

        parent::__construct(
            __("Bookings", TLBM_TEXT_DOMAIN),
            __("Booking", TLBM_TEXT_DOMAIN),
            10,
            __("You don't have any bookings yet", TLBM_TEXT_DOMAIN)
        );
    }

    public function extra_tablenav($which)
    {
        if ($which == "top" && ! $this->slim) {
            $calendars = $this->calendarManager->getAllCalendars();
            ?>
            <div class="alignleft actions bulkactions">
                <select name="calendar-filter">
                    <option value=""><?php
                        _e("All Calendars", TLBM_TEXT_DOMAIN); ?></option>
                    <?php
                    foreach ($calendars as $calendar): ?>
                        <option <?php
                        echo selected($_REQUEST['calendar-filter'], $calendar->GetId()) ?> value="<?php
                        echo $calendar->GetId() ?>"><?php
                            echo $calendar->GetTitle() ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <button class="button">Filter</button>
            </div>
            <?php
        }
    }

    public function display_tablenav($which)
    {
        if ( ! $this->slim) {
            parent::display_tablenav($which);
        }
    }

    /**
     * @param Booking|object $item
     */
    public function column_id($item)
    {
        $link = get_edit_post_link($item->wp_post_id);
        echo "<strong><a href ='" . $link . "'># " . $item->wp_post_id . "</a></strong>";
    }

    /**
     * @param Booking|object $item
     */
    public function column_datetime($item)
    {
        $post = get_post($item->wp_post_id);
        echo $this->dateTimeTools->formatWithTime(strtotime($post->post_date));
    }

    /**
     * @param Booking|object $item
     */
    public function column_calendar($item)
    {
        $calslots = $item->calendar_slots;
        foreach ($calslots as $calendar_slot) {
            $calendar = $this->calendarManager->getCalendar($calendar_slot->booked_calendar_id);
            $link     = get_edit_post_link($calendar_slot->booked_calendar_id);
            $prefix   = "";
            if (sizeof($calslots) > 1) {
                $prefix = $calendar_slot->title . "&nbsp;&nbsp;&nbsp;";
            }

            if ($calendar) {
                echo $prefix . "<a href='" . $link . "'>" . $calendar->GetTitle(
                    ) . "</a>&nbsp;&nbsp;&nbsp;" . $this->dateTimeTools->formatWithTime(
                        $calendar_slot->timestamp
                    ) . "<br>";
            } else {
                echo $prefix . "<strong>" . __(
                        "Calendar deleted",
                        TLBM_TEXT_DOMAIN
                    ) . "</strong>&nbsp;&nbsp;&nbsp;" . $this->dateTimeTools->formatWithTime(
                        $calendar_slot->timestamp
                    ) . "<br>";
            }
        }

        if (sizeof($calslots) == 0) {
            echo "-";
        }
    }

    /**
     * @param Booking $item
     */
    public function column_state($item)
    {
        $state = BookingStates::GetStateByName($item->state);
        $rgb   = $this->colors->getRgbFromHex($state['color']);

        ?>
        <div class='tlbm-table-list-state' style="background-color: rgba(<?php
        echo $rgb[0] . "," . $rgb[1] . "," . $rgb[2] ?>, 0.4)">
            <strong><?php
                echo $state['title'] ?></strong>
        </div>
        <?php
    }

    /**
     * @param Booking $item
     *
     * @return string|void
     */
    public function column_cb($item): string
    {
        return '<input type="checkbox" name="wp_post_ids[]" value="' . $item->wp_post_id . '" />';
    }

    protected function ProcessBuldActions()
    {
        if (isset($_REQUEST['wp_post_ids']) && ! $this->slim) {
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

    protected function GetTotalItemsCount(): int
    {
        if ( ! $this->slim) {
            return BookingManager::GetAllBookingsCount();
        }

        return BookingManager::GetAllBookingsCount(array(
            "posts_per_page" => 5,
            "paged"          => false
        ));
    }

    protected function GetItems($orderby, $order): array
    {
        $pt_args = array();
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            $pt_args = array("post_status" => "trash");
        }

        $pt_args['posts_per_page'] = 10;
        if (isset($_REQUEST['paged'])) {
            $pt_args['paged'] = $_REQUEST['paged'];
        }

        if ($this->slim) {
            $pt_args['numberposts']    = 5;
            $pt_args['posts_per_page'] = 5;
            $pt_args['paged']          = false;
            $orderby                   = "datetime";
            $order                     = "DESC";
        }

        $filteredbookings = array();
        $bookings         = BookingManager::GetAllBookings($pt_args, $orderby, $order);
        foreach ($bookings as $booking) {
            $add = sizeof(
                       $booking->calendar_slots
                   ) == 0 && ( ! isset($_REQUEST['calendar-filter']) || empty($_REQUEST['calendar-filter']));
            if ( ! $add) {
                foreach ($booking->calendar_slots as $slot) {
                    if ( ! isset($_REQUEST['calendar-filter']) || empty($_REQUEST['calendar-filter']) || $slot->booked_calendar_id == $_REQUEST['calendar-filter']) {
                        $add = true;
                    }
                }
            }

            if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "new") {
                $add = $booking->state == DefaultBookingState::GetDefaultName() || empty($booking->state);
            }

            if ($add) {
                $filteredbookings[] = $booking;
            }
        }

        return $filteredbookings;
    }

    protected function GetViews(): array
    {
        if ( ! $this->slim) {
            return array(
                "all"     => __("All", TLBM_TEXT_DOMAIN),
                "new"     => __("New", TLBM_TEXT_DOMAIN),
                "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
            );
        }

        return array();
    }

    protected function GetColumns(): array
    {
        if ( ! $this->slim) {
            return array(
                "cb"       => "<input type='checkbox' />",
                "id"       => __('ID', TLBM_TEXT_DOMAIN),
                "calendar" => __('Calendar', TLBM_TEXT_DOMAIN),
                "state"    => __('State', TLBM_TEXT_DOMAIN),
                "datetime" => __('Date', TLBM_TEXT_DOMAIN)
            );
        }

        return array(
            "id"       => __('ID', TLBM_TEXT_DOMAIN),
            "calendar" => __('Calendar', TLBM_TEXT_DOMAIN),
            "state"    => __('State', TLBM_TEXT_DOMAIN),
            "datetime" => __('Date', TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetSortableColumns(): array
    {
        if ( ! $this->slim) {
            return array(
                'id'       => array('id', true),
                'datetime' => array('datetime', true)
            );
        }

        return array();
    }

    protected function GetBulkActions(): array
    {
        if ( ! $this->slim) {
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

        return array();
    }

    /**
     * @param Booking $item
     *
     * @return int
     */
    protected function GetItemId($item): int
    {
        return $item->wp_post_id;
    }

}