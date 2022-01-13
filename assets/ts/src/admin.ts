import Admin from "./Admin/Admin";
import {CalendarCollector} from "./Calendar/CalendarCollector";

window.onload = function () {
    Admin.initAdmin();
    CalendarCollector.initAllCalendars();
}