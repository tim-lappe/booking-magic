import {FormElement} from "../../../Entity/FormElement";
import {FormData} from "../../../Entity/FormData";
import {BasicEditorElement} from "./Elements/BasicEditorElement";
import * as React from "react";

export class FormElementsManager {

    constructor(public formElements: FormElement[]) {

    }

    private static getElementComponents() {
        return {

        };
    }

    public getFormElementsList() {
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
    public getFormElementByName(unique_name: string) {
        let result = this.formElements.filter((elem) => elem.unique_name == unique_name);
        return result.length > 0 ? result[0] : null;
    }

    public createElementComponent(formElement: FormElement, formData: FormData): JSX.Element {
        let Components = FormElementsManager.getElementComponents();
        if(Components[formElement.unique_name]) {
           const ElementComponent = Components[formElement.unique_name];
           return <ElementComponent formElement={formElement} formData={formData} />;
        }

        return <BasicEditorElement formElement={formElement} formData={formData} />;
    }
}