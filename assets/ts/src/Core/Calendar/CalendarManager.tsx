import {CalendarComponentBase} from "./Views/CalendarComponentBase";
import {CalendarNoView} from "./Views/CalendarNoView";
import * as React from "react";
import {CalendarMonthView} from "./Views/MonthView/CalendarMonthView";
import {CalendarDisplay} from "../Entity/CalendarDisplay";

export class CalendarManager {

    private calendarComponents: Map<string, typeof CalendarComponentBase>;

    constructor() {
        this.calendarComponents = new Map<string, typeof CalendarComponentBase>();
        this.calendarComponents.set("no-view", CalendarNoView as (typeof CalendarComponentBase));
        this.calendarComponents.set("month", CalendarMonthView  as (typeof CalendarComponentBase));
    }

    public createCalendarComponent(display: CalendarDisplay) {
        if(this.calendarComponents.has(display.view)) {
            const ElementComponent = this.calendarComponents.get(display.view);
            return <ElementComponent display={display} />;
        } else {
            return <CalendarNoView display={display}/>
        }
    }
}