export abstract class RequestCommandBase<T> {

    /**
     *
     */
    public abstract getPayload(): null;

    /**
     *
     */
    public abstract getAction(): string;

    /**
     *
     * @param data
     */
    public abstract setResult(data: any);

    /**
     *
     */
    public abstract getResult(): T;
}
