import axios from 'axios';
import $ from '../constants/jquery';

export async function reloadPartials( ...partials: PagePartial[] )
{

    const page = await axios.get( '' );
    const $page = $( page.data );

    partials.forEach( ( { newPartialSelector, $partial } ) =>
    {
        const $newPartial = $page.find( newPartialSelector );

        $partial.replaceWith( $newPartial );
    } );

}

export interface PagePartial
{
    $partial: JQuery,
    newPartialSelector: string;
}
