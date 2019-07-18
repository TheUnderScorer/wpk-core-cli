import axios, { AxiosInstance } from 'axios';
import Response from '../types/Response';
import CoreVars from '../types/CoreVars';
import * as snackbar from 'node-snackbar';

declare const ibCoreVars: CoreVars;

/**
 * @author Przemysław Żydek
 **/
export default class WPClient
{

    protected static axios: AxiosInstance = axios.create( {
        baseURL:         ibCoreVars.ajaxUrl,
        withCredentials: true,
    } );

    protected static baseResponse: Response = {
        messages:    [],
        result:      false,
        additional:  [],
        redirectUrl: '',
        error:       true,
        statusCode:  500,
    };

    public static async post( action: string, data: any = {} ): Promise<Response>
    {

        let res;

        if ( data instanceof FormData ) {
            data.append( 'action', action );
        } else {
            data = {
                ...data,
                action,
            };
        }

        try {
            res = await this.axios.post( '', new URLSearchParams( data ) );
        } catch ( error ) {

            const baseResponse = { ...this.baseResponse };

            snackbar.show( {
                text: error,
                pos:  'top-right',
            } );

            return baseResponse;

        }

        const response = {
            ...res.data,
            statusCode: res.status,
        };

        this.showErrors( response );

        return response;

    }

    public static async get( action: string, queryParams: object = {} ): Promise<Response>
    {

        queryParams = {
            ...queryParams,
            action,
        };

        let queryString = `${ ibCoreVars.ajaxUrl }?`;

        if ( Object.keys( queryParams ).length ) {
            for ( let prop in queryParams ) {

                if ( queryParams.hasOwnProperty( prop ) ) {
                    // @ts-ignore
                    queryString += `${ prop }=${ queryParams[ prop ] }&`;
                }
            }
        }

        const res = await this.axios.get( queryString );

        const response = {
            ...res.data,
            statusCode: res.status,
        };

        this.showErrors( response );

        return response;
    }

    public static showErrors( response: Response ): void
    {

        if ( !response || !response.messages ) {
            return;
        }

        let timeout = 5000;

        response.messages.forEach( ( message ) =>
        {

            if ( message.type === 'error' ) {

                if ( timeout === 5000 ) {
                    snackbar.show( {
                        text:        message.message,
                        pos:         'top-right',
                        customClass: 'error',
                    } );

                    timeout += 5000;

                } else {
                    setTimeout( () => snackbar.show( { text: message.message, pos: 'top-right' } ), timeout );

                    timeout += 5000;
                }

            }

        } );

    }

    public static handleRequestErrors( error: Error ): void
    {

        snackbar.show( {
            text:        error.message,
            pos:         'top-right',
            customClass: 'error',
        } );

    }

}
