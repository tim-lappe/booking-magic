declare var ajax_information: any;

export class HttpRequest {
    public static Post(action: string, data: any, cb_success?: (response: any) => void, cb_error?: () => void) {
        const xmlhttp = new XMLHttpRequest();

        this.callbackHandler(xmlhttp, cb_success, cb_error);

        xmlhttp.open("POST", ajax_information.url + "?action=" + action, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.send(JSON.stringify(data));
    }

    public static Get(action: string, cb_success?: (response: any) => void, cb_error?: () => void) {
        const xmlhttp = new XMLHttpRequest();

        this.callbackHandler(xmlhttp, cb_success, cb_error);

        xmlhttp.open("GET", ajax_information.url + "?action=" + action, true);
        xmlhttp.send();
    }

    private static callbackHandler(xmlhttp: XMLHttpRequest, cb_success?: (response: any) => void, cb_error?: () => void) {
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === XMLHttpRequest.DONE) {
                if (xmlhttp.status === 0 || (xmlhttp.status >= 200 && xmlhttp.status < 400)) {
                    if (cb_success != null) {
                        cb_success(xmlhttp.responseText);
                    }
                } else {
                    if(cb_error != null) {
                        cb_error();
                    }
                }
            }
        };
    }
}