<?php

namespace TLBM\Admin\Pages\SinglePages;

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