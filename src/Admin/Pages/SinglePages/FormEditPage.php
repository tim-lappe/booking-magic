<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use Throwable;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\WpForm\FormEditorField;
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

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        FormElementsCollectionInterface $elementsCollection
    ) {
        parent::__construct(
             __("Form", TLBM_TEXT_DOMAIN), "form-edit", "booking-magic-form-edit", false
        );

        $this->entityRepository   = $entityRepository;
        $this->elementsCollection = $elementsCollection;
        $this->parent_slug        = "booking-magic-form";

        $this->defineFormFields();
    }

    private function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new FormEditorField($this->elementsCollection, "form", __("Form", TLBM_TEXT_DOMAIN))
        );
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {
        $form = $this->getEditingEntity();
        if(!$form) {
            $form = new Form();
        }

        ?>
        <?php if($form != null): ?>
            <input type="hidden" name="form_id" value="<?php echo $form->getId() ?>" />
        <?php endif; ?>
        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $form->getTitle() ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
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
        $form->setTitle($vars['title']);

        try {
            if(isset($vars['form'])) {
                $form_node_tree = json_decode(urldecode($vars['form']));
                $form->setFormData($form_node_tree);
            } else {
                return array(
                    "error" => __("Unknown Error occured.", TLBM_TEXT_DOMAIN)
                );
            }
        } catch (Throwable $exception) {
            return array(
                "error" => __("Unknown Error occured: " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
            );
        }

        $validationResult = $formValidator->getValidationErrors();
        if(count($validationResult) == 0) {
            if($this->entityRepository->saveEntity($form)) {
                $savedEntity = $form;

                return ["success" => __("Form has been saved", TLBM_TEXT_DOMAIN)];
            } else {
                return ["error" => __("An internal error occurred.", TLBM_TEXT_DOMAIN)];
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