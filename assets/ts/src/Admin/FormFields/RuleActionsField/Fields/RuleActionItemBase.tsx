import * as React from "react";
import {Localization} from "../../../../Localization";
import {WeekdaySelect, WeekdaySelectWeekday} from "../../WeekdaySelect";
import {TimeSelect, TimeSelectTime} from "../../TimeSelect";
import {RuleAction} from "../../../Entity/RuleAction";
import {CapacitySelect, CapacitySelectCapacity} from "../../CapacitySelect";

export interface ItemContentProps {
    content?: any;
    ruleAction: RuleAction;
    onChange: (ruleAction: RuleAction) => void;
}

export interface ItemContentState {
    ruleAction: RuleAction;
}

export abstract class RuleActionItemBase extends React.Component<ItemContentProps, ItemContentState> {

    protected constructor(props) {
        super(props);

        this.onChangeWeekday = this.onChangeWeekday.bind(this);
        this.onChangeTime = this.onChangeTime.bind(this);
        this.onChangeCapacity = this.onChangeCapacity.bind(this);

        this.state = {
            ruleAction: this.props.ruleAction
        }
    }

    onChangeWeekday(newWeekday: WeekdaySelectWeekday) {
        this.setState((prevState: ItemContentState) => {
            prevState.ruleAction.weekdays = newWeekday.weekday;
            this.props.onChange(prevState.ruleAction);

            return prevState;
        });
    }

    onChangeTime(newTime: TimeSelectTime) {
        this.setState((prevState: ItemContentState) => {
            prevState.ruleAction.time_hour = newTime.hour;
            prevState.ruleAction.time_min = newTime.minute;

            this.props.onChange(prevState.ruleAction);
            return prevState;
        });
    }

    onChangeCapacity(newCapacity: CapacitySelectCapacity) {
        this.setState((prevState: ItemContentState) => {
            prevState.ruleAction.actions = newCapacity;

            this.props.onChange(prevState.ruleAction);
            return prevState;
        });
    }

    render() {
        return (
            <div style={{display: "flex"}}>
                <div>
                    <small>{Localization.__("Weekdays")}</small><br/>
                    <WeekdaySelect initState={{
                        weekday: this.state.ruleAction.weekdays
                    }} onChange={this.onChangeWeekday}/>
                </div>
                {this.getFields()}
            </div>
        )
    }

    /**
     *
     * @protected
     */
    protected abstract getFields(): JSX.Element;

    /**
     *
     * @protected
     */
    protected getTimeSlotFields(title: string, nameMinute: string = null, nameHour: string = null): JSX.Element {
        return (
            <div style={{marginLeft: "20px"}}>
                <small>{title}</small><br/>
                <TimeSelect initState={{
                    minute: this.state.ruleAction.time_min,
                    hour: this.state.ruleAction.time_hour
                }} minutesSteps={5} onChange={this.onChangeTime} nameHour={nameHour} nameMinute={nameMinute}/>
            </div>
        );
    }

    protected getCapacityFields(): JSX.Element {
        return (
            <div style={{marginLeft: "20px"}}>
                <small>{Localization.__("Capacity")}</small><br/>
                <CapacitySelect initState={this.state.ruleAction.actions} onChange={this.onChangeCapacity}/>
            </div>
        );
    }
}