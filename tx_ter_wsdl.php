<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
/**
 * WSDL wrapper for the TYPO3 Extension Repository
 *
 * Note: We expect that you call this script from a directory "wsdl" in the
 * site's main directory (PATH_site)
 *
 * $Id$
 *
 * @author	Robert Lemke <robert@typo3.org>
 */

error_reporting (E_ALL ^ E_NOTICE);

define('TYPO3_OS', stristr(PHP_OS,'win')&&!stristr(PHP_OS,'darwin')?'WIN':'');
define('TYPO3_MODE','FE');
define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', (php_sapi_name()=='cgi'||php_sapi_name()=='isapi' ||php_sapi_name()=='cgi-fcgi')&&($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED'])? ($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED']):($_SERVER['ORIG_SCRIPT_FILENAME']?$_SERVER['ORIG_SCRIPT_FILENAME']:$_SERVER['SCRIPT_FILENAME']))));

define('PATH_site', str_replace(array('/wsdl', '/typo3conf/ext/ter'), '', dirname(PATH_thisScript)) . '/');
define('PATH_t3lib', PATH_site.'t3lib/');
define('PATH_typo3', PATH_site.'typo3/');
define('PATH_tslib', PATH_typo3.'sysext/cms/tslib/');
define('PATH_typo3conf', PATH_site.'typo3conf/');
define('TYPO3_mainDir', 'typo3/');

require_once(PATH_t3lib.'class.t3lib_div.php');
require_once(PATH_t3lib.'class.t3lib_extmgm.php');

require_once(PATH_t3lib.'config_default.php');
if (!defined ('TYPO3_db')) 	die ('The configuration file was not included.');

$serviceLocation = t3lib_div::getIndpEnv('TYPO3_SITE_URL').'index.php?id=ter';
$WSDLSource = file_get_contents(t3lib_extMgm::extPath('ter').'tx_ter.wsdl');
$WSDLSource = trim(str_replace ('---SERVICE_LOCATION---', $serviceLocation, $WSDLSource));

if (!headers_sent()) {
	header('Content-type: text/xml');
	header('Content-Length: ' . strlen($WSDLSource));
}
echo ($WSDLSource);

?>