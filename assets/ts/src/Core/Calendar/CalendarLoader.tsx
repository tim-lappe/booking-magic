import * as React from "react";
import {CalendarContainer} from "./CalendarContainer";
import ReactDOM = require("react-dom");

export class CalendarLoader {

    public static initAllCalendars(): void {
        document.querySelectorAll(".tlbm-calendar-container").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<CalendarContainer dataset={htmlelement.dataset} />, htmlelement);
        });
    }
}