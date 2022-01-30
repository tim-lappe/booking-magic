<?php


use DI\FactoryInterface;
use Psr\Container\ContainerInterface;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\Elements\AddressElem;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\Elements\CityElem;
use TLBM\Admin\FormEditor\Elements\ColumnsElem;
use TLBM\Admin\FormEditor\Elements\ContactEmailElem;
use TLBM\Admin\FormEditor\Elements\EmailElem;
use TLBM\Admin\FormEditor\Elements\FirstNameElem;
use TLBM\Admin\FormEditor\Elements\HrElem;
use TLBM\Admin\FormEditor\Elements\LastNameElem;
use TLBM\Admin\FormEditor\Elements\SelectElement;
use TLBM\Admin\FormEditor\Elements\SpacingElem;
use TLBM\Admin\FormEditor\Elements\TextBoxElem;
use TLBM\Admin\FormEditor\Elements\ZipElem;
use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\Admin\Pages\AdminPageManager;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\BookingMagicRoot;
use TLBM\Admin\Pages\SinglePages\BookingsPage;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarPage;
use TLBM\Admin\Pages\SinglePages\Dashboard\BestSellingCalendarsTile;
use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;
use TLBM\Admin\Pages\SinglePages\Dashboard\Dashboard;
use TLBM\Admin\Pages\SinglePages\Dashboard\DatesTodayTile;
use TLBM\Admin\Pages\SinglePages\Dashboard\LastBookingsTile;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Admin\Pages\SinglePages\FormPage;
use TLBM\Admin\Pages\SinglePages\RuleEditPage;
use TLBM\Admin\Pages\SinglePages\RulesPage;
use TLBM\Admin\Pages\SinglePages\SettingsPage;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SettingsManager;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TLBM\Admin\Settings\SingleSettings\General\AdminMail;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookingReceived;
use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Ajax\AjaxManager;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\Ajax\GetBookingOptions;
use TLBM\Ajax\PingPong;
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
use TLBM\Request\DoBookingRequest;
use TLBM\Request\RequestManager;
use TLBM\Request\ShowBookingOverview;
use TLBM\Rules\Actions\DateSlotActionHandler;
use TLBM\Rules\Actions\DateTimeSlotActionMerge;
use TLBM\Rules\Actions\DateTimeTimeSlotActionMerge;
use TLBM\Rules\Actions\RuleActionsManager;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Rules\RulesManager;
use TLBM\Rules\RulesQuery;
use TLBM\Utilities\Colors;
use TLBM\Utilities\Contracts\ColorsInterface;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;
use TLBM\Utilities\Contracts\PeriodsToolsInterface;
use TLBM\Utilities\Contracts\WeekdayToolsInterface;
use TLBM\Utilities\DateTimeTools;
use TLBM\Utilities\PeriodsTools;
use TLBM\Utilities\WeekdayTools;
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
    CalendarManagerInterface::class          => autowire(CalendarManager::class),
    CalendarGroupManagerInterface::class     => autowire(CalendarGroupManager::class),
    MailSenderInterface::class               => autowire(MailSender::class),
    FormManagerInterface::class              => autowire(FormManager::class),
    LabelsInterface::class                   => autowire(Labels::class),
    ScriptLocalizationInterface::class       => autowire(ScriptLocalization::class),
    CalendarSelectionHandlerInterface::class => autowire(CalendarSelectionHandler::class),
    RulesManagerInterface::class             => autowire(RulesManager::class),
    DateTimeToolsInterface::class            => autowire(DateTimeTools::class),
    PeriodsToolsInterface::class             => autowire(PeriodsTools::class),
    ColorsInterface::class                   => autowire(Colors::class),
    WeekdayToolsInterface::class             => autowire(WeekdayTools::class),
    FormPrintInterface::class                => autowire(FormPrint::class),
    FormBuilderInterface::class              => autowire(FormBuilder::class),
    RulesQueryInterface::class               => autowire(RulesQuery::class),

    CalendarEntityValidatorInterface::class    => autowire(CalendarEntityValidator::class),
    RulesActionEntityValidatorInterface::class => autowire(RulesActionEntityValidator::class),
    RulesPeriodEntityValidatorInterface::class => autowire(RulesPeriodEntityValidator::class),
    TimeSlotEntityValidatorInterface::class    => autowire(TimeSlotEntityValidator::class),
    RulesEntityValidatorInterface::class       => autowire(RulesEntityValidator::class),

    /**
     * Register Rule Actions
     */
    RuleActionsManagerInterface::class         => factory(function (ContainerInterface $container, FactoryInterface $factory) {
        $ruleActionManager = $container->get(RuleActionsManager::class);
        if ($ruleActionManager instanceof RuleActionsManager) {
            $ruleActionManager->registerActionHandlerClass("date_slot", DateSlotActionHandler::class);
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
        }

        return $formElementsCollection;
    }),

    /**
     * Requests
     */
    RequestManagerInterface::class             => factory(function (ContainerInterface $container) {
        $requestManager = $container->get(RequestManager::class);
        if ($requestManager instanceof RequestManagerInterface) {
            $requestManager->registerEndpoint($container->get(DoBookingRequest::class));
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
            $adminPageManager->registerPage($container->get(BookingMagicRoot::class));
            $adminPageManager->registerPage($container->get(BookingsPage::class));
            $adminPageManager->registerPage($container->get(CalendarPage::class));
            $adminPageManager->registerPage($container->get(CalendarEditPage::class));
            $adminPageManager->registerPage($container->get(RulesPage::class));
            $adminPageManager->registerPage($container->get(RuleEditPage::class));
            $adminPageManager->registerPage($container->get(FormPage::class));
            $adminPageManager->registerPage($container->get(FormEditPage::class));
            $adminPageManager->registerPage($container->get(SettingsPage::class));
        }

        return $adminPageManager;
    }),

    /**
     * Settings Manager
     */
    SettingsManagerInterface::class            => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $settingsManager = $container->get(SettingsManager::class);
        if ($settingsManager instanceof SettingsManagerInterface) {
            /**
             * General
             */
            $settingsManager->registerSettingsGroup("general", __("General", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(AdminMail::class));

            /**
             * Booking Process,
             */
            $settingsManager->registerSettingsGroup(
                "booking_process", __("Booking Process", TLBM_TEXT_DOMAIN)
            );
            $settingsManager->registerSetting($container->get(SinglePageBooking::class));
            $settingsManager->registerSetting($container->get(BookingStates::class));
            $settingsManager->registerSetting($container->get(DefaultBookingState::class));

            /**
             * E-Mails
             */
            $settingsManager->registerSettingsGroup("emails", __("E-Mails", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(EmailBookingConfirmation::class));

            /**
             * Text
             */
            $settingsManager->registerSettingsGroup("text", __("Text", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(WeekdayLabels::class));
            $settingsManager->registerSetting($container->get(TextBookingReceived::class));

            /**
             * Rules
             */
            $settingsManager->registerSettingsGroup("rules", __("Rules", TLBM_TEXT_DOMAIN));
            $settingsManager->registerSetting($container->get(PriorityLevels::class));

            /**
             * Advanced
             */
            $settingsManager->registerSettingsGroup("advanced", __("Advanced", TLBM_TEXT_DOMAIN));
        }

        return $settingsManager;
    }),

    /**
     * Admin Dashboard
     */
    DashboardInterface::class                  => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $dashboard = $container->get(Dashboard::class);
        if ($dashboard instanceof DashboardInterface) {
            $dashboard->registerTile(1, $factory->make(DatesTodayTile::class));
            $dashboard->registerTile(2, $factory->make(LastBookingsTile::class));
            $dashboard->registerTile(2, $factory->make(BestSellingCalendarsTile::class));
        }

        return $dashboard;
    }),

    AjaxManagerInterface::class => factory(function (FactoryInterface $factory, ContainerInterface $container) {
        $ajaxManager = $container->get(AjaxManager::class);
        if ($ajaxManager instanceof AjaxManagerInterface) {
            $ajaxManager->registerAjaxFunction($container->get(GetBookingOptions::class));
            $ajaxManager->registerAjaxFunction($container->get(PingPong::class));
        }

        return $ajaxManager;
    })
];