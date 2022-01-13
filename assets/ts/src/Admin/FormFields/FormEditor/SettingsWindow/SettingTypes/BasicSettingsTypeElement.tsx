import * as React from "react";
import {ElementSetting} from "../../../../Entity/FormEditor/ElementSetting";

interface BasicSettingsTypeElementProps {
    onChange?: (newVal: any, oldVal: any) => void;
    elementSetting: ElementSetting;
    value?: any;
}

interface BasicSettingsTypeElementState {
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
        let newVal = event.target.value;

        if(this.props.onChange != null) {
            this.props.onChange(newVal, this.state.value);
        }

        this.setState((prevState: BasicSettingsTypeElementState) => {
            prevState.value = newVal;
            return prevState;
        });

        event.preventDefault();
    }

    render() {
        return (
            <React.Fragment>
                <label>
                    {this.props.elementSetting.title}<br />
                    <input disabled={this.props.elementSetting.readonly} onChange={this.onChange} type={"text"} value={this.state.value ?? this.props.elementSetting.default_value} />
                </label>
            </React.Fragment>
        );
    }
}