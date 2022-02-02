<?php

namespace TLBM;

use DateTime;
use Exception;
use TLBM\Localization\Contracts\ScriptLocalizationInterface;

if ( !defined('ABSPATH')) {
    return;
}

class EnqueueAssets
{

    /**
     * @var ScriptLocalizationInterface
     */
    private ScriptLocalizationInterface $scriptLocalization;

    public function __construct(ScriptLocalizationInterface $scriptLocalization)
    {
        $this->scriptLocalization = $scriptLocalization;

        add_action("wp_enqueue_scripts", array($this, "globalEnqueueScripts"));
        add_action("admin_enqueue_scripts", array($this, "globalEnqueueScripts"));
        add_action("admin_enqueue_scripts", array($this, "adminEnqueueScripts"));
    }

    /**
     * @return void
     */
    public function adminEnqueueScripts()
    {
        wp_enqueue_script(TLBM_ADMIN_JS_SLUG, plugins_url("assets/js/dist/admin.js", TLBM_PLUGIN_FILE));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function globalEnqueueScripts()
    {
        wp_enqueue_style(TLBM_MAIN_CSS_SLUG, plugins_url("assets/css/main.css", TLBM_PLUGIN_FILE));
        wp_enqueue_script(TLBM_FRONTEND_JS_SLUG, plugins_url("assets/js/dist/frontend.js", TLBM_PLUGIN_FILE));

        wp_localize_script(TLBM_FRONTEND_JS_SLUG, 'ajax_information', array(
            'url' => admin_url('admin-ajax.php') . "?action=tlbm_ajax",
        ));

        wp_localize_script(TLBM_FRONTEND_JS_SLUG, 'tlbm_localization', $this->scriptLocalization->getLabels());
    }
}