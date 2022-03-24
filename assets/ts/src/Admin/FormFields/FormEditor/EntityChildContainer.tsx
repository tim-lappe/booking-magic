import * as React from "react";
import {CSSProperties} from "react";
import {Editor} from "./Editor";
import {FormEditorNode} from "../../Entity/FormEditor/FormEditorNode";
import {EditorEntity} from "./EditorEntity";
import {EditorEntityDropPosition, EntityNodeBase, EntityNodeBaseProps, EntityNodeBaseState} from "./EntityNodeBase";
import {Localization} from "../../../Localization";

interface EntityChildContainerProps extends EntityNodeBaseProps {
    additionalClassName?: string;
    style?: CSSProperties;
    children?: Element[];
    formEditor: Editor;
    formNode: FormEditorNode;
    hideAddElementsButton?: boolean;
    emptyText?: string;
}

interface EntityChildContainerState extends EntityNodeBaseState {
    formNode: FormEditorNode;
    emptyText: string;
}

export class EntityChildContainer extends EntityNodeBase<EntityChildContainerProps, EntityChildContainerState> {

    constructor(props) {
        super(props);

        this.onClickAddElement = this.onClickAddElement.bind(this);
        this.onClickContainer = this.onClickContainer.bind(this);
        this.onDragOver = this.onDragOver.bind(this);

        this.state = {
            formNode: this.props.formNode,
            isDragOver: EditorEntityDropPosition.NONE,
            isDragging: false,
            emptyText: this.props.emptyText ?? Localization.getText("This container is empty")
        }
    }

    onClickAddElement(event: any) {
        event.preventDefault();
        event.stopPropagation();
        this.props.formEditor.openAddElementWindow(this.state.formNode);
    }

    onClickContainer(event: any) {
        event.preventDefault();
    }

    onDragOver(event: React.DragEvent<HTMLDivElement>) {
        this.props.formEditor.onNodeDragOverInvoked(this, event);
        event.stopPropagation();
    }

    render() {
        return (
            <div style={this.props.style ?? {}} ref={this.entityDiv} onClick={this.onClickContainer}
                 onDragOver={this.onDragOver}
                 className={"tlbm-draggable-container tlbm-form-dragdrop-container " +
                     (this.props.additionalClassName ?? "") +
                     (this.state.isDragOver != EditorEntityDropPosition.NONE ? " tlbm-is-dragging-over-container" : "") +
                     (this.state.formNode.children.length == 0 ? " tlbm-empty-container" : "")
                }>
                {this.state.formNode.children.length == 0 ? (
                    <div className={"tlbm-form-container-empty"}>
                        {this.state.emptyText}
                    </div>
                ): null}
                {this.state.formNode.children.map((formNode) => {
                    return (
                        <EditorEntity formEntityContainer={this} key={formNode.id} formEditor={this.props.formEditor} formNode={formNode}/>
                    )
                })}
                <button style={{display: (this.props.hideAddElementsButton ? "none" : "block")}} onClick={this.onClickAddElement} className="button tlbm-button-white tlbm-button-add-element">Add Element</button>
            </div>
        );
    }
}