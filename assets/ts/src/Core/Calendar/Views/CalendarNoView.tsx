import * as React from "react";
import {CalendarBaseState, CalendarComponentBase} from "./CalendarComponentBase";
import {Localization} from "../../../Localization";
import {CalendarViewSetting} from "../../Entity/CalendarViewSetting";


interface CalendarNoViewState extends CalendarBaseState{

}

export class CalendarNoView extends CalendarComponentBase<CalendarViewSetting, CalendarNoViewState> {

    render() {
        return (
            <div className={"notice notice-info"}>
                <p>{Localization.__("No calendar view loaded")}</p>
            </div>
        );
    }
}