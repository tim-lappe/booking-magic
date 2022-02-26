<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\EnqueueAssetsInterface;

class EnqueueAssetsTestWrapper implements EnqueueAssetsInterface
{

    /**
     * @inheritDoc
     */
    public function enqueueScript(string $handle, string $src): void
    {

    }

    /**
     * @inheritDoc
     */
    public function enqueueStyle(string $handle, string $src)
    {

    }

    /**
     * @inheritDoc
     */
    public function localizeScript(string $handle, string $objectName, $l10n)
    {

    }
}