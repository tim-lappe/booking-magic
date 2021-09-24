import {CalendarBase} from "./CalendarBase";
import * as CalendarsCollections from "./CalendarsCollection";

export class CalendarCollector {
    public static Calendars: CalendarBase[] = [];

    public static initAllCalendars(): void {
        let calendars = document.querySelectorAll(".tlbm-calendar-container") as NodeListOf<HTMLElement>;
        calendars.forEach((calendarContainerElem) => {
            let tsClass = calendarContainerElem.getAttribute("tsClass");
            console.log("Init Calendar", calendarContainerElem, ", tsClass", tsClass);


            // @ts-ignore
            const newInstance = Object.create(CalendarsCollections[tsClass].prototype);
            newInstance.constructor.apply(newInstance, new Array(calendarContainerElem));
            newInstance.init();
            this.Calendars.push(newInstance);
        });
    }
}