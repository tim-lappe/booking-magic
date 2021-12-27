import * as React from "react";
import {Localization} from "../../../../Localization";
import {TimeSelect, TimeSlotTime} from "../TimeSelect";
import {WeekdaySelect, WeekdaySelectWeekday} from "../WeekdaySelect";
import {CapacitySelect, CapacitySelectCapacity} from "../CapacitySelect";
import {RuleAction} from "../../../Entity/RuleAction";


interface RuleActionsItemProps {
    onChange?: (action: RuleAction) => void;
    onRemove?: (action: RuleAction) => void;
    onMoveUp?: (action: RuleAction) => void;
    onMoveDown?: (action: RuleAction) => void;
    dataItem?: RuleAction;
}

interface RuleActionsItemState {
    item: RuleAction;
}

export class RuleActionsItem extends React.Component<RuleActionsItemProps, RuleActionsItemState> {

    constructor(props) {
        super(props);

        this.state = {
            item: props.dataItem
        };

        this.onRemove = this.onRemove.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onChange = this.onChange.bind(this);

        this.onChangeTime = this.onChangeTime.bind(this);
        this.onChangeWeekday = this.onChangeWeekday.bind(this);
        this.onChangeCapacity = this.onChangeCapacity.bind(this);
        this.onChangeMessage = this.onChangeMessage.bind(this);
    }

    onRemove(event: any) {
        this.props.onRemove(this.state.item);
        event.preventDefault();
    }

    onMoveUp(event: any) {
        this.props.onMoveUp(this.state.item);
        event.preventDefault();
    }

    onMoveDown(event: any) {
        this.props.onMoveDown(this.state.item);
        event.preventDefault();
    }

    onChange(item: RuleAction) {
        this.props.onChange(item);
    }

    onChangeTime(newTime: TimeSlotTime) {
        this.setState((prevState: RuleActionsItemState) => {
            prevState.item.time_hour = newTime.hour;
            prevState.item.time_min = newTime.minute;
            this.onChange(prevState.item);

            return prevState;
        });
    }

    onChangeWeekday(newWeekday: WeekdaySelectWeekday) {
        this.setState((prevState: RuleActionsItemState) => {
            prevState.item.weekdays = newWeekday.weekday;
            this.onChange(prevState.item);
            return prevState;
        });
    }

    onChangeCapacity(newCapacity: CapacitySelectCapacity) {
        this.setState((prevState: RuleActionsItemState) => {
            prevState.item.actions = newCapacity;
            this.onChange(prevState.item);
            return prevState;
        });
    }

    onChangeMessage(event: any) {
        this.setState((prevState: RuleActionsItemState) => {
            prevState.item.actions = {
                message: event.target.value
            };

            this.onChange(prevState.item);
            return prevState;
        });

        event.preventDefault();
    }

    componentDidMount() {
        this.onChange(this.state.item);
    }

    render() {
        let formcontent = (<div />);

        let weekday_select = (
            <div>
                <small>{Localization.__("Weekdays")}</small><br />
                <WeekdaySelect onChange={this.onChangeWeekday} />
            </div>
        );

        let time_select = (
            <div style={{marginLeft: "20px"}}>
                <small>{Localization.__("Timeslot")}</small><br />
                <TimeSelect minutesSteps={5} onChange={this.onChangeTime} />
            </div>
        )

        let capacity_select = (
            <div style={{marginLeft: "20px"}}>
                <small>{Localization.__("Capacity")}</small><br />
                <CapacitySelect onChange={this.onChangeCapacity} />
            </div>
        )

        if(this.state.item.action_type == "time_slot") {
            formcontent = (
                <div style={{display: "flex"}}>
                    {weekday_select}
                    {time_select}
                    {capacity_select}
                </div>
            )
        }

        if(this.state.item.action_type == "date_slot") {
            formcontent = (
                <div style={{display: "flex"}}>
                    {weekday_select}
                    {capacity_select}
                </div>
            )
        }

        if(this.state.item.action_type == "message") {
            formcontent = (
                <div style={{display: "flex"}}>
                    {weekday_select}
                    <div style={{marginLeft: "20px", flexGrow: 1}}>
                        <small>{Localization.__("Message")}</small><br />
                        <textarea style={{minWidth: "75%"}} onChange={this.onChangeMessage} value={this.state.item?.actions?.message} />
                    </div>
                </div>
            )
        }

        return (
            <div className={'tlbm-action-rule-item tlbm-gray-container'}>
                <div className={'tlbm-action-item-form'}>
                    {formcontent}
                </div>
                <div className={'tlbm-up-down-buttons'}>
                    <button onClick={this.onMoveUp} className={'tlbm-ud-button-up'}><span className={'dashicons dashicons-arrow-up-alt2'} /></button>
                    <button onClick={this.onMoveDown} className={'tlbm-ud-button-down'}><span className={'dashicons dashicons-arrow-down-alt2'} /></button>
                </div>
                <button onClick={this.onRemove} className={'button button-small tlbm-action-item-delete'}><span className={'dashicons dashicons-trash'} /></button>
            </div>
        );
    }
}