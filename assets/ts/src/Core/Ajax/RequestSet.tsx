import {RequestCommandBase} from "./RequestCommandBase";
import {HttpRequest} from "./HttpRequest";

export class RequestSet {

    private commands: RequestCommandBase<any>[] = [];

    constructor(...commands: RequestCommandBase<any>[]) {
        this.commands.push(...commands);
    }

    public getCommand(action: string): RequestCommandBase<any> {
        for (let cmd of this.commands) {
            if(cmd.getAction() == action) {
                return cmd;
            }
        }
        return null;
    }

    public send(): Promise<{ [props: string]: RequestCommandBase<any> }> {
        let payload = {};
        let actions: string[] = [];
        for(let cmd of this.commands) {
            payload[cmd.getAction()] = cmd.getPayload();
            actions.push(cmd.getAction());
        }

        let httpRequest = new HttpRequest(...actions);
        let httpPromise = httpRequest.send(payload);
        return new Promise<{ [props: string]: RequestCommandBase<any> }>((resolve, reject) => {
            httpPromise.then((data: any) => {
                try {
                    let resolvedData: { [props: string]: RequestCommandBase<any> } = {};
                    for (const [key, value] of Object.entries(data)) {
                        let cmd = this.getCommand(key);
                        cmd.setResult(value);
                        resolvedData[key] = cmd;
                    }

                    resolve(resolvedData);
                } catch {
                    reject();
                }
            }).catch(() => reject());
        });
    }
}