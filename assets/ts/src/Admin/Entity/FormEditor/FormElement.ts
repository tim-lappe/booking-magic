import {ElementSetting} from "./ElementSetting";

export class FormElement {

    public title: string;
    public uniqueName: string;
    public type: string;
    public settings: ElementSetting[];
    public menuCategory: string;
    public data: any;
    public description: string;
    public onlyInRoot: boolean;

    public static getSettingsCategories(formElement: FormElement) {
        let categories = [];
        for(let setting of formElement.settings) {
            if (categories.indexOf(setting.categoryTitle) == -1) {
                categories.push(setting.categoryTitle);
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
            settings[setting.categoryTitle].push(setting);
        }
        return settings;
    }
}