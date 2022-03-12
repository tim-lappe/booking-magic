import * as React from "react";
import {Localization} from "../../Localization";
import {DateSelect, DateSelectState} from "./DateSelect";
import {Period} from "../Entity/Period";
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
                <button onClick={this.onRemove} className={'button button-small tlbm-period-delete'}><span className={'dashicons dashicons-trash'} /></button>
            </div>
        )
    }
}