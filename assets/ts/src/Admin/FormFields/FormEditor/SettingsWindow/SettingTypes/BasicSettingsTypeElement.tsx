import * as React from "react";
import {ElementSetting} from "../../../../Entity/FormEditor/ElementSetting";

export interface BasicSettingsTypeElementProps {
    onChange?: (newVal: any, oldVal: any) => void;
    elementSetting: ElementSetting;
    value?: any;
}

export interface BasicSettingsTypeElementState {
    value?: any;
}

export class BasicSettingsTypeElement extends React.Component<BasicSettingsTypeElementProps, BasicSettingsTypeElementState> {

    constructor(props) {
        super(props);

        this.onChange = this.onChange.bind(this);
        this.state = {
            value: this.props.value ?? ""
        }
    }

    onChange(event: any) {
        let target = event.target as HTMLInputElement;
        let newVal = target.value;

        if(target.validity.valid) {
            if (this.props.onChange != null) {
                this.props.onChange(newVal, this.state.value);
            }

            this.setState((prevState: BasicSettingsTypeElementState) => {
                prevState.value = newVal;
                return prevState;
            });
        }

        event.preventDefault();
    }

    render() {
        return (
            <label>
                {this.props.elementSetting.title}<br />
                <input maxLength={this.props.elementSetting.input_maxlength ?? 100} minLength={this.props.elementSetting.input_minlength ?? 0} pattern={this.props.elementSetting.input_regex ?? ".*"} type={this.props.elementSetting.input_type ?? "text"} disabled={this.props.elementSetting.readonly} onChange={this.onChange} value={this.state.value ?? this.props.elementSetting.default_value} />
            </label>
        );
    }
}