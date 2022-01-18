import {DateTime} from "../DateTime";

export class CalendarBookingOptions {
    public bookable_slots: BookingOptionSlot[];

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);

            this.bookable_slots = [];
            for (let slot of from_data.bookable_slots) {
                this.bookable_slots.push(new BookingOptionSlot(slot));
            }
        }
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