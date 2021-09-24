import FormEditor from "./FormEditor";

export default class FormLoader {

    public formData: any = [];
    public readyForWork: boolean = false;

    private attachedInputElement: HTMLInputElement;

    constructor(private editor: FormEditor) {

    }

    public attachInputElement(elem: HTMLInputElement) {
        if(elem !== null && elem !== undefined) {
            this.attachedInputElement = elem;
            this.readFormData();

            console.log("Form Data", this.formData);

            document.addEventListener("formeditor_add_item", (event: Event) => this.onAddElementToForm(event));
            document.addEventListener("formeditor_delete_item_after", (event: Event) => this.onDeleteElementFromForm(event));
            document.addEventListener("formeditor_dragged_item",(event: Event) =>  this.onDragElementOnForm(event));
            document.addEventListener("formeditor_item_settings_saved", (event: Event) => this.onItemSettingsSaved(event));

            this.buildFormFromData();
            this.readyForWork = true;
            this.updateFormData();
        }
    }

    private onDragElementOnForm(e: Event) {
        this.updateFormData();
    }

    private onItemSettingsSaved(e: Event) {
        this.updateFormData();
    }

    private onAddElementToForm(e: Event) {
        this.updateFormData();
    }

    private onDeleteElementFromForm(e: Event) {
        this.updateFormData();
    }

    private _updateFormDataRecurisve(formDataArr: any, currentElement: HTMLElement) {
        let formdata = currentElement.getAttribute("form-data");
        const formdata_obj = JSON.parse(formdata);

        let firstdd = currentElement.querySelector(".tlbm-form-dragdrop-container");
        if(firstdd != null) {
            formdata_obj.children = [];
            for (let i = 0; i < firstdd.parentNode.children.length; i++) {
                formdata_obj.children.push([]);

                let container = firstdd.parentNode.children[i];
                let container_children = container.querySelectorAll(":scope > .tlbm-form-item-container") as NodeListOf<HTMLElement>;

                for(let j = 0; j < container_children.length; j++) {
                    this._updateFormDataRecurisve(formdata_obj.children[i], container_children[j]);
                }
            }
        }

        formDataArr.push(formdata_obj);
    }

    public updateFormData() {
        if(this.readyForWork) {
            let formeditor = document.querySelector(".tlbm-form-editor .tlbm-form-dragdrop-container");
            this.formData = [];

            if (formeditor != null) {
                let allitems = formeditor.querySelectorAll(":scope > .tlbm-form-item-container") as NodeListOf<HTMLElement>;
                for (let i = 0; i < allitems.length; i++) {
                    this._updateFormDataRecurisve(this.formData, allitems[i]);
                }
            }

            this.attachedInputElement.value = JSON.stringify(this.formData).replace(/"/g, '&quot;');
        }
    }


    private _buildFormFromDataRecursive(currentDataObj: any, container: HTMLElement) {
        let added_elem = this.editor.addElementToContainer(container, currentDataObj);
        if(currentDataObj.children != null) {
            let firstdd = added_elem.querySelector(".tlbm-form-dragdrop-container") as HTMLElement;
            if(firstdd != null) {
                for (let i = 0; i < firstdd.parentNode.children.length; i++) {
                    let childcontainer = firstdd.parentNode.children[i] as HTMLElement;
                    for (let j = 0; j < currentDataObj.children[i].length; j++) {
                        this._buildFormFromDataRecursive(currentDataObj.children[i][j], childcontainer);
                    }
                }
            }
        }
    }

    public buildFormFromData() {
        if(this.formData != null) {
            let container = document.querySelector(".tlbm-main-form-container .tlbm-draggable-container") as HTMLElement;
            for(let i = 0; i < this.formData.length; i++) {
                if (this.formData[i] != null) {
                    this._buildFormFromDataRecursive(this.formData[i], container);
                }
            }
        }
    }

    public readFormData() {
        let value = this.attachedInputElement.getAttribute("value");
        try {
            value.replace(/&quot;/g, '\"');
            if(value.trim().length > 0) {
                let data = JSON.parse(value);
                if (data != null) {
                    this.formData = data;
                }
            } else {
                console.log("Form Data is empty");
            }
        } catch {
            console.error("Cannot read FormData-JSON", value);
        }
    }
}