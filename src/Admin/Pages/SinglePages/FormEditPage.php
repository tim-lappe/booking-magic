<?php

namespace TLBM\Admin\Pages\SinglePages;

use DI\FactoryInterface;
use Exception;
use Throwable;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\FrontendGeneration\FormFrontendGenerator;
use TLBM\Admin\WpForm\FormEditorField;
use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;

class FormEditPage extends FormPageBase
{

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $formElementsCollection;


    public function __construct(
        FactoryInterface $factory,
        FormManagerInterface $formManager,
        FormElementsCollectionInterface $elementsCollection
    ) {
        parent::__construct(
            $factory, __("Form Edit", TLBM_TEXT_DOMAIN), "booking-magic-form-edit", false
        );

        $this->formManager            = $formManager;
        $this->formElementsCollection = $elementsCollection;
        $this->parent_slug            = "booking-magic-form";

        $this->defineFormFields();
    }

    private function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new FormEditorField($this->formElementsCollection, "form", __("Form", TLBM_TEXT_DOMAIN))
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
        <div class="tlbm-admin-page-tile">
            <input value="<?php
            echo $title ?>" placeholder="<?php
            _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title"
                   class="tlbm-admin-form-input-title">
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
        $form = null;
        if (isset($_REQUEST['form_id'])) {
            $form = $this->formManager->getForm($_REQUEST['form_id']);
        }

        if ($form == null) {
            $form = new Form();
        }

        $form->setTitle($vars['title']);
        try {
            $form_node_tree = json_decode(urldecode($vars['form']));
            $form->setFormData($form_node_tree);

            $generator = new FormFrontendGenerator($this->formElementsCollection, $form_node_tree);
            $html      = $generator->generateContent();
            $form->setFrontendHtml($html);
        } catch (Throwable $exception) {
            return array(
                "error" => __("Unknown Error " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
            );
        }

        $this->formManager->saveForm($form);

        return array();
    }
}