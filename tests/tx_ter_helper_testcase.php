<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2005-2006 Elmar Hinz (elmar.hinz@team-red.net)
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
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test class for tx_ter_helper.
 *
 */

require_once (t3lib_extMgm::extPath('ter').'class.tx_ter_helper.php');

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
			require_once (PATH_t3lib.'class.t3lib_page.php');
			$TSFE->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
			$TSFE->sys_page->init(true);
		}
		
		    // Create a dummy plugin
	    $piObject = (object) 'dummyPlugin';
	    $piObject->extensionsPID = $this->pid;
	
		    // Create helper class
	    $class = t3lib_div::makeInstanceClassName('tx_ter_helper');
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

?>