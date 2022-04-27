<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\RulesListTable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class RulesPage extends PageBase
{
    protected EscapingInterface $escaping;

    public function __construct(EscapingInterface $escaping, LocalizationInterface $localization)
    {
        parent::__construct($localization->getText("Rules", TLBM_TEXT_DOMAIN), "booking-magic-rules");
        $this->parentSlug = "booking-magic";
        $this->escaping = $escaping;
    }

    public function displayDefaultHeadBar()
    {
        ?>
        <a href="<?php echo admin_url('admin.php?page=booking-magic-rule-edit'); ?>" class="button button-primary tlbm-admin-button-bar"><?php
            _e("Add New Rule", TLBM_TEXT_DOMAIN) ?></a>
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
                    $rulesListTable = MainFactory::create(RulesListTable::class);
                    $rulesListTable->views();
                    $rulesListTable->prepare_items();
                    $rulesListTable->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}