import {Api} from "../Api/Api";

export abstract class CalendarBase {

    public calendarData: any;

    protected constructor(public calendarContainerElem: HTMLElement) {
        this.readCalendarData();
    }

    public init() {
        this.loadCalendar();
    }

    public loadCalendar(): void {
        this.calendarContainerElem.style.opacity = "0.5";

        Api.Post("loadCalendar", this.calendarData, response => {
            let responseData = JSON.parse(response);

            this.calendarContainerElem.innerHTML = responseData.html;
            this.calendarData = responseData.data;
            this.onCalendarLoaded();
            this.calendarContainerElem.style.opacity = "1";

        }, () => {
            console.error("Cannot load Calendar");
        });
    }

    public readCalendarData() {
        let calendarData = this.calendarContainerElem.getAttribute("data");
        calendarData = JSON.parse(calendarData);
        this.calendarData = calendarData;

        this.onCalendarDataReaded();
    }

    public abstract onCalendarLoaded(): void;
    public abstract onCalendarDataReaded(): void;
}