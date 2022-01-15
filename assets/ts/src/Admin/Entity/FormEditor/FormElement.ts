import {ElementSetting} from "./ElementSetting";

export class FormElement {

    public title: string;
    public unique_name: string;
    public type: string;
    public settings: ElementSetting[];
    public menu_category: string;
    public data: any;
    public description: string;
    public only_in_root: boolean;

    public static getSettingsCategories(formElement: FormElement) {
        let categories = [];
        for(let setting of formElement.settings) {
            if(categories.indexOf(setting.category_title) == -1) {
                categories.push(setting.category_title);
            }
        }

        return categories;
    }

    public static getSettingsWithCategory(formElement: FormElement): { [props: string]: ElementSetting[] }{
        let categories = FormElement.getSettingsCategories(formElement);
        let settings = {};
        for (let cat of categories) {
            settings[cat] = [];
        }
        for (let setting of formElement.settings) {
            settings[setting.category_title].push(setting);
        }
        return settings;
    }
}