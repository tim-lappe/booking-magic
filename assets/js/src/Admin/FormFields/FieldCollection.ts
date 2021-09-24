declare const tlbm_constants: any;

export class FieldCollection {

    public static createDateSelectField(everyYearOption: boolean, data: any = null) {
        let elem = document.createElement("div");
        elem.classList.add("tlbm-date-select");

        let selectDate = document.createElement("select") as HTMLSelectElement;
        selectDate.classList.add("tlbm-day-select");

        let selectMonth = document.createElement("select") as HTMLSelectElement;
        selectMonth.classList.add("tlbm-month-select");

        let selectYear = document.createElement("select") as HTMLSelectElement;
        selectYear.classList.add("tlbm-year-select");

        let updateDayOptions = () => {
            let year = selectYear.value;
            if(year.length === 0) {
                year = "2021";
            }

            let days = new Date(Number.parseInt(year), Number.parseInt(selectMonth.value), 0).getDate();
            let selected = selectDate.value;

            for (let i = selectDate.options.length - 1; i >= 0; i--) {
                selectDate.options.remove(i);
            }

            for (let i = 1; i <= days; i++) {
                selectDate.options.add(new Option(i.toString(), i.toString(), tlbm_constants.today.day == i, selected == i.toString()));
            }
        }

        for (const key in tlbm_constants.months) {
            let monthnum = parseInt(key) + 1;
            selectMonth.options.add(new Option(tlbm_constants.months[key], monthnum.toString(), tlbm_constants.today.month == monthnum, tlbm_constants.today.month == monthnum));
        }

        for(let i = 1900; i <= 2200; i++) {
            if(tlbm_constants.today.year == i) {
                if(everyYearOption) {
                    selectYear.options.add(new Option(tlbm_constants.labels.everyYear, "", true, true));
                }
            }
            selectYear.options.add(new Option(i.toString(), i.toString(), tlbm_constants.today.year == i && !everyYearOption, tlbm_constants.today.year == i && !everyYearOption));
        }


        selectMonth.addEventListener("change",  () => {
            updateDayOptions();
        });

        selectYear.addEventListener("change", () => {
            updateDayOptions();
        });

        updateDayOptions();

        if(data != null) {
            selectDate.value = data.day;
            selectMonth.value = data.month;
            selectYear.value = data.year;
        }

        elem.appendChild(selectDate);
        elem.appendChild(selectMonth);
        elem.appendChild(selectYear);

        return elem;
    }

    public static createDateRangeSelectField(everyYearOption: boolean, data: any = null, classFrom: string = "", classTo: string = "") {
        let newelem = document.createElement("div");
        newelem.style.display = "none";

        let elemfrom = document.createElement("div");
        elemfrom.classList.add(classFrom);
        elemfrom.innerText = tlbm_constants.labels.from;
        elemfrom.appendChild(FieldCollection.createDateSelectField(everyYearOption, data?.from));

        let elemto = document.createElement("div");
        elemto.classList.add(classTo);
        elemto.innerHTML = tlbm_constants.labels.to;
        elemto.appendChild(FieldCollection.createDateSelectField(everyYearOption, data?.to));

        newelem.appendChild(elemfrom);
        newelem.appendChild(elemto);
        return newelem;
    }

    public static createTimeSelectField(selectedMin = 0, selectedHour = 0, text = "") {
        let elem = document.createElement("div");
        elem.classList.add("tlbm-time-select");
        elem.appendChild(document.createTextNode(text));

        let subel = document.createElement("div");

        let selectMinutes = document.createElement("select") as HTMLSelectElement;
        selectMinutes.classList.add("tlbm-minute-select");
        for(let i = 0; i <= 59; i++) {
            selectMinutes.options.add(new Option(i.toString(), i.toString(), i == selectedMin, i == selectedMin));
        }

        let selectHour = document.createElement("select") as HTMLSelectElement;
        selectHour.classList.add("tlbm-hour-select");
        for(let i = 0; i <= 23; i++) {
            selectHour.options.add(new Option(i.toString(), i.toString(), i == selectedHour, i == selectedHour));
        }


        subel.appendChild(selectHour);
        subel.appendChild(selectMinutes);

        elem.appendChild(subel);
        return elem;
    }
}