import $, { $body, $document } from '../constants/jquery';

export function appendOptions( $input: JQuery, options: AutocompleteOption[], onClick: OnClickHandler ): void
{

    const $optionsWrapper = $( '<ul class="autocomplete-list"></ul>' );

    options.forEach( ( option, index ) =>
    {
        $optionsWrapper.append( `<li class="autocomplete-option" data-index="${ index }">${ option.label }</li>` )
    } );

    $( 'li', $optionsWrapper ).on( 'click', ( event: Event ) =>
    {

        event.stopPropagation();

        const $this = $( event.target );

        const index = $this.data( 'index' );
        const option = options[ index ];

        $this.parents( '.autocomplete-list' ).addClass( 'faded' );
        $input.val( option.label );

        onClick( option );

    } );

    $input
        .parents( '.autocomplete-wrapper' )
        .find( '.autocomplete-list' )
        .remove()
        .end()
        .append( $optionsWrapper );

}

function handleBodyClick( event: Event ): void
{

    const $this = $( event.target );

    if ( $this.parents( '.autocomplete-wrapper' ).length ) {
        return;
    }

    $( '.autocomplete-list:visible' ).addClass( 'faded' );

}

function handleEscPress( event: KeyboardEvent ): void
{

    if ( event.code !== 'Escape' ) {
        return;
    }

    $( '.autocomplete-list:visible' ).addClass( 'faded' );

}

function showList( event: Event ): void
{

    $( event.target )
        .parents( '.autocomplete-wrapper' )
        .find( '.autocomplete-list' )
        .removeClass( 'faded' );

}

document.addEventListener( 'keyup', handleEscPress );
$body.on( 'click', handleBodyClick );
$document.on( 'focus', '.autocomplete-wrapper input', showList );

export interface AutocompleteOption
{
    label: string;
    value: any;
}

type OnClickHandler = ( option: AutocompleteOption ) => void;
