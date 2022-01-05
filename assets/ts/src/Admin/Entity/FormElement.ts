import {FormElementSettingsType} from "./FormElementSettingsType";

export class FormElement {

    public title: string;
    public unique_name: string;
    public settings: FormElementSettingsType[];
    public editor_output: string;
    public menu_category: string;
    public settings_output: any;
    public data: any;
    public description: string;
    public has_user_input: boolean;
}