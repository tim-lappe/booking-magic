import {FormElement} from "../../Entity/FormEditor/FormElement";
import {FormElementData} from "../../Entity/FormEditor/FormElementData";
import {BasicEditorElement} from "./Elements/BasicEditorElement";
import * as React from "react";
import {ColumnsElement} from "./Elements/ColumnsElement";
import {FormEditorNode} from "../../Entity/FormEditor/FormEditorNode";
import {Editor} from "./Editor";
import {Spacing} from "./Elements/Spacing";
import {CalendarElement} from "./Elements/CalendarElement";

export class ElementsManager {

    private static elementComponents: Map<string, typeof BasicEditorElement>;

    constructor(public formElements: FormElement[]) {
        ElementsManager.elementComponents = new Map<string, typeof BasicEditorElement>();
        ElementsManager.elementComponents.set("columns", ColumnsElement as (typeof BasicEditorElement));
        ElementsManager.elementComponents.set("spacing", Spacing as (typeof BasicEditorElement));
        ElementsManager.elementComponents.set("calendar", CalendarElement as (typeof BasicEditorElement))
    }

    public static registerElementComponent(component_key: string, element: typeof BasicEditorElement) {
        if(!this.elementComponents.has(component_key)) {
            this.elementComponents.set(component_key, element);
        } else {
            this.elementComponents.delete(component_key);
            this.elementComponents.set(component_key, element);
        }
    }

    public createDefaultElementData(unique_name: string): FormElementData {
        let element = this.getFormElementByName(unique_name);
        let formData = new FormElementData();
        for(let setting of element.settings) {
            formData[setting.name] = setting.default_value;
        }
        return formData;
    }

    public getFormElementsList(): { [props: string]: FormElement[] } {
        let categories = this.getCategories();
        let elements = {};
        for (let cat of categories) {
            elements[cat] = [];
        }
        for (let elem of this.formElements) {
            elements[elem.menu_category].push(elem);
        }
        return elements;
    }

    public getCategories() {
        let categories = [];
        for(let formElement of this.formElements) {
            if(categories.indexOf(formElement.menu_category) == -1) {
                categories.push(formElement.menu_category);
            }
        }
        return categories;
    }

    /**
     *
     * @param unique_name
     */
    public getFormElementByName<T extends FormElement>(unique_name: string): T {
        let result = this.formElements.filter((elem) => elem.unique_name == unique_name);
        return result.length > 0 ? result[0] as T : null;
    }

    public createElementComponent(formEditor: Editor, formNode: FormEditorNode): JSX.Element {
        let Components = ElementsManager.elementComponents;
        let formElement = formEditor.formElementsManager.getFormElementByName(formNode.formData.unique_name);

        if(Components.has(formElement.type)) {
           const ElementComponent = Components.get(formElement.type);
           return <ElementComponent formEditor={formEditor} formNode={formNode} />;
        }

        return <BasicEditorElement formEditor={formEditor} formNode={formNode} />;
    }
}