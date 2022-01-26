<?php


use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\Admin\Pages\AdminPageManager;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Calendar\CalendarGroupManager;
use TLBM\Calendar\CalendarManager;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Database\ORMManager;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\Email\MailSender;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\Form\FormManager;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Localization\Contracts\ScriptLocalizationInterface;
use TLBM\Localization\Labels;
use TLBM\Localization\ScriptLocalization;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Output\FormPrint;
use TLBM\Request\Contracts\RequestManagerInterface;
use TLBM\Request\RequestManager;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Rules\RuleActionsManager;
use TLBM\Rules\RulesManager;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;
use TLBM\Utilities\Contracts\PeriodsToolsInterface;
use TLBM\Utilities\Contracts\WeekdayToolsInterface;
use TLBM\Utilities\DateTimeTools;
use TLBM\Utilities\PeriodsTools;
use TLBM\Utilities\WeekdayTools;

use function DI\autowire;

return [
    "config"                                 => __DIR__ . "/config.php",
    ORMInterface::class                      => autowire(ORMManager::class),
    CalendarManagerInterface::class          => autowire(CalendarManager::class),
    CalendarGroupManagerInterface::class     => autowire(CalendarGroupManager::class),
    FormElementsCollectionInterface::class   => autowire(FormElementsCollection::class),
    MailSenderInterface::class               => autowire(MailSender::class),
    FormManagerInterface::class              => autowire(FormManager::class),
    LabelsInterface::class                   => autowire(Labels::class),
    ScriptLocalizationInterface::class       => autowire(ScriptLocalization::class),
    CalendarSelectionHandlerInterface::class => autowire(CalendarSelectionHandler::class),
    RuleActionsManagerInterface::class       => autowire(RuleActionsManager::class),
    RulesManagerInterface::class             => autowire(RulesManager::class),
    DateTimeToolsInterface::class            => autowire(DateTimeTools::class),
    PeriodsToolsInterface::class             => autowire(PeriodsTools::class),
    ColorsInterface::class                   => autowire(Colors::class),
    WeekdayToolsInterface::class             => autowire(WeekdayTools::class),
    FormPrintInterface::class                => autowire(FormPrint::class),
    AdminPageManagerInterface::class         => autowire(AdminPageManager::class),
    RequestManagerInterface::class           => autowire(RequestManager::class),
    FormBuilderInterface::class              => autowire(FormBuilder::class)
];