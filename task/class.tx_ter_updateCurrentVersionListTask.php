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
 * Update json file with information of core versions
 *
 * @author Jigal van Hemert <jigal.van.hemert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter
 */
class tx_ter_updateCurrentVersionListTask extends tx_scheduler_Task {

	/**
	 * Public method, usually called by scheduler
	 *
	 * @return boolean TRUE on success
	 */
	public function execute() {
		$resultCoreData = $this->fetchCurrentCoreData();
		$resultDocsData = $this->fetchCurrentDocumentationData();
		return $resultCoreData && $resultDocsData;
	}

	/**
	 * @return boolean
	 */
	protected function fetchCurrentCoreData() {
		$result = FALSE;
		$targetFile = PATH_site . $GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'] . 'currentcoredata.json';
		$sourceData = t3lib_div::getUrl('http://get.typo3.org/json');
		if (json_decode($sourceData, TRUE) !== NULL) {
			$result = t3lib_div::writeFile($targetFile, $sourceData);
		}

		return $result;
	}

	/**
	 * @return boolean
	 */
	protected function fetchCurrentDocumentationData() {
		$result = FALSE;
		$targetFile = PATH_site . $GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'] . 'currentdocumentationdata.json';
		$sourceData = t3lib_div::getUrl('https://docs.typo3.org/typo3cms/extensions/manuals.json');
		if (json_decode($sourceData, TRUE) !== NULL) {
			$result = t3lib_div::writeFile($targetFile, $sourceData);
		}

		return $result;
	}
}
