<?php

namespace UnderScorer\Core\Http;

/**
 * Get global response object
 *
 * @return Response
 */
function response(): Response
{

    static $response = null;

    if ( is_null( $response ) ) {
        $response = new Response();
    }

    return $response;

}

/**
 * Get global request object
 *
 * @return Request
 */
function request(): Request
{

    static $request = null;

    if ( is_null( $request ) ) {
        $request = Request::createFromGlobals();
    }

    return $request;

}
