import {PeriodTimeSlot} from "./PeriodTimeSlot";
import {Utils} from "../../Utils";

export class Period {
    public id: number = 0;
    public from_tstamp: number = 0;
    public to_tstamp: number = 0;

    public time_slots: PeriodTimeSlot[] = [];

    constructor() {
        this.from_tstamp = Utils.getUnixTimestamp();
    }
}