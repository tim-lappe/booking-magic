<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\CacheManagerInterface;

/**
 * @template T of ManageableEntity
 *
 */
abstract class EntityEditPage extends FormPageBase
{
    /**
     * @var T
     */
    protected ?ManageableEntity $editingEntity = null;

    /**
     * @var string
     */
    protected string $entityTitle;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(string $entityTitle, string $menuTitle, string $menuSlug, bool $showInMenu = true, bool $displayDefaultHead = true, string $defaultHeadTitle = "")
    {
        $this->entityTitle = $entityTitle;
        $this->localization = MainFactory::get(LocalizationInterface::class);
        parent::__construct($menuTitle, $menuSlug, $showInMenu, $displayDefaultHead, $defaultHeadTitle);
    }

    /**
     * @return void
     */
    public function displayFormPageContent()
    {
        $entity = $this->getEditingEntity();
        if ($entity) {
            ?>
            <input type="hidden" name="edit_id" value="<?php echo $entity->getId() ?>">
            <?php
        }

        $this->displayEntityEditForm();
    }

    /**
     * @param mixed $vars
     *
     * @return array
     */
    public function onSave($vars): array
    {
        $cacheManager = MainFactory::get(CacheManagerInterface::class);
        $cacheManager->clearCache();

        $entity = null;
        $errors = $this->onSaveEntity($vars, $entity);
        $this->editingEntity = $entity;

        return $errors;
    }

    /**
     *
     * @return ?T
     */
    protected function getEditingEntity(): ?ManageableEntity
    {
        if($this->editingEntity) {
            return $this->editingEntity;
        }

        if (isset($_REQUEST['edit_id'])) {
            return $this->getEntityById($_REQUEST['edit_id']);
        }

        return null;
    }

    /**
     * @param ?int $entityId
     *
     * @return string
     */
    public function getEditLink(?int $entityId = null): string
    {
        if ($entityId != null) {
            return admin_url() . "admin.php?page=" . urlencode($this->menuSlug) . "&edit_id=" . urlencode($entityId);
        }

        return admin_url() . "admin.php?page=" . urlencode($this->menuSlug);
    }

    /**
     * @return string
     */
    protected function getHeadTitle(): string
    {
        return $this->getEditingEntity() == null ? sprintf($this->localization->getText("Add New %s", TLBM_TEXT_DOMAIN), $this->entityTitle) : sprintf($this->localization->getText("Edit %s", TLBM_TEXT_DOMAIN), $this->entityTitle);
    }

    /**
     * @param int $id
     *
     * @return T
     */
    abstract protected function getEntityById(int $id): ?ManageableEntity;

    /**
     * @return void
     */
    abstract protected function displayEntityEditForm(): void;

    /**
     * @param mixed $vars
     * @param ?T $savedEntity
     *
     * @return array
     */
    abstract protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array;
}