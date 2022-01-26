<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;

abstract class FormPageBase extends PageBase
{
    private array $notices = array();

    /**
     * @var FormBuilderInterface
     */
    protected FormBuilderInterface $formBuilder;

    /**
     * @param AdminPageManagerInterface $adminPageManager
     * @param FormBuilderInterface $formBuilder
     * @param string $menu_title
     * @param string $menu_slug
     * @param bool $show_in_menu
     * @param bool $display_default_head
     * @param string $display_default_head_title
     */
    public function __construct(
        AdminPageManagerInterface $adminPageManager,
        FormBuilderInterface $formBuilder,
        string $menu_title,
        string $menu_slug,
        bool $show_in_menu = true,
        bool $display_default_head = true,
        string $display_default_head_title = ""
    ) {
        $this->formBuilder = $formBuilder;

        parent::__construct(
            $adminPageManager,
            $menu_title,
            $menu_slug,
            $show_in_menu,
            $display_default_head,
            $display_default_head_title
        );
    }

    /**
     *
     * @return void
     */
    public function displayDefaultHead()
    {
        ?>
        <form method="post">
        <?php
        parent::displayDefaultHead();

        wp_nonce_field("tlbm-save-form-nonce-" . $this->menu_slug, "tlbm-save-form-nonce");
        ?>
        <?php
    }

    /**
     *
     * @return void
     */
    public function displayDefaultHeadBar()
    {
        ?>
        <button class="button button-primary tlbm-admin-button-bar"><?php
            _e("Save Changes", TLBM_TEXT_DOMAIN) ?></button>
        <?php
    }

    final public function display()
    {
        $this->formBuilder->init();

        if (isset($_POST['tlbm-save-form-nonce'])) {
            if (wp_verify_nonce($_POST['tlbm-save-form-nonce'], "tlbm-save-form-nonce-" . $this->menu_slug)) {
                $this->notices = $this->onSave($_POST);
            }
        }

        parent::display();
    }

    abstract public function onSave($vars): array;

    /**
     *
     * @return void
     */
    final public function displayPageBody()
    {

        ?>
        <div class="wrap">
            <div class="tlbm-admin-page">
                <?php
                $this->displayNotices(); ?>
                <?php
                $this->showFormPageContent(); ?>
            </div>
        </div>
        </form>
        <?php
    }

    public function displayNotices()
    {
        if (sizeof($this->notices) > 0) {
            foreach ($this->notices as $key => $msg) {
                ?>
                <div class="notice notice-<?php
                echo $key ?> is-dismissible">
                    <p><?php
                        _e($msg, TLBM_TEXT_DOMAIN); ?></p>
                </div>
                <?php
            }
        }
    }

    abstract public function showFormPageContent();
}