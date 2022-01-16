import Admin from "./Admin/Admin";
import {CalendarLoader} from "./Core/Calendar/CalendarLoader";

window.onload = function () {
    Admin.initAdmin();
    CalendarLoader.initAllCalendars();
}