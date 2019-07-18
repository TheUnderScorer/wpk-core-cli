import $ from '../../constants/jquery';
import ibCoreVars from '../../constants/vars';

/**
 * Validates form and appends errors to inputs if necessary.
 * Return boolean value as validation result.
 *
 * */
export function checkRequired( $form: JQuery ): boolean
{

    clearFormErrors( $form );

    // Determines whenever form has any errors
    let isValid = true;

    const $required = $( '.required:visible', $form );

    if ( !$required.length ) {
        return isValid;
    }

    $required.each( ( _index, item ) =>
    {

        const $item = $( item );
        const type = $item.attr( 'type' );

        // Local flag that determines if input is valid or not
        let valid = true;

        switch ( type ) {

            case 'checkbox':

                if ( !$item.is( ':checked' ) ) {
                    valid = false;
                }

                break;

            default:

                if ( !$item.val() ) {
                    valid = false;
                }

                break;

        }

        if ( !valid ) {
            isValid = false;

            let message = ibCoreVars.messages.requiredField;

            if ( $item.data( 'error' ) ) {
                message = $item.data( 'error' );
            }

            addInputError( $item, message )
        }

    } );

    return isValid;

}

/**
 * Appends error to provided input
 * */
function addInputError( $input: JQuery, message: string = '' ): void
{

    const $container = $input.parent();

    if ( message ) {
        $container.append( `
        <span class="error">${ message }</span>
        ` )
    }

    $container.addClass( 'has-error' );

}

export function clearFormErrors( $form: JQuery ): void
{

    $( '.error', $form ).remove();
    $( '.has-error', $form ).removeClass( 'has-error' );

}

export function toggleElements( $form: JQuery, status: boolean ): void
{

    const $targets = $( 'input, select, button', $form );

    $targets.attr( 'disable', !status );

}

export function fillForm( $form: JQuery, values: object, objectMap: object = null ): void
{

    for ( let prop in values ) {

        if ( !values.hasOwnProperty( prop ) ) {
            continue;
        }

        // @ts-ignore
        const value = values[ prop ];

        // @ts-ignore
        const targetID = objectMap ? objectMap[ prop ] : prop;
        const $target = $( `#${ targetID }`, $form );

        if ( $target.prop( 'tagName' ) === 'SELECT' ) {

            const $option = $( `option[value=${ value }]` );
            $option.attr( 'selected', true );

            $target.trigger( 'change', [ 'fillForm' ] );

        } else if ( $target.attr( 'type' ) === 'checkbox' ) {
            $target.trigger( 'click', [ 'fillForm' ] );
        } else {

            $target.val( value ).trigger( 'change', [ 'fillForm' ] );

        }

    }

}

export function clearForm( $form: JQuery ): void
{

    $( 'input, textarea, select', $form )
        .not( ':button, :submit, :reset, :hidden, [type="radio"], [type="checkbox"]' )
        .val( '' )
        .trigger( 'change', [ 'clearForm' ] )
        .trigger( 'wpk/clearForm' );

    $( 'input[type="radio"]:checked, input[type="checkbox"]:checked', $form ).prop( 'checked', false );

}

export function disableForm( $form: JQuery ): void
{
    $( 'input, select, textarea, button', $form ).attr( 'disabled', true );
}

export function enableForm( $form: JQuery ): void
{
    $( 'input, select, textarea, button', $form ).attr( 'disabled', false );
}
