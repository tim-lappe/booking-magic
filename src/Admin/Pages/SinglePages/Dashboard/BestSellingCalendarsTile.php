<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
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
     * @param EntityRepositoryInterface $entityRepository
     * @param LocalizationInterface $localization
     * @param Statistics $statistics
     * @param AdminPageManagerInterface $adminPageManager
     */
    public function __construct
    (
        EntityRepositoryInterface $entityRepository,
        LocalizationInterface $localization,
        Statistics $statistics,
        AdminPageManagerInterface $adminPageManager
    )
    {
        $this->entityRepository = $entityRepository;
        $this->localization = $localization;
        $this->statistics = $statistics;
        $this->adminPageManager = $adminPageManager;

        parent::__construct($this->localization->__("Top calendars in the last 30 days", TLBM_TEXT_DOMAIN));
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
                    <th><?php $this->localization->_e("Calendar", TLBM_TEXT_DOMAIN) ?></th>
                    <th><?php $this->localization->_e("Amount", TLBM_TEXT_DOMAIN) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($calendars as $calendarId => $amount) {
                    $calendar = $this->entityRepository->getEntity(Calendar::class, $calendarId);
                    if($calendar != null) {
                        $editPage = $this->adminPageManager->getPage(CalendarEditPage::class);
                        $link = $editPage->getEditLink($calendarId);
                        ?>
                        <tr>
                            <td><a href="<?php echo $link ?>"><?php echo $calendar->getTitle() ?></a></td>
                            <td><?php echo $amount ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}