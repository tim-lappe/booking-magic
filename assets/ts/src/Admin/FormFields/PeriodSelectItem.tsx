import * as React from "react";
import {Localization} from "../../Localization";
import {DateSelect, DateSelectState} from "./DateSelect";
import {Period} from "../Entity/Period";
import {PeriodTimeRange} from "../Entity/PeriodTimeRange";
import {TimeSelect, TimeSelectTime} from "./TimeSelect";
import {DateTime} from "../../Core/Adapter/DateTime";


interface PeriodSelectItemState {
    item: Period;
}

interface PeriodSelectItemProps {
    item?: Period;
    onChange: (item: Period) => void;
    onRemove: (item: Period) => void;
}

export class PeriodSelectItem extends React.Component<PeriodSelectItemProps, PeriodSelectItemState> {

    constructor(props) {
        super(props);

        this.onChangeEndDate = this.onChangeEndDate.bind(this);
        this.onChangeStartDate = this.onChangeStartDate.bind(this);
        this.onAddEnd = this.onAddEnd.bind(this);
        this.onRemoveEnd = this.onRemoveEnd.bind(this);
        this.onRemove = this.onRemove.bind(this);
        this.onAddDailyRange = this.onAddDailyRange.bind(this);
        this.onChangeDailyRangeFrom = this.onChangeDailyRangeFrom.bind(this);
        this.onChangeDailyRangeTo = this.onChangeDailyRangeTo.bind(this);

        this.state = {
            item: this.props.item ?? new Period()
        }
    }

    onChangeStartDate(dateSelect: DateSelectState) {
        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.fromDateTime = dateSelect.dateTime;
            this.props.onChange(prevState.item);
            return prevState;
        });
    }

    onChangeEndDate(dateSelect: DateSelectState) {
        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.toDateTime = dateSelect.dateTime;
            this.props.onChange(prevState.item);
            return prevState;
        });
    }

    onRemoveEnd(event: any) {
        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.toDateTime = null;
            this.props.onChange(prevState.item);
            return prevState;
        });

        event.preventDefault();
    }

    onAddEnd(event: any) {
        this.setState((prevState: PeriodSelectItemState) => {
            let date = DateTime.copy(prevState.item.fromDateTime);
            date.setHourMin(23, 59, 59);

            prevState.item.toDateTime = date;
            this.props.onChange(prevState.item);
            return prevState;
        });

        event.preventDefault();
    }

    onAddDailyRange(event: any) {
        let ranges = this.state.item.dailyTimeRanges;
        let dataItem = new PeriodTimeRange();
        dataItem.id = -Math.random();
        dataItem.from_min = 0;
        dataItem.from_hour = 0;
        dataItem.to_hour = 23;
        dataItem.to_min = 59;

        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.dailyTimeRanges = [...ranges, dataItem];
            this.props.onChange(prevState.item);

            return prevState;
        });

        event.preventDefault();
    }

    onChangeDailyRangeFrom(index: number, time: TimeSelectTime) {
        let range = this.state.item.dailyTimeRanges[index];
        range.from_hour = time.hour;
        range.from_min = time.minute;

        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.dailyTimeRanges[index] = range;
            this.props.onChange(prevState.item);

            return prevState;
        });
    }

    onChangeDailyRangeTo(index: number, time: TimeSelectTime) {
        let range = this.state.item.dailyTimeRanges[index];
        range.to_hour = time.hour;
        range.to_min = time.minute;

        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.dailyTimeRanges[index] = range;
            this.props.onChange(prevState.item);

            return prevState;
        });
    }

    onRemoveDailyRange(index: number) {
        let items = this.state.item.dailyTimeRanges;
        items.splice(index, 1);

        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.dailyTimeRanges = [...items];
            this.props.onChange(prevState.item);
            
            return prevState;
        });
    }

    onRemove(event: any) {
        this.props.onRemove(this.state.item);

        event.preventDefault();
    }

    hasEndDate(): boolean {
        return this.state.item.toDateTime != null;
    }

    render() {
        let currentDate = new Date();

        return (
            <div className={"tlbm-period-item tlbm-gray-container tlbm-admin-content-box"}>
                <div className={"tlbm-period-panel"}>
                    <div>
                        <small>{Localization.__("Start")}</small>
                        <DateSelect defaultDateTime={this.state.item.fromDateTime} allowTimeSet={true} onChange={this.onChangeStartDate} minYear={currentDate.getFullYear()} />
                    </div>

                    {this.hasEndDate() ? (
                        <div>
                            <small>{Localization.__("End")}</small>
                            <DateSelect defaultDateTime={this.state.item.toDateTime} allowTimeSet={true} onChange={this.onChangeEndDate} minYear={currentDate.getFullYear()} />
                        </div>
                    ): null }

                    {!this.hasEndDate() ? <button onClick={this.onAddEnd} className={"button"}>{Localization.__("Add End")}</button> : null}
                    {this.hasEndDate() ? <button onClick={this.onRemoveEnd} className={"button"}>{Localization.__("Remove End")}</button> : null}
                </div>
                <div className={"tlbm-period-panel tlbm-period-timeslots-panel"}>
                    <div className={"tlbm-timeslots-container"}>
                        <small>&nbsp;</small>
                        <div className={"tlbm-timeslots"}>
                            {this.state.item.dailyTimeRanges.map((item, index) => {
                                return (
                                    <div key={item.id} className={"tlbm-timeslot-item"}>
                                        <div>
                                            <small>{Localization.__("Daily from")}</small>
                                            <TimeSelect onChange={(newtime) => this.onChangeDailyRangeFrom(index, newtime)} initState={{
                                                minute: item.from_min,
                                                hour: item.from_hour
                                            }} />
                                        </div>
                                        <div style={{marginLeft: "20px"}}>
                                            <small>{Localization.__("Daily to")}</small>
                                            <TimeSelect onChange={(newtime) => this.onChangeDailyRangeTo(index, newtime)} initState={{
                                                minute: item.to_min,
                                                hour: item.to_hour
                                            }} />
                                        </div>
                                        <button style={{marginLeft: "20px"}} onClick={() => this.onRemoveDailyRange(index)} className={'button button-small tlbm-timeslot-delete'}><span className={'dashicons dashicons-trash'} /></button>
                                    </div>
                                )
                            })}
                        </div>
                        <button onClick={this.onAddDailyRange} className={"button"}>{Localization.__("Add Daily Time Range")}</button>
                    </div>
                </div>
                <button onClick={this.onRemove} className={'button button-small tlbm-period-delete'}><span className={'dashicons dashicons-trash'} /></button>
            </div>
        )
    }
}