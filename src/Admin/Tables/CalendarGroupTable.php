<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarGroupEditPage;
use TLBM\Admin\Tables\DisplayHelper\DisplayCalendarSelection;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\Entity\CalendarGroup;
use TLBM\MainFactory;
use TLBM\Repository\Query\BaseQuery;
use TLBM\Repository\Query\CalendarGroupQuery;
use TLBM\Repository\Query\ManageableEntityQuery;

class CalendarGroupTable extends ManagableEntityTable
{

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(AdminPageManagerInterface $adminPageManager, LocalizationInterface $localization) {
        $this->adminPageManager = $adminPageManager;
        $this->localization = $localization;

        parent::__construct(
            CalendarGroup::class, $this->localization->__("Groups", TLBM_TEXT_DOMAIN), $this->localization->__("Group", TLBM_TEXT_DOMAIN), 10, $this->localization->__("You haven't created any groups yet", TLBM_TEXT_DOMAIN)
        );
    }

    protected function getQuery(?string $orderby, ?string $order, ?int $page): ManageableEntityQuery
    {
        $query = parent::getQuery($orderby, $order, $page); // TODO: Change the autogenerated stub

        if($orderby == "title") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".title", $order]]);
        } elseif ($orderby == "booking_distribution") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".bookingDisitribution", $order]]);
        }

        return $query;
    }


    /**
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = parent::getColumns();

        array_splice($columns, 1, 0, [
            new Column("title", $this->localization->__("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
                $groupEditPage = $this->adminPageManager->getPage(CalendarGroupEditPage::class);
                $link = $groupEditPage->getEditLink($item->getId());

                if ( !empty($item->getTitle())) {
                    echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                } else {
                    echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                }

            }),
            new Column("selected_calendars", $this->localization->__('Selected Calendars', TLBM_TEXT_DOMAIN), false, function ($item) {
                $selection = $item->getCalendarSelection();
                $selectionDisplay = MainFactory::create(DisplayCalendarSelection::class);
                $selectionDisplay->setCalendarSelection($selection);
                $selectionDisplay->display();

            }),
            new Column("booking_distribution", $this->localization->__('Booking Distribution', TLBM_TEXT_DOMAIN), true, function ($item) {
                if ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_EVENLY) {
                    echo $this->localization->__("Evenly", TLBM_TEXT_DOMAIN);
                } elseif ($item->getBookingDisitribution() == TLBM_BOOKING_DISTRIBUTION_FILL_ONE) {
                    echo $this->localization->__("Fill One First", TLBM_TEXT_DOMAIN);
                }
            })
        ]);

        return $columns;
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