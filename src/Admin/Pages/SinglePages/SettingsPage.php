<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;

class SettingsPage extends PageBase
{
    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(SettingsManagerInterface $settingsManager)
    {
        parent::__construct("Settings", "booking-magic-settings");
        $this->settingsManager = $settingsManager;
        $this->parent_slug     = "booking-magic";
    }

    public function displayPageBody()
    {
        $tab = $_GET['tab'] ?? "general";
        ?>
        <div class="wrap">
            <nav class="nav-tab-wrapper">
                <?php
                foreach ($this->settingsManager->getAllSettingsGroups() as $key => $group): ?>
                    <a href="?page=<?php
                    echo $this->menu_slug ?>&tab=<?php
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