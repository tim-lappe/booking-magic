import * as React from "react";
import {Localization} from "../../../Localization";

interface WeekdaySelectProps {
    onChange?: (newWeekday: WeekdaySelectWeekday) => void;
    name?: string;
}

export interface WeekdaySelectWeekday {
    weekday?: string;
}

export class WeekdaySelect extends React.Component<WeekdaySelectProps, WeekdaySelectWeekday> {

    public selectDom = React.createRef<HTMLSelectElement>();

    constructor(props: any) {
        super(props);

        this.state = {
            weekday: ""
        }

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
        return (<select ref={this.selectDom} onLoad={this.onChange} onChange={this.onChange} name={this.props.name ?? "weekday"}>
            <optgroup label={Localization.__('Multiple Weekdays')}>
                <option value="every_day">{Localization.__("Every Day")}</option>
                <option value="mo_to_fr">{Localization.__("Monday to Friday")}</option>
                <option value="sat_and_sun">{Localization.__("Saturday and Sunday")}</option>
            </optgroup>
            <optgroup label={Localization.__('Single Weekday')}>
                {Object.entries(Localization.__Arr("weekdays")).map((item) => {
                    return (
                        <option value={item[0]} key={item[0]}>{item[1]}</option>
                    )
                })}
            </optgroup>
        </select>);
    }
}