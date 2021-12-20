declare const tlbm_rule_action_fields: any;

export class FormFieldRuleActionFields {

    public btnAdd: HTMLButtonElement;
    public selectField: HTMLSelectElement;
    public actionlist: HTMLElement;

    constructor(public elem: HTMLElement, public formElem: HTMLFormElement) {
        if(elem) {
            this.btnAdd = formElem.querySelector(".tlbm-add-action") as  HTMLButtonElement;
            this.btnAdd.addEventListener("click", (event) =>  {
                this.onAdd();
                event.preventDefault();
            });

            this.selectField = formElem.querySelector(".tlbm-action-select-type") as HTMLSelectElement;
            this.actionlist = formElem.querySelector(".tlbm-actions-list") as HTMLElement;
        }
    }

    public onAdd() {
        this.addElement(this.selectField.value);
    }

    public addElement(name: string) {
        let actionField = this.getActionFieldByName(name);
        if(actionField != null) {
            let newelem = null;

            let buttonup = newelem.querySelector("button.tlbm-ud-button-up");
            buttonup.addEventListener("click", (event) => {
                this.actionlist.insertBefore(newelem, newelem.previousSibling);
                event.preventDefault();
            });

            let buttondown = newelem.querySelector("button.tlbm-ud-button-down");
            buttondown.addEventListener("click", (event) => {
                this.actionlist.insertBefore(newelem, newelem.nextSibling?.nextSibling);
                event.preventDefault();
            });

            let formcontentelem = newelem.querySelector(".tlbm-action-item-form");
            formcontentelem.innerHTML = actionField.formHtml;

            let delbutton = document.createElement("button");
            delbutton.addEventListener("click", (event) => {
                newelem.remove();
                event.preventDefault();
            });

            newelem.appendChild(delbutton);

            this.actionlist.appendChild(newelem);
            return newelem;
        }
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
        const page = document.querySelector(".tlbm-admin-page") as HTMLFormElement;

        ffs.forEach(( htmlelement) => {
            new FormFieldRuleActionFields(htmlelement, page);
        });
    }
}