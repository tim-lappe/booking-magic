<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\SelectField;
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


    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
        parent::__construct( __("Group",TLBM_TEXT_DOMAIN), "calendar-group-edit", "calendar-group-edit", false);

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new CalendarPickerField($this->entityRepository, "calendars", __("Calendars", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new SelectField("booking_distribution", __("Booking Distribution", TLBM_TEXT_DOMAIN), array(
                TLBM_BOOKING_DISTRIBUTION_EVENLY => __("Evenly", TLBM_TEXT_DOMAIN),
                TLBM_BOOKING_DISTRIBUTION_FILL_ONE => __("Fill One", TLBM_TEXT_DOMAIN)
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
            return ["success" => __("Group has been saved", TLBM_TEXT_DOMAIN)];
        } else {
            return array(
                "error" => __("An internal error occured.", TLBM_TEXT_DOMAIN)
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