import {FormElementSettingsType} from "./FormElementSettingsType";

export class FormElement {
    title: string;

    constructor(public settings: FormElementSettingsType[],
                public settings_output: any,
                public unique_name: string,
                public menu_category: string,
                public editor_output: string,
                public data: any,
                public description: string = "") {
    }
}