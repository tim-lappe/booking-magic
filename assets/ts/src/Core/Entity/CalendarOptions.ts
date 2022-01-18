export class CalendarOptions {
    [props: string]: any;

    public data_source_id: number;
    public data_source_type: number;
    public focused_tstamp: number;
    public readonly: boolean;
    public weekday_form: any;

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);
        }
    }
}