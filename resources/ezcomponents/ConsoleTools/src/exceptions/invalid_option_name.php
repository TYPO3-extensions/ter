<?php
/**
 * File containing the ezcConsoleInvalidOptionNameException.
 * 
 * @package ConsoleTools
 * @version 1.1.3
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Thrown if an invalid option name (containing whitespaces or starting with 
 * a "-") was received by {@link ezcConsoleOption::__construct}.
 *
 * @package ConsoleTools
 * @version 1.1.3
 */
class ezcConsoleInvalidOptionNameException extends ezcConsoleException
{
    function __construct( $name )
    {
        parent::__construct( "The option name <{$name}> is invalid." );
    }
}

?>
