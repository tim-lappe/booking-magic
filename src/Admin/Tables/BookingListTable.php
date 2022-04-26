<?php


namespace TLBM\Admin\Tables;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\BookingEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\BookingChangeManager;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Entity\Booking;
use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Repository\Query\BookingsQuery;
use TLBM\Repository\Query\ManageableEntityQuery;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Utilities\ExtendedDateTime;

class BookingListTable extends ManagableEntityTable
{

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var ColorsInterface
     */
    private ColorsInterface $colors;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(AdminPageManagerInterface $adminPageManager, SettingsManagerInterface $settingsManager, LocalizationInterface $localization) {
        $this->adminPageManager = $adminPageManager;
        $this->settingsManager = $settingsManager;
        $this->localization = $localization;
        $this->colors          = new Colors();

        parent::__construct(Booking::class, $localization->getText("Bookings", TLBM_TEXT_DOMAIN), $localization->getText("Booking", TLBM_TEXT_DOMAIN));
    }

    protected function processBuldActions(string $action, array $ids)
    {
        parent::processBuldActions($action, $ids);

        $settingsStates = $this->settingsManager->getSetting(BookingStates::class);
        $states = $settingsStates->getStatesKeyValue();

        foreach ($states as $key => $title) {
            if($action == "set_state_" . $key) {
                foreach ($ids as $id) {
                    $booking       = $this->entityRepository->getEntity(Booking::class, $id);
                    $bookingChange = MainFactory::create(BookingChangeManager::class);
                    $bookingChange->setBooking($booking);
                    $bookingChange->setState($key);
                    $bookingChange->storeValuesToBooking();

                    $this->entityRepository->saveEntity($booking);
                }
            }
        }
    }

    protected function tableNav(string $witch): void
    {
        if ($witch == "top") {
            /**
             * @var Calendar[] $calendars
             */
            $calendars     = $this->entityRepository->getEntites(Calendar::class);
            $statesSetting = $this->settingsManager->getSetting(BookingStates::class);
            $states        = $statesSetting->getStatesKeyValue();
            ?>
            <div class="alignleft actions bulkactions">
                <select name="filter_calendar">
                    <option value=""><?php
                        echo $this->localization->getText("All Calendars", TLBM_TEXT_DOMAIN); ?></option>
                    <?php
                    foreach ($calendars as $calendar): ?>
                        <option <?php
                        selected($calendar->getId(),  $this->sanitizing->sanitizeKey($_GET['filter_calendar']), true) ?> value="<?php
                        echo $calendar->getId() ?>"><?php
                            echo $calendar->getTitle() ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <select name="filter_state">
                    <option value=""><?php
                        echo $this->localization->getText("All States", TLBM_TEXT_DOMAIN); ?></option>
                    <?php
                    foreach ($states as $key => $title): ?>
                        <option <?php
                        selected($key, $this->sanitizing->sanitizeKey($_GET['filter_state']), true) ?> value="<?php
                        echo $key ?>"><?php
                            echo $title ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <button class="button" type="submit">Filter</button>
            </div>

            <?php
        }
    }

    protected function getBulkActions(): array
    {
        $bulkActions    = parent::getBulkActions();
        $settingsStates = $this->settingsManager->getSetting(BookingStates::class);
        $states         = $settingsStates->getStatesKeyValue();

        $cstatelabel               = $this->localization->getText("Set state", TLBM_TEXT_DOMAIN);
        $bulkActions[$cstatelabel] = [];
        foreach ($states as $key => $title) {
            $bulkActions[$cstatelabel]["set_state_" . $key] = $title;
        }

        return $bulkActions;
    }

    protected function getQueryObject(): ManageableEntityQuery
    {
        return MainFactory::create(BookingsQuery::class);
    }

