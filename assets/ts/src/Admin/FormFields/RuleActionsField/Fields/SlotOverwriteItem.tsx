import React = require("react");
import {RuleActionItemBase} from "./RuleActionItemBase";
import {Localization} from "../../../../Localization";
import {TimeSelect, TimeSelectTime} from "../../TimeSelect";

export class SlotOverwriteItem extends RuleActionItemBase {

    constructor(props) {
        super(props);

        this.onChangeAllDay = this.onChangeAllDay.bind(this);
        this.onChangeTimeFrom = this.onChangeTimeFrom.bind(this);
        this.onChangeTimeTo = this.onChangeTimeTo.bind(this);

        if (!this.state.ruleAction.actions) {
            this.state.ruleAction.actions = {
                fromHour: 0,
                fromMinute: 0,
                toHour: 23,
                toMinute: 59,
                isFullDay: true,
                mode: "set",
                amount: 0
            };
        }
    }

    onChangeTimeFrom(time: TimeSelectTime) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.fromMinute = time.minute;
            prevState.ruleAction.actions.fromHour = time.hour;

            this.props.onChange(prevState.ruleAction);

            return prevState;
        });
    }

    onChangeTimeTo(time: TimeSelectTime) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.toMinute = time.minute;
            prevState.ruleAction.actions.toHour = time.hour;

            this.props.onChange(prevState.ruleAction);

            return prevState;
        });
    }

    onChangeAllDay(event: any) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.isFullDay = event.target.value == "true";
            this.props.onChange(prevState.ruleAction);
            return prevState;
        });

        event.preventDefault();
    }

    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                <div style={{marginLeft: "20px"}}>
                    <small>{Localization.getText("All Day")}</small><br/>
                    <select onChange={this.onChangeAllDay} value={this.state.ruleAction.actions.isFullDay}>
                        <option value={"true"}>{Localization.getText("Yes")}</option>
                        <option value={"false"}>{Localization.getText("No")}</option>
                    </select>
                </div>
                {this.state.ruleAction.actions.isFullDay ? null : (
                    <React.Fragment>
                        <div style={{marginLeft: "20px"}}>
                            <small>{Localization.getText("From")}</small><br/>
                            <TimeSelect initState={{
                                minute: this.state.ruleAction.actions.fromMinute,
                                hour: this.state.ruleAction.actions.fromHour
                            }} minutesSteps={1} onChange={this.onChangeTimeFrom} nameHour={"fromHour"}
                                        nameMinute={"fromMinute"}/>
                        </div>
                        <div style={{marginLeft: "20px"}}>
                            <small>{Localization.getText("To")}</small><br/>
                            <TimeSelect initState={{
                                minute: this.state.ruleAction.actions.toMinute,
                                hour: this.state.ruleAction.actions.toHour
                            }} minutesSteps={1} onChange={this.onChangeTimeTo} nameHour={"toHour"}
                                        nameMinute={"toMinute"}/>
                        </div>
                    </React.Fragment>
                )}
                {this.getCapacityFields()}
            </React.Fragment>
        );
    }
}