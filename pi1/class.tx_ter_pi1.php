<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Robert Lemke (robert@typo3.org)
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
 * SOAP server for the TYPO3 Extension Repository
 *
 * $Id$
 *
 * @author	Robert Lemke <robert@typo3.org>
 */
 
require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('ter').'class.tx_ter_api.php');

/**
 * TYPO3 Extension Repository, frontend plugin for SOAP service
 *
 * @author	Robert Lemke <robert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter
 */
class tx_ter_pi1 extends tslib_pibase {

	public $cObj;											// Standard cObj (parent)
	public $extensionsPID;									// Start page for extension records
	public $repositoryDir;									// Absolute path to extension repository directory
	
	function main ($content, $conf) {
		global $TSFE;

		$this->pi_initPIflexForm();

		$this->extensionsPID = $conf['pid'];
		$this->repositoryDir = $conf['repositoryDir'];
	
		
		$server = new SoapServer(NULL, array ('uri' => 'http://typo3.org/soap/tx_ter'));
		$server->setClass ('tx_ter_api', $this);
		$server->handle($GLOBALS['HTTP_RAW_POST_DATA']);
		return '';
	}
}
?>