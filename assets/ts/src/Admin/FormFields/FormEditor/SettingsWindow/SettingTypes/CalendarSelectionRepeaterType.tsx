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
        this.onTitleChanged = this.onTitleChanged.bind(this);

        this.state = {
            value: Array.isArray(this.props.value) ? this.props.value : []
        }
    }

    onCalendarChanged(event: any, index: number, item: { identifier: string, title: string }) {
        this.setState((prevState) => {
            item.identifier = event.target.value;
            prevState.value[index] = item;

            this.props.onChange(prevState.value)
            return prevState;
        });

        event.preventDefault();
    }

    onTitleChanged(event: any, index: number, item: { identifier: string, title: string }) {
        this.setState((prevState) => {
            item.title = event.target.value;
            prevState.value[index] = item;

            this.props.onChange(prevState.value)
            return prevState;
        });

        event.preventDefault();
    }

    onRemoveOption(option: any, event: any) {
        this.setState((prevState: any) => {
            prevState.value = prevState.value.filter((val) => val != option);

            this.props.onChange(prevState.value)
            return prevState;
        });

        event.preventDefault();
    }

    onAddOption(event: any) {
        this.setState((prevState) => {
            prevState.value.push({identifier: "", title: ""});

            this.props.onChange(prevState.value)
            return prevState;
        });

        event.preventDefault();
    }

    getCalendarSelection(index: number, item: { identifier: string, title: string }) {
        let settings = this.props.elementSetting as CalendarSelectElementSetting;
        return (
            <select onChange={(event) => this.onCalendarChanged(event, index, item)} value={item.identifier}>
                {Object.entries(settings.calendarKeyValues).map((item: any) => {
                    if (!((typeof item[1] == "string") || (typeof item[1] == "number"))) {
                        return (
                            <optgroup key={item[0]} label={item[0]}>
                                {item[1] != null ? (
                                    <React.Fragment>
                                        {Object.entries(item[1]).map((subitem: any) => {
                                            return (
                                                <option
                                                    disabled={this.state.value.filter(val => val.identifier == subitem[0]).length > 0}
                                                    key={subitem[0]} value={subitem[0]}>{subitem[1]}</option>
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
        let calendarTitlePairs: { identifier: string, title: string }[] = this.state.value;
        return (
            <label>
                {this.props.elementSetting.title}<br/>
                {calendarTitlePairs.map((titlePair, index) => {
                    return (
                        <div key={index} style={{display: "flex", gap: "1em", alignItems: "end"}}>
                            <input value={titlePair.title}
                                   onChange={(event) => this.onTitleChanged(event, index, titlePair)}
                                   placeholder={Localization.getText("Enter title of option")}
                                   className={"regular-text"} type={"text"}/>
                            {this.getCalendarSelection(index, titlePair)}
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