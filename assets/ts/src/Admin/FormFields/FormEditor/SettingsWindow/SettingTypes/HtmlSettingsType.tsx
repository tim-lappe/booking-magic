import {BasicSettingsTypeElement, BasicSettingsTypeElementState} from "./BasicSettingsTypeElement";
import * as React from "react";
import {HtmlEditorComponent} from "../../../HtmlEditorComponent";

export class HtmlSettingsType extends BasicSettingsTypeElement {

    onChange(value: string) {
        if (this.props.onChange != null) {
            this.props.onChange(value, this.state.value);
        }

        this.setState((prevState: BasicSettingsTypeElementState) => {
            prevState.value = value;
            return prevState;
        });
    }

    render(): JSX.Element {
        return (
            <label>
                {this.props.elementSetting.title}<br/>
                <HtmlEditorComponent width={"50vw"} minLines={20} maxLines={30} onChange={this.onChange}
                                     dataset={{value: this.state.value}}/>
            </label>
        )
    }
}