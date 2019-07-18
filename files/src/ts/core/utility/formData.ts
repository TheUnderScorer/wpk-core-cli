export const formDataToObject = ( data: FormData ): object =>
{

    const result = {};

    data.forEach( ( value, key ) =>
    {
        // @ts-ignore
        result[ key ] = value;
    } );

    return result;

};
