import {FieldCollection} from "../FieldCollection";

declare const tlbm_constants: any;


export default class FormFieldPeriodsSelector {

    public periodtypeselect: HTMLSelectElement;
    public addperiod: HTMLButtonElement;
    public periodlist: HTMLElement;
    public periodDataInput: HTMLInputElement;

    public periodData: any;

    public isBuildingFromRead = true;

    constructor(public elem: HTMLElement, public formElem: HTMLFormElement) {
        if(elem) {
            this.periodtypeselect = elem.querySelector(".tlbm-period-select-type") as HTMLSelectElement;
            this.addperiod = elem.querySelector(".tlbm-add-period") as HTMLButtonElement;
            this.periodlist = elem.querySelector(".tlbm-periods-rules-list") as HTMLElement;
            this.periodDataInput = elem.querySelector(".tlbm-period-select-data") as HTMLInputElement;

            if(this.addperiod != null && this.periodtypeselect != null && this.periodlist != null) {
                this.addperiod.addEventListener("click", (event) => {
                    this.addPeriod({
                        "type": this.periodtypeselect.value
                    });
                    event.preventDefault();
                });

                this.formElem.addEventListener("change", () => {
                    this.updateData();
                })

                this.readData();
            }
        }
    }

    public readData() {
        let value = this.periodDataInput.getAttribute("value");
        try {
            console.log(value);
            if(value.trim().length > 0) {
                let data = JSON.parse(value);
                if (data != null) {
                    this.periodData = data;
                    for(let i = 0; i < data.length; i++) {
                        this.addPeriod(data[i], false);
                    }
                }
            }

        } catch(e) {
            console.error("Cannot read PeriodData-JSON", value, e);
        }

        this.isBuildingFromRead = false;

        this.updateData();
    }

