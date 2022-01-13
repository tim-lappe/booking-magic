import * as React from "react";
import {Localization} from "../../Localization";
import {Utils} from "../../Utils";
import {TimeSelect, TimeSelectTime} from "./TimeSelect";

export interface DateSelectState {
    tstamp?: number;
    timeset: boolean;
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
    defaultTstamp?: number;
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
            tstamp: this.props.defaultTstamp ?? Utils.getUnixTimestamp(),
            timeset: this.props.timeset ?? false
        }
    }

    onChangeDay(event: any) {
        this.setState((prevState: DateSelectState) => {
            let date = new Date();
            date.setTime(prevState.tstamp * 1000);
            date.setDate(event.target.value);
            prevState.tstamp = Utils.getUnixTimestamp(date);

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeMonth(event: any) {
        this.setState((prevState: DateSelectState) => {
            let date = new Date();
            date.setTime(prevState.tstamp * 1000);

            prevState.tstamp = Utils.getUnixTimestamp(this.setDateFitInSameMonth(date, event.target.value - 1, date.getFullYear()));

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeYear(event: any) {
        this.setState((prevState: DateSelectState) => {
            let date = new Date();
            date.setTime(prevState.tstamp * 1000);

            prevState.tstamp = Utils.getUnixTimestamp(this.setDateFitInSameMonth(date, date.getMonth(), event.target.value));

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onAddTimeset(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.timeset = true;

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onRemoveTimeset(event: any) {
        this.setState((prevState: DateSelectState) => {
            prevState.timeset = false;

            this.props.onChange(prevState);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeTime(time: TimeSelectTime) {
        this.setState((prevState: DateSelectState) => {
            let date = new Date();
            date.setTime(prevState.tstamp * 1000);
            date.setHours(time.hour, time.minute, 0, 0);

            prevState.tstamp = Utils.getUnixTimestamp(date);
            this.props.onChange(prevState);

            return prevState;
        });
    }


    setDateFitInSameMonth(date: Date, newMonth: number, newYear: number) {
        let newDate = new Date();
        newDate.setDate(1);
        newDate.setFullYear(newYear, newMonth);
        newDate.setHours(date.getHours(), date.getMinutes(), date.getSeconds());

        let newMaxDates = this.getMonthDays(Utils.getUnixTimestamp(newDate));
        if(date.getDate() <= newMaxDates) {
            newDate.setDate(date.getDate());
            return newDate;
        } else {
            newDate.setDate(newMaxDates);
        }

        return newDate;
    }

    getMonthDays(tstamp: number) {
        let date = new Date();
        date.setTime(tstamp * 1000);
        date.setDate(1);
        date.setMonth(date.getMonth() + 1);
        date.setDate(0);

        return date.getDate();
    }

    getCurrentMonthDays() {
       return this.getMonthDays(this.state.tstamp);
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

        let selectedDate = new Date();
        selectedDate.setTime(this.state.tstamp * 1000);


        return (
            <div className={"tlbm-date-select"}>
                <select onChange={this.onChangeDay} value={selectedDate.getDate()}>
                    {dayOptions}
                </select>
                <select onChange={this.onChangeMonth} value={selectedDate.getMonth() + 1}>
                    {Object.entries(Localization.__Arr("months")).map((item) => {
                        return (
                            <option key={parseInt(item[0])} value={parseInt(item[0])}>{item[1]}</option>
                        )
                    })}
                </select>
                <select onChange={this.onChangeYear} value={selectedDate.getFullYear()}>
                    {yearOptions}
                </select>
                {((!this.state.timeset && !this.props.forceTimeSet) && this.props.allowTimeSet) ? (
                    <button onClick={this.onAddTimeset} className={"button button-add-time"}>Add Time</button>
                ) : null}
                {((this.state.timeset  || this.props.forceTimeSet) && this.props.allowTimeSet) ?
                    [
                        <span key={1} className="dashicons dashicons-clock" />,
                        <TimeSelect key={2} onChange={this.onChangeTime} initState={{minute: selectedDate.getMinutes(), hour: selectedDate.getHours()}} />,
                        <button key={3} onClick={this.onRemoveTimeset} className={"button button-remove-time"}>Remove Time</button>
                    ]: null}
            </div>
        );
    }
}