<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Tables\CalendarCategoryListTable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class CalendarCategoryPage extends PageBase
{
    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * @param LocalizationInterface $localization
     */
    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
        parent::__construct($this->localization->getText("Category", TLBM_TEXT_DOMAIN), "calendar-category", false);
    }

    public function displayDefaultHeadBar()
    {
        $calendarCategoryPage    = $this->adminPageManager->getPage(CalendarCategoryEditPage::class);
        $addCalendarCategoryLink = $calendarCategoryPage->getEditLink();

        ?>
        <a href="<?php echo $this->escaping->escUrl($addCalendarCategoryLink); ?>" class="button button-primary tlbm-admin-button-bar"><?php
            $this->localization->echoText("Add New Category", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <form method="get">
                <?php
                $categoryTable = MainFactory::create(CalendarCategoryListTable::class);
                $categoryTable->prepare_items();
                $categoryTable->display();
                ?>
            </form>
        </div>
        <?php
    }
}