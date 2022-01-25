import {DateTime} from "../Adapter/DateTime";
import {MergedRuleActionsSet} from "./RuleActions/MergedRuleActionsSet";

export class CalendarBookingOptions {
    [props: string]: any;

    public bookable_slots: BookingOptionSlot[];
    private merged_actions: any[];

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);

            this.bookable_slots = [];
            for (let slot of from_data.bookable_slots) {
                this.bookable_slots.push(new BookingOptionSlot(slot));
            }
        }
    }

    public getMergedActionsForDay(dateTime: DateTime): MergedRuleActionsSet {
        let action_set = new MergedRuleActionsSet();
        action_set.dateTime = dateTime;

        for(let [key, action] of Object.entries(this.merged_actions)) {
            if(DateTime.isSameDay(new DateTime(parseInt(key)), dateTime)) {
                action_set.add(action);
            }
        }

        return action_set;
    }

    public getFreeSlotsForMinute(dateTime: DateTime): BookingOptionSlot[] {
        let slots = [];
        for(let slot of this.bookable_slots) {
            if(DateTime.isSameMinute(slot.dateTime, dateTime)) {
                slots.push(slot);
            }
        }

        return slots;
    }

    public getFreeSlotsForDay(dateTime: DateTime): BookingOptionSlot[] {
        let slots = [];
        for(let slot of this.bookable_slots) {
            if(DateTime.isSameDay(slot.dateTime, dateTime)) {
                slots.push(slot);
            }
        }

        return slots;
    }
}

class BookingOptionSlot {
    public tstamp: number;
    public dateTime: DateTime;

    constructor(from_data: any = null) {
        if (from_data) {
            Object.assign(this, from_data);
        }

        this.dateTime = new DateTime(this.tstamp);
    }
}