<?php

namespace TLBM\Admin\Pages\SinglePages;

use Throwable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\CalendarCategory;
use TLBM\Entity\ManageableEntity;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Validation\ValidatorFactory;

class CalendarCategoryEditPage extends EntityEditPage
{
    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * @var EntityRepositoryInterface
     */
    protected EntityRepositoryInterface $repository;

    /**
     * @param LocalizationInterface $localization
     * @param EntityRepositoryInterface $repository
     */
    public function __construct(LocalizationInterface $localization, EntityRepositoryInterface $repository) {
        $this->repository = $repository;
        $this->localization = $localization;
        parent::__construct($this->localization->getText("Category", TLBM_TEXT_DOMAIN), "calendar-category-edit", "calendar-category-edit", false);
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->repository->getEntity(CalendarCategory::class, $id);
    }

    protected function displayEntityEditForm(): void
    {
        $category = $this->getEditingEntity();
        if ( !$category) {
            $category = new CalendarCategory();
        }
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <label>
                    <input value="<?php echo $this->escaping->escAttr($category->getTitle()); ?>" placeholder="<?php
                    $this->localization->echoText("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
                </label>
            </div>
        </div>
        <?php
    }

    protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $calendarCategory = isset($vars['createNew']) ? null : $this->getEditingEntity();
        if ( !$calendarCategory) {
            $calendarCategory = new CalendarCategory();
        }

        $calendarCategoryValidator = ValidatorFactory::createCalendarCategoryValidator($calendarCategory);
        $calendarCategory->setTitle($this->sanitizing->sanitizeTextfield($vars['title']));

        $validationResult = $calendarCategoryValidator->getValidationErrors();
        if(count($validationResult) == 0) {
            try {
                if ($this->repository->saveEntity($calendarCategory)) {
                    $savedEntity = $calendarCategory;
                    return ["success" => $this->localization->getText("Category has been saved", TLBM_TEXT_DOMAIN)];
                } else {
                    return ["error" => $this->localization->getText("An internal error occured. ", TLBM_TEXT_DOMAIN)];
                }
            } catch (Throwable $exception) {

            }
        }

        return $validationResult;
    }
}