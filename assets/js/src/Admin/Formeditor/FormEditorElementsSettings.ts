import FormElementsCollection from "./FormElementsCollection";
import FormElementsSettingsBehavioursCollection from "./FormElementsSettingsBehavioursCollection";
import FormEditor from "./FormEditor";

export default class FormEditorElementsSettings {

    constructor(editor: FormEditor) {
        FormElementsSettingsBehavioursCollection.registerSettingsBehaviours();
        document.addEventListener("formeditor_item_settings_window_opened", this.onSettingsWindowOpened)
    }

    private onSettingsWindowOpened() {
        let window_element = document.querySelector(".tlbm-form-editor-element-settings-window");
        FormElementsSettingsBehavioursCollection.callOnSettingsWindowOpened({});

        let input_elements = window_element.querySelectorAll("[name]");

        input_elements.forEach((elem: HTMLElement, key: number) => {
            elem.addEventListener("change", (event) => {
                let target = event.target as HTMLElement;
                FormElementsSettingsBehavioursCollection.callOnSettingsChanged({"elem": elem, "name": target.getAttribute("name")});
            });
        });
    }

    public setSettingsPrinting(formItemElem: HTMLElement, data_obj: any) {
        let formelem = FormElementsCollection.getByUniqueName(data_obj.unique_name);
        if(formelem != null) {
            for (const [key, setting] of Object.entries(formelem.settings)) {
                let printing = setting.settings_printing;
                if (printing != null) {
                    let output_var = printing.output_var;
                    if(output_var.length === 0) {
                        output_var = setting.name;
                    }

                    let output_html = data_obj[setting.name];

                    if(output_html == null) {
                        output_html = setting.default_value;
                    }

                    if(printing.replacings != null) {
                        for (const [value, replacement] of Object.entries(printing.replacings)) {
                            if (output_html === value) {
                                output_html = replacement;
                            } else if (value === "*") {
                                output_html = replacement.replaceAll("{x}", output_html);
                            }
                        }
                    }

                    if (printing.style_editings.length === 0) {
                        let elem = formItemElem.querySelector(".tlbm-form-settings-print-" + output_var) as HTMLElement;
                        if (elem != null) {
                            if (output_html.length > 0) {
                                elem.style.display = null;
                                elem.innerHTML = output_html;
                            } else {
                                elem.style.display = "none";
                            }
                        }
                    } else {
                        let editing = printing.style_editings[0];
                        let elem_to_edit = formItemElem.querySelector(editing.selector);
                        if (elem_to_edit != null) {
                            elem_to_edit.style[editing.style_name] = output_html;
                        }
                    }
                }
            }
        } else {
            console.error("Failed to set settings Printing. Unknown Data Object", data_obj, formItemElem);
        }
    }
}