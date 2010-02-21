<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Robert Lemke (robert@typo3.org)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * WSDL wrapper for the TYPO3 Extension Repository
 * 
 * Note: We expect that you call this script from a directory "wsdl" in the
 * site's main directory (PATH_site)
 *
 * $Id: tx_ter_wsdl.php 3801 2006-10-01 08:32:41Z sebastian $
 *
 * @author	Robert Lemke <robert@typo3.org>
 */
 
error_reporting (E_ALL ^ E_NOTICE);

define('TYPO3_OS', stristr(PHP_OS,'win')&&!stristr(PHP_OS,'darwin')?'WIN':'');
define('TYPO3_MODE','FE');
define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', (php_sapi_name()=='cgi'||php_sapi_name()=='isapi' ||php_sapi_name()=='cgi-fcgi')&&($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED'])? ($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED']):($_SERVER['ORIG_SCRIPT_FILENAME']?$_SERVER['ORIG_SCRIPT_FILENAME']:$_SERVER['SCRIPT_FILENAME']))));

define('PATH_site', str_replace ('/wsdl', '', dirname(PATH_thisScript)).'/');
define('PATH_t3lib', PATH_site.'t3lib/');
define('PATH_tslib', PATH_site.'tslib/');
define('PATH_typo3conf', PATH_site.'typo3conf/');
define('TYPO3_mainDir', 'typo3/');

require_once(PATH_t3lib.'class.t3lib_div.php');
require_once(PATH_t3lib.'class.t3lib_extmgm.php');

require_once(PATH_t3lib.'config_default.php');
if (!defined ('TYPO3_db')) 	die ('The configuration file was not included.');

$serviceLocation = t3lib_div::getIndpEnv('TYPO3_SITE_URL').'index.php?id=ter';
$WSDLSource = file_get_contents(t3lib_extMgm::extPath('ter').'tx_ter.wsdl');
$WSDLSource = str_replace ('---SERVICE_LOCATION---', $serviceLocation, $WSDLSource);

header('Content-type: text/xml');
echo ($WSDLSource);

?>