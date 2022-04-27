<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use Throwable;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\WpForm\FormEditorField;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Form;
use TLBM\Entity\ManageableEntity;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Validation\FormEntityValidator;

/**
 * @extends EntityEditPage<Form>
 */
class FormEditPage extends EntityEditPage
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $elementsCollection;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        FormElementsCollectionInterface $elementsCollection,
        LocalizationInterface $localization
    ) {
        parent::__construct(
            $localization->getText("Form", TLBM_TEXT_DOMAIN), "form-edit", "booking-magic-form-edit", false
        );

        $this->localization = $localization;
        $this->entityRepository   = $entityRepository;
        $this->elementsCollection = $elementsCollection;
        $this->parentSlug        = "booking-magic-form";

        $this->defineFormFields();
    }

    private function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new FormEditorField($this->elementsCollection, "form", $this->localization->getText("Form", TLBM_TEXT_DOMAIN))
        );
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $form = $this->getEditingEntity();
	    ?>

	    <?php if($form != null): ?>
            <input type="hidden" name="form_id" value="<?php echo $this->escaping->escAttr($form->getId()) ?>" />
        <?php endif; ?>
        <?php
        if(!$form) {
            $form = new Form();
        }
        ?>

        <div class="tlbm-admin-page-tile">
            <label>
                <input value="<?php echo $this->escaping->escAttr($form->getTitle()) ?>" placeholder="<?php $this->localization->echoText("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
            </label>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("form", $form->getFormData());
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <?php
    }

    /**
     * @throws Exception
     */
    public function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $form = $this->getEditingEntity();
        if ($form == null) {
            $form = new Form();
        }

        $formValidator = new FormEntityValidator($form);
        $form->setTitle($this->sanitizing->sanitizeTextfield($vars['title']));

        try {
            if(isset($vars['form'])) {
                $form_node_tree = json_decode(urldecode($vars['form']));
                $form->setFormData($form_node_tree);
            } else {
                return ["error" => $this->localization->getText("Unknown Error occured.", TLBM_TEXT_DOMAIN)
                ];
            }
        } catch (Throwable $exception) {
            return ["error" => $this->localization->getText("Unknown Error occured: " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
            ];
        }

        $validationResult = $formValidator->getValidationErrors();
        if(count($validationResult) == 0) {
            if($this->entityRepository->saveEntity($form)) {
                $savedEntity = $form;

                return ["success" => $this->localization->getText("Form has been saved", TLBM_TEXT_DOMAIN)];
            } else {
                return ["error" => $this->localization->getText("An internal error occurred.", TLBM_TEXT_DOMAIN)];
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
        return $this->entityRepository->getEntity(Form::class, $id);
    }
}