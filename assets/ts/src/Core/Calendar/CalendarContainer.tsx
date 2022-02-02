import * as React from "react";
import {Utils} from "../../Utils";
import {CalendarManager} from "./CalendarManager";
import {CalendarOptions} from "../Entity/CalendarOptions";


interface CalendarContainerProps {
    dataset: any;
}

interface CalendarContainerState {
    view: string;
    viewSettings: any;
    options: CalendarOptions;
    formName: string;
}

export class CalendarContainer extends React.Component<CalendarContainerProps, CalendarContainerState> {

    private calendarManager: CalendarManager = new CalendarManager();

    constructor(props) {
        super(props);

        let options = this.props.dataset.json;
        if(options) {
            options = JSON.parse(Utils.decodeUriComponent(options));
        }

        let viewSettings = this.props.dataset.viewSettings;
        if(viewSettings) {
            viewSettings = JSON.parse(Utils.decodeUriComponent(viewSettings));
        }

        this.state = {
            view: this.props.dataset.view ?? "no-view",
            viewSettings: viewSettings ?? {},
            options: options ?? new CalendarOptions(),
            formName: this.props.dataset.name ?? "calendar",
        }
    }

    render() {

        return (
            <React.Fragment>
                {this.calendarManager.createCalendarComponent(this.state.formName, this.state.view, this.state.options, this.state.viewSettings)}
            </React.Fragment>
        );
    }
}