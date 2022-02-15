import * as React from "react";
import {Localization} from "../../Localization";
import {TimeSelect, TimeSelectTime} from "./TimeSelect";
import {DateTime} from "../../Core/Adapter/DateTime";

export interface DateSelectState {
    dateTime?: DateTime;
}

interface DateSelectProps {
    minYear?: number;
    maxYear?: number;
    minHour?: number;
    maxHour?: number;
    minMinute?: number;
    maxMinute?: number;
    allowTimeSet?: boolean;
    forceTimeSet?: boolean;
    defaultDateTime?: DateTime;
    timeset?: boolean;
    onChange: (dateSelectState: DateSelectState) => void;
}

export class DateSelect extends React.Component<DateSelectProps, DateSelectState> {

    constructor(props: DateSelectProps) {
        super(props);

        this.onChangeYear = this.onChangeYear.bind(this);
        this.onChangeMonth = this.onChangeMonth.bind(this);
        this.onChangeDay = this.onChangeDay.bind(this);
        this.onAddTimeset = this.onAddTimeset.bind(this);
        this.onRemoveTimeset = this.onRemoveTimeset.bind(this);
        this.onChangeTime = this.onChangeTime.bind(this);

        this.state = {
            dateTime: this.props.defaultDateTime ?? new DateTime()
        }
    }

    onChangeDay(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime.setMonthDay(event.target.value);
            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeMonth(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime = this.setDateFitInSameMonth(prevState.dateTime, parseInt(event.target.value), prevState.dateTime.getYear());
            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeYear(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime = this.setDateFitInSameMonth(prevState.dateTime, prevState.dateTime.getMonth(), parseInt(event.target.value));
            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onAddTimeset(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime.setFullDay(false);

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onRemoveTimeset(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime.setFullDay(true);

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeTime(time: TimeSelectTime) {
        this.setState((prevState: DateSelectState) => {
            prevState.dateTime.setHourMin(time.hour, time.minute, 0);
            this.props.onChange(prevState);
            return prevState;
        });
    }

    setDateFitInSameMonth(date: DateTime, newMonth: number, newYear: number) {
        let newDate = DateTime.create();
        newDate.setYear(newYear, newMonth);
        newDate.setMonthDay(1);
        newDate.setHourMin(date.getHour(), date.getMinute(), date.getSeconds());


        let newMaxDates = this.getMonthDays(newDate.getMonth(), newDate.getYear());
        if(date.getMonthDay() <= newMaxDates) {
            newDate.setMonthDay(date.getMonthDay());
            return newDate;
        } else {
            newDate.setMonthDay(newMaxDates);
        }

        return newDate;
    }

    getMonthDays(month: number, year: number) {
        let date = new DateTime(year, month);
        date.setMonthDay(1);
        date.setMonth(date.getMonth() + 1);
        date.setMonthDay(0);

        return date.getMonthDay();
    }

    getCurrentMonthDays() {
        return this.getMonthDays(this.state.dateTime.getMonth(), this.state.dateTime.getYear());
    }

    render() {
        let dayOptions = [];
        let currentMonthDays = this.getCurrentMonthDays();
        for (let i = 1; i <= currentMonthDays; i++) {
            dayOptions.push(<option key={i} value={i}>{i}</option>);
        }

        let yearOptions = [];
        for(let y = (this.props.minYear ?? 1930); y <= (this.props.maxYear ?? 2100); y++) {
            yearOptions.push( <option key={y} value={y}>{y}</option>);
        }

        let hourOptions = [];
        for (let h = (this.props.minHour ?? 0); h <= (this.props.maxHour ?? 23); h++) {
            hourOptions.push(<option key={h} value={h}>{h}</option>);
        }

        let minuteOptions = [];
        for (let m = (this.props.minMinute ?? 0); m <= (this.props.maxMinute ?? 59); m++) {
            minuteOptions.push(<option key={m} value={m}>{m}</option>);
        }

        let selectedDate = this.state.dateTime;

        return (
            <div className={"tlbm-date-select"}>
                <select onChange={this.onChangeDay} value={selectedDate.getMonthDay()}>
                    {dayOptions}
                </select>
                <select onChange={this.onChangeMonth} value={selectedDate.getMonth()}>
                    {Object.entries(Localization.__Arr("months")).map((item) => {
                        return (
                            <option key={parseInt(item[0])} value={parseInt(item[0])}>{item[1]}</option>
                        )
                    })}
                </select>
                <select onChange={this.onChangeYear} value={selectedDate.getYear()}>
                    {yearOptions}
                </select>
                {((this.state.dateTime.isFullDay() && !this.props.forceTimeSet) && this.props.allowTimeSet) ? (
                    <button onClick={this.onAddTimeset} className={"button button-add-time"}>Add Time</button>
                ) : null}
                {((!this.state.dateTime.isFullDay()  || this.props.forceTimeSet) && this.props.allowTimeSet) ?
                    [
                        <span key={1} className="dashicons dashicons-clock" />,
                        <TimeSelect key={2} onChange={this.onChangeTime} initState={{minute: selectedDate.getMinute(), hour: selectedDate.getHour()}} />,
                        <button key={3} onClick={this.onRemoveTimeset} className={"button button-remove-time"}>Remove Time</button>
                    ]: null}
            </div>
        );
    }
}