export default class DragDrop {

    private listener_dragstart: any = null;
    private listener_dragend: any = null;
    private listener_dragover: any = null;

    constructor() {

    }

    public bindToDocument() {
        this.attach_listeners();
        this.configurate_mutation_observer();
    }

    private attach_listeners() {
        let draggables = document.querySelectorAll(".tlbm-draggable");
        let draggable_containers = document.querySelectorAll(".tlbm-draggable-container");

        draggables.forEach((draggable_elem) => {
            draggable_elem.removeEventListener("dragstart", this.listener_dragstart);

            this.listener_dragstart = (event: any) => {
                event.stopPropagation();
                draggable_elem.classList.add("tlbm-dragging");
            }

            draggable_elem.addEventListener("dragstart", this.listener_dragstart);
            draggable_elem.removeEventListener("dragend", this.listener_dragend);

            this.listener_dragend = function (event: any) {
                event.stopPropagation();
                draggable_elem.classList.remove("tlbm-dragging");
                draggable_elem.dispatchEvent(new Event("mouseout"));
                draggable_elem.dispatchEvent(new Event("mouseleave"));

                draggable_elem.dispatchEvent(new CustomEvent("dragupdate", { bubbles: true }));
            }

            draggable_elem.addEventListener("dragend", this.listener_dragend);
        });

        draggable_containers.forEach((draggable_container) => {
            draggable_container.removeEventListener("dragover", this.listener_dragover);

            this.listener_dragover = function (e: any) {
                e.preventDefault();
                let dragging_element = document.querySelector(".tlbm-dragging");

                if(dragging_element.contains(draggable_container)) {
                    return;
                }

                let child = DragDrop.getHoveredChildren(draggable_container, e.clientY);
                if(child.element !== undefined && child.element !== null)   {
                    let potentialContainer = child.element.querySelectorAll(".tlbm-draggable-container");
                    if(potentialContainer !== null && potentialContainer.length > 0) {
                        let anyin = false;
                        potentialContainer.forEach(function (potcon: any) {
                            let box = potcon.getBoundingClientRect();
                            if(e.clientY > box.top && e.clientY < box.bottom && e.clientX > box.left && e.clientX < box.right) {
                                anyin = true;
                            }
                        });

                        if(anyin) {
                            return;
                        }
                    }
                }

                if(child.element === null || child.element === undefined ) {
                    draggable_container.appendChild(dragging_element);
                } else {
                    if(child.before) {
                        if(child.element !== dragging_element.nextSibling) {
                            draggable_container.insertBefore(dragging_element, child.element);
                        }
                    } else {
                        if(child.element.nextSibling !== dragging_element.nextSibling) {
                            draggable_container.insertBefore(dragging_element, child.element.nextSibling);
                        }
                    }
                }
            };

            draggable_container.addEventListener("dragover", this.listener_dragover);
        });
    }

    private configurate_mutation_observer() {
        let targetNode = document.getElementById('wpcontent');
        let config = { attributes: false, childList: true, subtree: true };

        let lastupdate = Date.now();
        let observer = new MutationObserver((mutations) => {
            if(mutations.length === 1) {
                this.attach_listeners();
            }
        });

        if(targetNode != null) {
            observer.observe(targetNode, config);
        }
    }

    private static getHoveredChildren(container: any, ypos: any) {
        let draggableElems = container.children;

        let closest_distance = Number.NEGATIVE_INFINITY;
        let closest_child = null;
        let closest_before = true;
        let closest_inside = false;

        for(let i = 0; i < draggableElems.length; i++) {
            let child = draggableElems[i];
            if(child.classList.contains("tlbm-draggable") && !child.classList.contains("tlbm-dragging")) {
                let box = child.getBoundingClientRect();
                let offset = ypos - box.top - (box.height);
                if (offset < 0 && offset > closest_distance) {
                    closest_distance = offset;
                    closest_child = child;
                    closest_before = offset < (-box.height / 2);
                    closest_inside = offset > -box.height;
                }
            }
        }

        return {
            offset: closest_distance,
            element: closest_child,
            before: closest_before,
            is_inside: closest_inside,
        }
    }
}