    public updateData() {
        if(this.isBuildingFromRead) {
            return;
        }

        let periods = this.periodlist.querySelectorAll(".tlbm-period-item");

        this.periodData = [];

        periods.forEach((elem) => {
            let type = elem.getAttribute("type");

            let timeslots: any[] = [];

            elem.querySelectorAll(".tlbm-timeslots .tlbm-timeslot-item").forEach((telem) => {
                let from_hour = telem.querySelector(".tlbm-timeslot-item-from .tlbm-hour-select") as HTMLSelectElement;
                let from_minute = telem.querySelector(".tlbm-timeslot-item-from .tlbm-minute-select") as HTMLSelectElement;
                let to_hour = telem.querySelector(".tlbm-timeslot-item-to .tlbm-hour-select") as HTMLSelectElement;
                let to_minute = telem.querySelector(".tlbm-timeslot-item-to .tlbm-minute-select") as HTMLSelectElement;

                timeslots.push({
                    "from_hour": from_hour.value,
                    "from_minute": from_minute.value,
                    "to_hour": to_hour.value,
                    "to_minute": to_minute.value
                });
            });

            if(type == "date") {
                let dateRange = elem.querySelector(".tlbm-use-date-range");

                if(dateRange == null) {
                    let daySelect = elem.querySelector(".tlbm-day-select") as HTMLSelectElement;
                    let monthSelect = elem.querySelector(".tlbm-month-select") as HTMLSelectElement;
                    let yearSelect = elem.querySelector(".tlbm-year-select") as HTMLSelectElement;

                    this.periodData.push({
                        "type": type,
                        "from": {
                            "day": daySelect.value,
                            "month": monthSelect.value,
                            "year": yearSelect.value
                        },
                        "timeslots": timeslots
                    });
                } else {
                    let daySelectFrom = elem.querySelector(".tlbm-dateselect-from .tlbm-day-select") as HTMLSelectElement;
                    let monthSelectFrom = elem.querySelector(".tlbm-dateselect-from .tlbm-month-select") as HTMLSelectElement;
                    let yearSelectFrom = elem.querySelector(".tlbm-dateselect-from .tlbm-year-select") as HTMLSelectElement;
                    let daySelectTo = elem.querySelector(".tlbm-dateselect-to .tlbm-day-select") as HTMLSelectElement;
                    let monthSelectTo = elem.querySelector(".tlbm-dateselect-to .tlbm-month-select") as HTMLSelectElement;
                    let yearSelectTo = elem.querySelector(".tlbm-dateselect-to .tlbm-year-select") as HTMLSelectElement;

                    this.periodData.push({
                        "type": type,
                        "from": {
                            "day": daySelectFrom.value,
                            "month": monthSelectFrom.value,
                            "year": yearSelectFrom.value
                        },
                        "to": {
                            "day": daySelectTo.value,
                            "month": monthSelectTo.value,
                            "year": yearSelectTo.value
                        },
                        "timeslots": timeslots
                    });
                }
            } else if(type == "weekday") {
                let weekdays: number[] = [];
                elem.querySelectorAll(".tlbm-weekdays-checkboxes input[type=checkbox]").forEach((welem ) => {
                    if(welem instanceof HTMLInputElement) {
                        if (welem.checked) {
                            weekdays.push(Number.parseInt(welem.name) + 1);
                        }
                    }
                });

                let limit = null;
                let limitcheck = elem.querySelector(".tlbm-checkbox-use-limit") as HTMLInputElement;

                if(limitcheck.checked) {
                    let fromdaySelect = elem.querySelector(".tlbm-flex-date-from .tlbm-day-select") as HTMLSelectElement;
                    let frommonthSelect = elem.querySelector(".tlbm-flex-date-from .tlbm-month-select") as HTMLSelectElement;
                    let fromyearSelect = elem.querySelector(".tlbm-flex-date-from .tlbm-year-select") as HTMLSelectElement;

                    let todaySelect = elem.querySelector(".tlbm-flex-date-to .tlbm-day-select") as HTMLSelectElement;
                    let tomonthSelect = elem.querySelector(".tlbm-flex-date-to .tlbm-month-select") as HTMLSelectElement;
                    let toyearSelect = elem.querySelector(".tlbm-flex-date-to .tlbm-year-select") as HTMLSelectElement;

                    limit = {
                        "from" : {
                            "day": fromdaySelect.value,
                            "month": frommonthSelect.value,
                            "year": fromyearSelect.value
                        },
                        "to" : {
                            "day": todaySelect.value,
                            "month": tomonthSelect.value,
                            "year": toyearSelect.value
                        }
                    }
                }
                this.periodData.push({
                    "type": type,
                    "weekdays": weekdays,
                    "timeslots": timeslots,
                    "limit": limit
                });
            }
        });

        this.periodDataInput.setAttribute("value", JSON.stringify(this.periodData).replace(/"/g, '&quot;'));

        console.log(this.periodDataInput.value);
    }

    public addPeriod(data: any, overwrite_data = true) {
        let newelem = document.createElement("div");
        newelem.classList.add("tlbm-period-item");
        newelem.classList.add("tlbm-gray-container");
        newelem.setAttribute("type", data.type);

        let delbutton = document.createElement("button");
        delbutton.classList.add("button");
        delbutton.classList.add("button-small");
        delbutton.classList.add("button-timeslot-delete");
        delbutton.classList.add("tlbm-period-delete");
        delbutton.innerHTML = "<span class='dashicons dashicons-trash'></span>";
        delbutton.addEventListener("click", (event) => {
            newelem.remove();
            event.preventDefault();

            this.updateData();
        });

        let horicontent = document.createElement("div");
        horicontent.classList.add("tlbm-horizontal");

        let maindiv = document.createElement("div");
        maindiv.classList.add("tlbm-main-panel");
 
        if(data.type == "date") {
            let dtto = FieldCollection.createDateRangeSelectField(true, data, "tlbm-dateselect-from", "tlbm-dateselect-to");
            let singledt = FieldCollection.createDateSelectField(true, data.from);
            maindiv.appendChild(singledt);
            maindiv.appendChild(dtto);

            let addrangebtn = document.createElement("button");
            addrangebtn.textContent = "Add Range";
            addrangebtn.classList.add("button");
            addrangebtn.classList.add("tlbm-button-add-range");

            let addRangeFunc = () => {
                addrangebtn.style.display = "none";
                dtto.style.display = "flex";
                singledt.style.display = "none";
                dtto.classList.add("tlbm-use-date-range");

                this.updateData();
            };

            addrangebtn.addEventListener("click", (event) => {
                addRangeFunc();
                event.preventDefault();
            });

            if(data.to != null) {
                addRangeFunc();
            }

            maindiv.appendChild(addrangebtn);
            horicontent.appendChild(maindiv);
            horicontent.appendChild(this.createTimeSlotsTable(data?.timeslots, overwrite_data));

        } else if(data.type == "weekday") {
            let weekdaysdiv = document.createElement("div");
            weekdaysdiv.classList.add("tlbm-weekdays-checkboxes");
            for (const key in tlbm_constants.weekdays) {
                let checkdiv  = document.createElement("label");
                let checkWeekday = document.createElement("input");
                checkWeekday.type = "checkbox";
                checkWeekday.name = key;

                let textWeekday = document.createElement("span");
                textWeekday.innerText = tlbm_constants.weekdays[key];

                checkdiv.appendChild(checkWeekday);
                checkdiv.appendChild(textWeekday);
                weekdaysdiv.appendChild(checkdiv);
            }

            if(data.weekdays) {
                for(let i = 0; i < data.weekdays.length; i++) {
                    let check = weekdaysdiv.querySelector("input[name='" + (data.weekdays[i] - 1) + "']") as HTMLInputElement;
                    check.checked = true;
                }
            }

            maindiv.appendChild(weekdaysdiv);

            let limitdiv = document.createElement("div");
            limitdiv.style.width = "100%";

            let flexdatetime = FieldCollection.createDateRangeSelectField(true, data.limit, "tlbm-flex-date-from", "tlbm-flex-date-to");
            flexdatetime.classList.add("tlbm-flex-date-range");

            let labelcheckbox = document.createElement("label");
            let checkboxFlexDatetime = document.createElement("input") as HTMLInputElement;

            checkboxFlexDatetime.type = "checkbox";
            checkboxFlexDatetime.classList.add("tlbm-checkbox-use-limit");
            checkboxFlexDatetime.addEventListener("change", () => {
                flexdatetime.style.display = checkboxFlexDatetime.checked ? "flex" : "none";
                this.updateData();
            });

            if(data.limit) {
                checkboxFlexDatetime.checked = true;
                flexdatetime.style.display = "flex";
            }

            labelcheckbox.appendChild(checkboxFlexDatetime);
            labelcheckbox.appendChild(document.createTextNode(tlbm_constants.labels.onlyUseInTimeSpan));

            limitdiv.appendChild(document.createElement("hr"));
            limitdiv.appendChild(labelcheckbox);
            limitdiv.appendChild(flexdatetime);


            maindiv.appendChild(limitdiv);
            horicontent.appendChild(maindiv);
            horicontent.appendChild(this.createTimeSlotsTable(data?.timeslots, overwrite_data));
        }

        horicontent.querySelectorAll("button,select,input").forEach(inputelem => {
            inputelem.addEventListener("change", () => this.updateData());
            inputelem.addEventListener("click", () => this.updateData());
        });

        newelem.appendChild(delbutton);
        newelem.appendChild(horicontent);
        this.periodlist.appendChild(newelem);

        if(overwrite_data) {
            this.updateData();
        }
    }

    public createTimeSlotsTable(data: any = null, overwrite_data = true) {
        let timeslots = document.createElement("div");
        timeslots.classList.add("tlbm-timeslots");

        let timeslotslist = document.createElement("div");
        timeslotslist.classList.add("tlbm-timeslots-list");

        let btn = document.createElement("button");
        btn.classList.add("button");
        btn.innerText = tlbm_constants.labels.addTimeSlot;

        let addtimeslot = (single_data: any = null) => {
            let timeFrom = FieldCollection.createTimeSelectField(0, 0, tlbm_constants.labels.from);
            let timeTo = FieldCollection.createTimeSelectField(59, 23, tlbm_constants.labels.to);

            if(single_data) {
                timeFrom = FieldCollection.createTimeSelectField(single_data.from_minute, single_data.from_hour, tlbm_constants.labels.from);
                timeTo = FieldCollection.createTimeSelectField(single_data.to_minute, single_data.to_hour, tlbm_constants.labels.to);
            }

            let item = document.createElement("div");
            item.classList.add("tlbm-timeslot-item");

            timeFrom.classList.add("tlbm-timeslot-item-from");
            timeTo.classList.add("tlbm-timeslot-item-to");

            item.appendChild(timeFrom);
            item.appendChild(timeTo);

            let btndelete = document.createElement("button");
            btndelete.classList.add("button");
            btndelete.classList.add("button-small");
            btndelete.classList.add("button-timeslot-delete");
            btndelete.innerHTML = "<span class='dashicons dashicons-trash'></span>";
            btndelete.addEventListener("click", (event) => {
                item.remove();
                event.preventDefault();
                this.updateData();
            });


            item.appendChild(btndelete);

            timeslotslist.appendChild(item);

            if(overwrite_data) {
                this.updateData();
            }
        }

        btn.addEventListener("click", (event) => {
            addtimeslot();
            event.preventDefault();
        });

        if(data) {
            for(let i = 0; i < data.length; i++) {
                addtimeslot(data[i]);
            }
        }

        timeslots.appendChild(timeslotslist);
        timeslots.appendChild(btn);

        return timeslots;
    }

    public static attach() {
        const ffs = document.querySelectorAll(".tlbm-periods-picker") as NodeListOf<HTMLElement>;
        const wpform = document.querySelector("form#post") as HTMLFormElement;

        ffs.forEach(( htmlelement) => {
            let ff = new FormFieldPeriodsSelector(htmlelement, wpform);
        });
    }
}