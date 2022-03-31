export class ElementSetting {
    [props: string]: any;

    public name: string;
    public title: string;
    public defaultValue: string;
    public readonly: boolean;
    public type: string;
    public mustUnique: boolean;
    public forbiddenValues: any[] = [];
    public categoryTitle: string = "General";
    public expand: boolean = false;
}