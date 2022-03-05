declare var ajax_information: any;

export class HttpRequest {

    private actions: string[] = [];

    /**
     *
     * @param action
     */
    constructor(...action: string[]) {
        this.actions.push(...action);
    }

    public send(payload: any = {}): Promise<any> {
        const xmlhttp = new XMLHttpRequest();

        let promise = this.callbackHandler(xmlhttp);
        let jsonPromse = new Promise<any>((resolve, reject) => {
            promise.then((responseText) => {
                try {
                    let data = JSON.parse(responseText);
                    resolve(data);
                } catch (e) {
                    console.log(e);
                    reject();
                }
            }).catch((e) => {
                console.log(e);
                reject()
            });
        });

        xmlhttp.open("POST", ajax_information.url, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

        xmlhttp.send(JSON.stringify({
            "actions": this.actions,
            "payload": payload
        }));

        return jsonPromse;
    }

    private callbackHandler(xmlhttp: XMLHttpRequest): Promise<string> {
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