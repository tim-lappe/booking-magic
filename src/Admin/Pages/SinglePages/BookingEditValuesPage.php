<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Booking\BookingProcessor;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Entity\Booking;
use TLBM\Entity\CalendarBooking;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

/**
 * @extends EntityEditPage<Booking>
 */
class BookingEditValuesPage extends EntityEditPage
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var FormPrintInterface
     */
    private FormPrintInterface $formPrint;

    public function __construct(EntityRepositoryInterface $entityRepository, FormPrintInterface $formPrint)
    {
        $this->entityRepository = $entityRepository;
        $this->formPrint = $formPrint;
        parent::__construct(__("Edit booking form values", TLBM_TEXT_DOMAIN), "booking-edit-form-values", "booking-edit-form-values", false);
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        $booking = $this->getEditingEntity();
        if ($booking) {
            return __("Edit Booking", TLBM_TEXT_DOMAIN);
        }

        return __("Add New Booking", TLBM_TEXT_DOMAIN);
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->entityRepository->getEntity(Booking::class, $id);
    }

    /**
     * @inheritDoc
     */
    protected function displayEntityEditForm(): void
    {
        $booking = $this->getEditingEntity();
        if ( !$booking) {
            $booking = new Booking();
        }

        $semantic = MainFactory::create(BookingValueSemantic::class);
        $semantic->setValuesFromBooking($booking);

        $form = $booking->getForm();
        $inputVars = $booking->getBookingKeyValuesPairs();
        $formWalker = MainFactory::create(FormDataWalker::class);
        $formWalker->setFormDataTree($form->getFormData());

        $formFieldNames = [];
        foreach($formWalker->walkLinkedElements($inputVars) as $field) {
            $formFieldNames[] = $field->getLinkedSettings()->getValue("name");
        }

        foreach($booking->getCalendarBookings() as $calendarBooking) {
            if ($calendarBooking->getCalendar() != null) {
                $inputVars[$calendarBooking->getNameFromForm()] = $calendarBooking;
            }
        }


        $missed = [];
        foreach($inputVars as $name => $title) {
            if(!in_array($name, $formFieldNames)) {
                $missed[$name] = $title;
            }
        }


        ?>

        <?php if(count($missed) > 0): ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e("Some fields cannot be edited because the associated form was changed after the booking was made. The following fields are affected: ", TLBM_TEXT_DOMAIN) ?></p>
                <ul>
                    <?php foreach ($missed as $name => $value): ?>
                        <li><b><?php echo $name ?></b></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="tlbm-admin-page-tile tlbm-admin-page-tile-middle-container">
            <div class="tlbm-frontend-form">
                <?php
                    echo $this->formPrint->printForm($form->getId(), $inputVars);
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * @inheritDoc
     */
    protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $booking = $this->getEditingEntity();
        if ( !$booking) {
            $booking = new Booking();
        }

        $booking->setState($vars['state']);

        if ($this->entityRepository->saveEntity($booking)) {
            $savedEntity = $booking;

            return ["success" => __("Booking has been saved", TLBM_TEXT_DOMAIN)
            ];
        } else {
            return ["error" => __("An internal error occured. ", TLBM_TEXT_DOMAIN)
            ];
        }
    }
}