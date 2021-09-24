import FormElementsCollection from "./FormElementsCollection";
import FormEditor from "./FormEditor";

export default class FormAddElementsWindow {

    private currentTarget: HTMLElement = null;

    constructor (private editor: FormEditor) {

    }

    public attachAddElementsWindow() {
        let windowlist = this.editor.editorElement.querySelector(".tlbm-form-editor-select-element-window .tlbm-form-elements-list");
        if(windowlist !== null) {
            let element_list = FormElementsCollection.getCategorised();
            console.log("Registered Form Elements", FormElementsCollection.getCategorised());

            for (const [category, value] of Object.entries(element_list)) {
                let h3 = document.createElement("h3");
                h3.textContent = category;
                windowlist.appendChild(h3);

                for(let i = 0; i < value.formelements.length; i++) {
                    let formelem = value.formelements[i];
                    let newelem = document.createElement("div");
                    newelem.classList.add("tlbm-element-list-item");
                    newelem.setAttribute("unique_name", formelem.unique_name);
                    newelem.innerHTML = "<div class='tlbm-elem-title'>" + formelem.title + "</div><div class='tlbm-elem-description'>" + formelem.description + "</div>";
                    windowlist.appendChild(newelem);

                    let elemkey = i;
                    newelem.onclick = (elem) => {
                        elem.preventDefault();
                        let data_obj = {
                            "unique_name": newelem.getAttribute("unique_name")
                        };

                        let created_elem = this.editor.addElementToContainer(this.currentTarget, data_obj);

                        this.closeAddElementWindow();

                        setTimeout(function () {
                            document.dispatchEvent(new CustomEvent('formeditor_add_item_by_user', {detail: { elem: created_elem, data_obj: data_obj}}));
                        }, 1);
                    }
                }
            }

            let closebtn = this.editor.editorElement.querySelector(".tlbm-form-editor-select-element-window .tlbm-close-button");
            closebtn.addEventListener("click", (event) => {
                event.preventDefault();
                this.closeAddElementWindow();
            });
        }
    }

    public openAddElementWindow(target_container: HTMLElement) {
        let addelemwindow = this.editor.editorElement.querySelector(".tlbm-form-editor-select-element-window");
        if(addelemwindow != null) {
            addelemwindow.classList.remove("closed");
            this.currentTarget = target_container;
        }
    }

    public closeAddElementWindow() {
        let addelemwindow = this.editor.editorElement.querySelector(".tlbm-form-editor-select-element-window");
        if(addelemwindow !== null) {
            addelemwindow.classList.add("closed");
        }
    }
}