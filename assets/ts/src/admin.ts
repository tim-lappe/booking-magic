import DragDrop from "./DragDrop";
import Admin from "./Admin/Admin";
import {CalendarCollector} from "./Calendar/CalendarCollector";

window.onload = function () {
    const dragdrop = new DragDrop();
    Admin.initAdmin();

    dragdrop.bindToDocument();

    CalendarCollector.initAllCalendars();
}