<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Entity\Calendar;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarDisplay;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Validation\ValidatorFactory;

/**
 * @extends EntityEditPage<Calendar>
 */
class CalendarEditPage extends EntityEditPage
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;


    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
        parent::__construct(__("Calendar", TLBM_TEXT_DOMAIN), "calendar-edit", "booking-calendar-edit", false);
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $calendar = $this->getEditingEntity();
        if (!$calendar) {
            $calendar = new Calendar();
        }

        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php
            echo $calendar->getTitle() ?>" placeholder="<?php
            _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title"
                   class="tlbm-admin-form-input-title">
        </div>

        <div class="tlbm-admin-page-tile">
            <?php
            $display = MainFactory::create(CalendarDisplay::class);
            if($calendar->getId() != null) {
                $display->setCalendarIds([$calendar->getId()]);
            }
            $display->setView("month");
            $display->setViewSettings(MainFactory::create(MonthViewSetting::class));
            $display->setReadonly(true);

            echo $display->getDisplayContent();
            ?>
        </div>

        <?php
    }

    /**
     * @param mixed $vars
     * @param ManageableEntity|null $savedEntity
     *
     * @return array
     */
    public function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $calendar = $this->getEditingEntity();
        if (!$calendar) {
            $calendar = new Calendar();
        }

        $calendarValidator = ValidatorFactory::createCalendarValidator($calendar);
        $calendar->setTimestampEdited(time());
        $calendar->setTitle($vars['title']);

        $validationResult = $calendarValidator->getValidationErrors();

        if(count($validationResult) == 0) {
            if($this->entityRepository->saveEntity($calendar)) {
                $savedEntity = $calendar;
                return array(
                    "success" => __("Calendar has been saved", TLBM_TEXT_DOMAIN)
                );

            } else {
                return array(
                    "error" => __("An internal error occured. ", TLBM_TEXT_DOMAIN)
                );
            }
        }

        return $validationResult;
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->entityRepository->getEntity(Calendar::class, $id);
    }
}