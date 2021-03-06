<?php
/**
 * Autoload definition for classes in SystemInformation package.
 *
 * @package SystemInformation
 * @version 1.0.1
 * @copyright Copyright (C) 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
return array(
    'ezcSystemInfo' => 'SystemInformation/system/info.php',
    'ezcSystemInfoReader' => 'SystemInformation/system/interfaces/info_reader.php',    
    'ezcSystemInfoLinuxReader' => 'SystemInformation/system/readers/info_linux.php',
    'ezcSystemInfoFreeBsdReader' => 'SystemInformation/system/readers/info_freebsd.php',
    'ezcSystemInfoWindowsReader' => 'SystemInformation/system/readers/info_windows.php',
    'ezcSystemInfoAccelerator' => 'SystemInformation/system/structs/accelerator_info.php',
    'ezcSystemInfoReaderCantScanOSException' => 'SystemInformation/system/exceptions/cant_scan.php'
);

?>
