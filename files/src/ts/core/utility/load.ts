import LoaderItem from '../types/LoaderItem';

export default ( items: LoaderItem[] ): Promise<number> =>
{
    return new Promise( ( resolve, reject ) => handle( resolve, reject, items ) );
}

/**
 * Performs load of single module
 **/
export function maybeLoadModule( loaderItem: LoaderItem ): Promise<any>
{

    let { condition, module } = loaderItem;

    if ( !condition ) {
        return new Promise( ( _resolve, reject ) => reject() );
    }

    return module();

}

/**
 * Handles promise returned by load()
 **/
function handle( resolve: any, _reject: any, items: LoaderItem[] )
{

    const count = items.length - 1;

    let parsedModules = 0;
    let loadedItems = 0;

    const handleLoad = () =>
    {

        loadedItems++;
        parsedModules++;

        if ( parsedModules === count ) {
            resolve( loadedItems );
        }

    };

    const handleReject = () =>
    {

        parsedModules++;

        if ( parsedModules === count ) {
            resolve( loadedItems );
        }

    };

    items.forEach( ( item ) =>
    {

        maybeLoadModule( item )
            .then( handleLoad )
            .catch( handleReject );

    } );

}
