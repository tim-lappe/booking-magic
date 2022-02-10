export class CalendarDisplay {
    [props: string]: any;

    public calendarIds = [];
    public groupIds = [];
    public view = "no-view";
    public viewSettings = null;
    public inputName = "calendar";
    public readonly = false;

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);
        }
    }
}