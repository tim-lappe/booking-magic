<?php


namespace TLBM\Admin\Tables;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Calendar;
use TLBM\Repository\Query\ManageableEntityQuery;


class CalendarListTable extends ManagableEntityTable
{
    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param AdminPageManagerInterface $adminPageManager
	 * @param LocalizationInterface $localization
	 */
    public function __construct(EscapingInterface $escaping, AdminPageManagerInterface $adminPageManager, LocalizationInterface $localization)
    {
        $this->localization = $localization;
        $this->adminPageManager = $adminPageManager;
		$this->escaping = $escaping;

        parent::__construct(
            Calendar::class, $this->localization->getText("Calendars", TLBM_TEXT_DOMAIN), $this->localization->getText("Calendar", TLBM_TEXT_DOMAIN), 10, $this->localization->getText("You haven't created any calendars yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @param string|null $orderby
     * @param string|null $order
     * @param int|null $page
     * @param bool $useCustomFilters
     *
     * @return ManageableEntityQuery
     * @SuppressWarnings(PHPMD)
     */
    protected function getQuery(?string $orderby, ?string $order, ?int $page, bool $useCustomFilters = true): ManageableEntityQuery
    {
        $calendarQuery = parent::getQuery($orderby, $order, $page);
        if ($orderby == "title") {
            $calendarQuery->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".title", $order]]);
        }

        return $calendarQuery;
    }

    protected function getColumns(): array
    {
        $columns = parent::getColumns();

        array_splice(
            $columns, 1, 0, [new Column("title", $this->localization->getText('Title', TLBM_TEXT_DOMAIN), true, function ($item) {
                        $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                        if ($calendarEditPage != null) {
                            $link = $calendarEditPage->getEditLink($item->getId());
                            if ( !empty($item->getTitle())) {
                                echo "<strong><a href='" . $this->escaping->escAttr($link) . "'>" . $this->escaping->escHtml($item->getTitle()) . "</a></strong>";
                            } else {
                                echo "<strong><a href='" . $this->escaping->escAttr($link) . "'>" . $this->escaping->escHtml($item->getId()) . "</a></strong>";
                            }
                        }
                    })
                ]
        );

        return $columns;
    }
}