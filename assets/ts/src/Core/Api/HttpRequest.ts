declare var ajax_information: any;

export class HttpRequest {

    public static Post(action: string, data: any): Promise<string> {
        const xmlhttp = new XMLHttpRequest();

        let promise = this.callbackHandler(xmlhttp);

        xmlhttp.open("POST", ajax_information.url + "?action=tlbm_" + action, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.send(JSON.stringify(data));

        return promise;
    }

    public static PostRequestJson(action: string, data: any): Promise<any> {
        const xmlhttp = new XMLHttpRequest();

        let promise = this.callbackHandler(xmlhttp);
        let jsonPromse = new Promise<any>((resolve, reject) => {
            promise.then((responseText) => {
                try {
                    let data = JSON.parse(responseText);
                    resolve(data);
                } catch {
                    reject();
                }
            }).catch(() => reject());
        });

        xmlhttp.open("POST", ajax_information.url + "?action=tlbm_" + action, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.send(JSON.stringify(data));

        return jsonPromse;
    }

    public static Get(action: string): Promise<string> {
        const xmlhttp = new XMLHttpRequest();

        let promise = this.callbackHandler(xmlhttp);

        xmlhttp.open("GET", ajax_information.url + "?action=tlbm_" + action, true);
        xmlhttp.send();

        return promise;
    }

    public static GetJson(action: string): Promise<any> {
        const xmlhttp = new XMLHttpRequest();

        let promise = this.callbackHandler(xmlhttp);
        let jsonPromse = new Promise<any>((resolve, reject) => {
            promise.then((responseText) => {
                try {
                    let data = JSON.parse(responseText);
                    resolve(data);
                } catch {
                    reject();
                }
            }).catch(() => reject());
        });


        xmlhttp.open("GET", ajax_information.url + "?action=tlbm_" + action, true);
        xmlhttp.send();

        return jsonPromse;
    }

    private static callbackHandler(xmlhttp: XMLHttpRequest): Promise<string> {
        return new Promise((resolve, reject) => {
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === XMLHttpRequest.DONE) {
                    if (xmlhttp.status === 0 || (xmlhttp.status >= 200 && xmlhttp.status < 400)) {
                        resolve(xmlhttp.responseText);
                    } else {
                        reject();
                    }
                }
            };
        });
    }
}