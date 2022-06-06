<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\WpForm\CalendarSelectionField;
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

        parent::__construct($this->localization->getText("Group", TLBM_TEXT_DOMAIN), "calendar-group-edit", "calendar-group-edit", false);

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new CalendarSelectionField("calendars", $this->localization->getText("Calendars", TLBM_TEXT_DOMAIN), false)
        );

        $this->formBuilder->defineFormField(
            new SelectField("booking_distribution", $this->localization->getText("Booking Distribution", TLBM_TEXT_DOMAIN), [TLBM_BOOKING_DISTRIBUTION_EVENLY => $this->localization->getText("Evenly", TLBM_TEXT_DOMAIN),
                TLBM_BOOKING_DISTRIBUTION_FILL_ONE => $this->localization->getText("Fill One", TLBM_TEXT_DOMAIN)
            ])
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
            <label>
                <input value="<?php echo $this->escaping->escAttr($calendarGroup->getTitle()) ?>" placeholder="<?php $this->localization->echoText("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
            </label>
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
        $calendarGroup->setTitle( $this->sanitizing->sanitizeTextfield($vars['title']));
        $calendarSelection = $this->formBuilder->readVars("calendars", $vars);

        $calendarGroup->setCalendarSelection($calendarSelection);
        $calendarGroup->setBookingDisitribution($this->sanitizing->sanitizeTextfield($vars['booking_distribution']));

        if($this->entityRepository->saveEntity($calendarGroup)) {
            $savedEntity = $calendarGroup;

            return ["success" => $this->localization->getText("Group has been saved", TLBM_TEXT_DOMAIN)];
        } else {
            return array("error" => $this->localization->getText("An internal error occured.", TLBM_TEXT_DOMAIN)
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