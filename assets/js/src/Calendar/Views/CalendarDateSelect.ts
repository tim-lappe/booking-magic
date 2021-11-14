import {CalendarBase} from "../CalendarBase";

export class CalendarDateSelect extends CalendarBase {

    constructor(public calendarContainerElem: HTMLElement) {
        super(calendarContainerElem);
    }

    onCalendarDataReaded(): void {

    }

    onCalendarLoaded(): void {
        if(this.calendarData.screen == "default") {
            this.calendarContainerElem.querySelector(".tlbm-next-month")?.addEventListener("click", (e) => {
                this.calendarData.nextMonth = true;
                this.loadCalendar();

                e.preventDefault();
            });

            this.calendarContainerElem.querySelector(".tlbm-prev-month")?.addEventListener("click", (e) => {
                this.calendarData.prevMonth = true;
                this.loadCalendar();

                e.preventDefault();
            });

            this.calendarContainerElem.querySelectorAll(".tlbm-cell-selectable")?.forEach((elem) => {
                elem.addEventListener("click", (e) => {
                    this.calendarData.screen = "dateSelected";
                    this.calendarData.selected_tstamp = elem.getAttribute("date");
                    this.loadCalendar();
                    e.preventDefault();
                })
            });

        } else if(this.calendarData.screen == "dateSelected") {
            this.calendarContainerElem.querySelectorAll(".tlbm-button-select-another")?.forEach((elem) => {
                elem.addEventListener("click", (e) => {
                    this.calendarData.screen = "default";
                    this.loadCalendar();
                    e.preventDefault();
                })
            });
        }
    }
}