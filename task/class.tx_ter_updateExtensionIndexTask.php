<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2011 Kai Vogel (kai.vogel@speedprogs.de)
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

	require_once t3lib_extMgm::extPath('ter') . 'class.tx_ter_helper.php';


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
		protected $updateFileName = 'extensions.xml.gz.needsupdate';

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

				// Trigger update
			$repositoryDir = rtrim($extensionConfig['repositoryDir'], '/') . '/';
			if (!file_exists($repositoryDir . $this->updateFileName)) {
				return TRUE;
			}

				// Remove update trigger file
			unlink($repositoryDir . $this->updateFileName);

				// Write new extensions xml file
			$dummyObject = new stdClass();
			$dummyObject->repositoryDir = $repositoryDir;
			$terHelper = t3lib_div::makeInstance('tx_ter_helper', $dummyObject);
			$terHelper->writeExtensionIndexFile();

				// Clear page cache to force reload of the extension list
			$pageIds = t3lib_div::intExplode(',', $this->clearCachePages, TRUE);
			if (!empty($pageIds)) {
				$terHelper->loadBackendUser(1, '_ter_', TRUE);
				$terHelper->loadLang();
				$tce = t3lib_div::makeInstance('t3lib_TCEmain');
				$tce->admin = 1;
				$tce->start(array(), array());
				foreach ($pageIds as $pageId) {
					$tce->clear_cacheCmd($pageId);
				}
			}

			return TRUE;
		}
	}

?>