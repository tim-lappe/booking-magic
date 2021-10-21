<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Settings\SettingsManager;

class SettingsPage extends PageBase {

	public function __construct() {
		parent::__construct( "Settings", "booking-magic-settings" );

		$this->parent_slug = "booking-magic";
	}

	public function ShowPageContent() {
		$tab = isset($_GET['tab']) ? $_GET['tab'] : "general";
		?>
		<div class="wrap">
			<h1><?php _e("Settings", TLBM_TEXT_DOMAIN); ?></h1>
            <nav class="nav-tab-wrapper">
	            <?php foreach (SettingsManager::$groups as $key => $group): ?>
                    <a href="?page=<?php echo $this->menu_slug ?>&tab=<?php echo $key ?>" class="nav-tab <?php if($tab == $key):?>nav-tab-active<?php endif; ?>"><?php echo $group ?></a>
	            <?php endforeach; ?>
            </nav>
			<form method="post" action="<?php echo admin_url() . "options.php" ?>">
                <?php do_settings_sections("tlbm_settings_" . $tab); ?>
                <?php settings_fields( "tlbm_" . $tab ); ?>
                <?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}