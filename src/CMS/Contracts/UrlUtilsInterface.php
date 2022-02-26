<?php

namespace TLBM\CMS\Contracts;

interface UrlUtilsInterface
{
    /**
     * @param string $path
     * @param string $pluginFile
     *
     * @return string
     */
    public function pluginsUrl(string $path, string $pluginFile): string;

    /**
     * @param string $path
     *
     * @return string
     */
    public function adminUrl(string $path): string;
}