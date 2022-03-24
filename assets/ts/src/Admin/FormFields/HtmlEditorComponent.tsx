import * as React from "react";
import AceEditor from "react-ace";

import "ace-builds/src-noconflict/mode-html";
import "ace-builds/src-noconflict/theme-chrome";
import "ace-builds/src-noconflict/ext-language_tools"
import {Utils} from "../../Utils";

interface HtmlEditorComponentProps {
    onChange?: (value: string) => void;
    dataset?: { value?: any, name?: any };
    width?: string;
    minLines?: number;
    maxLines?: number
}

export class HtmlEditorComponent extends React.Component<HtmlEditorComponentProps, any> {


    constructor(props) {
        super(props);

        this.onChange = this.onChange.bind(this);
        this.state = {
            value: this.props.dataset != null ? Utils.decodeUriComponent(this.props.dataset?.value) : ""
        }
    }

    onChange(value: string) {
        this.setState((prevState) => {
            prevState.value = value;
            this.props.onChange?.call(this, value);
            return prevState;
        })
    }

    render() {
        return (
            <React.Fragment>
                <input type={"hidden"} name={this.props.dataset?.name} value={encodeURIComponent(this.state.value)}/>
                <AceEditor style={{minWidth: "500px"}} mode={"html"} width={this.props.width ?? "75%"}
                           showPrintMargin={false} onChange={this.onChange} minLines={this.props.minLines ?? 10}
                           maxLines={this.props.maxLines ?? 25} value={this.state.value} fontSize={14} setOptions={{
                    enableBasicAutocompletion: true,
                    enableLiveAutocompletion: true,
                    enableSnippets: false,
                    showLineNumbers: true,
                    tabSize: 2,
                }}/>
            </React.Fragment>
        );
    }
}