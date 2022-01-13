import * as React from "react";
import {FormEditorNode} from "../../Entity/FormEditor/FormEditorNode";
import {Editor} from "./Editor";

export interface EntityNodeBaseProps {
    formNode: FormEditorNode;
    formEditor: Editor;
}

export interface EntityNodeBaseState {
    formNode: FormEditorNode;
    isDragging: boolean;
    isDragOver: EditorEntityDropPosition;
}

export enum EditorEntityDropPosition {
    TOP,
    BOTTOM,
    NONE
}


export abstract class EntityNodeBase<P extends EntityNodeBaseProps, S extends EntityNodeBaseState> extends React.Component<P, S> {

    public entityDiv = React.createRef<HTMLDivElement>();


    protected constructor(props) {
        super(props);
    }
}