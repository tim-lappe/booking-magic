<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\ApiUtils\Contracts\UrlUtilsInterface;

class SettingsPage extends PageBase
{
    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

	/**
	 * @var SanitizingInterface
	 */
    private SanitizingInterface $sanitizing;

	/**
	 * @var UrlUtilsInterface
	 */
    private UrlUtilsInterface $urlUtils;

    public function __construct(UrlUtilsInterface $urlUtils, SettingsManagerInterface $settingsManager, SanitizingInterface $sanitizing)
    {
        parent::__construct("Settings", "booking-magic-settings");
        $this->sanitizing = $sanitizing;
        $this->settingsManager = $settingsManager;
        $this->parentSlug      = "booking-magic";
        $this->urlUtils        = $urlUtils;
    }

    public function displayPageBody()
    {
        $tab = $this->sanitizing->sanitizeTextfield($_GET['tab'] ?? "general");
        ?>
        <div class="wrap">
            <nav class="nav-tab-wrapper">
                <?php
                foreach ($this->settingsManager->getAllSettingsGroups() as $key => $group): ?>
                    <a href="?page=<?php echo $this->sanitizing->sanitizeKey($this->menuSlug); ?>&tab=<?php echo $this->sanitizing->sanitizeKey($key) ?>" class="nav-tab <?php if ($tab == $key): ?>nav-tab-active<?php endif; ?>">
                        <?php echo $this->escaping->escHtml($group); ?>
                    </a>
                <?php
                endforeach; ?>
            </nav>
            <form method="post" action="<?php echo $this->escaping->escUrl($this->urlUtils->adminUrl("options.php")); ?>">
                <?php do_settings_sections("tlbm_settings_" . $tab); ?>
                <?php settings_fields("tlbm_" . $tab); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}