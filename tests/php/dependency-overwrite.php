<?php

use TLBM\CMS\Contracts\AdminPagesInterface;
use TLBM\CMS\Contracts\EnqueueAssetsInterface;
use TLBM\CMS\Contracts\HooksInterface;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\CMS\Contracts\OptionsInterface;
use TLBM\CMS\Contracts\PluginActivationInterface;
use TLBM\CMS\Contracts\ShortcodeInterface;
use TLBM\CMS\Contracts\TimeUtilsInterface;
use TLBM\CMS\Contracts\UrlUtilsInterface;
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