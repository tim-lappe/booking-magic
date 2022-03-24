import * as React from "react";
import {BasicEditorElement} from "./BasicEditorElement";
import {ColumnFormElement} from "../../../Entity/FormEditor/ColumnFormElement";
import {EntityChildContainer} from "../EntityChildContainer";
import {Localization} from "../../../../Localization";

export class ColumnsElement<T extends ColumnFormElement> extends BasicEditorElement<T> {

    constructor(props) {
        super(props);
    }

    componentDidMount() {
        let columnElement = this.state.formElement;
        if(this.state.formNode.children.length != columnElement.columns) {
            this.state.formNode.children = [];
            for(let i = 0; i < columnElement.columns; i++) {
                let newNode = this.state.formNode.addNewEmptyChildNode();
                newNode.canReceiveNewChildren = true;
            }
        }

        this.forceUpdate();
    }

    render(): JSX.Element {
        let columnComponents = [];

        for (let c = 0; c < this.state.formNode.children.length; c++) {
            let grow = this.state.formNode.formData["split_" + (c + 1)] ?? 1;
            columnComponents.push((

                <EntityChildContainer
                    emptyText={Localization.getText("This column is empty")}
                    formEditor={this.props.formEditor}
                    formNode={this.state.formNode.children[c]}
                    key={c} style={{flexGrow: grow}} additionalClassName={'tlbm-form-column tlbm-form-column-number-' + c} />
            ));
        }

        return (
            <div className='tlbm-form-item-columns'>
                {columnComponents}
            </div>
        );
    }
}