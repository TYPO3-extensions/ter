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