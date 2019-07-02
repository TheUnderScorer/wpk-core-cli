<?php

if ( ! function_exists( 'getFieldByKey' ) ):

    /**
     * @param string $key
     *
     * @return bool|mixed
     */
    function getFieldByKey( string $key )
    {
        return _acf_get_field_by_key( $key );
    }

endif;
