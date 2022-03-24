<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\FormListTable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class FormPage extends PageBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct($localization->getText("Form", TLBM_TEXT_DOMAIN), "booking-magic-form");
        $this->parentSlug = "booking-magic";
    }

    public function displayDefaultHeadBar()
    {
        $formEditPage = $this->adminPageManager->getPage(FormEditPage::class);
        if ($formEditPage instanceof FormEditPage) {
            ?>
            <a href="<?php
            echo $formEditPage->getEditLink() ?>" class="button button-primary tlbm-admin-button-bar"><?php
                _e("Add New Form", TLBM_TEXT_DOMAIN) ?></a>
            <?php
        }
    }


    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php
                    echo $_REQUEST['page'] ?>"/>
                    <?php
                    $formListTable = MainFactory::create(FormListTable::class);
                    $formListTable->views();
                    $formListTable->prepare_items();
                    $formListTable->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}