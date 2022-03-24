import * as React from "react";
import {Localization} from "../../Localization";

interface WeekdaySelectProps {
    onChange?: (newWeekday: WeekdaySelectWeekday) => void;
    name?: string;
    initState: WeekdaySelectWeekday;
}

export interface WeekdaySelectWeekday {
    weekday?: string;
}

export class WeekdaySelect extends React.Component<WeekdaySelectProps, WeekdaySelectWeekday> {

    public selectDom = React.createRef<HTMLSelectElement>();

    constructor(props: any) {
        super(props);

        this.state = props.initState ?? {
            weekday: "every_day"
        };

        this.onChange = this.onChange.bind(this);
    }

    componentDidMount() {
        this.setState((prevState: WeekdaySelectWeekday) => {
            prevState.weekday = this.selectDom.current.value.toString();
            this.props.onChange(this.state);
            return prevState;
        });
    }

    onChange(event: any) {
        this.setState((prevState: WeekdaySelectWeekday) => {
            prevState.weekday = event.target.value.toString();
            this.props.onChange(this.state);

            return prevState;
        });

        event.preventDefault();
    }


    render() {
        return (<select ref={this.selectDom} value={this.state.weekday} onLoad={this.onChange} onChange={this.onChange}
                        name={this.props.name ?? "weekday"}>
            <optgroup label={Localization.getText('Multiple Weekdays')}>
                {Object.entries(Localization.getTextArr("weekdaysRange")).map((item) => {
                    return (
                        <option value={item[0]} key={item[0]}>{item[1]}</option>
                    )
                })}
            </optgroup>
            <optgroup label={Localization.getText('Single Weekday')}>
                {Object.entries(Localization.getTextArr("weekdays")).map((item) => {
                    return (
                        <option value={item[0]} key={item[0]}>{item[1]}</option>
                    )
                })}
            </optgroup>
        </select>);
    }
}