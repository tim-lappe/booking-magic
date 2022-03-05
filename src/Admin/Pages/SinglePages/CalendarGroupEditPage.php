<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\SelectField;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\CalendarGroup;
use TLBM\Entity\ManageableEntity;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

/**
 * @extends EntityEditPage<CalendarGroup>
 */
class CalendarGroupEditPage extends EntityEditPage
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
        $this->localization = $localization;

        parent::__construct( $this->localization->__("Group",TLBM_TEXT_DOMAIN), "calendar-group-edit", "calendar-group-edit", false);

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new CalendarPickerField($this->entityRepository, "calendars", $this->localization->__("Calendars", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new SelectField("booking_distribution", $this->localization->__("Booking Distribution", TLBM_TEXT_DOMAIN), array(
                TLBM_BOOKING_DISTRIBUTION_EVENLY => $this->localization->__("Evenly", TLBM_TEXT_DOMAIN),
                TLBM_BOOKING_DISTRIBUTION_FILL_ONE => $this->localization->__("Fill One", TLBM_TEXT_DOMAIN)
            ))
        );
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $calendarGroup = $this->getEditingEntity();
        if (!$calendarGroup) {
            $calendarGroup = new CalendarGroup();
        }
        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $calendarGroup->getTitle() ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>

        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("calendars", $calendarGroup->getCalendarSelection());
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("booking_distribution", $calendarGroup->getBookingDisitribution());
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <?php
    }

    public function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $calendarGroup = $this->getEditingEntity();
        if (!$calendarGroup) {
            $calendarGroup = new CalendarGroup();
        }

        //TODO: Validator für Calendar Group implementieren
        $calendarGroup->setTitle($vars['title']);
        $calendarSelection = $this->formBuilder->readVars("calendars", $vars);

        $calendarGroup->setCalendarSelection($calendarSelection);
        $calendarGroup->setBookingDisitribution($vars['booking_distribution']);

        if($this->entityRepository->saveEntity($calendarGroup)) {
            $savedEntity = $calendarGroup;
            return ["success" => $this->localization->__("Group has been saved", TLBM_TEXT_DOMAIN)];
        } else {
            return array(
                "error" => $this->localization->__("An internal error occured.", TLBM_TEXT_DOMAIN)
            );
        }
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->entityRepository->getEntity(CalendarGroup::class, $id);
    }
}