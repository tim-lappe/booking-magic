export class ElementSetting {
    [props: string]: any;

    public name: string;
    public title: string;
    public default_value: string;
    public readonly: boolean;
    public type: string;
    public must_unique: boolean;
    public forbidden_values: any[] = [];
    public category_title: string = "General";
    public expand: boolean = false;
}