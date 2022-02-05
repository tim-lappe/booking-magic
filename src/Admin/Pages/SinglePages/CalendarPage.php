<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\CalendarGroupTable;
use TLBM\Admin\Tables\CalendarListTable;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\MainFactory;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class CalendarPage extends PageBase
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    /**
     * @var CalendarGroupManagerInterface
     */
    private CalendarGroupManagerInterface $calendarGroupManager;

    public function __construct(
        CalendarGroupManagerInterface $calendarGroupManager,
        CalendarRepositoryInterface $calendarManager,
        DateTimeToolsInterface $dateTimeTools
    ) {
        parent::__construct(__("Calendars", TLBM_TEXT_DOMAIN), "booking-magic-calendar");

        $this->calendarManager      = $calendarManager;
        $this->dateTimeTools        = $dateTimeTools;
        $this->calendarGroupManager = $calendarGroupManager;
        $this->parent_slug          = "booking-magic";
    }

    public function getHeadTitle(): string
    {
        return __("Calendars");
    }

    public function displayDefaultHeadBar()
    {
        ?>
        <a href="<?php echo admin_url('admin.php?page=booking-calendar-edit'); ?>"
           class="button button-secondary tlbm-admin-button-bar"><?php
            _e("Add New Group", TLBM_TEXT_DOMAIN) ?></a>
        <a href="<?php echo admin_url('admin.php?page=booking-calendar-edit'); ?>"
           class="button button-primary tlbm-admin-button-bar"><?php
            _e("Add New Calendar", TLBM_TEXT_DOMAIN) ?></a>
        <?php
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
                    $post_list_table = MainFactory::create(CalendarListTable::class);
                    $post_list_table->views();
                    $post_list_table->prepare_items();
                    $post_list_table->display();
                    ?>
                </form>
            </div>
            <div class="tlbm-admin-page-tile">
                <h2>Groups</h2>
                <form method="get">
                    <input type="hidden" name="page" value="<?php
                    echo $_REQUEST['page'] ?>"/>
                    <?php
                    $group_list_table = new CalendarGroupTable(
                        $this->calendarGroupManager, $this->calendarManager, $this->dateTimeTools
                    );
                    $group_list_table->views();
                    $group_list_table->prepare_items();
                    $group_list_table->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}