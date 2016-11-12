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
 * SOAP server for the TYPO3 Extension Repository
 *
 * $Id$
 *
 * @author	Robert Lemke <robert@typo3.org>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_ter_pi1 extends tslib_pibase
 *   61:     function main ($content, $conf)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

require_once(ExtensionManagementUtility::extPath('ter').'class.tx_ter_api.php');

/**
 * TYPO3 Extension Repository, frontend plugin for SOAP service
 *
 * @author	Robert Lemke <robert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter
 */
class tx_ter_pi1 extends AbstractPlugin {

	public $cObj;											// Standard cObj (parent)
	public $extensionsPID;									// Start page for extension records
	public $repositoryDir;									// Absolute path to extension repository directory
	public $conf;											// The FE Plugin's TS configuration

	function main ($content, $conf) {
		global $TSFE;

		$this->pi_initPIflexForm();
		$this->conf = $conf;

		$this->extensionsPID = $conf['pid'];
		$staticConfArr = unserialize ($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ter']);
		if (is_array ($staticConfArr)) {
			$this->repositoryDir = $staticConfArr['repositoryDir'];
			if (substr ($this->repositoryDir, -1, 1) != '/') $this->repositoryDir .= '/';
		}

		try {
			$server = new SoapServer(NULL, array ('uri' => 'http://typo3.org/soap/tx_ter', "exceptions" => true));
			$server->setClass ('tx_ter_api', $this);
			$server->handle($GLOBALS['HTTP_RAW_POST_DATA']);
		} catch(tx_ter_exception $e) {
			/**
			 * @author Christian Zenker <christian.zenker@599media.de>
			 * @see http://forge.typo3.org/issues/44135
			 *
			 * SoapServer is a little nasty. When you throw a SoapFault (extends Exception) inside a soap call,
			 * you won't have any possible chance of catching and handling that exception. SoapServer will instantly
			 * return a 500 InternalServerError without any way of intervention.
			 *
			 * That's why there is an own set of exceptions defined that basically stand for a status code.
			 */
			$statusCode = 404;
			if($e instanceof tx_ter_exception_unauthorized) {
				$statusCode = 401;
			} elseif($e instanceof tx_ter_exception_notFound) {
				$statusCode = 404;
			}  elseif($e instanceof tx_ter_exception_failedDependency) {
				$statusCode = 424;
			}  elseif($e instanceof tx_ter_exception_internalServerError) {
				$statusCode = 500;
				error_log(sprintf('TER Server internal error occurred. Error message is: "%s"', $e->getMessage()));
			}
			header(' ', true, $statusCode);
			/**
			 * Using $server->fault will cause a http 500 status code to be sent which in turn
			 * will trigger the web server's error page to be shown, instead of the SOAP XML
			 * The header sent above will just be ignored.
			 * Because of that, we forge a SOAP Fault XML ourselves and just echo it.
			 *
			 * flush() to prevent SoapServer to change the status code obviously also does not work
			 * This fails when some other kind of output buffering is in place (e.g. for gzip compression)
			 * in the web server.
			 */
			// flush to prevent Soap to change the status code (does not work)
			// flush();
			// $server->fault($e->getCode(), $e->getMessage());
			$faultStringXmlTemplate = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
	<SOAP-ENV:Body>
		<SOAP-ENV:Fault>
			<faultcode>%s</faultcode>
			<faultstring>%s</faultstring>
		</SOAP-ENV:Fault>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';
			echo sprintf($faultStringXmlTemplate, $e->getCode(), $e->getMessage());
			exit;
		}
		return '';
	}
}
?>
