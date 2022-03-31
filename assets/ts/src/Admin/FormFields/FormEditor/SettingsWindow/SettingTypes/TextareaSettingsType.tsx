import {BasicSettingsTypeElement} from "./BasicSettingsTypeElement";
import * as React from "react";

export class TextareaSettingsType extends BasicSettingsTypeElement {
    render(): JSX.Element {
        return (
            <label>
                {this.props.elementSetting.title}<br/>
                <textarea maxLength={this.props.elementSetting.input_maxlength ?? 100}
                          minLength={this.props.elementSetting.input_minlength ?? 0}
                          disabled={this.props.elementSetting.readonly} onChange={this.onChange}
                          value={this.state.value ?? this.props.elementSetting.defaultValue}>

                </textarea>
            </label>
        )
    }
}