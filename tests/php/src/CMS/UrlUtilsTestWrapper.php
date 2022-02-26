<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\UrlUtilsInterface;

class UrlUtilsTestWrapper implements UrlUtilsInterface
{

    /**
     * @inheritDoc
     */
    public function pluginsUrl(string $path, string $pluginFile): string
    {
        return "/test/plugin/" . $path;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function adminUrl(string $path): string
    {
        return "/test/admin/" . $path;
    }
}