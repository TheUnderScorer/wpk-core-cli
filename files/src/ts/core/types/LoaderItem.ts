export default interface LoaderItem
{
    condition: number | boolean;
    module: () => Promise<any>;
}
