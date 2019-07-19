import $, { $body } from '../constants/jquery';
import ModalSettings from '../types/ModalSettings';
import { loadDatepicker } from './app/datePickers';

/**
 * @author Przemysław Żydek
 * */
export default class Modal
{

    protected static defaults: ModalSettings = {
        css:                 {},
        classList:           '',
        closeOnOverlayClick: true,
    };
    public readonly $modal: JQuery;
    protected settings: ModalSettings;
    protected removed: boolean = false;
    protected $overlay: JQuery;

    protected constructor( content: string | JQuery, settings: ModalSettings )
    {

        this.settings = settings;

        this.$modal = $( '<div class="modal"></div>' );
        this.$overlay = $( '<div class="modal-overlay"></div>' );

        this.$modal.append( '<div class="modal-close"><i class="icon-close"></i></div>' );

        // @ts-ignore Append content to modal
        this.$modal.append( content ).css( settings.css ).addClass( settings.classList );

        // Append modal and overlay to body
        $body.append( this.$modal ).append( this.$overlay ).addClass( 'has-overlay' );

        if ( settings.closeOnOverlayClick ) {
            this.$overlay.on( 'click', this.remove.bind( this ) );
        }

        this.$modal.find( '.modal-close i' ).on( 'click', this.remove.bind( this ) );

    }

    public static create(
        content: string | JQuery,
        {
            css = {},
            classList = '',
            closeOnOverlayClick = true,
        } = Modal.defaults ): Modal
    {

        let modal = new Modal( content, { css, classList, closeOnOverlayClick } );
        modal.show();

        return modal;

    }

    public show(): Modal
    {

        this.throwErrorIfRemoved();

        this.$modal.fadeIn();

        return this;

    }

    public remove(): void
    {

        this.throwErrorIfRemoved();

        this.$modal.fadeOut( () =>
        {

            this.$modal.remove();
            this.$overlay.remove();

            $body.removeClass( 'has-overlay' );

            this.removed = true;

        } )

    }

    public loadDatepickers(): Modal
    {

        const $datePickers = $( '.datepicker', this.$modal );

        if ( !$datePickers.length ) {
            return this;
        }

        // Load datepickers in modal, if there are any
        $datePickers.each( ( _index, picker ) => loadDatepicker( $( picker ) ) );

        return this;

    }

    protected throwErrorIfRemoved(): void
    {

        if ( this.removed ) {
            throw new Error( 'This modal was removed.' );
        }

    }

}
