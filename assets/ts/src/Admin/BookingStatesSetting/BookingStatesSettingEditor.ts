export class BookingStatesSettingEditor {

    public nametag: string = "";
    public count: number = 0;

    constructor(public elem: HTMLTableRowElement) {
        this.nametag = elem.getAttribute("data-nametag");
        this.count = Number.parseInt(elem.getAttribute("data-count"));

        let btn = elem.querySelector("button.tlbm-add-booking-state") as HTMLButtonElement;
        btn.addEventListener("click", (event) => {
            this.addEmptyState();
            event.preventDefault();
        });

        elem.parentElement.querySelectorAll(".button-status-delete").forEach((btnelem) => {
            btnelem.addEventListener("click", (event) => {
                btnelem.parentElement.parentElement.remove();
                event.preventDefault();
            });
        });
    }

    private addEmptyState() {
        let tr = document.createElement("tr") as HTMLTableRowElement;
        tr.innerHTML = "<td>\n" +
            "<label>\n" +
            "        <input type='hidden' name=\"" + this.nametag + "[" + this.count + "][custom]\" value='true'>" +
            "        <input type=\"checkbox\" name=\"" + this.nametag + "[" + this.count + "][enabled]\" value=\"true\">\n" +
            "</label>\n" +
            "</td>\n" +
            "<td>\n" +
            "    <label>\n" +
            "        <input class='tlbm-status-name-input' required minlength='3' type=\"text\" name=\"" + this.nametag + "[" + this.count + "][name]\">\n" +
            "    </label>\n" +
            "</td>\n" +
            "<td>\n" +
            "    <label>\n" +
            "        <input type=\"text\" required class=\"regular-text tlbm-settings-table-short-input\" name=\"" + this.nametag + "[" + this.count + "][title]\">\n" +
            "    </label>\n" +
            "</td>\n" +
            "<td>\n" +
            "    <label>\n" +
            "        <input type=\"color\" class=\"tlbm-settings-table-short-input\" name=\"" + this.nametag + "[" + this.count + "][color]\">\n" +
            "    </label>\n" +
            "</td>" +
            "<td>" +
            "<a class='button-status-delete button-link-delete' href='#'><span class=\"dashicons dashicons-trash\"></span></a>" +
            "</td>"

        let delbtn = tr.querySelector("a.button-status-delete");
        if(delbtn != null) {
            delbtn.addEventListener("click", (event) => {
                tr.remove();
                event.preventDefault();
            });
        }

        let nameinput = tr.querySelector(".tlbm-status-name-input") as HTMLInputElement;
        nameinput.addEventListener("keyup", () => {
            let ok = true;
            nameinput.value = nameinput.value.toLowerCase();
            nameinput.value = nameinput.value.replace(/ /g, "_");

            this.elem.parentElement.querySelectorAll(".tlbm-status-name-input").forEach((otherinput: HTMLInputElement) => {
                if(otherinput !== nameinput) {
                    if(otherinput.value == nameinput.value) {
                        nameinput.style.background= "lightcoral";
                        nameinput.setCustomValidity("Name already exists");
                        ok = false;
                    }
                }
            });

            if(ok) {
                nameinput.style.background= "";
                nameinput.setCustomValidity("");
            }
        });

        this.elem.parentElement.insertBefore(tr, this.elem);
        this.count++;
    }

    public static init() {
        document.querySelectorAll("tr.tlbm-booking-states-edit").forEach((elem: HTMLTableRowElement) => {
            new BookingStatesSettingEditor(elem);
        });
    }
}