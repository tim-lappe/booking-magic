import * as React from "react";
import {Localization} from "../../../../../Localization";
import {FormElement} from "../../../../Entity/FormElement";
import {FormElementsManager} from "../FormElementsManager";
import {SelectableFormElementWindowItem} from "./SelectableFormElementWindowItem";

interface SelectFormElementWindowProps {
    formElementsManager: FormElementsManager;
    show: boolean;
    onCancel?: () => void;
    onElementSelected?: (formElement: FormElement) => void;
}

interface SelectFormElementWindowState {

}

export class SelectFormElementWindow extends React.Component<SelectFormElementWindowProps, SelectFormElementWindowState> {

    constructor(props) {
        super(props);
        this.onCancelClicked = this.onCancelClicked.bind(this);
        this.onItemClicked = this.onItemClicked.bind(this);
        this.onBackgroundClicked = this.onBackgroundClicked.bind(this);
    }

    onCancelClicked(event: any) {
        if(this.props.onCancel != null) {
            this.props.onCancel();
        }

        event.preventDefault();
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

    render() {
        let elements = this.props.formElementsManager.getFormElementsList();
        let categories = this.props.formElementsManager.getCategories();

        return (
            <div onClick={this.onBackgroundClicked} style={{display: this.props.show ? "flex" : "none"}} className={"tlbm-form-editor-select-element-window tlbm-window-outer"}>
                <div className={"tlbm-add-elements-window-inner tlbm-window-inner"}>
                    <div className={"tlbm-add-elements-top-bar"}>
                        <input className={"regular-text"} type={"text"} placeholder={Localization.__("Search...")} />
                        <button onClick={this.onCancelClicked} className={"button tlbm-cancel-button"}>{Localization.__("Cancel")}</button>
                    </div>
                    <div className={"tlbm-add-elements-list-container"}>
                        {categories.map((category) => {
                            return (
                                <div key={category} className={"tlbm-form-elements-category"}>
                                    <h3>{category}</h3>
                                    <div className={"tlbm-form-elements-list"}>
                                        {elements[category].map((item) => {
                                            return (
                                                <SelectableFormElementWindowItem key={item.unique_name} onClicked={this.onItemClicked} formElement={item} />
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

