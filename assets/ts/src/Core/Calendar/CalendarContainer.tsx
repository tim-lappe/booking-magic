import * as React from "react";
import {Utils} from "../../Utils";
import {CalendarManager} from "./CalendarManager";


interface CalendarContainerProps {
    dataset: any;
}

interface CalendarContainerState {
    view: string;
    viewSettings: any;
    options: any;
    formName: string;
    formValue: any;
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

        console.log("Container shell data", options, " viewSettings", viewSettings);

        this.state = {
            view: this.props.dataset.view ?? "no-view",
            viewSettings: viewSettings ?? {},
            options: options ?? {},
            formValue: {},
            formName: this.props.dataset.name ?? "calendar"
        }
    }

    render() {
        let json = encodeURIComponent(JSON.stringify(this.state.formValue));
        return (
            <React.Fragment>
                <input type={"hidden"} value={json} name={this.state.formName}/>
                {this.calendarManager.createCalendarComponent(this.state.view, this.state.options, this.state.viewSettings)}
            </React.Fragment>
        );
    }
}