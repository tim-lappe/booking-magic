<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\BookingEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Entity\Booking;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\BookingsQuery;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Utilities\ExtendedDateTime;

class BookingListTable extends TableBase
{
    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var ColorsInterface
     */
    private ColorsInterface $colors;

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        AdminPageManagerInterface $adminPageManager
    ) {
        $this->entityRepository = $entityRepository;
        $this->adminPageManager = $adminPageManager;
        $this->colors          = new Colors();

        parent::__construct(
            __("Bookings", TLBM_TEXT_DOMAIN), __("Booking", TLBM_TEXT_DOMAIN), 10, __("You don't have any bookings yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param string $which
     *
     * @return void
     */
    public function tableNav(string $which): void
    {
        if ($which == "top" && !$this->slim) {
            $entityQuery = MainFactory::get(CalendarQuery::class);
            $calendars = $entityQuery->getResult();
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
     * @return void
     */
    protected function processBuldActions()
    {
        //TODO: Bulk Actions für Bookings Tabelle implementieren

    }

    protected function getTotalItemsCount(): int
    {
        return $this->entityRepository->getEntityCount(Booking::class);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     */
    protected function getItems(string $orderby, string $order, int $page): array
    {
        $bookingsQuery = MainFactory::create(BookingsQuery::class);

        if($orderby == "date") {
            $bookingsQuery->setOrderBy([[TLBM_BOOKING_QUERY_ALIAS . ".timestampCreated", $order]]);

        } elseif ($orderby == "id") {
            $bookingsQuery->setOrderBy([[TLBM_BOOKING_QUERY_ALIAS . ".id", $order]]);

        } elseif($orderby == "state") {
            $bookingsQuery->setOrderBy([[TLBM_BOOKING_QUERY_ALIAS . ".state", $order]]);

        } else {
            $bookingsQuery->setOrderBy([[TLBM_BOOKING_QUERY_ALIAS . ".timestampCreated", "desc"]]);
        }

        return iterator_to_array($bookingsQuery->getResult());
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
        $columns = [];
        if ( !$this->slim) {
            $columns[] = $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            });
        }

        $columns[] = new Column("id", __("ID", TLBM_TEXT_DOMAIN), true, array($this, "columnDisplayId"));
        $columns[] = new Column("calendar", __("Calendar", TLBM_TEXT_DOMAIN), false, array($this, "columnDisplayCalendar"));
        $columns[] = new Column("state", __("State", TLBM_TEXT_DOMAIN), true, array($this, "columnDisplayState"));
        $columns[] = new Column("date", __("Date", TLBM_TEXT_DOMAIN), true, array($this, "columnDisplayDate"));

        return $columns;
    }

    /**
     * @param Booking $item
     *
     * @return void
     */
    protected function columnDisplayId(Booking $item) {
        $editPage = $this->adminPageManager->getPage(BookingEditPage::class);
        if ($editPage) {
            $link = $editPage->getEditLink($item->getId());
            echo "<strong><a href ='" . $link . "'># " . $item->getId() . "</a></strong>";
        }
    }

    /**
     * @param Booking $item
     *
     * @return void
     */
    protected function columnDisplayCalendar(Booking $item) {
        $calslots = $item->getCalendarBookings();
        $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
        if($calendarEditPage) {
            foreach ($calslots as $calendarBooking) {
                $calendar = $calendarBooking->getCalendar();
                $link     = $calendarEditPage->getEditLink($calendar->getId());
                $prefix   = "";
                if (sizeof($calslots) > 1) {
                    $prefix = $calendarBooking->getTitleFromForm() . "&nbsp;&nbsp;&nbsp;";
                }

                //TODO: Es wird derzeit nur "From" in der Tabelle angezeigt.
                if ($calendar != null) {
                    echo $prefix . "<a href='" . $link . "'>" . $calendar->getTitle() . "</a>&nbsp;&nbsp;&nbsp;" . new ExtendedDateTime($calendarBooking->getFromTimestamp()) . "<br>";
                } else {
                    echo $prefix . "<strong>" . __("Calendar deleted", TLBM_TEXT_DOMAIN) . "</strong>&nbsp;&nbsp;&nbsp;" .  new ExtendedDateTime($calendarBooking->getFromTimestamp()) . "<br>";
                }
            }

            if (sizeof($calslots) == 0) {
                echo "-";
            }
        }
    }

    protected function columnDisplayState(Booking $item) {
        //TODO Buchungsstatus in Booking Tabelle anzeigen
        /*
        $rgb   = $this->colors->getRgbFromHex($state['color']);

         ?>
         <div class='tlbm-table-list-state' style="background-color: rgba(<?php
         echo $rgb[0] . "," . $rgb[1] . "," . $rgb[2] ?>, 0.4)">
             <strong><?php
                 echo $state['title'] ?></strong>
         </div>
         <?php*/
    }

    protected function columnDisplayDate(Booking $item) {
        echo new ExtendedDateTime($item->getTimestampCreated());
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
}