import * as React from "react";
import {Utils} from "../../Utils";
import {CalendarManager} from "./CalendarManager";
import {CalendarDisplay} from "../Entity/CalendarDisplay";


interface CalendarContainerProps {
    dataset: any;
}

interface CalendarContainerState {
    display: CalendarDisplay;
}

export class CalendarContainer extends React.Component<CalendarContainerProps, CalendarContainerState> {

    private calendarManager: CalendarManager = new CalendarManager();

    constructor(props) {
        super(props);

        let display = this.props.dataset.json;
        if(display) {
            display = JSON.parse(Utils.decodeUriComponent(display));
        }

        this.state = {
            display: display ? new CalendarDisplay(display) : new CalendarDisplay(),
        }
    }

    render() {

        return (
            <React.Fragment>
                {this.calendarManager.createCalendarComponent(this.state.display)}
            </React.Fragment>
        );
    }
}