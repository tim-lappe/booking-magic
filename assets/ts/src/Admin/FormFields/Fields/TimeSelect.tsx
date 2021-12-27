import * as React from "react";

interface TimeSelectProps {
    hoursSteps?: number;
    minutesSteps?: number;
    nameHour?: string;
    nameMinute?: string;
    onChange?: (newtime: TimeSlotTime) => void;
    hours?: [];
    minutes?: [];
}

export interface TimeSlotTime {
    hour?: number;
    minute?: number;
}

export class TimeSelect extends React.Component<TimeSelectProps, TimeSlotTime> {

    public selectHourDom = React.createRef<HTMLSelectElement>();
    public selectMinuteDom = React.createRef<HTMLSelectElement>();

    constructor(props: any) {
        super(props);

        this.state = {
            minute: 0,
            hour: 0
        }

        this.onChangeHour = this.onChangeHour.bind(this);
        this.onChangeMinute = this.onChangeMinute.bind(this);
    }

    componentDidMount() {
        this.setState((prevState: TimeSlotTime) => {
            prevState.hour = parseInt(this.selectHourDom.current.value);
            prevState.minute = parseInt(this.selectMinuteDom.current.value);
            this.props.onChange(prevState);
            return prevState;
        });
    }

    onChangeHour(event: any) {
        this.setState((prevState: TimeSlotTime) => {
            prevState.hour = parseInt(event.target.value);
            this.props.onChange(prevState);

            return prevState;
        });
    }

    onChangeMinute(event: any) {
        this.setState((prevState: TimeSlotTime) => {
            prevState.minute = parseInt(event.target.value);
            this.props.onChange(prevState);

            return prevState;
        });
    }

    render() {
        let hourSteps = this.props.hoursSteps ?? 1;
        let minuteSteps = this.props.minutesSteps ?? 1;


        let hoursArr = this.props.hours ??
            [...new Array( Math.floor(24 / hourSteps))]
                .map((_,i) => i * hourSteps);

        let minutesArr = this.props.minutes ??
            [...new Array(Math.floor(60 / minuteSteps))]
                .map((_,i) => i * minuteSteps);

        return (
            <div style={{"display": "flex"}}>
                <select ref={this.selectHourDom} onLoad={this.onChangeHour} onChange={this.onChangeHour} name={this.props.nameHour ?? "hour"}>
                    {hoursArr.map((i) => (
                            <option value={i} key={i}>{i}</option>
                        )
                    )}
                </select>
                <span>&nbsp;:&nbsp;</span>
                <select ref={this.selectMinuteDom} onLoad={this.onChangeMinute} onChange={this.onChangeMinute} name={this.props.nameMinute ?? "minute"}>
                    {minutesArr.map((i) => (
                            <option value={i} key={i}>{i}</option>
                        )
                    )}
                </select>
            </div>
        );
    }
}