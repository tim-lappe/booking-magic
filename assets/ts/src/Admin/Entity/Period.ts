import {PeriodTimeRange} from "./PeriodTimeRange";
import {Utils} from "../../Utils";

export class Period {
    public id: number = 0;
    public from_tstamp: number = 0;
    public from_timeset: boolean = false;
    public to_tstamp: number = 0;
    public to_timeset: boolean = false;

    public daily_time_ranges: PeriodTimeRange[] = [];

    constructor() {
        this.from_tstamp = Utils.getUnixTimestamp();
    }
}