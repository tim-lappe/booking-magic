export default class FormFieldCalendarSelector {

    public select: HTMLSelectElement;
    public selectPanel: HTMLElement;

    constructor(private elem: HTMLElement) {
        if(elem) {
            this.select = elem.querySelector("select") as HTMLSelectElement;
            this.selectPanel = elem.querySelector(".tlbm-calendar-select-panel") as HTMLElement;

            if(this.select != null && this.selectPanel != null) {
                this.select.addEventListener("change", () => {
                    this.update();
                });

                this.update();
            }
        }
    }

    public update() {
        if(this.select.value == "all") {
            this.selectPanel.style.display = "none";
        } else {
            this.selectPanel.style.display = "block";
        }
    }

    public static attach() {
        const ffs = document.querySelectorAll(".tlbm-form-field-calendar-selector") as NodeListOf<HTMLElement>;
        ffs.forEach(( htmlelement) => {
            let ff = new FormFieldCalendarSelector(htmlelement);
        });
    }
}