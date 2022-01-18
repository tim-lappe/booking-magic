export class CalendarViewSetting {
    [pros: string]: any;

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);
        }
    }
}