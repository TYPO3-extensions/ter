<?php
/**
 * File containing the ezcBaseStruct.
 *
 * @package Base
 * @version 1.1.1
 * @copyright Copyright (C) 2005 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * @package Base
 * @version 1.1.1
 */
class ezcBaseStruct
{
    /**
     * Throws a BasePropertyNotFound exception.
     * @ignore
     */
    final public function __set( $name, $value )
    {
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * Throws a BasePropertyNotFound exception.
     * @ignore
     */
    final public function __get( $name )
    {
        throw new ezcBasePropertyNotFoundException( $name );
    }
}
?>
