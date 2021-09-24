import FormAddElementsWindow from "./FormAddElementsWindow";
import FormStyling from "./FormStyling";
import FormElementsFactory from "./FormElementsFactory";
import FormEditorElementsSettings from "./FormEditorElementsSettings";
import FormEditorElementsSettingsWindow from "./FormEditorElementsSettingsWindow";
import FormLoader from "./FormLoader";

export default class FormEditor {

    public addElementsWindow: FormAddElementsWindow;
    public formStyling: FormStyling;
    public formEditorElementsSettings: FormEditorElementsSettings;
    public formSettingsWindow: FormEditorElementsSettingsWindow;
    public formLoader: FormLoader;

    constructor(public editorElement: HTMLElement) {
        this.addElementsWindow = new FormAddElementsWindow(this);
        this.formStyling = new FormStyling(this);
        this.formEditorElementsSettings = new FormEditorElementsSettings(this);
        this.formSettingsWindow = new FormEditorElementsSettingsWindow(this);
        this.formLoader = new FormLoader(this);
    }

    public attachEditor() {
        this.addElementsWindow.attachAddElementsWindow();
        this.formSettingsWindow.init();
        this.initAddButtons();

        let formDataInput = this.editorElement.querySelector("#tlbm-form-editor-data") as HTMLInputElement;
        this.formLoader.attachInputElement(formDataInput);

        document.addEventListener("keyup", ev => this.onKeyUp(ev));
        document.addEventListener("click", ev => this.onEditorClick(ev));
        document.addEventListener("dragupdate", ev => this.onDragUpdate(ev));
    }

    private initAddButtons() {
        this.editorElement.querySelectorAll(".tlbm-button-add-element").forEach((elem: HTMLElement) => {
            elem.onclick = (event: Event) => {
                this.addElementOnClick(event, elem as HTMLElement);
            };
        });
    }

    private addElementOnClick(event: Event, button: HTMLElement) {
        let parentcontainer = button.parentElement;
        parentcontainer = parentcontainer.querySelector(".tlbm-form-dragdrop-container");

        this.addElementsWindow.openAddElementWindow(parentcontainer);
        event.preventDefault();
    }

    private onKeyUp(event: KeyboardEvent) {
        if(event.keyCode === 46) {
            this.deleteSelectedElements();
        }
    }

    private onEditorClick(event: Event) {
        const tar = event.target as HTMLElement;
        if(!tar.classList.contains("tlbm-form-item-container")) {
            this.removeSelections();
        }
    }

    private onClickElement(event: MouseEvent) {
        let obj = event.currentTarget;
        this.onSelectionClick(event);
        event.stopPropagation();
    }

    private onSelectionClick(event: MouseEvent) {
        let obj = event.currentTarget as HTMLElement;
        let selectedCurrent = obj.classList.contains("tlbm-item-selected");

        if(!event.shiftKey && !event.ctrlKey) {
            this.removeSelections();
        }

        if(!selectedCurrent) {
            obj.classList.add("tlbm-item-selected");
        } else {
            obj.classList.remove("tlbm-item-selected");
        }
    }

    private onDragUpdate(event: Event) {
        document.dispatchEvent(new CustomEvent('formeditor_dragged_item'));
    }

    private onDoubleClickElement(event: Event) {
        this.formSettingsWindow.openSettingsWindow(event.currentTarget as HTMLElement, false);
    }

    public removeSelections() {
        let currentselected = document.querySelectorAll(".tlbm-item-selected");
        for(let i = 0; i < currentselected.length; i++) {
            currentselected[i].classList.remove("tlbm-item-selected");
        }
    }

    public deleteSelectedElements() {
        let currentselected = document.querySelectorAll(".tlbm-item-selected") as NodeListOf<HTMLElement>;
        for(let i = 0; i < currentselected.length; i++) {
            this.deleteElement(currentselected[i]);
        }
    }

    public addElementToContainer(container: HTMLElement, data_obj: any) {
        let div = FormElementsFactory.createFormItemElement();
        data_obj = FormElementsFactory.setMissingDefaultValuesToData(data_obj);

        FormElementsFactory.setFormDataToElement(div, data_obj);
        this.formEditorElementsSettings.setSettingsPrinting(div, data_obj);

        container.appendChild(div);

        div.addEventListener("click",(event) => this.onClickElement(event));
        div.addEventListener('dblclick', (event: Event) => this.onDoubleClickElement(event));

        setTimeout(function () {
            document.dispatchEvent(new CustomEvent('formeditor_add_item', { detail: { elem: div, dob: data_obj } }));
        }, 10);
        return div;
    }

    public deleteElement(elem: HTMLElement) {
        document.dispatchEvent(new CustomEvent('formeditor_delete_item', { detail: { deleted_element: elem }}));
        elem.remove();
        document.dispatchEvent(new CustomEvent('formeditor_delete_item_after', { detail: { deleted_element: elem }}));
    }

    public static formEditor: FormEditor = null;
    public static attachFormEditor(): HTMLElement {
        let formeditorelem = document.querySelector(".tlbm-form-editor") as HTMLElement;
        if(formeditorelem != null) {
            FormEditor.formEditor = new FormEditor(formeditorelem);
            FormEditor.formEditor.attachEditor();
            return formeditorelem;
        } else {
            return null;
        }
    }
}