<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\WpForm\SelectField;
use TLBM\Booking\Semantic\BookingValueSemantic;
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

    public function __construct(EntityRepositoryInterface $entityRepository, BookingRepositoryInterface $bookingManager, SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $this->bookingManager = $bookingManager;
        $this->entityRepository = $entityRepository;

        parent::__construct(__("Booking", TLBM_TEXT_DOMAIN), "booking-edit", "booking-edit", false);

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $states = [];
        $settings = $this->settingsManager->getSetting(BookingStates::class);
        if($settings instanceof BookingStates) {
            $states = $settings->getStatesKeyValue();
        }

        $this->formBuilder->defineFormField(new SelectField("state", __("State", TLBM_TEXT_DOMAIN), $states, true));
    }

    protected function getHeadTitle(): string
    {
        return __("View Booking", TLBM_TEXT_DOMAIN);
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $booking = $this->getEditingEntity();
        if (!$booking) {
            $booking = new Booking();
        }

        $semantic = MainFactory::create(BookingValueSemantic::class);
        $semantic->setValuesFromBooking($booking);
        ?>

        <div class="tlbm-admin-page-tile-row">
            <div class="tlbm-admin-page-tile tlbm-admin-page-tile-grow-3 tlbm-admin-page-booking-value-tile">
                <div class="tlbm-admin-booking-id"> <?php echo sprintf(__("#%s", TLBM_TEXT_DOMAIN), $booking->getId()) ?></div>
                <div class="tlbm-admin-booking-values">
                    <?php if($semantic->hasFullName()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php _e("Name", TLBM_TEXT_DOMAIN); ?></span>
                            <span class="tlbm-booking-value-content"><?php echo $semantic->getFullName() ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($semantic->hasFullAddress()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php _e("Address", TLBM_TEXT_DOMAIN); ?></span>
                            <span class="tlbm-booking-value-content"><?php echo $semantic->getFullAddress() ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($semantic->hasContactEmail()): ?>
                        <div class="tlbm-admin-booking-block">
                            <span class="tlbm-booking-value-title"><?php _e("E-Mail", TLBM_TEXT_DOMAIN); ?></span>
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
                                <?php if($fromDt->isSameDate($toDt)): ?>
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
        if (!$booking) {
            $booking = new Booking();
        }

        $booking->setState($vars['state']);

        if($this->entityRepository->saveEntity($booking)) {
            $savedEntity = $booking;
            return array(
                "success" => __("Booking has been saved", TLBM_TEXT_DOMAIN)
            );

        } else {
            return array(
                "error" => __("An internal error occured. ", TLBM_TEXT_DOMAIN)
            );
        }
    }
}