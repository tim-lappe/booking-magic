<?php

namespace TLBM\Admin\Pages\SinglePages;

use Throwable;
use TLBM\Admin\FormEditor\Formeditor;
use TLBM\Admin\FormEditor\FrontendGeneration\FormFrontendGenerator;
use TLBM\Admin\Pages\PageManager;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\FormEditorField;
use TLBM\Entity\Form;
use TLBM\Entity\Rule;
use TLBM\Form\FormManager;
use TLBM\Rules\RulesManager;

class FormEditPage extends FormPageBase {

    public function __construct( ) {
        parent::__construct( __("Form Edit", TLBM_TEXT_DOMAIN), "booking-magic-form-edit", false );

        $this->parent_slug = "booking-magic-form";
    }

    public static function GetEditLink(int $id = -1): string {
        $page = PageManager::GetPageInstance(FormEditPage::class);
        if($id >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($page->menu_slug) . "&form_id=".urlencode($id);
        }
        return admin_url() . "admin.php?page=" . urlencode($page->menu_slug);
    }

    public function GetHeadTitle(): string {
        return $this->GetEditingForm() == null ? __("Add New Form", TLBM_TEXT_DOMAIN) : __("Edit Form", TLBM_TEXT_DOMAIN);
    }

    /**
     * @return Form|null
     */
    private function GetEditingForm(): ?Form {
        $form = null;
        if(isset($_REQUEST['form_id'])) {
            $form = FormManager::GetForm($_REQUEST['form_id']);
        }
        return $form;
    }

    public function ShowFormPageContent() {
        $form = $this->GetEditingForm();
        $title = $form ? $form->GetTitle() : "";
        $form_data = $form ? $form->GetFormData() : null;

        ?>
        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $title ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $form_builder = new FormBuilder();
            $form_builder->PrintFormHead();
            $form_builder->PrintFormField(new FormEditorField("form",  __("Form", TLBM_TEXT_DOMAIN), $form_data));
            $form_builder->PrintFormFooter();
            ?>
        </div>
        <?php
    }

    /**
     * @throws \Exception
     */
    public function OnSave($vars): array {
        $form = null;
        if(isset($_REQUEST['form_id'])) {
            $form = FormManager::GetForm($_REQUEST['form_id']);
        }

        if($form == null) {
            $form = new Form();
        }

        $form->SetTitle($vars['title']);
        try {
            $form_node_tree = json_decode(urldecode($vars['form']));
            $form->SetFormData($form_node_tree);

            $generator = new FormFrontendGenerator($form_node_tree);
            $html = $generator->GenerateHtml();
            $form->SetFrontendHtml($html);

        } catch (Throwable $exception) {
            return array(
                "error" => __("Unknown Error " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
            );
        }

        FormManager::SaveForm($form);
        return array();
    }
}