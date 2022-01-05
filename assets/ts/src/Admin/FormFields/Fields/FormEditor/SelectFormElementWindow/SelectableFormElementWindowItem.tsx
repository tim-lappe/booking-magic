import * as React from "react";
import {FormElement} from "../../../../Entity/FormElement";


interface SelectableItemProps {
    formElement: FormElement;
    onClicked: (formElement: FormElement) => void;
}

interface SelectableItemState {
    formElement: FormElement;
}

export class SelectableFormElementWindowItem extends React.Component<SelectableItemProps, SelectableItemState> {

    constructor(props) {
        super(props);
        this.onClick = this.onClick.bind(this);
    }

    onClick(event: any) {
        this.props.onClicked(this.props.formElement);
        event.preventDefault();
    }

    render() {
        return (
            <div onClick={this.onClick} className={"tlbm-element-list-item"}>
                <strong>{this.props.formElement.title}</strong><br />
                <div className={"tlbm-elem-description"}>
                    {this.props.formElement.description}
                </div>
            </div>
        )
    }
}