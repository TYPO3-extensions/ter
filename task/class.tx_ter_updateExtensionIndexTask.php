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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

require_once ExtensionManagementUtility::extPath('ter') . 'class.tx_ter_helper.php';


/**
 * Create xml file to force update of ter_fe tables
 *
 * @author Kai Vogel <kai.vogel@speedprogs.de>
 * @package TYPO3
 * @subpackage tx_ter
 */
class tx_ter_updateExtensionIndexTask extends tx_scheduler_Task {

	/**
	 * @var string
	 */
	public $clearCachePages;


	/**
	 * Public method, usually called by scheduler
	 *
	 * @return boolean TRUE on success
	 */
	public function execute() {
			// Check extension configuration
		if (empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ter'])) {
			throw new Exception('No extension configuration found in $TYPO3_CONF_VARS', 1303220916);
			return FALSE;
		}

			// Check extension repository path
		$extensionConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ter']);
		if (empty($extensionConfig['repositoryDir'])) {
			throw new Exception('No repository path found in extension configuration', 1303220917);
			return FALSE;
		}

			// Write new extensions xml file
		$repositoryDir = rtrim($extensionConfig['repositoryDir'], '/') . '/';
		$dummyObject = new stdClass();
		$dummyObject->repositoryDir = $repositoryDir;
		$terHelper = GeneralUtility::makeInstance('tx_ter_helper', $dummyObject);
		$terHelper->writeExtensionIndexFile();

			// Clear page cache to force reload of the extension list
		$pageIds = GeneralUtility::intExplode(',', $this->clearCachePages, TRUE);
		if (!empty($pageIds)) {
			$terHelper->loadBackendUser(1, '_ter_', TRUE);
			$terHelper->loadLang();
			$tce = GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
			$tce->admin = 1;
			$tce->start(array(), array());
			foreach ($pageIds as $pageId) {
				$tce->clear_cacheCmd($pageId);
			}
		}

		return TRUE;
	}
}