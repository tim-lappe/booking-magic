import FormElementsCollection from "./FormElementsCollection";

export default class FormElementsFactory {

    public static createFormItemElement() {
        let div = document.createElement("div");
        div.classList.add("tlbm-draggable");
        div.classList.add("tlbm-form-item-container");
        div.setAttribute("draggable", "true");
        return div;
    }

    public static setFormDataToElement(elem: HTMLElement, data_obj : any) {
        let formelem_key = FormElementsCollection.getKeyByUniqueName(data_obj.unique_name);
        elem.innerHTML =  FormElementsCollection.getByElemKey(formelem_key).editor_output.toString();
        elem.setAttribute("form-data", JSON.stringify(data_obj));
    }

    public static setMissingDefaultValuesToData(data_obj: any) {
        let formelem = FormElementsCollection.getByUniqueName(data_obj.unique_name);

        for(const [key, setting] of Object.entries(formelem.settings)) {
            if(data_obj[setting.name] == null) {
                data_obj[setting.name] = setting.default_value;
            }
        }

        return data_obj;
    }
}