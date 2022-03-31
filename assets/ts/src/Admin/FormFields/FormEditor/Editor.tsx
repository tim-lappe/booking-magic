import * as React from "react";
import {createRef} from "react";
import {Utils} from "../../../Utils";
import {FormElement} from "../../Entity/FormEditor/FormElement";
import {FormElementData} from "../../Entity/FormEditor/FormElementData";
import {ElementsManager} from "./ElementsManager";
import {SelectFormElementWindow} from "./SelectFormElementWindow/SelectFormElementWindow";
import {ElementSettingsWindow} from "./SettingsWindow/ElementSettingsWindow";
import {FormEditorNode} from "../../Entity/FormEditor/FormEditorNode";
import {EntityChildContainer} from "./EntityChildContainer";
import {EditorEntityDropPosition, EntityNodeBase, EntityNodeBaseProps, EntityNodeBaseState} from "./EntityNodeBase";
import {Localization} from "../../../Localization";

interface FormEditorProps {
    dataset: any;
}

interface FormEditorState {
    rootNode: FormEditorNode;
}

export class Editor extends React.Component<FormEditorProps, FormEditorState> {

    /**
     * Definition of Editor Form Fields
     *
     */
    public formElementsManager: ElementsManager;
    public settingsWindow = createRef<ElementSettingsWindow>();
    public addElementsWindow = createRef<SelectFormElementWindow>();
    public rootEntityChildContainer = createRef<EntityChildContainer>();

    private currentDraggingNode: EntityNodeBase<EntityNodeBaseProps, EntityNodeBaseState>;
    private currentDragOverNode: EntityNodeBase<EntityNodeBaseProps, EntityNodeBaseState>;

    constructor(props) {
        super(props);

        this.onElementFromAddElementWindowSelected = this.onElementFromAddElementWindowSelected.bind(this);
        this.onAddElementWindowClosed = this.onAddElementWindowClosed.bind(this);
        this.onSettingsWindowClosed = this.onSettingsWindowClosed.bind(this);
        this.openSettingsWindow = this.openSettingsWindow.bind(this);
        this.onSettingsWindowApplied = this.onSettingsWindowApplied.bind(this);
        this.onEditorDragLeave = this.onEditorDragLeave.bind(this);
        this.onSettingsWindowRemoveElement = this.onSettingsWindowRemoveElement.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        let data: any = JSON.parse(jsondata);

        let formfields = Utils.decodeUriComponent(props.dataset.fields);
        formfields = JSON.parse(formfields);

        if (Array.isArray(formfields)) {
            this.formElementsManager = new ElementsManager(formfields);
        } else {
            this.formElementsManager = new ElementsManager([]);
        }

        let assignedData = Utils.deepObjectAssign<FormEditorNode>(data, FormEditorNode);
        this.state = {
            rootNode: (assignedData as FormEditorNode) != null ? (assignedData as FormEditorNode) : new FormEditorNode()
        }
    }

    onEditorDragLeave(event: React.DragEvent) {
        let t = event.target as HTMLElement;
        let editorBox = t.getBoundingClientRect();
        if(event.clientY > editorBox.bottom && event.clientY < editorBox.top && event.clientX > editorBox.right && event.clientX < editorBox.left) {
            if (this.currentDragOverNode != null) {
                this.currentDragOverNode.setState((prevState: EntityNodeBaseState) => {
                    prevState.isDragOver = EditorEntityDropPosition.NONE;
                    return prevState;
                });
            }

            this.currentDragOverNode = null;
        }
    }

    onNodeDragStartInvoked(entity: EntityNodeBase<EntityNodeBaseProps, EntityNodeBaseState>, event: React.DragEvent) {
        this.currentDraggingNode = entity;
        this.currentDraggingNode.setState((prevState: EntityNodeBaseState) => {
            prevState.isDragging = true;
            return prevState;
        });
    }

    onNodeDragOverInvoked(entity: EntityNodeBase<EntityNodeBaseProps, EntityNodeBaseState>, event: React.DragEvent) {
        if(!entity.state.formNode.isParent(this.currentDraggingNode.state.formNode) && this.currentDraggingNode != entity) {
           if(this.currentDragOverNode != null) {
               this.currentDragOverNode.setState((prevState: EntityNodeBaseState) => {
                   prevState.isDragOver = EditorEntityDropPosition.NONE;
                   return prevState;
               });
           }

            this.currentDragOverNode = entity;
            entity.setState((prevState: EntityNodeBaseState) => {
                let box = entity.entityDiv.current.getBoundingClientRect();
                if (event.clientY > (box.top + (box.height / 2))) {
                    prevState.isDragOver = EditorEntityDropPosition.BOTTOM;
                } else {
                    prevState.isDragOver = EditorEntityDropPosition.TOP;
                }

                return prevState;
            });
        }
    }

