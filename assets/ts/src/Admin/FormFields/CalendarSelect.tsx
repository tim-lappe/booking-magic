import {Localization} from "../../Localization";
import * as React from "react";
import {Utils} from "../../Utils";

interface CalendarSelectState {
    selection: any;
    calendars: any;
    tags: any;
}

export class CalendarSelect extends React.Component<any, CalendarSelectState>{

    constructor(props) {
        super(props);

        this.onSelectionModeChanged = this.onSelectionModeChanged.bind(this);
        this.onCalendarCheckedChanged = this.onCalendarCheckedChanged.bind(this);
        this.onTagCheckedChanged = this.onTagCheckedChanged.bind(this);

        let jsonstring = Utils.decodeUriComponent(props.dataset.json);
        let jsondata = JSON.parse(jsonstring);

        let calendarsData = Utils.decodeUriComponent(props.dataset.calendars);
        calendarsData = JSON.parse(calendarsData);

        let tagsData = Utils.decodeUriComponent(props.dataset.tags);
        tagsData = JSON.parse(tagsData);

        if(!jsondata.calendar_ids || !jsondata.tag_ids || !jsondata.selection_mode) {
            jsondata = {
                "calendar_ids": [],
                "tag_ids": [],
                "selection_mode": "all"
            };
        }

        this.state = {
            selection: jsondata ?? {
                "calendar_ids": [],
                "tag_ids": [],
                "selection_mode": "all"
            },
            calendars: calendarsData,
            tags: tagsData
        }
    }

    onSelectionModeChanged(event: any) {
        this.setState(prevState => {
            prevState.selection.selection_mode = event.target.value;
            return prevState;
        });
    }

    onTagCheckedChanged(event: any) {
        this.setState(prevState => {
            if(event.target.checked) {
                prevState.selection.tag_ids.push(parseInt(event.target.value));
            } else {
                let index = prevState.selection.tag_ids.indexOf(parseInt(event.target.value));
                if (index > -1) {
                    prevState.selection.tag_ids.splice(index, 1);
                }
            }

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

                <div className={"tlbm-calendar-select-panel-row"}  style={{"display": this.state.selection.selection_mode != "all" ? "flex" : "none"}}>
                    <div className={"tlbm-calendar-select-panel-col"}>
                        <p className={"tlbm-select-panel-category-label"}>{Localization.getText("Calendars:")}</p>
                        <div className="tlbm-calendar-select-panel">
                            <span style={{"display": Object.entries(this.state.calendars).length > 0 ? "none" : "block"}}>{Localization.getText("There are no calendars to select")}</span>
                            {Object.entries(this.state.calendars).map((item) => {
                                return (
                                    <div key={item[0]} className="tlbm-calendar-select-item">
                                        <label>
                                            <input type={"checkbox"} onChange={this.onCalendarCheckedChanged} value={item[0]}
                                                   checked={this.state.selection.calendar_ids?.includes(parseInt(item[0]))}/>
                                            {item[1]}
                                        </label>
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                    <div className={"tlbm-calendar-select-panel-col"} style={{"display": Object.entries(this.state.tags).length > 0 ? "block" : "none"}}>
                        <p className={"tlbm-select-panel-category-label"}>{Localization.getText("Tags:")}</p>
                        <div className="tlbm-calendar-tag-select-panel">
                            {Object.entries(this.state.tags).map((item) => {
                                return (
                                    <div key={item[0]} className="tlbm-calendar-select-item">
                                        <label>
                                            <input type={"checkbox"} onChange={this.onTagCheckedChanged} value={item[0]}
                                                   checked={this.state.selection.tag_ids?.includes(parseInt(item[0]))}/>
                                            {item[1]}
                                        </label>
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}