import FormEditor from "./FormEditor";
import {FormElement} from "./Entities/FormElement";
import FormElementsCollection from "./FormElementsCollection";

export default class FormEditorElementsSettingsWindow {

    private currentSettingsElement: HTMLElement;
    private openWithCancelElemRemove: boolean = false;

    constructor(private editor: FormEditor) {

    }

    public init() {
        let cancelbtn = document.querySelector(".tlbm-form-editor-element-settings-window .tlbm-cancel-button") as HTMLElement;
        if(cancelbtn != null) {
            cancelbtn.onclick = (event) => {
                event.preventDefault();
                this.closeSettingsWindow(false);
            }
        }

        let savebtn = document.querySelector(".tlbm-form-editor-element-settings-window .tlbm-save-button") as HTMLElement;
        if(savebtn != null) {
            savebtn.onclick = (event) => {
                event.preventDefault();
                this.closeSettingsWindow(true);
            }
        }

        document.addEventListener("formeditor_add_item_by_user", (e: CustomEvent) => {
            let data_obj = e.detail.data_obj;
            let formelem = FormElementsCollection.getByUniqueName(data_obj.unique_name);

            if(formelem.settings.length > 0) {
                this.openSettingsWindow(e.detail.elem, true);
            }
        });
    }

    public openSettingsWindow(elem: HTMLElement, open_with_cancel: boolean) {
        if(this.currentSettingsElement == null) {
            let settingswindow = document.querySelector(".tlbm-form-editor-element-settings-window");
            if (settingswindow != null) {
                settingswindow.classList.remove("closed");
                this.currentSettingsElement = elem;
                this.generateSettingsHtml();
                this.loadElementFormData();

                document.dispatchEvent(new CustomEvent('formeditor_item_settings_window_opened'));
                this.openWithCancelElemRemove = open_with_cancel;
            }
        }
    }

    public closeSettingsWindow(save: boolean) {
        let settingswindow = document.querySelector(".tlbm-form-editor-element-settings-window") as HTMLElement;

        document.querySelectorAll(".tlbm-form-editor-element-settings-window [unique]").forEach((elem) => {
            let name = elem.getAttribute("name");

        })

        if(settingswindow !== null) {
            settingswindow.classList.add("closed");
        }

        if(save) {
            this.saveElementFormData();
        } else {
            if (this.openWithCancelElemRemove) {
                this.editor.deleteElement(this.currentSettingsElement);
            }
        }

        this.currentSettingsElement = null;


        document.dispatchEvent(new CustomEvent('formeditor_item_settings_window_closed'));
    }

    public generateSettingsHtml() {
        let form_data_str = this.currentSettingsElement.getAttribute("form-data");
        let form_data = JSON.parse(form_data_str) as FormElement;

        let form_element = FormElementsCollection.getByUniqueName(form_data.unique_name);
        let settingscontainer = document.querySelector(".tlbm-form-editor-element-settings-window .tlbm-form-settings-container");

        let html = "<h2>" + form_element.title + "</h2><hr><div class=\"tlbm-form-settings-items-collection\">";

        for(let i = 0; i < form_element.settings_output.length; i++) {
            let itemclasses = "";

            if(form_element.settings[i].expand) {
                itemclasses += "form-item-expand ";
            }
            if(form_element.settings[i].stretch) {
                itemclasses += "form-item-stretch ";
            }

            html += "<div class='form-item-settings-control " + itemclasses + "'>" + form_element.settings_output[i] + "</div>";
        }

        html += "</div>";
        settingscontainer.innerHTML = html;

        settingscontainer.querySelectorAll("input,select,textarea").forEach((elem: HTMLElement) => {
            elem.addEventListener("keydown", (event) => {
                if (event.key == "Enter") {
                    this.closeSettingsWindow(true);
                    event.preventDefault();
                }
            });
        });
    }

    public loadElementFormData() {
        let settingscontainer = document.querySelector(".tlbm-form-editor-element-settings-window .tlbm-form-settings-container");
        let valfields = settingscontainer.querySelectorAll("[name]") as NodeListOf<HTMLInputElement>;
        let form_data_str = this.currentSettingsElement.getAttribute("form-data");
        let form_data = JSON.parse(form_data_str);

        for(let i = 0; i < valfields.length; i++) {
            let field = valfields[i];
            let name = field.getAttribute("name");
            let value = form_data[name];

            if(value != null) {
                if (field.tagName === "select") {
                    let optionfield = field.querySelector("option[value='" + value + "']");
                    optionfield.setAttribute("selected", "selected");
                }
                field.value = value;
            }
        }
    }

    public saveElementFormData() {
        let settingscontainer = document.querySelector(".tlbm-form-editor-element-settings-window .tlbm-form-settings-container");
        let valfields = settingscontainer.querySelectorAll("[name]") as NodeListOf<HTMLInputElement>;
        let form_data_str = this.currentSettingsElement.getAttribute("form-data");
        let form_data = JSON.parse(form_data_str);

        for(let i = 0; i < valfields.length; i++) {
            let field = valfields[i];
            let name = field.getAttribute("name");
            form_data[name] = field.value;
        }

        this.currentSettingsElement.setAttribute("form-data", JSON.stringify(form_data));
        this.editor.formEditorElementsSettings.setSettingsPrinting(this.currentSettingsElement, form_data);

        document.dispatchEvent(new CustomEvent('formeditor_item_settings_saved'));
    }
}