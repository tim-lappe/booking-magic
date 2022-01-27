<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\FormListTable;
use TLBM\Form\Contracts\FormManagerInterface;

class FormPage extends PageBase
{

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    public function __construct(FormManagerInterface $formManager)
    {
        parent::__construct(__("Form", TLBM_TEXT_DOMAIN), "booking-magic-form");
        $this->formManager = $formManager;
        $this->parent_slug = "booking-magic";
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
                    $post_list_table = new FormListTable($this->formManager, $this->adminPageManager);
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