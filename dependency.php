<?php


use DI\FactoryInterface;
use Psr\Container\ContainerInterface;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\Elements\AddressElem;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\Elements\CityElem;
use TLBM\Admin\FormEditor\Elements\ColumnsElem;
use TLBM\Admin\FormEditor\Elements\ContactEmailElem;
use TLBM\Admin\FormEditor\Elements\CustomHtmlElem;
use TLBM\Admin\FormEditor\Elements\EmailElem;
use TLBM\Admin\FormEditor\Elements\FirstNameElem;
use TLBM\Admin\FormEditor\Elements\HrElem;
use TLBM\Admin\FormEditor\Elements\LastNameElem;
use TLBM\Admin\FormEditor\Elements\SelectElement;
use TLBM\Admin\FormEditor\Elements\SpacingElem;
use TLBM\Admin\FormEditor\Elements\TextareaElem;
use TLBM\Admin\FormEditor\Elements\TextBoxElem;
use TLBM\Admin\FormEditor\Elements\ZipElem;
use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\Admin\Pages\AdminPageManager;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\BookingEditPage;
use TLBM\Admin\Pages\SinglePages\BookingEditValuesPage;
use TLBM\Admin\Pages\SinglePages\BookingMagicRoot;
use TLBM\Admin\Pages\SinglePages\BookingsPage;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarGroupEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarGroupPage;
use TLBM\Admin\Pages\SinglePages\CalendarPage;
use TLBM\Admin\Pages\SinglePages\Dashboard\BestSellingCalendarsTile;
use TLBM\Admin\Pages\SinglePages\Dashboard\BookingsChartTile;
use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;
use TLBM\Admin\Pages\SinglePages\Dashboard\Dashboard;
use TLBM\Admin\Pages\SinglePages\Dashboard\LastBookingsTile;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Admin\Pages\SinglePages\FormPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Pages\SinglePages\RulesPage;
use TLBM\Admin\Pages\SinglePages\SettingsPage;
use TLBM\Admin\RuleActionsEditor\Actions\DaySlotEditorElem;
use TLBM\Admin\RuleActionsEditor\Actions\MessageEditorElem;
use TLBM\Admin\RuleActionsEditor\Actions\MultipleTimeSlotEditorElem;
use TLBM\Admin\RuleActionsEditor\Actions\SlotOverwriteEditorElem;
use TLBM\Admin\RuleActionsEditor\Actions\TimeSlotEditorElem;
use TLBM\Admin\RuleActionsEditor\Contracts\RuleActionsEditorCollectionInterface;
use TLBM\Admin\RuleActionsEditor\RuleActionsEditorCollection;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SettingsManager;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\ExpiryTime;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\LatestBookingPossibility;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\Admin\Settings\SingleSettings\Emails\AdminEmailBookingReceived;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingCancelled;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingInProcess;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingReceived;
use TLBM\Admin\Settings\SingleSettings\General\AdminMail;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookingReceived;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookNow;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Ajax\AjaxManager;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\Ajax\GetMergedActions;
use TLBM\Ajax\PingPong;
use TLBM\ApiUtils\AdminPagesWrapper;
use TLBM\ApiUtils\Contracts\AdminPagesInterface;
use TLBM\ApiUtils\Contracts\EnqueueAssetsInterface;
use TLBM\ApiUtils\Contracts\HooksInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface as LocalizationInterfaceAlias;
use TLBM\ApiUtils\Contracts\MailInterface;
use TLBM\ApiUtils\Contracts\OptionsInterface;
use TLBM\ApiUtils\Contracts\PluginActivationInterface;
use TLBM\ApiUtils\Contracts\ShortcodeInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\ApiUtils\Contracts\UrlUtilsInterface;
use TLBM\ApiUtils\EnqueueAssetsWrapper;
use TLBM\ApiUtils\HooksWrapper;
use TLBM\ApiUtils\LocalizationWrapper;
use TLBM\ApiUtils\MailWrapper;
use TLBM\ApiUtils\OptionsWrapper;
use TLBM\ApiUtils\PluginActivationWrapper;
use TLBM\ApiUtils\ShortcodeWrapper;
use TLBM\ApiUtils\TimeUtilsWrapper;
use TLBM\ApiUtils\UrlUtilsWrapper;
use TLBM\Booking\CalendarBookingManager;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\Email\MailSender;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Localization\Contracts\ScriptLocalizationInterface;
use TLBM\Localization\Labels;
use TLBM\Localization\ScriptLocalization;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Output\Contracts\FrontendMessengerInterface;
use TLBM\Output\FormPrint;
use TLBM\Output\FrontendMessenger;
use TLBM\Repository\BookingRepository;
use TLBM\Repository\CacheManager;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Repository\Contracts\CacheManagerInterface;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\EntityRepository;
use TLBM\Repository\ORMManager;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Repository\Query\FullRuleActionQuery;
use TLBM\Request\CompleteBookingRequest;
use TLBM\Request\Contracts\RequestManagerInterface;
use TLBM\Request\RequestManager;
use TLBM\Request\ShowBookingOverview;
use TLBM\Rules\Actions\DateSlotActionHandler;
use TLBM\Rules\Actions\MessageActionHandler;
use TLBM\Rules\Actions\MultipleTimeSlotActionHandler;
use TLBM\Rules\Actions\RuleActionsManager;
use TLBM\Rules\Actions\SlotOverwriteHandler;
use TLBM\Rules\Actions\TimeSlotActionHandler;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Rules\RulesCapacityManager;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Validation\CalendarEntityValidator;
use TLBM\Validation\Contracts\CalendarEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesActionEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesPeriodEntityValidatorInterface;
use TLBM\Validation\Contracts\TimeSlotEntityValidatorInterface;
use TLBM\Validation\RulesActionEntityValidator;
use TLBM\Validation\RulesEntityValidator;
use TLBM\Validation\RulesPeriodEntityValidator;
use TLBM\Validation\TimeSlotEntityValidator;

