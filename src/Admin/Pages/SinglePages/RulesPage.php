<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\RulesListTable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\ApiUtils\Contracts\UrlUtilsInterface;
use TLBM\MainFactory;

class RulesPage extends PageBase
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
	 * @var UrlUtilsInterface
	 */
    protected UrlUtilsInterface $urlUtils;

	/**
	 * @var SanitizingInterface
	 */
    protected SanitizingInterface $sanitizing;

	/**
	 * @param SanitizingInterface $sanitizing
	 * @param UrlUtilsInterface $urlUtils
	 * @param EscapingInterface $escaping
	 * @param LocalizationInterface $localization
	 */
    public function __construct(SanitizingInterface $sanitizing, UrlUtilsInterface $urlUtils, EscapingInterface $escaping, LocalizationInterface $localization)
    {
        parent::__construct($localization->getText("Rules", TLBM_TEXT_DOMAIN), "booking-magic-rules");
        $this->parentSlug = "booking-magic";
        $this->escaping = $escaping;
        $this->localization = $localization;
        $this->urlUtils = $urlUtils;
        $this->sanitizing = $sanitizing;
    }

    public function displayDefaultHeadBar()
    {
        ?>
        <a href="<?php echo $this->escaping->escUrl($this->urlUtils->adminUrl('admin.php?page=booking-magic-rule-edit')); ?>" class="button button-primary tlbm-admin-button-bar">
            <?php $this->localization->echoText("Add New Rule", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $this->escaping->escAttr($this->sanitizing->sanitizeKey($_REQUEST['page'])); ?>"/>
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