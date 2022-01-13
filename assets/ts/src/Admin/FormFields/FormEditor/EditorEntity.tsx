import * as React from "react";
import {EntityChildContainer} from "./EntityChildContainer";
import {EditorEntityDropPosition, EntityNodeBase, EntityNodeBaseProps, EntityNodeBaseState} from "./EntityNodeBase";

interface FormEditorElementProps extends EntityNodeBaseProps {
    formEntityContainer: EntityChildContainer;
}

interface FormEditorElementState extends EntityNodeBaseState {

}

export class EditorEntity extends EntityNodeBase<FormEditorElementProps, FormEditorElementState> {

    constructor(props) {
        super(props);

        this.onClick = this.onClick.bind(this);
        this.onDragStart = this.onDragStart.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);
        this.onDragOver = this.onDragOver.bind(this);

        this.state = {
            formNode: this.props.formNode,
            isDragging: false,
            isDragOver: EditorEntityDropPosition.NONE
        }
    }

    onClick(event: React.MouseEvent) {
        this.props.formEditor.openSettingsWindow(this.state.formNode);
        event.preventDefault();
        event.stopPropagation();
    }

    onDragStart(event: React.DragEvent<HTMLDivElement>) {
        event.stopPropagation();
        this.props.formEditor.onNodeDragStartInvoked(this, event);
    }

    onDragEnd(event: React.DragEvent<HTMLDivElement>) {
        event.stopPropagation();
        this.props.formEditor.onNodeDragEndInvoked(this, event);
    }

    onDragOver(event: React.DragEvent<HTMLDivElement>) {
        this.props.formEditor.onNodeDragOverInvoked(this, event);
        event.stopPropagation();
    }

    render() {
        return (
            <React.Fragment>
                <div ref={this.entityDiv} onDragOver={this.onDragOver} onDragStart={this.onDragStart} onDragEnd={this.onDragEnd} onClick={this.onClick}
                     className={"tlbm-draggable tlbm-form-item-container " + (this.state.isDragging ? "tlbm-dragging" : "")} draggable={true}>

                    <div className={"tlbm-drop-line-top " + (this.state.isDragOver == EditorEntityDropPosition.TOP ? "" : "tlbm-drop-line-disabled")} />
                    {this.props.formEditor.formElementsManager.createElementComponent(this.props.formEditor, this.state.formNode)}
                    <div className={"tlbm-drop-line-bottom " + (this.state.isDragOver == EditorEntityDropPosition.BOTTOM ? "" : "tlbm-drop-line-disabled")} />

                </div>
            </React.Fragment>
        )
    }
}