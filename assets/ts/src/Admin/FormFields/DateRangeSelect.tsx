import {DateSelect, DateSelectState} from "./DateSelect";
import * as React from "react";
import {DateTime} from "../../Core/Adapter/DateTime";
import {Localization} from "../../Localization";


interface DateRangeSelectProps {
    standalone?: boolean;
    fromDateTime?: DateTime;
    toDateTime?: DateTime;
    formName?: string;
}

interface DateRangeSelectState {
    fromDateTime: DateTime;
    toDateTime?: DateTime;
    hasDateRange: boolean;
}

export class DateRangeSelect extends React.Component<DateRangeSelectProps, DateRangeSelectState> {

    constructor(props) {
        super(props);

        this.onChangeFromDate = this.onChangeFromDate.bind(this);
        this.onChangeToDate = this.onChangeToDate.bind(this);
        this.onAddRange = this.onAddRange.bind(this);
        this.onRemoveRange = this.onRemoveRange.bind(this);

        this.state = {
            fromDateTime: this.props.fromDateTime ?? new DateTime(),
            toDateTime: this.props.toDateTime,
            hasDateRange: this.props.toDateTime != null && !DateTime.isEqual(this.props.fromDateTime, this.props.toDateTime)
        }
    }

    onChangeFromDate(dateSelectState: DateSelectState) {
        this.setState((prevState: DateRangeSelectState) => {
            prevState.fromDateTime = DateTime.copy(dateSelectState.dateTime);
            return prevState;
        });
    }

    onChangeToDate(dateSelectState: DateSelectState) {
        this.setState((prevState: DateRangeSelectState) => {
            prevState.toDateTime = DateTime.copy(dateSelectState.dateTime);
            return prevState;
        });
    }

    onAddRange(event: any) {
        this.setState((prevState: DateRangeSelectState) => {
            prevState.toDateTime = DateTime.copy(prevState.fromDateTime);
            prevState.hasDateRange = true;
            return prevState;
        });

        event.preventDefault();
    }

    onRemoveRange(event: any) {
        this.setState((prevState: DateRangeSelectState) => {
            prevState.toDateTime = null;
            prevState.hasDateRange = false;
            return prevState;
        });

        event.preventDefault();
    }


    render() {
        let input = encodeURIComponent(JSON.stringify({
            "from": this.state.fromDateTime,
            "to": this.state.hasDateRange ? this.state.toDateTime : null
        }));

        return (
            <div>
                <input type={"hidden"} name={this.props.formName} value={input} />
                {this.state.hasDateRange ? (
                    <React.Fragment>
                        <small>{Localization.getText("From")}</small>
                        <br/>
                    </React.Fragment>
                ): null}

                <DateSelect defaultDateTime={this.state.fromDateTime} allowTimeSet={true} onChange={this.onChangeFromDate} />
                    {this.state.hasDateRange ? (
                        <React.Fragment>
                            <div style={{marginTop: "0.5em"}}>
                                <small>{Localization.getText("To")}</small><br/>
                                <DateSelect defaultDateTime={this.state.toDateTime} allowTimeSet={true}
                                            onChange={this.onChangeToDate}/>
                                <div style={{marginTop: "0.5em"}}>
                                    <button onClick={this.onRemoveRange}
                                            className={"button"}>{Localization.getText("Remove Range")}</button>
                                </div>
                            </div>
                        </React.Fragment>
                        ): (
                        <div style={{marginTop: "0.5em"}}>
                            <button onClick={this.onAddRange}
                                    className={"button"}>{Localization.getText("Add Range")}</button>
                        </div>
                    )}
            </div>
        );
    }
}