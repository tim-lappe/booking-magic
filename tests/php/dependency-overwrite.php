<?php

use TLBM\ApiUtils\Contracts\AdminPagesInterface;
use TLBM\ApiUtils\Contracts\EnqueueAssetsInterface;
use TLBM\ApiUtils\Contracts\HooksInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\OptionsInterface;
use TLBM\ApiUtils\Contracts\PluginActivationInterface;
use TLBM\ApiUtils\Contracts\ShortcodeInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\ApiUtils\Contracts\UrlUtilsInterface;
use TLBM\Repository\Contracts\ORMInterface;
use TLBMTEST\CMS\AdminPagesTestWrapper;
use TLBMTEST\CMS\EnqueueAssetsTestWrapper;
use TLBMTEST\CMS\HooksTestWrapper;
use TLBMTEST\CMS\LocalizationTestWrapper;
use TLBMTEST\CMS\OptionsTestWrapper;
use TLBMTEST\CMS\PluginActivationTestWrapper;

use TLBMTEST\CMS\RepositoryTestWrapper;

use TLBMTEST\CMS\ShortcodeTestWrapper;

use TLBMTEST\CMS\TimeUtilsTestWrapper;
use TLBMTEST\CMS\UrlUtilsTestWrapper;

use function DI\autowire;

return [
    PluginActivationInterface::class => autowire(PluginActivationTestWrapper::class),
    HooksInterface::class => autowire(HooksTestWrapper::class),
    LocalizationInterface::class => autowire(LocalizationTestWrapper::class),
    OptionsInterface::class => autowire(OptionsTestWrapper::class),
    ORMInterface::class => autowire(RepositoryTestWrapper::class),
    AdminPagesInterface::class => autowire(AdminPagesTestWrapper::class),
    ShortcodeInterface::class => autowire(ShortcodeTestWrapper::class),
    EnqueueAssetsInterface::class => autowire(EnqueueAssetsTestWrapper::class),
    UrlUtilsInterface::class => autowire(UrlUtilsTestWrapper::class),
    TimeUtilsInterface::class => autowire(TimeUtilsTestWrapper::class)
];