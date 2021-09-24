import {MenuCategory} from "./Entities/MenuCategory";
import {FormElement} from "./Entities/FormElement";

declare var tlbm_form_elements: any;

export default class FormElementsCollection {
    public static getList(): any[] {
        return tlbm_form_elements;
    }

    public static getByElemKey(key: number): any {
        if(this.getList().length >= key + 1) {
            return this.getList()[key];
        } else {
            console.error("The Key doesnt exists", key, this.getList());
        }
    }

    public static getByUniqueName(name: string): FormElement {
        let list = this.getList();
        for(let i = 0; i < list.length; i++) {
            if(list[i].unique_name === name) {
                return list[i];
            }
        }
    }

    public static getKeyByUniqueName(name: string): number {
        let list = this.getList();
        for(let i = 0; i < list.length; i++) {
            if(list[i].unique_name === name) {
                return i;
            }
        }
    }

    public static getCategorised(): MenuCategory[] {
        let list = this.getList();
        let categorised: MenuCategory[] = [];

        for(let i = 0; i < list.length; i++) {
            if(categorised[list[i].menu_category] != null) {
                categorised[list[i].menu_category].formelements.push(list[i]);
            } else {
                categorised[list[i].menu_category] = new MenuCategory();
                categorised[list[i].menu_category].formelements = [list[i]];
            }
        }

        return categorised;
    }
}