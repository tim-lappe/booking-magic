<?php

namespace TLBM\Admin\Pages\SinglePages;

use Throwable;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\RecursiveFormContentWalker;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\BookingProcessor;
use TLBM\Entity\Booking;
use TLBM\Entity\Calendar;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Utilities\ExtendedDateTime;

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
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(EntityRepositoryInterface $entityRepository, LocalizationInterface $localization)
    {
        $this->entityRepository = $entityRepository;
        $this->localization     = $localization;
        parent::__construct($this->localization->getText("Edit booking form values", TLBM_TEXT_DOMAIN), "booking-edit-form-values", "booking-edit-form-values", false);
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        $booking = $this->getEditingEntity();
        if ($booking) {
            return $this->localization->getText("Edit Booking", TLBM_TEXT_DOMAIN);
        }

        return $this->localization->getText("Add New Booking", TLBM_TEXT_DOMAIN);
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

        $form = $booking->getForm();
        $inputVars = $booking->getBookingKeyValuesPairs();
        $bookingValues = $booking->getBookingValues();

        $formWalker = MainFactory::create(FormDataWalker::class);
        $formWalker->setFormDataTree($form->getFormData());

        $formFieldNames = [];
        $missed = [];

        foreach($formWalker->walkLinkedElements($inputVars) as $field) {
            $name = $field->getLinkedSettings()->getValue("name");
            $formFieldNames[] = $name;
        }

        foreach ($bookingValues as $value) {
            if (!in_array($value->getName(), $formFieldNames)) {
                $missed[$value->getTitle()] = $value->getValue();
            }
        }

        ?>

        <?php if(count($missed) > 0): ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e("Some fields cannot be edited because the associated form was changed after the booking was made. The following fields are affected: ", TLBM_TEXT_DOMAIN) ?></p>
                <ul>
                    <?php foreach ($missed as $name => $value): ?>
                        <li><?php echo $name ?>: <b><?php echo $value ?></b></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="tlbm-admin-page-tile-row">
            <div class="tlbm-admin-page-tile">
                <div class="tlbm-frontend-form">
                    <?php
                    $formWalker    = FormDataWalker::createFromData($form->getFormData());
                    $contentWalker = new RecursiveFormContentWalker($inputVars);
                    $contentWalker->setExcludeElementClasses([ CalendarElem::class ]);
                    $result        = $formWalker->walkCallback($contentWalker);
                    echo $result;
                    ?>
                </div>
            </div>
            <div class="tlbm-admin-page-tile">
                <?php foreach($booking->getCalendarBookings() as $calendarBooking):
                    $calendarsQuery = MainFactory::create(CalendarQuery::class);
                    ?>
                    <span class='tlbm-calendar-edit-title'><?php echo $calendarBooking->getTitleFromForm() ?></span>
                    <div class='tlbm-admin-calendar-field tlbm-admin-content-box'>
                        <div>
                            <div>
                                <small><?php _e("Calendar", TLBM_TEXT_DOMAIN) ?></small><br>
                                <select name='calendarBookings[<?php echo $calendarBooking->getNameFromForm() ?>][calendar_id]'>
                                    <?php

                                    $currentId = -1;
                                    if($calendarBooking->getCalendar() != null) {
                                        $currentId = $calendarBooking->getCalendar()->getId();
                                    }

                                    /**
                                     * @var Calendar $calendar
                                     */
                                    foreach ($calendarsQuery->getResult() as $calendar): ?>
                                        <option <?php selected($calendar->getId(), $currentId, true); ?> value='<?php echo $calendar->getId() ?>'>
                                            <?php echo $calendar->getTitle() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style='margin-top: 1em'>
                                <small><?php _e("Time", TLBM_TEXT_DOMAIN) ?></small><br>
                                <div class='tlbm-date-range-field' data-name='calendarBookings[<?php echo $calendarBooking->getNameFromForm() ?>][time]' data-to='<?php echo urlencode(json_encode($calendarBooking->getToDateTime())) ?>' data-from='<?php echo urlencode(json_encode($calendarBooking->getFromDateTime())) ?>'></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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

        $form = $booking->getForm();
        $formWalker = MainFactory::create(FormDataWalker::class);
        $formWalker->setFormDataTree($form->getFormData());

        $bookingProcessor = MainFactory::create(BookingProcessor::class);
        $bookingProcessor->setForm($form);
        $bookingProcessor->setVars($vars);
        $wrong = $bookingProcessor->validateVars(CalendarElem::class);

        if(count($wrong) > 0) {
            return ["error" => $this->localization->getText("Some fields are required", TLBM_TEXT_DOMAIN)
            ];
        }

        $bookingValues = $bookingProcessor->createBookingValues();
        foreach ($bookingValues as $bookingValue) {
            $keys = array_keys($booking->getBookingKeyValuesPairs());
            if(in_array($bookingValue->getName(), $keys)) {
                $booking->removeBookingValueByName($bookingValue->getName());
            }

            $booking->addBookingValue($bookingValue);
        }

        if($vars['calendarBookings'] && is_array($vars['calendarBookings'])) {
            foreach ($vars['calendarBookings'] as $name => $value) {
                $calendarBookingOriginal = $booking->getCalendarBookingByName($name);
                if(isset($value['time'])) {
                    try {
                        $data = json_decode(urldecode($value['time']), JSON_OBJECT_AS_ARRAY);
                        $fromDateTime = new ExtendedDateTime();
                        $fromDateTime->setFromObject($data['from']);
                        $calendarBookingOriginal->setFromFullDay($fromDateTime->isFullDay());
                        $calendarBookingOriginal->setFromTimestamp($fromDateTime->getTimestamp());

                        if(isset($data['to'])) {
                            $toDateTime = new ExtendedDateTime();
                            $toDateTime->setFromObject($data['to']);
                            $calendarBookingOriginal->setToFullDay($toDateTime->isFullDay());
                            $calendarBookingOriginal->setToTimestamp($toDateTime->getTimestamp());
                        } else {
                            $calendarBookingOriginal->setToTimestamp($fromDateTime->getTimestamp());
                            $calendarBookingOriginal->setToFullDay($fromDateTime->isFullDay());
                        }

                    } catch (Throwable $exception) {
                        return ["error" => $this->localization->getText("An internal error occured. ", TLBM_TEXT_DOMAIN)
                        ];
                    }
                }
                if(isset($value['calendar_id'])) {
                    $calendarBookingOriginal->setCalendar($this->entityRepository->getEntity(Calendar::class, $value['calendar_id']));
                }
            }
        }


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