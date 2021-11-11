declare const tlbm_rule_action_fields: any;

export class FormFieldRuleActionFields {

    public btnAdd: HTMLButtonElement;
    public selectField: HTMLSelectElement;
    public actionlist: HTMLElement;
    public datafield: HTMLInputElement;

    public ready = false;

    constructor(public elem: HTMLElement, public formElem: HTMLFormElement) {
        if(elem) {
            this.btnAdd = formElem.querySelector(".tlbm-add-action") as  HTMLButtonElement;
            this.btnAdd.addEventListener("click", (event) =>  {
                this.onAdd();
                event.preventDefault();
            });

            this.selectField = formElem.querySelector(".tlbm-action-select-type") as HTMLSelectElement;
            this.actionlist = formElem.querySelector(".tlbm-actions-list") as HTMLElement;
            this.datafield = formElem.querySelector(".tlbm-action-select-data") as HTMLInputElement;

            this.buildFormFromData();
        }
    }

    public buildFormFromData() {
        try {
            let data = JSON.parse(this.datafield.value);
            for(const item of data) {
                let elem = this.addElement(item.actiontype);
                for (const [key, value] of Object.entries(item.values)) {
                    let iteminput = elem.querySelector("[name='" + key + "']");
                    if (iteminput instanceof HTMLInputElement || iteminput instanceof HTMLTextAreaElement || iteminput instanceof HTMLSelectElement) {
                        iteminput.value = value.toString();
                    }
                }
            }
        } catch {

        }

        this.ready = true;
        this.updateData();
    }

    public onAdd() {
        this.addElement(this.selectField.value);
    }

    public addElement(name: string) {
        let actionField = this.getActionFieldByName(name);
        if(actionField != null) {
            let newelem = document.createElement("div");
            newelem.classList.add("tlbm-action-rule-item");
            newelem.classList.add("tlbm-gray-container");
            newelem.setAttribute("actiontype", name);

            let updownbuttons = document.createElement("div");
            updownbuttons.classList.add("tlbm-up-down-buttons");

            let buttonup = document.createElement("button");
            buttonup.innerHTML = "<span class=\"dashicons dashicons-arrow-up-alt2\"></span>";
            buttonup.classList.add("tlbm-ud-button-up");
            buttonup.addEventListener("click", (event) => {
                this.actionlist.insertBefore(newelem, newelem.previousSibling);
                event.preventDefault();
                this.updateData();
            });

            let buttondown = document.createElement("button");
            buttondown.innerHTML = "<span class=\"dashicons dashicons-arrow-down-alt2\"></span>";
            buttondown.classList.add("tlbm-ud-button-down");
            buttondown.addEventListener("click", (event) => {
                this.actionlist.insertBefore(newelem, newelem.nextSibling?.nextSibling);
                event.preventDefault();
                this.updateData();
            });

            updownbuttons.appendChild(buttonup);
            updownbuttons.appendChild(buttondown);

            let formcontentelem = document.createElement("div");
            formcontentelem.classList.add("tlbm-action-item-form");
            formcontentelem.innerHTML = actionField.formHtml;

            let delbutton = document.createElement("button");
            delbutton.classList.add("button");
            delbutton.classList.add("button-small");
            delbutton.classList.add("tlbm-action-item-delete");
            delbutton.innerHTML = "<span class='dashicons dashicons-trash'></span>";
            delbutton.addEventListener("click", (event) => {
                newelem.remove();
                this.updateData();
                event.preventDefault();
            });

            newelem.appendChild(formcontentelem);
            newelem.appendChild(updownbuttons);
            newelem.appendChild(delbutton);

            let inputelems = newelem.querySelectorAll("[name]") as NodeListOf<HTMLElement>;
            inputelems.forEach((iteminput: HTMLElement) => {
                if(iteminput instanceof HTMLInputElement || iteminput instanceof HTMLTextAreaElement || iteminput instanceof HTMLSelectElement) {
                    iteminput.addEventListener("change", () => this.updateData());
                }
            });

            this.actionlist.appendChild(newelem);
            this.updateData();

            return newelem;
        }
    }

    public updateData() {
        if(!this.ready) {
            return;
        }

        let actionitems = this.elem.querySelectorAll(".tlbm-action-rule-item[actiontype]") as NodeListOf<HTMLElement>;
        let actionitemarr: any = [];
        actionitems.forEach((actionelem: HTMLElement) => {
            let inputelems = actionelem.querySelectorAll("[name]");
            let vars: any = {};
            inputelems.forEach((iteminput: HTMLElement) => {
                let value = "";
                if(iteminput instanceof HTMLInputElement || iteminput instanceof HTMLTextAreaElement || iteminput instanceof HTMLSelectElement) {
                    value = iteminput.value;
                    vars[iteminput.name] = value;
                }
            });

            actionitemarr.push({
                "actiontype" : actionelem.getAttribute("actiontype"),
                "values" : vars
            });
        });

        let parsed = JSON.stringify(actionitemarr);
        parsed = parsed.replace(/"/g, "&quot;");
        this.datafield.setAttribute("value", parsed);
    }

    public getActionFieldByName(name: string) {
        for(const actionfield of tlbm_rule_action_fields) {
            if(actionfield.key == name) {
                return actionfield;
            }
        }

        return null;
    }

    public static attach() {
        const ffs = document.querySelectorAll(".tlbm-rule-actions-field") as NodeListOf<HTMLElement>;
        const wpform = document.querySelector("form#post") as HTMLFormElement;

        ffs.forEach(( htmlelement) => {
            let ff = new FormFieldRuleActionFields(htmlelement, wpform);
        });
    }
}