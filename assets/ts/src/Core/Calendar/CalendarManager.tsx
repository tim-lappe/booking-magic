import {CalendarComponentBase} from "./Views/CalendarComponentBase";
import {CalendarNoView} from "./Views/CalendarNoView";
import * as React from "react";
import {CalendarMonthView} from "./Views/MonthView/CalendarMonthView";
import {CalendarOptions} from "../Entity/CalendarOptions";
import {CalendarViewSetting} from "../Entity/CalendarViewSetting";

export class CalendarManager {

    private calendarComponents: Map<string, typeof CalendarComponentBase>;

    constructor() {
        this.calendarComponents = new Map<string, typeof CalendarComponentBase>();
        this.calendarComponents.set("no-view", CalendarNoView as (typeof CalendarComponentBase));
        this.calendarComponents.set("month", CalendarMonthView  as (typeof CalendarComponentBase));
    }

    public createCalendarComponent(name: string, view: string, options: CalendarOptions, viewSettings: CalendarViewSetting) {
        if(this.calendarComponents.has(view)) {
            const ElementComponent = this.calendarComponents.get(view);
            return <ElementComponent name={name} options={options} viewSettings={viewSettings} />;
        } else {
            return <CalendarNoView name={name} options={options} viewSettings={viewSettings}/>
        }
    }
}