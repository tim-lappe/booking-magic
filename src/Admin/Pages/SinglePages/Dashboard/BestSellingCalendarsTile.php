<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Statistics;
use TLBM\Entity\Calendar;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Utilities\ExtendedDateTime;

class BestSellingCalendarsTile extends DashboardTile
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @var Statistics
     */
    private Statistics $statistics;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

	/**
	 * @var EscapingInterface
	 */
    private EscapingInterface $escaping;

	/**
	 * @param EntityRepositoryInterface $entityRepository
	 * @param LocalizationInterface $localization
	 * @param Statistics $statistics
	 * @param AdminPageManagerInterface $adminPageManager
	 * @param EscapingInterface $escaping
	 */
    public function __construct
    (
        EntityRepositoryInterface $entityRepository,
        LocalizationInterface $localization,
        Statistics $statistics,
        AdminPageManagerInterface $adminPageManager,
        EscapingInterface $escaping
    )
    {
        $this->escaping = $escaping;
        $this->entityRepository = $entityRepository;
        $this->localization = $localization;
        $this->statistics = $statistics;
        $this->adminPageManager = $adminPageManager;

        parent::__construct($this->localization->getText("Top calendars in the last 30 days", TLBM_TEXT_DOMAIN));
    }

    public function displayBody(): void
    {
        $begin = new ExtendedDateTime();
        $begin->setDay($begin->getDay() - 30);
        $calendars = $this->statistics->getMostBookedCalendars($begin, new ExtendedDateTime());

        ?>
        <table class="wp-list-table widefat fixed striped table-view-list tlbm-admin-plain-table">
            <thead>
                <tr>
                    <th><?php $this->localization->echoText("Calendar", TLBM_TEXT_DOMAIN) ?></th>
                    <th><?php $this->localization->echoText("Amount", TLBM_TEXT_DOMAIN) ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(count($calendars) > 0) {
                foreach ($calendars as $calendarId => $amount) {
                    $calendar = $this->entityRepository->getEntity(Calendar::class, $calendarId);
                    if ($calendar != null) {
                        $editPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                        $link     = $editPage->getEditLink($calendarId);
                        ?>
                        <tr>
                            <td><a href="<?php echo $this->escaping->escAttr($link) ?>"><?php echo $this->escaping->escHtml($calendar->getTitle()); ?></a></td>
                            <td><?php echo $this->escaping->escHtml($amount); ?></td>
                        </tr>
                        <?php
                    }
                }
            } else {
                ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="2"><?php $this->localization->echoText("No elements found", TLBM_TEXT_DOMAIN); ?></td></tr>
                <?php
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php $this->localization->echoText("Calendar", TLBM_TEXT_DOMAIN) ?></th>
                    <th><?php $this->localization->echoText("Amount", TLBM_TEXT_DOMAIN) ?></th>
                </tr>
            </tfoot>
        </table>
        <?php
    }
}