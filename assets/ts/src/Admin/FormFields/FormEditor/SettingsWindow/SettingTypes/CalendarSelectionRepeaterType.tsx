import {BasicSettingsTypeElement} from "./BasicSettingsTypeElement";
import {ElementSetting} from "../../../../Entity/FormEditor/ElementSetting";
import {Localization} from "../../../../../Localization";
import React = require("react");

class CalendarSelectElementSetting extends ElementSetting {
    public calendarKeyValues: { [props: number]: string };
}

export class CalendarSelectionRepeaterType extends BasicSettingsTypeElement {

    constructor(props) {
        super(props);

        this.onAddOption = this.onAddOption.bind(this);
        this.onRemoveOption = this.onRemoveOption.bind(this);

        let prevState = this.state;
        this.state = {
            ...prevState,
            calendarTitlePairs: []
        }
    }


    onChange(event: any) {

    }

    onRemoveOption(option: any, event: any) {
        this.setState((prevState) => {
            prevState.calendarTitlePairs.push({identifier: "", title: ""});
            return prevState;
        });

        event.preventDefault();
    }

    onAddOption(event: any) {
        this.setState((prevState) => {
            prevState.calendarTitlePairs.push({identifier: "", title: ""});
            return prevState;
        });

        event.preventDefault();
    }

    getCalendarSelection(value: string) {
        let settings = this.props.elementSetting as CalendarSelectElementSetting;
        return (
            <select onChange={this.onChange} value={value}>
                {Object.entries(settings.calendarKeyValues).map((item: any) => {
                    if (!((typeof item[1] == "string") || (typeof item[1] == "number"))) {
                        return (
                            <optgroup key={item[0]} label={item[0]}>
                                {item[1] != null ? (
                                    <React.Fragment>
                                        {Object.entries(item[1]).map((subitem: any) => {
                                            return (
                                                <option key={subitem[0]} value={subitem[0]}>{subitem[1]}</option>
                                            );
                                        })}
                                    </React.Fragment>
                                ) : null}
                            </optgroup>
                        );
                    } else {
                        return (
                            <option key={item[0]} value={item[0]}>{item[1]}</option>
                        )
                    }
                })}
            </select>
        );
    }

    render(): JSX.Element {
        let calendarTitlePairs: { identifier: string, title: string }[] = this.state.calendarTitlePairs;
        return (
            <label>
                {this.props.elementSetting.title}<br/>
                {calendarTitlePairs.map((titlePair) => {
                    return (
                        <div key={titlePair.identifier} style={{display: "flex", gap: "1em", alignItems: "end"}}>
                            <input placeholder={Localization.getText("Enter title of option")}
                                   className={"regular-text"} type={"text"}/>
                            {this.getCalendarSelection(titlePair.title)}
                            <button onClick={(event) => this.onRemoveOption(titlePair, event)}
                                    className={"button tlbm-button-danger"}>{Localization.getText("Remove")}</button>
                        </div>
                    )
                })}

                <button className={"button button-primary"} style={{marginTop: "1em"}}
                        onClick={this.onAddOption}>{Localization.getText("Add Option")}</button>
            </label>
        );
    }
}