    onNodeDragEndInvoked(entity: EntityNodeBase<EntityNodeBaseProps, EntityNodeBaseState>, event: React.DragEvent) {
        if(this.currentDraggingNode == entity) {
            console.log("onNodeDragEndInvoked", this.currentDragOverNode);
            if(this.currentDragOverNode != null && !this.currentDragOverNode.state.formNode.isParent(entity.state.formNode)) {
                entity.setState((prevState: EntityNodeBaseState) => {
                     prevState.isDragging = false;
                     prevState.formNode.removeSelfFromParent();
                     return prevState;
                }, () => {
                    this.currentDragOverNode.setState((prevDragOverState: EntityNodeBaseState) => {
                        if(prevDragOverState.formNode.canReceiveNewChildren) {
                            prevDragOverState.formNode.addChildNode(entity.state.formNode);
                        } else {
                            if(prevDragOverState.formNode.parent != null) {
                                if(prevDragOverState.isDragOver == EditorEntityDropPosition.TOP) {
                                    prevDragOverState.formNode.parent.insertChildBefore(prevDragOverState.formNode, entity.state.formNode);
                                } else {
                                    prevDragOverState.formNode.parent.insertChildAfter(prevDragOverState.formNode, entity.state.formNode);
                                }

                            } else {
                                this.state.rootNode.addChildNode(entity.state.formNode);
                            }
                        }

                        prevDragOverState.isDragOver = EditorEntityDropPosition.NONE;
                        return prevDragOverState;
                    }, () => {
                        this.currentDragOverNode = null;
                        this.forceUpdate();
                    });
                });
            } else {
                entity.setState((prevState: EntityNodeBaseState) => {
                    prevState.isDragging = false;
                    return prevState;
                });
            }
        }
    }

    onElementFromAddElementWindowSelected(formElement: FormElement) {
        if (!formElement.onlyInRoot || this.addElementsWindow.current.state.formNode.parent == null) {
            let formNode = this.addElementsWindow.current.state.formNode;
            let formData = this.formElementsManager.createDefaultElementData(formElement.uniqueName);
            formData.uniqueName = formElement.uniqueName;

            let formDataCopy = {...formData};
            let invalidFields = this.getInvalidFormDataFields(formData);
            let fieldsDone = 0;
            for (let fieldname of invalidFields) {
                for (let i = 2; i <= 100; i++) {
                    formData[fieldname] = formDataCopy[fieldname] + i;
                    let newInvalidFields = this.getInvalidFormDataFields(formData);
                    if (newInvalidFields.indexOf(fieldname) == -1) {
                        fieldsDone++;
                        break;
                    }
                }
            }

            if(fieldsDone == invalidFields.length) {
                formNode.addNewChildFromData(formData);
                this.addElementsWindow.current.close();
                this.forceUpdate();
            }
        }
    }

    getInvalidFormDataFields(formData: FormElementData) {
        let invalidFields = [];
        let formElement = this.formElementsManager.getFormElementByName(formData.uniqueName);

        for(let setting of formElement.settings) {
            if (setting.mustUnique) {
                if (this.state.rootNode.findNodesWithData(setting.name, formData[setting.name]).length > 0) {
                    invalidFields.push(setting.name);
                }
            }
        }

        return invalidFields;
    }

    onAddElementWindowClosed() {
        this.addElementsWindow.current.close();
    }

    openAddElementWindow(formNode: FormEditorNode) {
        this.addElementsWindow.current.open(formNode);
    }

    openSettingsWindow(formNode: FormEditorNode) {
        this.settingsWindow.current.open(formNode, this.formElementsManager.getFormElementByName(formNode.formData.uniqueName));
    }

    onSettingsWindowClosed() {
        this.settingsWindow.current.close();
    }

    onSettingsWindowApplied(formDataCopy: FormElementData, formNode: FormEditorNode) {
        this.setState((prevState: FormEditorState) => {
            Object.assign(formNode.formData, formDataCopy);
            this.settingsWindow.current.close();
            return prevState;
        });
    }

    onSettingsWindowRemoveElement(formNode: FormEditorNode) {
        this.setState((prevState: FormEditorState) => {
            formNode.removeSelfFromParent();

            this.settingsWindow.current.close();
            return prevState;
        });
    }

    render() {
        let datajson = encodeURIComponent(JSON.stringify(Utils.decycle(this.state.rootNode)));

        return (
            <div onDragLeave={this.onEditorDragLeave} className={"tlbm-form-editor"}>
                <input type={"hidden"} name={this.props.dataset.name}  value={datajson} />
                <SelectFormElementWindow formEditor={this}
                                         ref={this.addElementsWindow} onCancel={this.onAddElementWindowClosed}
                                         onElementSelected={this.onElementFromAddElementWindowSelected}
                                         formElementsManager={this.formElementsManager}/>

                <ElementSettingsWindow formEditor={this} onRemove={this.onSettingsWindowRemoveElement} ref={this.settingsWindow} onCancel={this.onSettingsWindowClosed} onApply={this.onSettingsWindowApplied}/>

                {this.state.rootNode.children.length == 0 ? (
                    <div className={"tlbm-editor-is-empty"}>
                        <span>{Localization.getText("Click the button below to add elements to this form")}</span>
                        <button onClick={(event) => {
                            this.rootEntityChildContainer.current?.onClickAddElement(event);
                            event.preventDefault();
                        }} className={"button button-primary button-large"}
                                style={{marginTop: "20px"}}>{Localization.getText("Add Element")}</button>
                    </div>
                ) : null}
                <EntityChildContainer emptyText={""} ref={this.rootEntityChildContainer} hideAddElementsButton={this.state.rootNode.children.length == 0}  additionalClassName={"tlbm-editor-root-container"} formEditor={this} formNode={this.state.rootNode} />
            </div>
        );
    }
}