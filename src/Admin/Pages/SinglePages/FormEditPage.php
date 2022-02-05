<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use Throwable;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\WpForm\FormEditorField;
use TLBM\Entity\Form;
use TLBM\Repository\Contracts\FormRepositoryInterface;
use TLBM\Validation\FormEntityValidator;

class FormEditPage extends FormPageBase
{

    /**
     * @var FormRepositoryInterface
     */
    private FormRepositoryInterface $formManager;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $elementsCollection;

    /**
     * @var Form|null
     */
    private ?Form $editingForm = null;


    public function __construct(
        FormRepositoryInterface $formManager,
        FormElementsCollectionInterface $elementsCollection
    ) {
        parent::__construct(
             __("Form Edit", TLBM_TEXT_DOMAIN), "booking-magic-form-edit", false
        );

        $this->formManager        = $formManager;
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

    public function getEditLink(int $form_id = -1): string
    {
        if ($form_id >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($this->menu_slug) . "&form_id=" . urlencode($form_id);
        }

        return admin_url() . "admin.php?page=" . urlencode($this->menu_slug);
    }

    public function getHeadTitle(): string
    {
        return $this->getEditingForm() == null ? __("Add New Form", TLBM_TEXT_DOMAIN) : __(
            "Edit Form", TLBM_TEXT_DOMAIN
        );
    }

    /**
     * @return Form|null
     */
    private function getEditingForm(): ?Form
    {
        if($this->editingForm != null) {
            return $this->editingForm;
        }

        $form = null;
        if (isset($_REQUEST['form_id'])) {
            $form = $this->formManager->getForm($_REQUEST['form_id']);
        }

        return $form;
    }

    public function showFormPageContent()
    {
        $form      = $this->getEditingForm();
        $form_data = $form ? $form->getFormData() : null;
        $title     = $form ? $form->getTitle() : "";
        $form_data = $form ? $form->getFormData() : null;

        ?>
        <?php if($form != null): ?>
            <input type="hidden" name="form_id" value="<?php echo $form->getId() ?>" />
        <?php endif; ?>
        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $title ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("form", $form_data);
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <?php
    }

    /**
     * @throws Exception
     */
    public function onSave($vars): array
    {
        $form = $this->getEditingForm();
        if ($form == null) {
            $form = new Form();
        }

        $this->editingForm = $form;
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
            $this->formManager->saveForm($form);
            return array(
                "success" => __("Form has been saved", TLBM_TEXT_DOMAIN)
            );
        }

        return $validationResult;
    }
}