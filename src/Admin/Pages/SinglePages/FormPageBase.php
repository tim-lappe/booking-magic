<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\MainFactory;

abstract class FormPageBase extends PageBase
{
    /**
     * @var FormBuilderInterface
     */
    protected FormBuilderInterface $formBuilder;

    /**
     * @var array
     */
    protected array $notices = array();

	/**
	 * @var SanitizingInterface
	 */
    protected SanitizingInterface $sanitizing;

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

    /**
     * @param string $menuTitle
     * @param string $menuSlug
     * @param bool $showInMenu
     * @param bool $displayDefaultHead
     * @param string $defaultHeadTitle
     *
     */
    public function __construct(
        string $menuTitle,
        string $menuSlug,
        bool $showInMenu = true,
        bool $displayDefaultHead = true,
        string $defaultHeadTitle = ""
    ) {
        $this->formBuilder = MainFactory::create(FormBuilderInterface::class);
        $this->sanitizing = MainFactory::get(SanitizingInterface::class);
        $this->escaping = MainFactory::get(EscapingInterface::class);
        parent::__construct(
            $menuTitle, $menuSlug, $showInMenu, $displayDefaultHead, $defaultHeadTitle
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

        wp_nonce_field("tlbm-save-form-nonce-" . $this->menuSlug, "tlbm-save-form-nonce");
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
        if (isset($_POST['tlbm-save-form-nonce'])) {
            if (wp_verify_nonce($this->sanitizing->sanitizeTextfield($_POST['tlbm-save-form-nonce']), "tlbm-save-form-nonce-" . $this->menuSlug)) {
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
                $this->displayFormPageContent(); ?>
            </div>
        </div>
        </form>
        <?php
    }

    public function displayNotices()
    {
        if (count($this->notices) > 0) {
            foreach ($this->notices as $key => $msg) {
                ?>
                <div class="notice notice-<?php echo $this->escaping->escAttr(is_numeric($key) ? "error" : $key); ?> is-dismissible">
                    <p><?php _e($msg, TLBM_TEXT_DOMAIN); ?></p>
                </div>
                <?php
            }
        }
    }

    abstract public function displayFormPageContent();
}