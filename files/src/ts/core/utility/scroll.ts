export function scrollToBottom( $element: JQuery ): void
{
    $element.scrollTop( $element.prop( 'scrollHeight' ) )
}
