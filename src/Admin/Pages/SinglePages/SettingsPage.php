<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;

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

    public function __construct(SettingsManagerInterface $settingsManager, SanitizingInterface $sanitizing)
    {
        parent::__construct("Settings", "booking-magic-settings");
        $this->sanitizing = $sanitizing;
        $this->settingsManager = $settingsManager;
        $this->parentSlug      = "booking-magic";
    }

    public function displayPageBody()
    {
        $tab = $this->sanitizing->sanitizeKey($_GET['tab'] ?? "general");
        ?>
        <div class="wrap">
            <nav class="nav-tab-wrapper">
                <?php
                foreach ($this->settingsManager->getAllSettingsGroups() as $key => $group): ?>
                    <a href="?page=<?php
                    echo $this->menuSlug ?>&tab=<?php
                    echo $key ?>" class="nav-tab <?php
                    if ($tab == $key): ?>nav-tab-active<?php
                    endif; ?>"><?php
                        echo $group ?></a>
                <?php
                endforeach; ?>
            </nav>
            <form method="post" action="<?php
            echo admin_url() . "options.php" ?>">
                <?php
                do_settings_sections("tlbm_settings_" . $tab); ?>
                <?php
                settings_fields("tlbm_" . $tab); ?>
                <?php
                submit_button(); ?>
            </form>
        </div>
        <?php
    }
}