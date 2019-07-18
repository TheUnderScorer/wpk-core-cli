import $ from '../constants/jquery';
import WPClient from '../http/WPClient';
import { appendLoader, removeLoader } from '../app/loader';

export default ( target: EventTarget, filterItems: any[], action: string ) =>
{

    let $input = $( target );
    let $form = $input.closest( 'form' );
    let $formData = new FormData( $form[ 0 ] );
    let $filtersItems = {};

    filterItems.forEach( el =>
    {
        $filtersItems[ el ] = $formData.getAll( el );
    } );

    appendLoader( $form, true );

    WPClient.post( action, $filtersItems )
        .then( ( res ) =>
        {
            let $htmlResult = $( res.result );

            console.log( $htmlResult );

            $( '.filter-row' ).remove();

            $htmlResult.insertAfter( $( '.filter-headers' ) );

            removeLoader( $form );

        } )

        .catch( ( err ) =>
        {
            console.log( err );
        } );
}

export function createFilterItem( { value, label, type }: CreateFilterItemProperties ): string
{

    return `
        <li class="row middle-md filters-list-item filters-location-item">
            <input id="${ type }_${ value }" class="nodisplay" type="checkbox" value="${ value }">
            <label class="row middle-md checkbox-label" for="${ type }_${ value }">${ label }</label>
        </li>
        `

}

export interface CreateFilterItemProperties
{
    value: string;
    label: string;
    type: string;
}
