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

require_once (t3lib_extMgm::extPath('ter').'class.tx_ter_api.php');

/**
 * Test case for checking the TER 2.0 API directly
 *
 * @author	Elmar Hinz <elmar.hinz@team-red.net>
 */
class tx_ter_api_direct_testcase extends tx_t3unit_testcase {

	var $user = 't3unit';
	var $password = 't3unitpassword';
	var $key = 'taken_x_y_z';
	var $invalidKey1 = 'user_x_y_z';
	var $invalidKey2 = 'tx_x_y_z';
	var $invalidKey3 = 'free_X_Y_Z';
	var $freeKey = 'free_x_y_z';
	var $takenKey1 = 'takenxyz';
	var $takenKey2 = 'take_nxyz';
	var $pid = 999999;
	var $account;
	var $api;
	var $tearDown = true;

	/**
	 * Setup test environment
	 *
	 * Create test fe_users into database.
	 * Create test extension keys into database.
	 * Create sys_page.
	 * Create dummy account.
	 * Create dummy cObject.
	 * Create dummy plugin object.
	 * Create testcase api object.
	 */
	protected function setUp() {
		global $TYPO3_DB, $TSFE;

		$keysAndValues = array(
			'pid' => $this->pid,
			'username' => $this->user,
			'password' => $this->password
		);
		$TYPO3_DB->exec_INSERTquery ('fe_users', $keysAndValues);

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

		    // Create dummy Account
		$this->account = (object) 'dummyAccount';
		$this->account->username = $this->user;
		$this->account->password = $this->password;

		    // Create a dummy cObj
		$cObject = (object) 'dummyCObj';

		    // Create a dummy parent
		$pObject = (object) 'dummyParent';
		$pObject->cObject = $cObject;
		$pObject->extensionsPID = $this->pid;

			// Create api class
		$class = t3lib_div::makeInstanceClassName('tx_ter_api');
		$this->api = new $class($pObject);
	}

	/**
	 * Cleanup
	 *
	 * Delete test extension keys from database
	 * Delete test fe_user from database
	 */
	protected function tearDown() {
		global $TYPO3_DB;

	    if($this->tearDown) {
			$TYPO3_DB->exec_DELETEquery('tx_ter_extensionkeys', 'pid='.intval($this->pid));
			$TYPO3_DB->exec_DELETEquery('fe_users', 'pid='.intval($this->pid));
	    }
	}

	/**
	 * Test function for method checkExtensionKey
	 * @return void
	 */
	public function test_checkExtensionKey() {
		$resArray = $this->api->checkExtensionKey($this->account, $this->invalidKey1);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$resArray = $this->api->checkExtensionKey($this->account, $this->invalidKey2);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$resArray = $this->api->checkExtensionKey($this->account, $this->invalidKey3);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$resArray = $this->api->checkExtensionKey($this->account, $this->freeKey);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYDOESNOTEXIST);
		$resArray = $this->api->checkExtensionKey($this->account, $this->takenKey1);
		self::assertEquals((int)$resArray['resultCode'], (int)TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS);
		$resArray = $this->api->checkExtensionKey($this->account, $this->takenKey2);
		self::assertEquals((int)$resArray['resultCode'], (int)TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS);
	}

	/**
	 * Test function for method registerExtensionKey
	 * @return void
	 */
	public function test_registerExtensionKey() {
		$keyData = (object) 'dummyKeyData';
		$keyData->extensionKey = $this->invalidKey1;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$keyData->extensionKey = $this->invalidKey2;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$keyData->extensionKey = $this->invalidKey3;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYNOTVALID);
		$keyData->extensionKey = $this->freeKey;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals($resArray['resultCode'], TX_TER_RESULT_EXTENSIONKEYSUCCESSFULLYREGISTERED);
		$keyData->extensionKey = $this->takenKey1;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals((int)$resArray['resultCode'], (int)TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS);
		$keyData->extensionKey = $this->takenKey2;
		$resArray = $this->api->registerExtensionKey($this->account, $keyData);
		self::assertEquals((int)$resArray['resultCode'], (int)TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS);
	}


}