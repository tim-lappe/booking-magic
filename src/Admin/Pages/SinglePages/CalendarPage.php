<?php


namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Tables\CalendarListTable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class CalendarPage extends PageBase
{
    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    public function __construct(LocalizationInterface $localization) {
        parent::__construct($localization->__("Calendars", TLBM_TEXT_DOMAIN), "booking-magic-calendar");
        $this->parentSlug   = "booking-magic";
        $this->localization = $localization;
    }

    public function getHeadTitle(): string
    {
        return $this->localization->__("Calendars", TLBM_TEXT_DOMAIN);
    }

    public function displayDefaultHeadBar()
    {
        $calendarPage = $this->adminPageManager->getPage(CalendarEditPage::class);
        $addCalendarLink = $calendarPage->getEditLink();

        ?>
        <a href="<?php echo $addCalendarLink ?>" class="button button-primary tlbm-admin-button-bar"><?php _e("Add New Calendar", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                    <?php
                    $calendarListTable = MainFactory::create(CalendarListTable::class);
                    $calendarListTable->views();
                    $calendarListTable->prepare_items();
                    $calendarListTable->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}