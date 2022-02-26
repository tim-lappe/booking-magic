<?php

namespace TLBM;

use Exception;
use TLBM\CMS\Contracts\EnqueueAssetsInterface;
use TLBM\CMS\Contracts\HooksInterface;
use TLBM\CMS\Contracts\UrlUtilsInterface;
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

    /**
     * @var EnqueueAssetsInterface
     */
    private EnqueueAssetsInterface $assets;

    /**
     * @var UrlUtilsInterface
     */
    private UrlUtilsInterface $urlUtils;

    public function __construct(ScriptLocalizationInterface $scriptLocalization, HooksInterface $hooks, EnqueueAssetsInterface $assets, UrlUtilsInterface $urlUtils)
    {
        $this->scriptLocalization = $scriptLocalization;
        $this->assets = $assets;
        $this->urlUtils = $urlUtils;

        $hooks->addAction("wp_enqueue_scripts", array($this, "globalEnqueueScripts"));
        $hooks->addAction("admin_enqueue_scripts", array($this, "globalEnqueueScripts"));
        $hooks->addAction("admin_enqueue_scripts", array($this, "adminEnqueueScripts"));
    }

    /**
     * @return void
     */
    public function adminEnqueueScripts()
    {
        $this->assets->enqueueScript(TLBM_ADMIN_JS_SLUG, $this->urlUtils->pluginsUrl("assets/js/dist/admin.js", TLBM_PLUGIN_FILE));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function globalEnqueueScripts()
    {
        $this->assets->enqueueStyle(TLBM_MAIN_CSS_SLUG, $this->urlUtils->pluginsUrl("assets/css/main.css", TLBM_PLUGIN_FILE));
        $this->assets->enqueueScript(TLBM_FRONTEND_JS_SLUG, $this->urlUtils->pluginsUrl("assets/js/dist/frontend.js", TLBM_PLUGIN_FILE));

        $this->assets->localizeScript(TLBM_FRONTEND_JS_SLUG, 'ajax_information', array(
            'url' => $this->urlUtils->adminUrl('admin-ajax.php') . "?action=tlbm_ajax",
        ));

        $this->assets->localizeScript(TLBM_FRONTEND_JS_SLUG, 'tlbm_localization', $this->scriptLocalization->getLabels());
    }
}