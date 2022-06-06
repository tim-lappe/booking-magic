<?php

namespace TLBM\Admin\WpForm;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarCategoryEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarCategoryPage;
use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\CalendarCategory;
use TLBM\Entity\RuleAction;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

class CategorySelectionField extends FormFieldBase implements FormFieldReadVarsInterface
{

    /**
     * @var AdminPageManagerInterface
     */
    protected AdminPageManagerInterface $adminPageManager;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * @var EntityRepositoryInterface
     */
    protected EntityRepositoryInterface $repository;

    /**
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        parent::__construct($name, $title);

        $this->adminPageManager = MainFactory::get(AdminPageManagerInterface::class);
        $this->localization = MainFactory::get(LocalizationInterface::class);
        $this->repository = MainFactory::get(EntityRepositoryInterface::class);
    }

    /**
     * @param string $name
     * @param mixed $vars
     * @return array
     */
    public function readFromVars(string $name, $vars): array
    {
        if (isset($vars[$name])) {
            $decodedVar = urldecode($vars[$name]);
            $categoryIds = json_decode($decodedVar);
            if (is_array($categoryIds)) {
                $categories = [];
                foreach ($categoryIds as $id) {
                    if(is_numeric($id)) {
                        $entity = $this->repository->getEntity(CalendarCategory::class, $id);
                        if ($entity) {
                            $categories[] = $entity;
                        }
                    }
                }
                return $categories;
            }
        }

        return [];
    }

    /**
     * @param array|null $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        /**
         * @var RuleAction[] $categories
         */
        $categories = $value;
        if ( !is_array($categories)) {
            $categories = [];
        }

        $ids = [];
        foreach ($categories as $category) {
            $ids[] = $category->getId();
        }

        $entityRepository = MainFactory::get(EntityRepositoryInterface::class);
        $allCategories = iterator_to_array($entityRepository->getEntites(CalendarCategory::class));
        ?>

        <tr>
            <th scope="row">
                <label for="<?php echo $this->escaping->escAttr($this->name) ?>">
                    <?php echo $this->escaping->escHtml($this->title); ?>
                </label>
            </th>
            <td>
                <div class="tlbm-calendar-tag-field"
                    data-value="<?php echo $this->escaping->escAttr(urlencode(json_encode($ids))); ?>"
                    data-categories="<?php echo $this->escaping->escAttr(urlencode(json_encode($allCategories))); ?>"
                    data-name="<?php echo $this->escaping->escAttr($this->name) ?>">
                </div>
                <?php
                $categoryEditPage = $this->adminPageManager->getPage(CalendarCategoryEditPage::class);
                $categoryPage = $this->adminPageManager->getPage(CalendarCategoryPage::class);

                if($categoryEditPage instanceof CalendarCategoryEditPage && $categoryPage instanceof CalendarCategoryPage) {
                    $editUrl = $categoryEditPage->getEditLink();
                    $manageUrl = $categoryPage->getUrl();
                    ?>
                    <a class="button button-primary" href="<?php echo $this->escaping->escUrl($manageUrl); ?>"><?php $this->localization->echoText("Manage Categories", TLBM_TEXT_DOMAIN); ?></a>
                    <a class="button" href="<?php echo $this->escaping->escUrl($editUrl); ?>"><?php $this->localization->echoText("Create new Category", TLBM_TEXT_DOMAIN); ?></a>
                    <?php
                }
                ?>
            </td>
        </tr>
        <?php
    }
}