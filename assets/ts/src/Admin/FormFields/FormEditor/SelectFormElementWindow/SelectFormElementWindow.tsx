import * as React from "react";
import {Localization} from "../../../../Localization";
import {FormElement} from "../../../Entity/FormEditor/FormElement";
import {ElementsManager} from "../ElementsManager";
import {SelectableFormElementWindowItem} from "./SelectableFormElementWindowItem";
import {FormEditorNode} from "../../../Entity/FormEditor/FormEditorNode";
import FormEditor from "../../../Formeditor/FormEditor";
import {Editor} from "../Editor";

interface SelectFormElementWindowProps {
    formElementsManager: ElementsManager;
    formEditor: Editor;
    onCancel?: () => void;
    onElementSelected?: (formElement: FormElement) => void;
}

interface SelectFormElementWindowState {
    formNode?: FormEditorNode;
    searchString: string;
}

export class SelectFormElementWindow extends React.Component<SelectFormElementWindowProps, SelectFormElementWindowState> {

    constructor(props) {
        super(props);
        this.onCancelClicked = this.onCancelClicked.bind(this);
        this.onItemClicked = this.onItemClicked.bind(this);
        this.onBackgroundClicked = this.onBackgroundClicked.bind(this);
        this.onSearchInputChanged = this.onSearchInputChanged.bind(this);

        this.state = {
            formNode: null,
            searchString: ""
        }
    }

    onCancelClicked(event: any) {
        if(this.props.onCancel != null) {
            this.props.onCancel();
        }

        event.preventDefault();
    }

    open(formNode: FormEditorNode) {
        this.setState((prevState: SelectFormElementWindowState) => {
            prevState.formNode = formNode;
            return prevState;
        });
    }

    close() {
        this.setState((prevState: SelectFormElementWindowState) => {
            prevState.formNode = null;
            return prevState;
        });
    }

    onBackgroundClicked(event: any) {
        if(event.target.classList.contains("tlbm-form-editor-select-element-window")) {
            if (this.props.onCancel != null) {
                this.props.onCancel();
            }
        }

        event.preventDefault();
    }

    onItemClicked(formElement: FormElement) {
        this.props.onElementSelected(formElement);
    }

    onSearchInputChanged(event: any) {
        this.setState((prevState: SelectFormElementWindowState) => {
            prevState.searchString = event.target.value;
            return prevState;
        });
    }

    render() {
        let elements = this.props.formElementsManager.getFormElementsList();
        let categories = this.props.formElementsManager.getCategories();

        let filteredElements = {};
        let filteredCategories = [];

        Object.entries(elements).forEach((item) => {
            let filteredFormElements = item[1].filter((formElement) => {
                let search = this.state.searchString.toLowerCase().replace(" ", "").replace("-", "");
                let title = formElement.title.toLowerCase().replace(" ", "").replace("-", "");
                let description = formElement.description.toLowerCase().replace(" ", "").replace("-", "");

                let uniqueReadonlySettings = formElement.settings.filter((setting) => {
                    return setting.must_unique && setting.readonly;
                });

                let canAdd = true;
                if(uniqueReadonlySettings.length > 0) {
                    let setting = uniqueReadonlySettings[0];
                    canAdd = this.props.formEditor.state.rootNode.findNodesWithData(setting.name, setting.default_value).length == 0;
                }

                return canAdd && (title.indexOf(search) != -1 || description.indexOf(search) != -1);
            });
            if(filteredFormElements.length > 0) {
                filteredElements[item[0]] = filteredFormElements;
                filteredCategories.push(item[0]);
            }
        });

        return (
            <div onClick={this.onBackgroundClicked} style={{display: this.state.formNode != null ? "flex" : "none"}} className={"tlbm-form-editor-select-element-window tlbm-window-outer"}>
                <div className={"tlbm-add-elements-window-inner tlbm-window-inner"}>
                    <div className={"tlbm-add-elements-top-bar"}>
                        <input onChange={this.onSearchInputChanged} className={"regular-text"} type={"text"} placeholder={Localization.__("Search...")} />
                        <button onClick={this.onCancelClicked} className={"button tlbm-cancel-button"}>{Localization.__("Cancel")}</button>
                    </div>
                    <div className={"tlbm-add-elements-list-container"}>
                        {filteredCategories.map((category) => {
                            return (
                                <div key={category} className={"tlbm-form-elements-category"}>
                                    <h3>{category}</h3>
                                    <div className={"tlbm-form-elements-list"}>
                                        {filteredElements[category].map((item) => {
                                            let disabeld = item.only_in_root ? this.state.formNode?.parent != null : false;
                                            return (
                                                <SelectableFormElementWindowItem disabled={disabeld} key={item.unique_name} onClicked={this.onItemClicked} formElement={item} />
                                            )
                                        })}
                                    </div>
                                </div>
                            )
                        })}
                    </div>
                </div>
            </div>
        );
    }
}

