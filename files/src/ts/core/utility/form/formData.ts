export const formDataToObject = ( data: FormData ): object =>
{

    const result: any = {};

    data.forEach( ( value, key ) =>
    {

        if ( key.includes( '[]' ) ) {

            if ( !result[ key ] ) {
                result[ key ] = [];
            }

            result[ key.replace( '[]', '' ) ].push( value );

        } else {
            result[ key ] = value;
        }
    } );

    return result;

};
