import {CalendarBase} from "./CalendarBase";
import {CalendarDateSelect} from "./Views/CalendarDateSelect";

export class CalendarCollector {
    public static Calendars: CalendarBase[] = [];

    public static CalendarTypes: any = {
        "dateselect_monthview": CalendarDateSelect
    };

    public static initAllCalendars(): void {
        let calendars = document.querySelectorAll(".tlbm-calendar-container") as NodeListOf<HTMLElement>;
        calendars.forEach((calendarContainerElem) => {
            let view = calendarContainerElem.getAttribute("view");
            if(view != null) {
                let instance = new this.CalendarTypes[view](calendarContainerElem);
                instance.init();
                this.Calendars.push(instance);
            }
        });
    }
}