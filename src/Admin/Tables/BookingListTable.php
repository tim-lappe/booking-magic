<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;
use TLBM\Booking\BookingManager;
use TLBM\Booking\Contracts\BookingManagerInterface;
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
     * @var BookingManagerInterface
     */
    private BookingManagerInterface $bookingManager;

    /**
     * @var ColorsInterface
     */
    private ColorsInterface $colors;

    public function __construct(
        CalendarManagerInterface $calendarManager,
        DateTimeToolsInterface $dateTimeTools,
        BookingManagerInterface $bookingManager
    ) {
        $this->calendarManager = $calendarManager;
        $this->dateTimeTools   = $dateTimeTools;
        $this->bookingManager = $bookingManager;
        $this->colors          = new Colors();

        parent::__construct(
            __("Bookings", TLBM_TEXT_DOMAIN), __("Booking", TLBM_TEXT_DOMAIN), 10, __("You don't have any bookings yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param $which
     *
     * @return void
     */
    public function extra_tablenav($which)
    {
        if ($which == "top" && !$this->slim) {
            $calendars = $this->calendarManager->getAllCalendars();
            ?>
            <div class="alignleft actions bulkactions">
                <select name="calendar-filter">
                    <option value=""><?php
                        _e("All Calendars", TLBM_TEXT_DOMAIN); ?></option>
                    <?php
                    foreach ($calendars as $calendar): ?>
                        <option <?php
                        echo selected($_REQUEST['calendar-filter'], $calendar->getId()) ?> value="<?php
                        echo $calendar->getId() ?>"><?php
                            echo $calendar->getTitle() ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <button class="button">Filter</button>
            </div>
            <?php
        }
    }

    /**
     * @param $which
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function display_tablenav($which)
    {
        if ( !$this->slim) {
            parent::display_tablenav($which);
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Booking|object $item
     */
    public function column_id($item)
    {
        $link = get_edit_post_link($item->wp_post_id);
        echo "<strong><a href ='" . $link . "'># " . $item->wp_post_id . "</a></strong>";
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Booking|object $item
     */
    public function column_datetime($item)
    {
        $post = get_post($item->wp_post_id);
        echo $this->dateTimeTools->formatWithTime(strtotime($post->post_date));
    }

    /**
     * @SuppressWarnings(PHPMD)
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
                echo $prefix . "<a href='" . $link . "'>" . $calendar->getTitle() . "</a>&nbsp;&nbsp;&nbsp;" . $this->dateTimeTools->formatWithTime(
                        $calendar_slot->timestamp
                    ) . "<br>";
            } else {
                echo $prefix . "<strong>" . __(
                        "Calendar deleted", TLBM_TEXT_DOMAIN
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
     * @SuppressWarnings(PHPMD)
     * @param Booking $item
     */
    public function column_state($item)
    {
        $state = BookingStates::getStateByName($item->state);
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
     * @SuppressWarnings(PHPMD)
     * @param Booking $item
     *
     * @return string
     */
    public function column_cb($item): string
    {
        return '<input type="checkbox" name="wp_post_ids[]" value="' . $item->wp_post_id . '" />';
    }

    /**
     * @return void
     */
    protected function processBuldActions()
    {

    }

    protected function getTotalItemsCount(): int
    {
        return $this->bookingManager->getAllBookingsCount();
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param string $orderby
     * @param string $order
     *
     * @return array
     */
    protected function getItems(string $orderby, string $order): array
    {
        return $this->bookingManager->getAllBookings(array(), $orderby, $order);
    }

    protected function getViews(): array
    {
        if ( !$this->slim) {
            return array(
                "all"     => __("All", TLBM_TEXT_DOMAIN),
                "new"     => __("New", TLBM_TEXT_DOMAIN),
                "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
            );
        }

        return array();
    }

    protected function getColumns(): array
    {
        if ( !$this->slim) {
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

    protected function getSortableColumns(): array
    {
        if ( !$this->slim) {
            return array(
                'id'       => array('id', true),
                'datetime' => array('datetime', true)
            );
        }

        return array();
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    protected function getBulkActions(): array
    {
        if ( !$this->slim) {
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
    protected function getItemId($item): int
    {
        return $item->wp_post_id;
    }

}