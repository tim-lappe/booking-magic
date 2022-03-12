import * as React from "react";
import AceEditor from "react-ace";

import "ace-builds/src-noconflict/mode-html";
import "ace-builds/src-noconflict/theme-chrome";
import "ace-builds/src-noconflict/ext-language_tools"
import {Utils} from "../../Utils";

export class HtmlEditorComponent extends React.Component<any, any> {


    constructor(props) {
        super(props);

        this.onChange = this.onChange.bind(this);

        this.state = {
            value: Utils.decodeUriComponent(this.props.dataset?.value)
        }
    }

    onChange(value: string) {
        this.setState((prevState) => {
            prevState.value = value;
            return prevState;
        })
    }

    render() {
        return (
            <React.Fragment>
                <input type={"hidden"} name={this.props.dataset?.name} value={encodeURIComponent(this.state.value)}/>
                <AceEditor mode={"html"} width={"75%"} showPrintMargin={false} onChange={this.onChange} minLines={10}
                           maxLines={25} value={this.state.value} fontSize={14} setOptions={{
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