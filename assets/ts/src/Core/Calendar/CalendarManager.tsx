import {CalendarComponentBase} from "./Views/CalendarComponentBase";
import {CalendarNoView} from "./Views/CalendarNoView";
import * as React from "react";
import {CalendarMonthView} from "./Views/MonthView/CalendarMonthView";

export class CalendarManager {

    private calendarComponents: Map<string, typeof CalendarComponentBase>;

    constructor() {
        this.calendarComponents = new Map<string, typeof CalendarComponentBase>();
        this.calendarComponents.set("no-view", CalendarNoView);
        this.calendarComponents.set("month", CalendarMonthView);
    }

    public createCalendarComponent(view: string, options: any, viewSettings: any) {
        if(this.calendarComponents.has(view)) {
            const ElementComponent = this.calendarComponents.get(view);
            return <ElementComponent options={options} viewSettings={viewSettings} />;
        } else {
            return <CalendarNoView options={options} viewSettings={viewSettings}/>
        }
    }
}