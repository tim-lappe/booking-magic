<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\WpForm\SelectField;
use TLBM\Admin\WpForm\TextareaField;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\BookingChangeManager;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\Entity\Booking;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

/**
 * @extends EntityEditPage<Booking>
 */
class BookingEditPage extends EntityEditPage
{
    /**
     * @var BookingRepositoryInterface
     */
    private BookingRepositoryInterface $bookingManager;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    protected MailSenderInterface $mailSender;

    public function __construct(MailSenderInterface $mail, EntityRepositoryInterface $entityRepository, BookingRepositoryInterface $bookingManager, SettingsManagerInterface $settingsManager, LocalizationInterface $localization)
    {
        $this->mailSender       = $mail;
        $this->settingsManager  = $settingsManager;
        $this->bookingManager   = $bookingManager;
        $this->entityRepository = $entityRepository;
        $this->localization     = $localization;

        parent::__construct($this->localization->getText("Booking", TLBM_TEXT_DOMAIN), "booking-edit", "booking-edit", false);

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $states   = [];
        $settings = $this->settingsManager->getSetting(BookingStates::class);
        if ($settings instanceof BookingStates) {
            $states = $settings->getStatesKeyValue();
        }

        $this->formBuilder->defineFormField(new SelectField("state", $this->localization->getText("State", TLBM_TEXT_DOMAIN), $states, true));
        $this->formBuilder->defineFormField(new TextareaField("notes", $this->localization->getText("Notes", TLBM_TEXT_DOMAIN)));
    }

    protected function getHeadTitle(): string
    {
        return $this->localization->getText("View Booking", TLBM_TEXT_DOMAIN);
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $booking = $this->getEditingEntity();
        if ( !$booking) {
            $booking = new Booking();
        }

        $semantic = MainFactory::create(BookingValueSemantic::class);
        $semantic->setValuesFromBooking($booking);
        ?>

        <div class="tlbm-admin-page-tile-row">
            <div class="tlbm-admin-page-tile tlbm-admin-page-tile-grow-2 tlbm-admin-page-booking-value-tile">
                <div class="tlbm-admin-booking-id"> <?php
                    echo sprintf($this->localization->getText("#%s", TLBM_TEXT_DOMAIN), $booking->getId()) ?></div>
                <div class="tlbm-admin-booking-values">
                    <?php
                    if ($semantic->hasFullName()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php
                                _e("Name", TLBM_TEXT_DOMAIN); ?></span>
                            <span class="tlbm-booking-value-content"><?php
                                echo $semantic->getFullName() ?></span>
                        </div>
                    <?php
                    endif; ?>
                    <?php
                    if ($semantic->hasFullAddress()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php
                                _e("Address", TLBM_TEXT_DOMAIN); ?></span>
                            <span class="tlbm-booking-value-content"><?php
                                echo $semantic->getFullAddress() ?></span>
                        </div>
                    <?php
                    endif; ?>
                    <?php
                    if ($semantic->hasContactEmail()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php
                                _e("E-Mail", TLBM_TEXT_DOMAIN); ?></span>
                            <span class="tlbm-booking-value-content">
                                <a href="mailto:<?php echo $semantic->getContactEmail() ?>">
                                    <?php echo $semantic->getContactEmail() ?>
                                </a>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php

                    foreach ($booking->getBookingValues() as $value) {
                        if(!in_array( $value->getName(), $semantic->getAllSemanticFieldNames())) {
                            ?>
                            <div class="tlbm-admin-booking-block">
                                <span class="tlbm-booking-value-title"><?php echo $value->getTitle(); ?></span>
                                <span class="tlbm-booking-value-content">
                                    <?php echo $value->getValue(); ?>
                                </span>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="tlbm-admin-booking-values tlbm-admin-booking-calendars">
                    <div class="tlbm-admin-booking-block">
                        <?php
                        $calendarBookings = $booking->getCalendarBookings();
                        foreach ($calendarBookings as $calendarBooking) {
                            $fromDt = $calendarBooking->getFromDateTime();
                            $toDt = $calendarBooking->getToDateTime();
                            ?>
                            <span class="tlbm-booking-value-title"><?php echo $calendarBooking->getTitleFromForm(); ?></span>
                            <span class="tlbm-booking-calendar-content">
                                <?php if($toDt == null || $fromDt->isEqualTo($toDt)): ?>
                                    <?php echo $calendarBooking->getFromDateTime(); ?>
                                <?php else: ?>
                                    <?php echo $calendarBooking->getFromDateTime(); ?> - <?php echo $calendarBooking->getToDateTime(); ?>
                                <?php endif; ?>
                            </span>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div class="tlbm-admin-page-tile tlbm-admin-page-tile-sm-form tlbm-admin-page-tile-big-padding">
                <?php
                $this->formBuilder->displayFormHead();
                $this->formBuilder->displayFormField("state", $booking->getState());
                $this->formBuilder->displayFormField("notes", $booking->getNotes());
                $this->formBuilder->displayFormFooter();
                ?>
            </div>
        </div>
        <?php
    }

    public function displayDefaultHeadBar()
    {
        $booking = $this->getEditingEntity();
        if($booking != null) {
            ?>
            <a class="button tlbm-admin-button-bar" href="<?php echo $this->adminPageManager->getPage(BookingEditValuesPage::class)->getEditLink($booking->getId()); ?>">
                <span class="dashicons dashicons-edit" style="margin-right: 0.25em"></span> <?php _e("Edit Booking", TLBM_TEXT_DOMAIN); ?>
            </a>
            <?php
        }
        parent::displayDefaultHeadBar(); // TODO: Change the autogenerated stub
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->bookingManager->getBooking($id);
    }

    /**
     * @param mixed $vars
     * @param ManageableEntity|null $savedEntity
     *
     * @return array
     */
    protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $booking = $this->getEditingEntity();
        if ( !$booking) {
            $booking = new Booking();
        }

        $bookingChange = MainFactory::create(BookingChangeManager::class);
        $bookingChange->setBooking($booking);
        $bookingChange->setState($vars['state']);
        $bookingChange->storeValuesToBooking();

        $booking->setNotes($vars['notes']);


        if ($this->entityRepository->saveEntity($booking)) {
            $savedEntity = $booking;

            return ["success" => $this->localization->getText("Booking has been saved", TLBM_TEXT_DOMAIN)
            ];
        } else {
            return ["error" => $this->localization->getText("An internal error occured. ", TLBM_TEXT_DOMAIN)
            ];
        }
    }
}