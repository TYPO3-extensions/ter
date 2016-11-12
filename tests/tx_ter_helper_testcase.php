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
 * Test class for tx_ter_helper.
 *
 */

require_once (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ter').'class.tx_ter_helper.php');

class tx_ter_helper_testcase extends tx_t3unit_testcase {

	var $key = 'taken_x_y_z';
	var $freeKey = 'free_x_y_z';
	var $takenKey1 = 'takenxyz';
	var $takenKey2 = 'take_nxyz';
	var $pid = 999999;
	var $helper;
	var $tearDown = true;

	/**
	 * Setup test environment
	 *
	 * Create test extension keys into database.
	 * Create sys_page.
	 * Create dummy plugin object.
	 * Create testcase helper object.
	 */
	protected function setUp() {
		global $TYPO3_DB, $TSFE;

		$keysAndValues = array(
			'pid' => $this->pid,
			'extensionkey' => $this->key
		);
		$TYPO3_DB->exec_INSERTquery ('tx_ter_extensionkeys', $keysAndValues);

		    // Create a sys_page for TSFE
		if(!is_object($TSFE->sys_page)) {
			$TSFE->sys_page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Page\PageRepository::class);
			$TSFE->sys_page->init(true);
		}

		    // Create a dummy plugin
	    $piObject = (object) 'dummyPlugin';
	    $piObject->extensionsPID = $this->pid;

		    // Create helper class
	    $class = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstanceClassName('tx_ter_helper');
	    $this->helper = new $class($piObject);

	}

	/**
	 * Cleanup
	 *
	 * Delete test extension keys from database
	 */

	protected function tearDown() {
		global $TYPO3_DB;

		if($this->tearDown) {
			$TYPO3_DB->exec_DELETEquery('tx_ter_extensionkeys', 'pid='.intval($this->pid));
		}
	}

	/**
	 * Test function for method extensionKeyIsAvailable
	 * @return void
	 */
	public function test_extensionKeyIsAvailable() {
		self::assertFalse($this->helper->extensionKeyIsAvailable($this->key));
		self::assertFalse($this->helper->extensionKeyIsAvailable($this->takenKey1));
		self::assertFalse($this->helper->extensionKeyIsAvailable($this->takenKey2));
		self::assertTrue($this->helper->extensionKeyIsAvailable($this->freeKey));
	}
}