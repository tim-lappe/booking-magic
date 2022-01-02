import * as React from "react";
import {Localization} from "../../../Localization";
import {DateSelect, DateSelectState} from "./DateSelect";
import {Period} from "../../Entity/Period";
import {Utils} from "../../../Utils";


interface PeriodSelectItemState {
    item: Period;
}

interface PeriodSelectItemProps {
    item?: Period;
    onChange: (item: Period) => void;
}

export class PeriodSelectItem extends React.Component<PeriodSelectItemProps, PeriodSelectItemState> {

    constructor(props) {
        super(props);

        this.onChangeEndDate = this.onChangeEndDate.bind(this);
        this.onChangeStartDate = this.onChangeStartDate.bind(this);
        this.onAddEnd = this.onAddEnd.bind(this);
        this.onRemoveEnd = this.onRemoveEnd.bind(this);

        this.state = {
            item: this.props.item ?? new Period()
        }
    }

    onChangeStartDate(dateSelect: DateSelectState) {
        this.setState((prevState: PeriodSelectItemState) => {
            let date = new Date();
            date.setTime(dateSelect.tstamp * 1000);
            if(!dateSelect.timeset) {
                date.setHours(0, 0, 0);
            }

            prevState.item.from_tstamp = Utils.getUnixTimestamp(date);

            this.props.onChange(prevState.item);
            return prevState;
        });
    }

    onChangeEndDate(dateSelect: DateSelectState) {
        this.setState((prevState: PeriodSelectItemState) => {
            let date = new Date();
            date.setTime(dateSelect.tstamp * 1000);
            if(!dateSelect.timeset) {
                date.setHours(23, 59, 59);
            }

            prevState.item.to_tstamp = Utils.getUnixTimestamp(date);

            this.props.onChange(prevState.item);
            return prevState;
        });
    }

    onRemoveEnd(event: any) {
        this.setState((prevState: PeriodSelectItemState) => {
            prevState.item.to_tstamp = 0;
            this.props.onChange(prevState.item);
            return prevState;
        });

        event.preventDefault();
    }

    onAddEnd(event: any) {
        this.setState((prevState: PeriodSelectItemState) => {
            let date = new Date();
            date.setTime(prevState.item.from_tstamp * 1000);
            date.setHours(23, 59, 59);

            prevState.item.to_tstamp = Utils.getUnixTimestamp(date);
            this.props.onChange(prevState.item);
            return prevState;
        });

        event.preventDefault();
    }

    hasEndDate(): boolean {
        return this.state.item.to_tstamp > 0;
    }

    render() {
        let currentDate = new Date();

        return (
            <div className={"tlbm-period-item tlbm-gray-container"}>
                <div className={"tlbm-period-main-panel"}>
                    <div>
                        <small>{Localization.__("Start")}</small>
                        <DateSelect defaultTstamp={this.state.item.from_tstamp} allowTimeSet={true} onChange={this.onChangeStartDate} minYear={currentDate.getFullYear()} />
                    </div>

                    {this.hasEndDate() ? (
                        <div>
                            <small>{Localization.__("End")}</small>
                            <DateSelect defaultTstamp={this.state.item.to_tstamp} allowTimeSet={true} onChange={this.onChangeEndDate} minYear={currentDate.getFullYear()} />
                        </div>
                    ): null }

                    {!this.hasEndDate() ? <button onClick={this.onAddEnd} className={"button"}>{Localization.__("Add End")}</button> : null}
                    {this.hasEndDate() ? <button onClick={this.onRemoveEnd} className={"button"}>{Localization.__("Remove End")}</button> : null}
                </div>
            </div>
        )
    }
}