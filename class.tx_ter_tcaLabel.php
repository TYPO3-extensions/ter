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


	/**
	 * TCA label provider
	 *
	 * @author Kai Vogel <kai.vogel@speedprogs.de>
	 * @package TYPO3
	 * @subpackage tx_ter
	 */
	class tx_ter_tcaLabel {

		/**
		 * Returns an extension key by given params
		 * 
		 * @param array $params Parameters
		 * @param object $pObj Parent object reference
		 * @return string
		 */
		public function getExtensionKey(&$params, &$pObj) {
			if (empty($params['row']['uid'])) {
				return;
			}

			$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
				'extensionkey',
				'tx_ter_extensions',
				'uid=' . (int) $params['row']['uid']
			);

			if (!empty($result['extensionkey'])) {
				$params['title'] = htmlspecialchars($result['extensionkey']);
			} else {
				$params['title'] = (int) $params['row']['uid'];
			}
		}

	}
?>