use function DI\autowire;
use function DI\factory;

return [
    "config"                                 => __DIR__ . "/config.php",
    ORMInterface::class                      => autowire(ORMManager::class),
    BookingRepositoryInterface::class        => autowire(BookingRepository::class),
    MailSenderInterface::class               => autowire(MailSender::class),
    LabelsInterface::class                   => autowire(Labels::class),
    ScriptLocalizationInterface::class       => autowire(ScriptLocalization::class),
    CalendarSelectionHandlerInterface::class => autowire(CalendarSelectionHandler::class),
    ColorsInterface::class                   => autowire(Colors::class),
    FormPrintInterface::class                => autowire(FormPrint::class),
    FormBuilderInterface::class              => autowire(FormBuilder::class),
    FullRuleActionQueryInterface::class      => autowire(FullRuleActionQuery::class),
    FrontendMessengerInterface::class        => autowire(FrontendMessenger::class),
    RulesCapacityManagerInterface::class     => autowire(RulesCapacityManager::class),
    CalendarBookingManagerInterface::class   => autowire(CalendarBookingManager::class),
    EntityRepositoryInterface::class => autowire(EntityRepository::class),
    CacheManagerInterface::class => autowire(CacheManager::class),

    CalendarEntityValidatorInterface::class    => autowire(CalendarEntityValidator::class),
    RulesActionEntityValidatorInterface::class => autowire(RulesActionEntityValidator::class),
    RulesPeriodEntityValidatorInterface::class => autowire(RulesPeriodEntityValidator::class),
    TimeSlotEntityValidatorInterface::class    => autowire(TimeSlotEntityValidator::class),
    RulesEntityValidatorInterface::class       => autowire(RulesEntityValidator::class),

    PluginActivationInterface::class => autowire(PluginActivationWrapper::class),
    HooksInterface::class => autowire(HooksWrapper::class),
    LocalizationInterfaceAlias::class => autowire(LocalizationWrapper::class),
    OptionsInterface::class => autowire(OptionsWrapper::class),
    AdminPagesInterface::class => autowire(AdminPagesWrapper::class),
    ShortcodeInterface::class => autowire(ShortcodeWrapper::class),
    EnqueueAssetsInterface::class => autowire(EnqueueAssetsWrapper::class),
    UrlUtilsInterface::class => autowire(UrlUtilsWrapper::class),
    TimeUtilsInterface::class => autowire(TimeUtilsWrapper::class),
    MailInterface::class => autowire(MailWrapper::class),


    RuleActionsEditorCollectionInterface::class => factory(function (ContainerInterface $container, FactoryInterface $factory) {
        $ruleActionsEditorCollection = $container->get(RuleActionsEditorCollection::class);
        if ($ruleActionsEditorCollection instanceof RuleActionsEditorCollection) {
            $ruleActionsEditorCollection->registerRuleActionEditorElem(DaySlotEditorElem::class);
            $ruleActionsEditorCollection->registerRuleActionEditorElem(TimeSlotEditorElem::class);
            $ruleActionsEditorCollection->registerRuleActionEditorElem(MultipleTimeSlotEditorElem::class);
            $ruleActionsEditorCollection->registerRuleActionEditorElem(MessageEditorElem::class);
            $ruleActionsEditorCollection->registerRuleActionEditorElem(SlotOverwriteEditorElem::class);
        }

        return $ruleActionsEditorCollection;
    }),

    /**
     * Register Rule Actions
     */
    RuleActionsManagerInterface::class => factory(function (ContainerInterface $container, FactoryInterface $factory) {
        $ruleActionManager = $container->get(RuleActionsManager::class);
        if ($ruleActionManager instanceof RuleActionsManager) {
            $ruleActionManager->registerActionHandlerClass("day_slot", DateSlotActionHandler::class);
            $ruleActionManager->registerActionHandlerClass("time_slot", TimeSlotActionHandler::class);
            $ruleActionManager->registerActionHandlerClass("multiple_time_slots", MultipleTimeSlotActionHandler::class);
            $ruleActionManager->registerActionHandlerClass("message", MessageActionHandler::class);
            $ruleActionManager->registerActionHandlerClass("slot_overwrite", SlotOverwriteHandler::class);
        }

        return $ruleActionManager;
    }),

    /**
     * Register all Form Elements
     */
    FormElementsCollectionInterface::class     => factory(function (ContainerInterface $container, FactoryInterface $factory) {
        $formElementsCollection = $container->get(FormElementsCollection::class);
        if ($formElementsCollection instanceof FormElementsCollectionInterface) {
            $formElementsCollection->registerFormElement(
                $factory->make(ColumnsElem::class, [
                    "name"    => "2er_columns",
                    "columns" => 2
                ])
            );

            $formElementsCollection->registerFormElement(
                $factory->make(ColumnsElem::class, [
                    "name"    => "3er_columns",
                    "columns" => 3
                ])
            );

            $formElementsCollection->registerFormElement(
                $factory->make(ColumnsElem::class, [
                    "name"    => "4er_columns",
                    "columns" => 4
                ])
            );

            $formElementsCollection->registerFormElement(
                $factory->make(ColumnsElem::class, [
                    "name"    => "5er_columns",
                    "columns" => 5
                ])
            );

            $formElementsCollection->registerFormElement(
                $factory->make(ColumnsElem::class, [
                    "name"    => "6er_columns",
                    "columns" => 6
                ])
            );

            $formElementsCollection->registerFormElement($factory->make(HrElem::class));
            $formElementsCollection->registerFormElement($factory->make(SpacingElem::class));
            $formElementsCollection->registerFormElement($factory->make(CalendarElem::class));
            $formElementsCollection->registerFormElement($factory->make(ContactEmailElem::class));
            $formElementsCollection->registerFormElement($factory->make(FirstNameElem::class));
            $formElementsCollection->registerFormElement($factory->make(LastNameElem::class));
            $formElementsCollection->registerFormElement($factory->make(AddressElem::class));
            $formElementsCollection->registerFormElement($factory->make(ZipElem::class));
            $formElementsCollection->registerFormElement($factory->make(CityElem::class));
            $formElementsCollection->registerFormElement($factory->make(EmailElem::class));
            $formElementsCollection->registerFormElement($factory->make(TextBoxElem::class));
            $formElementsCollection->registerFormElement($factory->make(SelectElement::class));
            $formElementsCollection->registerFormElement($factory->make(CustomHtmlElem::class));
            $formElementsCollection->registerFormElement($factory->make(TextareaElem::class));
        }

        return $formElementsCollection;
    }),

    /**
     * Requests
     */
    RequestManagerInterface::class             => factory(function (ContainerInterface $container) {
        $requestManager = $container->get(RequestManager::class);
        if ($requestManager instanceof RequestManagerInterface) {
            $requestManager->registerEndpoint($container->get(CompleteBookingRequest::class));
            $requestManager->registerEndpoint($container->get(ShowBookingOverview::class));
            $requestManager->beforeInit();
        }

        return $requestManager;
    }),

    /**
     * Admin Pages
     */
    AdminPageManagerInterface::class           => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $adminPageManager = $container->get(AdminPageManager::class);
        if ($adminPageManager instanceof AdminPageManagerInterface) {
            $adminPageManager->registerPage(BookingMagicRoot::class);
            $adminPageManager->registerPage(BookingsPage::class);
            $adminPageManager->registerPage(CalendarPage::class);
            $adminPageManager->registerPage(CalendarGroupPage::class);

            $adminPageManager->registerPage(CalendarEditPage::class);
            $adminPageManager->registerPage(RulesPage::class);
            $adminPageManager->registerPage(RuleEditPage::class);
            $adminPageManager->registerPage(FormPage::class);
            $adminPageManager->registerPage(FormEditPage::class);
            $adminPageManager->registerPage(SettingsPage::class);
            $adminPageManager->registerPage(BookingEditPage::class);
            $adminPageManager->registerPage(CalendarGroupEditPage::class);
            $adminPageManager->registerPage(BookingEditValuesPage::class);
        }

        return $adminPageManager;
    }),

    /**
     * Settings Manager
     */
    SettingsManagerInterface::class            => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $settingsManager = $container->get(SettingsManager::class);
        if ($settingsManager instanceof SettingsManagerInterface) {
            $localization = $container->get(LocalizationInterfaceAlias::class);
            /**
             * General
             */
            $settingsManager->registerSettingsGroup("general", $localization->getText("General", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(AdminMail::class));

            /**
             * Booking Process,
             */
            $settingsManager->registerSettingsGroup(
                "booking_process", $localization->getText("Booking Process", TLBM_TEXT_DOMAIN)
            );
            $settingsManager->registerSetting($container->get(SinglePageBooking::class));
            $settingsManager->registerSetting($container->get(BookingStates::class));
            $settingsManager->registerSetting($container->get(DefaultBookingState::class));
            $settingsManager->registerSetting($container->get(ExpiryTime::class));
            $settingsManager->registerSetting($container->get(LatestBookingPossibility::class));


            /**
             * E-Mails
             */
            $settingsManager->registerSettingsGroup("emails", $localization->getText("E-Mails", TLBM_TEXT_DOMAIN));
            //  $settingsManager->registerSetting($container->get(SenderName::class));
            //  $settingsManager->registerSetting($container->get(SenderMail::class));
            $settingsManager->registerSetting($container->get(EmailBookingReceived::class));
            $settingsManager->registerSetting($container->get(EmailBookingInProcess::class));
            $settingsManager->registerSetting($container->get(EmailBookingConfirmation::class));
            $settingsManager->registerSetting($container->get(EmailBookingCancelled::class));
            $settingsManager->registerSetting($container->get(AdminEmailBookingReceived::class));

            /**
             * Text
             */
            $settingsManager->registerSettingsGroup("text", $localization->getText("Text", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(TextBookingReceived::class));
            $settingsManager->registerSetting($container->get(TextBookNow::class));
            /**
             * Rules
             */
            $settingsManager->registerSettingsGroup("rules", $localization->getText("Rules", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(PriorityLevels::class));
        }

        return $settingsManager;
    }),

    /**
     * Admin Dashboard
     */
    DashboardInterface::class                  => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $dashboard = $container->get(Dashboard::class);
        if ($dashboard instanceof DashboardInterface) {
            $dashboard->registerTile(1, $factory->make(BookingsChartTile::class));
            $dashboard->registerTile(2, $factory->make(LastBookingsTile::class));
            $dashboard->registerTile(2, $factory->make(BestSellingCalendarsTile::class));
        }

        return $dashboard;
    }),

    AjaxManagerInterface::class => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $ajaxManager = $container->get(AjaxManager::class);
        if ($ajaxManager instanceof AjaxManagerInterface) {
            $ajaxManager->registerAjaxFunction($container->get(GetMergedActions::class));
            $ajaxManager->registerAjaxFunction($container->get(PingPong::class));
        }

        return $ajaxManager;
    })
];