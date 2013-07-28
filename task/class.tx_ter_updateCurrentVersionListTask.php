<?php
	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2013 Jigal van Hemert (jigal.van.hemert@typo3.org)
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
			$result = FALSE;
			$targetFile = PATH_site . $GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'] . 'currentcoredata.json';
			$sourceData = t3lib_div::getUrl('http://get.typo3.org/json');
			if (json_decode($sourceData, TRUE) !== NULL) {
				$result = t3lib_div::writeFile($targetFile, $sourceData);
			}
			return $result;
		}
	}

?>