    protected function getQuery(?string $orderby, ?string $order, ?int $page, bool $useCustomFilters = true): BookingsQuery
    {
        $query = parent::getQuery($orderby, $order, $page);
        if ($query instanceof BookingsQuery) {
            if ($orderby == "id") {
                $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".id", $order]]);
            } elseif ($orderby == "state") {
                $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".state", $order]]);
            }

            if ($useCustomFilters) {
                if (isset($_GET['filter_calendar'])) {
                    $filterCalendar = $this->entityRepository->getEntity(Calendar::class, intval($_GET['filter_calendar']));
                    if ($filterCalendar != null) {
                        $query->setFilterCalendars([$filterCalendar]);
                    }
                }

                if (isset($_GET['filter_state'])) {
                    $statesSetting = $this->settingsManager->getSetting(BookingStates::class);
                    $statesKeys    = array_keys($statesSetting->getStatesKeyValue());
                    if (in_array($_GET['filter_state'], $statesKeys)) {
                        $query->setFilterStates([$this->sanitizing->sanitizeKey($_GET['filter_state'])]);
                    }
                }
            }

            $query->addWhere(TLBM_ENTITY_QUERY_ALIAS . ".internalState = '" . TLBM_BOOKING_INTERNAL_STATE_COMPLETED . "'");

            return $query;
        }

        return MainFactory::create(BookingsQuery::class);
    }

    protected function getColumns(): array
    {
        $columns = parent::getColumns();

        array_splice($columns, 1, 0, [new Column("id", $this->localization->getText("ID", TLBM_TEXT_DOMAIN), true, [$this, "columnDisplayId"]),
            new Column("values", $this->localization->getText("Form values", TLBM_TEXT_DOMAIN), false, function (Booking $booking) {
                $semantic = MainFactory::create(BookingValueSemantic::class);
                $semantic->setValuesFromBooking($booking);

                $content = $semantic->getFullName() . "<br>";
                $content .= $semantic->getFullAddress();

                echo $content;
            }),
            new Column("calendar", $this->localization->getText("Calendar", TLBM_TEXT_DOMAIN), false, [$this, "columnDisplayCalendar"]),
            new Column("state", $this->localization->getText("State", TLBM_TEXT_DOMAIN), true, [$this, "columnDisplayState"]),
        ]);

        return $columns;
    }

    /**
     * @param Booking $item
     *
     * @return void
     */
    protected function columnDisplayId(Booking $item) {
        $editPage = $this->adminPageManager->getPage(BookingEditPage::class);
        if ($editPage != null) {
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
        if ($calendarEditPage != null) {
            foreach ($calslots as $calendarBooking) {
                $calendar = $calendarBooking->getCalendar();
                $prefix   = "";
                if (sizeof($calslots) > 1) {
                    $prefix = $calendarBooking->getTitleFromForm() . "&nbsp;&nbsp;&nbsp;";
                }

                if ($calendar != null) {
                    $link = $calendarEditPage->getEditLink($calendar->getId());
                    //TODO: Es wird derzeit nur "From" in der Tabelle angezeigt.

                    echo $prefix . "<a href='" . $link . "'>" . $calendar->getTitle() . "</a>&nbsp;&nbsp;&nbsp;" . $calendarBooking->getFromDateTime() . "<br>";
                } else {
                    echo $prefix . "<strong>" . $this->localization->getText("Calendar deleted", TLBM_TEXT_DOMAIN) . "</strong>&nbsp;&nbsp;&nbsp;" . $calendarBooking->getFromDateTime() . "<br>";
                }
            }

            if (sizeof($calslots) == 0) {
                echo "-";
            }
        }
    }

    protected function columnDisplayState(Booking $item)
    {
        $bookingStatesSetting = $this->settingsManager->getSetting(BookingStates::class);
        $state = $bookingStatesSetting->getStateByName($item->getState());
        $rgb   = $this->colors->getRgbFromHex($state['color']); ?>

         <div class='tlbm-table-list-state' style="background-color: rgba(<?php echo $rgb[0] . "," . $rgb[1] . "," . $rgb[2] ?>, 0.4)">
             <span><?php echo $state['title'] ?></span>
         </div>
        <?php
    }

    protected function columnDisplayDate(Booking $item) {
        echo new ExtendedDateTime($item->getTimestampCreated());
    }
}