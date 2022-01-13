export interface NestedObject<T> {
    children: T[];
    parent?: T;
}