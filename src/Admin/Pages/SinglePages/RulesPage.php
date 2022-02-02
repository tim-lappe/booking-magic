<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\AllRulesListTable;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\MainFactory;
use TLBM\Rules\Contracts\RulesManagerInterface;

class RulesPage extends PageBase
{
    /**
     * @var RulesManagerInterface
     */
    private RulesManagerInterface $rulesManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct(RulesManagerInterface $rulesManager, CalendarManagerInterface $calendarManager)
    {
        parent::__construct(__("Rules", TLBM_TEXT_DOMAIN), "booking-magic-rules");
        $this->rulesManager    = $rulesManager;
        $this->calendarManager = $calendarManager;
        $this->parent_slug     = "booking-magic";
    }

    public function displayDefaultHeadBar()
    {
        ?>
        <a href="<?php
        echo admin_url('admin.php?page=booking-magic-rule-edit'); ?>"
           class="button button-primary tlbm-admin-button-bar"><?php
            _e("Add New Rule", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }


    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php
                    echo $_REQUEST['page'] ?>"/>
                    <?php
                    $post_list_table = MainFactory::create(AllRulesListTable::class);
                    $post_list_table->views();
                    $post_list_table->prepare_items();
                    $post_list_table->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}