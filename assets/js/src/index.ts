import DragDrop from "./DragDrop";
import Admin from "./Admin/Admin";
import {CalendarCollector} from "./Calendar/CalendarCollector";
import {BookingStatesSettingEditor} from "./Admin/BookingStatesSetting/BookingStatesSettingEditor";

window.onload = function () {
    const dragdrop = new DragDrop();
    Admin.initAdmin();

    dragdrop.bindToDocument();

    CalendarCollector.initAllCalendars();

}