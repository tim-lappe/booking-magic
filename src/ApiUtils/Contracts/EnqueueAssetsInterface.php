<?php

namespace TLBM\ApiUtils\Contracts;

interface EnqueueAssetsInterface
{
    /**
     * @param string $handle
     * @param string $src
     *
     * @return void
     */
    public function enqueueScript(string $handle, string $src): void;

    /**
     * @param string $handle
     * @param string $src
     *
     * @return mixed
     */
    public function enqueueStyle(string $handle, string $src);

    /**
     * @param string $handle
     * @param string $objectName
     * @param mixed $l10n
     *
     * @return mixed
     */
    public function localizeScript(string $handle, string $objectName, $l10n);
}