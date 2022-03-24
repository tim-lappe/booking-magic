import {Localization} from "../../Localization";
import * as React from "react";
import {Utils} from "../../Utils";

interface CalendarSelectState {
    selection: any;
    calendars: any;
}

export class CalendarSelect extends React.Component<any, CalendarSelectState>{

    constructor(props) {
        super(props);

        this.onSelectionModeChanged = this.onSelectionModeChanged.bind(this);
        this.onCalendarCheckedChanged = this.onCalendarCheckedChanged.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        jsondata = JSON.parse(jsondata);

        let calendarsdata = Utils.decodeUriComponent(props.dataset.calendars);
        calendarsdata = JSON.parse(calendarsdata);

        this.state = {
            selection: jsondata ?? {
                "calendar_ids": [],
                "selection_mode": "all"
            },
            calendars: calendarsdata
        }
    }

    onSelectionModeChanged(event: any) {
        this.setState(prevState => {
            prevState.selection.selection_mode = event.target.value;
            return prevState;
        });
    }

    onCalendarCheckedChanged(event: any) {
        this.setState(prevState => {
            if(event.target.checked) {
                prevState.selection.calendar_ids.push(parseInt(event.target.value));
            } else {
                let index = prevState.selection.calendar_ids.indexOf(parseInt(event.target.value));
                if (index > -1) {
                    prevState.selection.calendar_ids.splice(index, 1);
                }
            }

            return prevState;
        });
    }

    render() {
        let selection = encodeURIComponent(JSON.stringify(this.state.selection));

        return (
            <div className={"tlbm-form-field-calendar-selector"}>
                <input type={"hidden"} name={this.props.dataset.name} value={selection}/>
                <select value={this.state.selection.selection_mode} onChange={this.onSelectionModeChanged}>
                    <option value="all">{Localization.getText("All")}</option>
                    <option value="all_but">{Localization.getText("All But")}</option>
                    <option value="only">{Localization.getText("Only These")}</option>
                </select>
                <div className="tlbm-calendar-select-panel"
                     style={{"display": this.state.selection.selection_mode != "all" ? "block" : "none"}}>
                    <span
                        style={{"display": Object.entries(this.state.calendars).length > 0 ? "none" : "block"}}>{Localization.getText("There are no calendars to select")}</span>
                    {Object.entries(this.state.calendars).map((item) => {
                        return (
                            <div key={item[0]} className="tlbm-calendar-select-item">
                                <label>
                                    <input type={"checkbox"} onChange={this.onCalendarCheckedChanged} value={item[0]}
                                           checked={this.state.selection.calendar_ids.includes(parseInt(item[0]))}/>
                                    {item[1]}
                                </label>
                            </div>
                        )
                    })}
                </div>
            </div>
        );
    }
}