<?php

namespace TLBM\Admin\Pages\SinglePages;

use phpDocumentor\Reflection\Types\This;

abstract class FormPageBase extends PageBase {

    private array $notices = array();

    public function __construct($menu_title, $menu_slug, $show_in_menu = true) {
        parent::__construct($menu_title, $menu_slug, $show_in_menu);
    }

    public abstract function OnSave($vars): array;

    /**
     *
     * @return void
     */
    public function DisplayDefaultHead() {
        ?>
        <form method="post">
            <?php
            parent::DisplayDefaultHead();

            wp_nonce_field("tlbm-save-form-nonce-" . $this->menu_slug, "tlbm-save-form-nonce");
            ?>
        <?php
    }

    /**
     *
     * @return void
     */
    public function DisplayDefaultHeadBar() {
        ?>
        <button class="button button-primary tlbm-admin-button-bar"><?php _e("Save Changes", TLBM_TEXT_DOMAIN) ?></button>
        <?php
    }

    public function Display() {
        if(isset($_POST['tlbm-save-form-nonce'])) {
            if(wp_verify_nonce($_POST['tlbm-save-form-nonce'], "tlbm-save-form-nonce-" . $this->menu_slug)) {
                $this->notices = $this->OnSave($_POST);
            }
        }

        parent::Display();
    }

    public function DisplayNotices() {
        if(sizeof($this->notices) > 0) {
            foreach ($this->notices as $key => $msg) {
                ?>
                <div class="notice notice-<?php echo $key ?> is-dismissible">
                    <p><?php _e( 'Done!', TLBM_TEXT_DOMAIN ); ?></p>
                </div>
                <?php
            }
        }
    }

    /**
     *
     * @return void
     */
    public final function DisplayPageBody() {
        ?>
        <div class="wrap">
            <div class="tlbm-admin-page">
                <?php $this->DisplayNotices(); ?>
                <?php $this->ShowFormPageContent(); ?>
            </div>
        </div>
        </form>
        <?php
    }

    public abstract function ShowFormPageContent();
}