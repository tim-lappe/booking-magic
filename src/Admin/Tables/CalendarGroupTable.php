<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarGroupEditPage;
use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarGroupQuery;

class CalendarGroupTable extends TableBase
{

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    public function __construct(
        AdminPageManagerInterface $adminPageManager
    ) {
        $this->adminPageManager = $adminPageManager;

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
        $calendarGroupQuery = MainFactory::create(CalendarGroupQuery::class);

        //TODO: Sortierung und filter für Calendar Groups einbauen

        if($orderby == "title") {
            $calendarGroupQuery->setOrderBy([[TLBM_CALENDAR_GROUP_QUERY_ALIAS . ".title", $order]]);
        } elseif ($orderby == "booking_distribution") {
            $calendarGroupQuery->setOrderBy([[TLBM_CALENDAR_GROUP_QUERY_ALIAS . ".bookingDisitribution", $order]]);
        }

        return iterator_to_array($calendarGroupQuery->getResult());
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
                $groupEditPage = $this->adminPageManager->getPage(CalendarGroupEditPage::class);
                $link = $groupEditPage->getEditLink($item->getId());

                if ( !empty($item->getTitle())) {
                    echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                } else {
                    echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                }

            }),
            new Column("selected_calendars", __('Selected Calendars', TLBM_TEXT_DOMAIN), false, function ($item) {
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
        $calendarGroupQuery = MainFactory::create(CalendarGroupQuery::class);
        return $calendarGroupQuery->getResultCount();
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