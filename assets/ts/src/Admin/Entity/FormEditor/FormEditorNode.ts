import {FormElementData} from "./FormElementData";
import {NestedObject} from "../NestedObject";

export class FormEditorNode implements NestedObject<FormEditorNode>{
    public id: number;
    public parent?: FormEditorNode;
    public formData?: FormElementData;
    public children: FormEditorNode[] = [];
    public canReceiveNewChildren: boolean = false;

    constructor() {
        this.id = Math.random();
    }

    public addNewEmptyChildNode(): FormEditorNode {
        let newNode = new FormEditorNode();
        this.addChildNode(newNode);

        return newNode;
    }

    public isParent(parentToCheck: FormEditorNode): boolean {
        if(parentToCheck != null && this.parent != null) {
            if(this.parent != parentToCheck) {
                return this.parent.isParent(parentToCheck);
            } else {
                return true;
            }
        }

        return false;
    }

    public findNodesWithData(dataKey?: string, dataValue?: any, exclude: FormEditorNode = null): FormEditorNode[] {
        let foundnodes: FormEditorNode[] = [];

        if(this.formData != null) {
            for (let [key, value] of Object.entries(this.formData)) {
                if (dataKey == null || key == dataKey) {
                    if (dataValue == null || dataValue == value) {
                        if(this != exclude) {
                            foundnodes.push(this);
                        }
                    }
                }
            }
        }

        for(let child of this.children) {
            foundnodes.push(...child.findNodesWithData(dataKey, dataValue, exclude));
        }

        return foundnodes;
    }

    public addNewChildFromData(formData: FormElementData) {
        let newNode = new FormEditorNode();
        newNode.formData = {...formData};
        this.addChildNode(newNode);
    }

    public addChildNode(formNode: FormEditorNode) {
        if(this.children.indexOf(formNode) == -1) {
            formNode.parent = this;
            this.children.push(formNode);
        }
    }

    public insertChildNode(index: number, formNode: FormEditorNode) {
        formNode.parent = this;
        this.children.splice(Math.max(0, index), 0, formNode);
    }

    public insertChildAfter(existingFormNode: FormEditorNode, newFormNode: FormEditorNode) {
        let index = this.children.indexOf(existingFormNode);
        if(index != -1) {
            console.log("Insert",newFormNode.formData, " After ", existingFormNode.formData, " Index: ", index)
            this.insertChildNode(index + 1, newFormNode);
        }
    }

    public insertChildBefore(extistingFormNode: FormEditorNode, newFormNode: FormEditorNode) {
        let index = this.children.indexOf(extistingFormNode);
        if(index != -1) {
            console.log("Insert", newFormNode.formData, " Before ", extistingFormNode.formData, " Index: ", index)
            this.insertChildNode(index, newFormNode);
        }
    }

    public removeSelfFromParent() {
        if(this.parent != null) {
            this.parent.removeChildNode(this);
        }
    }

    public removeChildNode(formNode: FormEditorNode) {
        let index = this.children.indexOf(formNode);
        if(index != -1) {
            this.children.splice(index, 1);
            formNode.parent = null;
        }
    }

}