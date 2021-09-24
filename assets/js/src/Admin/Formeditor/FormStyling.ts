import FormEditor from "./FormEditor";

export default class FormStyling {

    constructor(private editor: FormEditor) {

    }

    private attachFormStyling() {
        this.editor.editorElement.addEventListener("formeditor_add_item", function (e) {
            this.loadlisteners();
        });

        this.loadListeners();
    }

    private loadListeners() {
        let items = this.editor.editorElement.querySelectorAll(".tlbm-form-item-container");
        for(let i = 0; i < items.length; i++) {
            let item = items[i];
            let item_mouseenter = (e: any) => {
                item.classList.add("tlbm-item-mouse-over");
                e.stopPropagation();
            }
            let item_mousleave = (e: any) => {
                item.classList.remove("tlbm-item-mouse-over");
                e.stopPropagation();
            }

            items[i].removeEventListener("mouseover", item_mouseenter);
            items[i].removeEventListener("mouseout", item_mousleave);
            items[i].addEventListener("mouseover", item_mouseenter);
            items[i].addEventListener("mouseout", item_mousleave);
        }
    }
}