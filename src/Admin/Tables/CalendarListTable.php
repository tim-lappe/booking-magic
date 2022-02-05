<?php


namespace TLBM\Admin\Tables;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Utilities\ExtendedDateTime;

/**
 * @extends TableBase<Calendar>
 */
class CalendarListTable extends TableBase
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    public function __construct(CalendarRepositoryInterface $calendarManager, AdminPageManagerInterface $adminPageManager)
    {
        $this->adminPageManager = $adminPageManager;
        $this->calendarManager = $calendarManager;

        parent::__construct(
            __("Calendars", TLBM_TEXT_DOMAIN), __("Calendar", TLBM_TEXT_DOMAIN), 10, __("You haven't created any calendars yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @return void
     */
    protected function processBuldActions()
    {
        //TODO: Bulk Actions für Calendar Tabelle implementieren

    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    protected function getItems(string $orderby, string $order, int $page): array
    {
        $calendarQuery = MainFactory::create(CalendarQuery::class);

        if($orderby == "date") {
            $calendarQuery->setOrderBy([[TLBM_CALENDAR_QUERY_ALIAS . ".tstampCreated", $order]]);

        } elseif($orderby == "title") {
            $calendarQuery->setOrderBy([[TLBM_CALENDAR_QUERY_ALIAS . ".title", $order]]);

        } else {
            $calendarQuery->setOrderBy([[TLBM_CALENDAR_QUERY_ALIAS . ".tstampCreated", "desc"]]);
        }


        return iterator_to_array($calendarQuery->getResult());
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

    protected function getColumns(): array
    {
        return array(
            $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            }),

            new Column("title", __('Title', TLBM_TEXT_DOMAIN), true, function ($item) {
                $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                if($calendarEditPage != null) {
                    $link = $calendarEditPage->getEditLink($item->getId());
                    if ( !empty($item->getTitle())) {
                        echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                    } else {
                        echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                    }
                }
            }),

            new Column("date", __('Date created', TLBM_TEXT_DOMAIN), true, function ($item) {

                /**
                 * @var Calendar $item
                 */
                echo new ExtendedDateTime($item->getTstampCreated());
            })
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
     * @return int
     */
    protected function getTotalItemsCount(): int
    {
        return $this->calendarManager->getAllCalendarsCount();
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