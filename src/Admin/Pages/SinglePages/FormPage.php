<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\FormListTable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\MainFactory;

class FormPage extends PageBase
{

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

	/**
	 * @var LocalizationInterface
	 */
    protected LocalizationInterface $localization;

	/**
	 * @var SanitizingInterface
	 */
    protected SanitizingInterface $sanitizing;

	/**
	 * @param SanitizingInterface $sanitizing
	 * @param EscapingInterface $escaping
	 * @param LocalizationInterface $localization
	 */
    public function __construct(SanitizingInterface $sanitizing, EscapingInterface $escaping, LocalizationInterface $localization)
    {
        parent::__construct($localization->getText("Form", TLBM_TEXT_DOMAIN), "booking-magic-form");

        $this->localization = $localization;
        $this->parentSlug = "booking-magic";
        $this->escaping = $escaping;
        $this->sanitizing = $sanitizing;
    }

    public function displayDefaultHeadBar()
    {
        $formEditPage = $this->adminPageManager->getPage(FormEditPage::class);
        if ($formEditPage instanceof FormEditPage) {
            ?>
            <a href="<?php echo $this->escaping->escUrl($formEditPage->getEditLink()); ?>" class="button button-primary tlbm-admin-button-bar"><?php $this->localization->echoText("Add New Form", TLBM_TEXT_DOMAIN) ?></a>
            <?php
        }
    }


    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $this->escaping->escAttr($this->sanitizing->sanitizeKey($_REQUEST['page'])); ?>"/>
                <?php
                $formListTable = MainFactory::create(FormListTable::class);
                $formListTable->views();
                $formListTable->prepare_items();
                $formListTable->display();
                ?>
            </form>
        </div>
        <?php
    }
}