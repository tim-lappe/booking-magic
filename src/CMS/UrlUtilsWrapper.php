<?php

namespace TLBM\CMS;

class UrlUtilsWrapper implements Contracts\UrlUtilsInterface
{

    /**
     * @inheritDoc
     */
    public function pluginsUrl(string $path, string $pluginFile): string
    {
        return plugins_url($path, $pluginFile);
    }

    public function adminUrl(string $path): string
    {
        return admin_url($path);
    }
}