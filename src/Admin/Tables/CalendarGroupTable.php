<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\MainFactory;

class CalendarGroupTable extends TableBase
{

    /**
     * @var CalendarGroupManagerInterface
     */
    private CalendarGroupManagerInterface $calendarGroupManager;


    public function __construct(
        CalendarGroupManagerInterface $calendarGroupManager
    ) {
        $this->calendarGroupManager = $calendarGroupManager;

        parent::__construct(
            __("Groups", TLBM_TEXT_DOMAIN), __("Group", TLBM_TEXT_DOMAIN), 10, __("You haven't created any groups yet", TLBM_TEXT_DOMAIN)
        );
    }

    protected function processBuldActions()
    {
        //TODO: Bulk Actions für Calendar Group Tabelle implementieren

    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     * @SuppressWarnings(PHPMD)
     *
     */
    protected function getItems(string $orderby, string $order, int $page): array
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
        return [
            $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            }),
            new Column("title", __("Title", TLBM_TEXT_DOMAIN), true, function ($item) {

                $link = get_edit_post_link($item->getId());
                if ( !empty($item->getTitle())) {
                    echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                } else {
                    echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                }

            }),
            new Column("selected_calendars", __('Selected Calendars', TLBM_TEXT_DOMAIN), true, function ($item) {

                $selection = $item->getCalendarSelection();
                $selectionDisplay = MainFactory::create(DisplayCalendarSelection::class);
                $selectionDisplay->setCalendarSelection($selection);
                $selectionDisplay->display();

            }),
            new Column("booking_distribution", __('Booking Distribution', TLBM_TEXT_DOMAIN), true, function ($item) {
                if ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_EVENLY) {
                    echo __("Evenly", TLBM_TEXT_DOMAIN);
                } elseif ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_FILL_ONE) {
                    echo __("Fill One First", TLBM_TEXT_DOMAIN);
                }
            })
        ];
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
     * @return int
     */
    protected function getTotalItemsCount(): int
    {
        return $this->calendarGroupManager->getAllGroupsCount();
    }

    /**
     * @param string $which
     *
     * @return void
     */
    protected function tableNav(string $which): void
    {

    }
}