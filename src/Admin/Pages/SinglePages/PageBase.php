<?php


namespace TLBM\Admin\Pages\SinglePages;


abstract class PageBase {

	public string $menu_title;

	public string $menu_secondary_title;

	public string $capabilities = "manage_options";

	public string $menu_slug = "";

	public string $icon = "dashicons-calendar";

	public string $parent_slug = "";

    public bool $show_in_menu = true;

    public bool $display_default_head = true;

    public string $display_default_head_title = "";

    /**
     * @param string $menu_title
     * @param string $menu_slug
     * @param bool $show_in_menu
     * @param bool $display_default_head
     * @param string $display_default_head_title
     */
	public function __construct(string $menu_title, string $menu_slug, bool $show_in_menu = true, bool $display_default_head = true, string $display_default_head_title = "") {
		$this->menu_title = $menu_title;
		$this->menu_slug = $menu_slug;
        $this->show_in_menu = $show_in_menu;
        $this->display_default_head = $display_default_head;

        if(empty($display_default_head_title)) {
            $this->display_default_head_title = $menu_title;
        }
	}

    protected function GetHeadTitle(): string {
        return $this->display_default_head_title;
    }

    public function DisplayDefaultHead() {
        ?>
        <div class="tlbm-admin-page-head">
            <span class="tlbm-admin-page-head-title"><?php echo $this->GetHeadTitle() ?></span>
            <div class="tlbm-admin-page-head-bar">
                <?php $this->DisplayDefaultHeadBar() ?>
            </div>
        </div>
        <?php
    }

    public function DisplayDefaultHeadBar() {

    }

	public abstract function DisplayPageBody();

    public function Display() {
        if($this->display_default_head) {
            $this->DisplayDefaultHead();
        }

        $this->DisplayPageBody();
    }
}