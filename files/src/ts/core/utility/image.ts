export function imageUrlToBase64( url: string ): Promise<string>
{

    return new Promise( async ( resolve ) =>
    {

        const request = await fetch( url );
        const image = await request.blob();

        const reader = new FileReader();

        reader.onloadend = () =>
        {
            resolve( reader.result as string )
        };
        reader.readAsDataURL( image );

    } )

}
