import * as React from "react";
import {CalendarComponentBase} from "./CalendarComponentBase";
import {Localization} from "../../../Localization";
import {MergedActionsRequest} from "../../Ajax/MergedActionsRequest";

interface CalendarNoViewState {

}

export class CalendarNoView extends CalendarComponentBase<CalendarNoViewState> {

    render() {
        return (
            <div className={"notice notice-info"}>
                <p>{Localization.__("No calendar view loaded")}</p>
            </div>
        );
    }

    protected prepareUpdateBookingOptions(calendarReuqest: MergedActionsRequest): MergedActionsRequest {
        return calendarReuqest;
    }
}