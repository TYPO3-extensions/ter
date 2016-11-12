#! /usr/local/bin/php -q
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
 * (Re-)builds the extension index (extensions.xml.gz) if neccessary.
 * Use this as a cron-job which is scheduled every minute or so ...
 *
 * $Id$
 *
 * @author	Robert Lemke <robert@typo3.org>
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

	// Defining circumstances for CLI mode:
define('TYPO3_cliMode', TRUE);

	// Defining PATH_thisScript here: Must be the ABSOLUTE path of this script in the right context:
	// This will work as long as the script is called by it's absolute path!
#define('PATH_thisScript',$_ENV['_']?$_ENV['_']:$_SERVER['_']);
define('PATH_thisScript',$_SERVER['SCRIPT_FILENAME']);

require(dirname(PATH_thisScript).'/conf.php');
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');

require_once ExtensionManagementUtility::extPath('ter').'class.tx_ter_helper.php';

/**
 * Dummy class which exists only because the tx_ter_helper class expects a
 * "plugin object" which is passed to the constructor. The repository directory
 * is the only content of this object.
 */
class tx_ter_buildextensionindex {

	public		$repositoryDir;

	/**
	 * Initializes our little class
	 *
	 * @result	void
	 * @access	protected
	 */
	public function __construct() {
		$staticConfArr = unserialize ($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ter']);
		if (is_array ($staticConfArr)) {
			$this->repositoryDir = $staticConfArr['repositoryDir'];
			if (substr ($this->repositoryDir, -1, 1) != '/') $this->repositoryDir .= '/';
		}
	}

}

$pluginObj = new tx_ter_buildextensionindex();

if (@is_file($pluginObj->repositoryDir.'extensions.xml.gz.needsupdate')) {
	@unlink ($pluginObj->repositoryDir.'extensions.xml.gz.needsupdate');
	$helperObj = new tx_ter_helper($pluginObj);
	$helperObj->writeExtensionIndexFile();
}

?>
