<?php

namespace TLBM\ApiUtils;

class EnqueueAssetsWrapper implements Contracts\EnqueueAssetsInterface
{

    /**
     * @inheritDoc
     */
    public function enqueueScript(string $handle, string $src): void
    {
        wp_enqueue_script($handle, $src);
    }

    /**
     * @inheritDoc
     */
    public function enqueueStyle(string $handle, string $src)
    {
        wp_enqueue_style($handle, $src);
    }

    /**
     * @param string $handle
     * @param string $objectName
     * @param mixed $l10n
     *
     * @return void
     */
    public function localizeScript(string $handle, string $objectName, $l10n)
    {
        wp_localize_script($handle, $objectName, $l10n);
    }
}