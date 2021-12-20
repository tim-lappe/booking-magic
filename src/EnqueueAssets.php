<?php

namespace TLBM;

use DateTime;
use TLBM\Admin\FormEditor\Formeditor;
use TLBM\Admin\WpForm\RuleActionsField;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class EnqueueAssets {

	public function __construct() {
		add_action("wp_enqueue_scripts", array($this, "global_enqueue_scripts"));
		add_action("admin_enqueue_scripts", array($this, "global_enqueue_scripts"));
        add_action("admin_enqueue_scripts", array($this, "admin_enqueue_scripts"));
    }

    public function admin_enqueue_scripts() {
        wp_enqueue_script(TLBM_ADMIN_JS_SLUG, plugins_url("assets/js/dist/admin.js", TLBM_PLUGIN_FILE));
    }

	public function global_enqueue_scripts() {
		wp_enqueue_style(TLBM_MAIN_CSS_SLUG, plugins_url("assets/css/main.css", TLBM_PLUGIN_FILE));
		wp_enqueue_script(TLBM_FRONTEND_JS_SLUG, plugins_url("assets/js/dist/frontend.js", TLBM_PLUGIN_FILE));

		wp_localize_script( TLBM_FRONTEND_JS_SLUG, 'ajax_information',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
			)
		);

		Formeditor::InsertFormelementsIntoScript(TLBM_FRONTEND_JS_SLUG);
		RuleActionsField::InsertRuleActionFields(TLBM_FRONTEND_JS_SLUG);

		$this->localize_constants();
	}

	public function localize_constants() {

        $dt = new DateTime("now", wp_timezone());
        wp_localize_script(TLBM_FRONTEND_JS_SLUG, "tlbm_constants",
            array(
                "months" => array(
                    __('January', TLBM_TEXT_DOMAIN),
                    __('February', TLBM_TEXT_DOMAIN),
                    __('March', TLBM_TEXT_DOMAIN),
                    __('April', TLBM_TEXT_DOMAIN),
                    __('May', TLBM_TEXT_DOMAIN),
                    __('June', TLBM_TEXT_DOMAIN),
                    __('July ', TLBM_TEXT_DOMAIN),
                    __('August', TLBM_TEXT_DOMAIN),
                    __('September', TLBM_TEXT_DOMAIN),
                    __('October', TLBM_TEXT_DOMAIN),
                    __('November', TLBM_TEXT_DOMAIN),
                    __('December', TLBM_TEXT_DOMAIN)
                ),
                "weekdays" => array(
                    __('Monday', TLBM_TEXT_DOMAIN),
                    __('Tuesday', TLBM_TEXT_DOMAIN),
                    __('Wednesday', TLBM_TEXT_DOMAIN),
                    __('Thursday', TLBM_TEXT_DOMAIN),
                    __('Friday', TLBM_TEXT_DOMAIN),
                    __('Saturday', TLBM_TEXT_DOMAIN),
                    __('Sunday', TLBM_TEXT_DOMAIN)
                ),
                "today" => array(
                    "day" => intval($dt->format("j")),
                    "month" => intval($dt->format("n")),
                    "year" => intval($dt->format("Y"))
                ),
                "labels" => array(
                    "to" => __("To", TLBM_TEXT_DOMAIN),
                    "from" => __("From", TLBM_TEXT_DOMAIN),
                    "everyYear" => __("Yearly", TLBM_TEXT_DOMAIN),
                    "onlyUseInTimeSpan" => __("Limit", TLBM_TEXT_DOMAIN),
                    "addTimeSlot" => __("Add Timespan", TLBM_TEXT_DOMAIN),
                    "delete" => __("Delete", TLBM_TEXT_DOMAIN)
                )
            )
        );
    }
}