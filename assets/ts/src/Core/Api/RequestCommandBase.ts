export abstract class RequestCommandBase<T> {

    /**
     *
     */
    public abstract send(): Promise<T>;

}
