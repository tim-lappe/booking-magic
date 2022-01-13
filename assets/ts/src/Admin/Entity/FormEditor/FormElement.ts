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
}