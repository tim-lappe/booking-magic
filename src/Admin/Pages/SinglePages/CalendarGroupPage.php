<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Tables\CalendarGroupTable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\MainFactory;

class CalendarGroupPage extends PageBase
{

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

	/**
	 * @var SanitizingInterface
	 */
    private SanitizingInterface $sanitizing;

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

    public function __construct(LocalizationInterface $localization, EscapingInterface $escaping, SanitizingInterface $sanitizing)
    {
        parent::__construct($localization->getText("Calendar Groups", TLBM_TEXT_DOMAIN), "booking-magic-calendar-group");
        $this->parentSlug   = "booking-magic";
        $this->sanitizing = $sanitizing;
        $this->escaping = $escaping;
        $this->localization = $localization;
    }

    public function getHeadTitle(): string
    {
        return $this->localization->getText("Calendar Groups", TLBM_TEXT_DOMAIN);
    }

    public function displayDefaultHeadBar()
    {
        $calendarGroupPage    = $this->adminPageManager->getPage(CalendarGroupEditPage::class);
        $addCalendarGroupLink = $calendarGroupPage->getEditLink();

        ?>
        <a href="<?php echo $this->escaping->escAttr($addCalendarGroupLink); ?>" class="button button-primary tlbm-admin-button-bar"><?php
            _e("Add New Group", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $this->escaping->escAttr($_REQUEST['page']) ?>"/>
                    <?php
                    $calendarGroupListTable = MainFactory::create(CalendarGroupTable::class);
                    $calendarGroupListTable->views();
                    $calendarGroupListTable->prepare_items();
                    $calendarGroupListTable->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}