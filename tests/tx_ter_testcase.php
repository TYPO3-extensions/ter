<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Robert Lemke (robert@typo3.org)
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
 * Test case for checking the TER 2.0 API via SOAP.
 *
 * Notes: 
 *    
 *    - Most of the tests assume that there exists a FE user "t3unit"
 *      with password "t3unitpassword"
 * 
 *    - Although the tx_ter_api takes the PID of extension keys and
 *      extensions into account, these tests don't. Just make sure that
 *      only one repository exists in your site database.
 *
 * @author	Robert Lemke <robert@typo3.org>
 */


class tx_ter_testcase extends tx_t3unit_testcase {

	protected $WSDLURI;
	protected $SOAPServiceURI;

	public function __construct ($name) {
		parent::__construct ($name);

		$this->WSDLURI = t3lib_div::getIndpEnv('TYPO3_SITE_URL').t3lib_extMgm::siteRelPath('ter').'tx_ter.wsdl';
		$this->SOAPServiceURI = t3lib_div::getIndpEnv('TYPO3_SITE_URL').'index.php?id=ter';
	}





	/*********************************************************
	 *
	 * BASIC TESTS
	 *
	 *********************************************************/

	public function test_soapBasicWithoutWSDL() {
		$soapClientObj = new SoapClient (
			NULL,
			array (
				'location' => $this->SOAPServiceURI,
				'uri' => 'urn:examples:helloservice'
			)
		);
		try {
			$result = $soapClientObj->__call ('ping', array ('dummy'));
		} catch (SOAPFault $exception) {
			debug ($exception);			
		}
		self::assertEquals ($result, 'pongdummy', 'Basic SOAP server ping check without using WSDL definition failed.');
	}

	public function test_soapBasicWithWSDL() {

		try {
			$soapClientObj = new SoapClient ($this->WSDLURI);
		} catch (SOAPFault $exception) {
			self::fail ('Reading the WSDL definition ('.$this->WSDLURI.') throwed an exception: '.$exception->faultstring);
		}

		try {
			$result = $soapClientObj->ping ('dummy');			
			self::assertEquals ($result, 'pongdummy', 'Basic SOAP server ping check WITH using WSDL definition failed.');
		} catch (SOAPFault $exception) {
			self::fail ('Basic SOAP server ping check WITH using the WSDL definition ('.$this->WSDLURI.') throwed an exception: '.$exception->faultstring);
		}

	}





	/*********************************************************
	 *
	 * EXTENSION UPLOAD TESTS
	 *
	 * The following tests check the upload function of the 
	 * TER API. We use a serialized array as a fixture which 
	 * originally was created by the Extension Manager for 
	 * TER < 2.0.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword" being the owner of the 
	 *       extension "nothing" where the upload password is 
	 *       "t3unituploadpassword"
	 *
	 *********************************************************/

	public function test_uploadExtension_withValidData() {
		global $TYPO3_DB;
		
		$this->createFixture_uploadExtension (&$accountData, &$extensionData, &$filesData);
		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- TEST A VALID EXTENSION UPLOAD ---------------------------------------------------------------
		try {
			$result = $soapClientObj->uploadExtension (
				$accountData,
				$extensionData,
				$filesData
			);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
	
		self::assertTrue (is_array ($result) && ($result['resultCode'] == 10504), 'Upload of extension was not successful (result: '.$result['resultCode'].')');
		
			// --- CHECK THE DATABASE DIRECTLY IF UPLOAD WAS STORED CORRECTLY -----------------------------------
		$res = $TYPO3_DB->exec_SELECTquery (
			'uid,version,title,description,state,category,ismanualincluded',
			'tx_ter_extensions',
			'extensionkey="'.$extensionData['extensionKey'].'" AND version="'.$result['version'].'"'
		);
		if (!$res) self::fail ('No MySQL result while checking if extension was correctly inserted into the DB');
		$extensionsRow = $TYPO3_DB->sql_fetch_assoc ($res);
		$extensionsRowCheck = (
			$extensionsRow['title'] == $extensionData['metaData']['title'] &&
			$extensionsRow['description'] == $extensionData['metaData']['description'] &&
			$extensionsRow['state'] == $extensionData['metaData']['state'] &&
			$extensionsRow['category'] == $extensionData['metaData']['category'] &&
			$extensionsRow['ismanualincluded'] == $extensionData['technicalData']['isManualIncluded'] 
		);
		
		self::assertTrue ($extensionsRowCheck, 'Row of table "tx_ter_extensions" does not contain the uploaded extension data!');

		$res = $TYPO3_DB->exec_SELECTquery (
			'*',			
			'tx_ter_extensiondetails',
			'extensionuid='.$extensionsRow['uid']
		);

		if (!$res) self::fail ('No MySQL result while checking if extension details were correctly inserted into the DB');
		$extensionDetailsRow = $TYPO3_DB->sql_fetch_assoc ($res);
		$extensionDetailsRowCheck = (
			$extensionDetailsRow['extensionuid'] == $extensionsRow['uid'] &&
			$extensionDetailsRow['uploadcomment'] == $extensionData['infoData']['uploadComment'] &&
			$extensionDetailsRow['lastuploadbyusername'] == 't3unit' &&
#			$extensionDetailsRow['files'] == serialize ($filesData) &&
			$extensionDetailsRow['codelines'] == $extensionData['infoData']['codeLines'] &&
			$extensionDetailsRow['codebytes'] == $extensionData['infoData']['codeBytes'] &&
#			$extensionDetailsRow['techinfo'] == $extensionData['technicalData']['technicalInfo'] &&
			$extensionDetailsRow['shy'] == $extensionData['technicalData']['shy'] &&
#			$extensionDetailsRow['dependencies'] == serialize ($extensionData['technicalData']['dependencies']) &&
			$extensionDetailsRow['createdirs'] == $extensionData['technicalData']['createDirs'] &&
			$extensionDetailsRow['priority'] == $extensionData['technicalData']['priority'] &&
			$extensionDetailsRow['modules'] == $extensionData['technicalData']['modules'] &&
			$extensionDetailsRow['uploadfolder'] == $extensionData['technicalData']['uploadFolder'] &&
			$extensionDetailsRow['modifytables'] == $extensionData['technicalData']['modifyTables'] &&
			$extensionDetailsRow['clearcacheonload'] == $extensionData['technicalData']['clearCacheOnLoad'] &&
			$extensionDetailsRow['locktype'] == $extensionData['technicalData']['lockType'] &&
			$extensionDetailsRow['authorname'] == $extensionData['metaData']['authorName'] &&
			$extensionDetailsRow['authoremail'] == $extensionData['metaData']['authorEmail'] &&
			$extensionDetailsRow['authorcompany'] == $extensionData['metaData']['authorCompany'] &&
			$extensionDetailsRow['codingguidelinescompliance'] == $extensionData['infoData']['codingGuidelinesCompliance'] &&
			$extensionDetailsRow['codingguidelinescompliancenote'] == $extensionData['infoData']['codingGuidelinesComplianceNote'] &&
			$extensionDetailsRow['loadorder'] == $extensionData['technicalData']['loadOrder']
		);

		self::assertTrue ($extensionDetailsRowCheck, 'Row of table "tx_ter_extensiondetails" does not contain the uploaded extension data!');
	}

	public function test_uploadExtension_withoutUserPasswordAndUploadPassword() {
		$this->createFixture_uploadExtension (&$accountData, &$extensionData, &$filesData);
		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));
		
			// --- TEST AN EXTENSION UPLOAD WITHOUT ANY USERNAME, PASSWORD AND UPLOAD PASSWORD ---------------
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => '', 'password' => 't3unitpassword', 'uploadPassword' => 't3unituploadpassword'),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload without specifying a username did not throw an exception!');
		} catch (SoapFault $exception) {
		}
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => 't3unit', 'password' => '', 'uploadPassword' => 't3unituploadpassword'),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload without specifying a password did not throw an exception!');
		} catch (SoapFault $exception) {
		}
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => 't3unit', 'password' => 't3unitpassword', 'uploadPassword' => ''),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload without specifying a upload password did not throw an exception!');
		} catch (SoapFault $exception) {
		}
	}

	public function test_uploadExtension_withWrongUserPasswordAndUploadPassword() {
		$this->createFixture_uploadExtension (&$accountData, &$extensionData, &$filesData);
		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));
		
			// --- TEST AN EXTENSION UPLOAD WITH WRONG USERNAME, PASSWORD AND UPLOAD PASSWORD ---------------
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => 't3something', 'password' => 't3unitpassword', 'uploadPassword' => 't3unituploadpassword'),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload with wrong username did not throw an exception!');
		} catch (SoapFault $exception) {
		}
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => 't3unit', 'password' => 'wrongpassword', 'uploadPassword' => 't3unituploadpassword'),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload with wrong password did not throw an exception!');
		} catch (SoapFault $exception) {
		}
		try {
			$result = $soapClientObj->uploadExtension (
				array ('username' => 't3unit', 'password' => 't3unitpassword', 'uploadPassword' => 'wronguploadpassword'),
				$extensionData,
				$filesData
			);
			self::fail ('Extension upload with wrong upload password did not throw an exception!');
		} catch (SoapFault $exception) {
		}
	}





	/*********************************************************
	 *
	 * CHECK EXTENSION KEY TESTS
	 *
	 * The following tests check the function for checking 
	 * extensions keys at the TER API.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword"
	 *
	 *********************************************************/

	public function test_checkExtensionKey_existingKeyAndValidAccount () {

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CHECK AN EXISTING KEY WITH VALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			
		try {
			$result = $soapClientObj->checkExtensionKey ($accountData, 'templavoila');
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($result) && ($result['resultCode'] == 10500), 'Extension key "templavoila" does not exist (or an error ocurred) although it should (result: '.$result['resultCode'].')');
	}
	
	public function test_checkExtensionKey_nonExistingKeyAndValidAccount () {

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CHECK A NON-EXISTING KEY WITH VALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			
		$extensionKey = 'ter_testcase_nonexisting';
		try {
			$result = $soapClientObj->checkExtensionKey ($accountData, $extensionKey);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($result) && ($result['resultCode'] == 10501), 'Non existing extension key did not result in the correct result code (result: '.$result['resultCode'].', key: '.$extensionKey.')');
	}

	public function test_checkExtensionKey_invalidKeyAndValidAccount () {

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CHECK NON-EXISTING BUT INVALID KEYS WITH VALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			

			// Invalid prefixes: tx,u,user_,pages,tt_,sys_,ts_language_,csh_
			// Allowed characters: a-z (lowercase), 0-9 and '_' (underscore)
			// Extension keys cannot start or end with 0-9 and '_' (underscore)			
			// Minimum 3, maximum 30 characters

		$invalidKeys = array ('user_test', 'tx_test', 'u_test', 'pages_test', 'tt_tertest', 'sys_tertest', 'ts_language_tertest', 'csh_tertesttest', 'test_öhm', 'abc23--test', 'a12345678901234567890123456789012345', '123test', 'tertest_', '_tertest', 'a_b'); 
		foreach ($invalidKeys as $extensionKey) {	
			try {
				$result = $soapClientObj->checkExtensionKey ($accountData, $extensionKey);
			} catch (SoapFault $exception) {
				self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
			}
			
			self::assertTrue (is_array ($result) && ($result['resultCode'] == 10502), 'Extension key "'.$extensionKey.'" is not valid, but no error was returned! (result: '.$result['resultCode'].')');	
		}
	}
	
	public function test_checkExtensionKey_existingKeyAndInvalidAccount () {

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CHECK AN EXISTING KEY WITH INVALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 'invalidaccount',
			'password' => 'invalidpassword'
		);			
		try {
			$result = $soapClientObj->checkExtensionKey ($accountData, 'templavoila');
			self::fail ('Checking extension key with wrong account data should throw an exception but it didn\'t!');
		} catch (SoapFault $exception) {
		}
		
	}






	/*********************************************************
	 *
	 * REGISTER EXTENSION KEY TESTS
	 *
	 * The following tests check the function for registering 
	 * new extensions at the TER API.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword"
	 *
	 *********************************************************/

	public function test_registerExtensionKey_validKey () {
		global $TYPO3_DB;

			// --- DELETE EXTENSION KEY WHICH MIGHT REMAIN FROM PREVIOUS TESTS ---------------------------------
		$TYPO3_DB->exec_DELETEquery (
			'tx_ter_extensionkeys',
			'extensionkey="ter_testcase_testkey"'
		);
			

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- TEST A VALID EXTENSION KEY ------------------------------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);
		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'title' => 'TER T3Unit Key(äöüæøåñè<&) <em>xss</em>',
			'description' => 'This key was registered by T3Unit for checking the registerExtensionKey service (äöüæøåñè<&) <em>xss</em>',
			'uploadPassword' => 't3unituploadpasswordäöü'
		);
		try {
			$result = $soapClientObj->registerExtensionKey ($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($result) && ($result['resultCode'] == 10503), 'Registration of valid extension key was not successful (result: '.$result['resultCode'].')');
		
			// --- CHECK THE DATABASE DIRECTLY IF EXTENSION KEY WAS STORED CORRECTLY -----------------------------------
		$res = $TYPO3_DB->exec_SELECTquery (
			'uid,pid,extensionkey,title,description,ownerusername,uploadpassword,maxstoresize,downloadcounter',
			'tx_ter_extensionkeys',
			'extensionkey="'.$extensionKeyData['extensionKey'].'"'
		);
		if (!$res) self::fail ('No MySQL result while checking if extension key was correctly inserted into the DB');
		$extensionKeysRow = $TYPO3_DB->sql_fetch_assoc ($res);
		$extensionKeysRowCheck = (
			$extensionKeysRow['extensionkey'] == $extensionKeyData['extensionKey'] &&
			$extensionKeysRow['title'] == $extensionKeyData['title'] &&
			$extensionKeysRow['ownerusername'] == $accountData['username'] &&
			$extensionKeysRow['uploadpassword'] == $extensionKeyData['uploadPassword'] &&
			$extensionKeysRow['maxstoresize'] == 0
		);

		$TYPO3_DB->exec_DELETEquery (
			'tx_ter_extensionkeys',
			'extensionkey="'.$extensionKeyData['extensionKey'].'"'
		);
		
		self::assertTrue ($extensionKeysRowCheck, 'Row of table "tx_ter_extensionkeys" does not contain the uploaded extension key data!');

		
	}





	/*********************************************************
	 *
	 * GET EXTENSION KEY TESTS
	 *
	 * The following tests check the function for fetching 
	 * extensions keys from the TER API.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword". It further assumes that
	 *       there are extension keys registered by the user
	 *       "kasper" 
	 *
	 *********************************************************/

	public function test_getExtensionKeys_existingKeyAndValidAccount () {

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- FETCH AN EXISTING KEY WITH VALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			
		$filterOptions = array (
			'username' => 'kasper'
		);
		
		try {
			$resultArr = $soapClientObj->getExtensionKeys ($accountData, $filterOptions);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['simpleResult']['resultCode'] == 10000), 'Fetching extension keys did not return the expected result (result: '.$result['simpleResult']['resultCode'].')');
	}





	/*********************************************************
	 *
	 * DELETE EXTENSION KEY TESTS
	 *
	 * The following tests check the function for deleting 
	 * extensions keys from the TER API.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword" and a second account
	 *       "t3unit-2" with the same password. 
	 *
	 *********************************************************/

	public function test_deleteExtensionKey_withoutAndWithAppropriateRights () {
		global $TYPO3_DB;

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CREATE A VALID EXTENSION KEY ------------------------------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);
		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'title' => 'TER T3Unit Key(äöüæøåñè<&) <em>xss</em>',
			'description' => 'This key was registered by T3Unit for checking the registerExtensionKey service (äöüæøåñè<&) <em>xss</em>',
			'uploadPassword' => 't3unituploadpasswordäöü'
		);
		try {
			$resultArr = $soapClientObj->registerExtensionKey ($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['resultCode'] == 10503), 'Registration of valid extension key was not successful (result: '.$resultArr['resultCode'].')');

			// --- DELETE THE CREATED KEY WITH INVALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit-2',
			'password' => 't3unitpassword'
		);			
		
		try {
			$resultArr = $soapClientObj->deleteExtensionKey($accountData, 'ter_testcase_testkey');
			self::fail ('No exception thrown while trying to delete an extension key with user account not having the right to!');
		} catch (SoapFault $exception) {
			if ($exception->faultcode != 500) {
				self::fail ('Exception was thrown while trying to delete an extension key with bad user account - but with wrong faultcode! (faultcode: '.$exception->faultcode.' - '.$exception->faultString.')');
			}
		}

			// --- DELETE THE CREATED KEY WITH VALID ACCOUNT DATA ----------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			
		
		try {
			$resultArr = $soapClientObj->deleteExtensionKey($accountData, 'ter_testcase_testkey');
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['resultCode'] == 10000), 'Deleting extension key did not return the expected result (result: '.$resultArr['resultCode'].')');

			// --- CHECK THE DATABASE DIRECTLY IF EXTENSION KEY WAS REALLY DELETED -----------------------------
		$res = $TYPO3_DB->exec_SELECTquery (
			'extensionkey',
			'tx_ter_extensionkeys',
			'extensionkey="ter_testcase_testkey"'
		);
		if (!$res) self::fail ('No MySQL result while checking if extension key was correctly inserted into the DB');
		
		self::assertTrue ($TYPO3_DB->sql_num_rows($res) == 0, 'The deleted extension key still exists!');
	}





	/*********************************************************
	 *
	 * MODIFY EXTENSION KEY TESTS
	 *
	 * The following tests check the function for modifying 
	 * extensions keys by the TER API.
	 *
	 * Note: This test requires a valid FE user "t3unit" with 
	 *       password "t3unitpassword" and a second account
	 *       "t3unit-2" with the same password. 
	 *
	 *********************************************************/

	public function test_modifyExtensionKey_all () {
		global $TYPO3_DB;

		$soapClientObj = new SoapClient ($this->WSDLURI, array ('trace' => 1, 'exceptions' => 1));

			// --- CREATE A VALID EXTENSION KEY ------------------------------------------------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);
		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'title' => 'TER T3Unit Key(äöüæøåñè<&) <em>xss</em>',
			'description' => 'This key was registered by T3Unit for checking the registerExtensionKey service (äöüæøåñè<&) <em>xss</em>',
			'uploadPassword' => 't3unituploadpasswordäöü'
		);
		try {
			$resultArr = $soapClientObj->registerExtensionKey ($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['resultCode'] == 10503), 'Registration of valid extension key was not successful (result: '.$resultArr['resultCode'].')');

			// --- MODIFY THE UPLOAD PASSWORD OF CREATED KEY WITH INVALID ACCOUNT DATA ---------------------------
		$accountData = array(
			'username' => 't3unit-2',
			'password' => 't3unitpassword'
		);			
		
		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'uploadPassword' => 't3unitchangedpassword'
		);
		
		try {
			$resultArr = $soapClientObj->modifyExtensionKey($accountData, $extensionKeyData);
			self::fail ('No exception thrown while trying to modify an extension key with user account not having the right to!');
		} catch (SoapFault $exception) {
			if ($exception->faultcode != '600') {
				self::fail ('Exception was thrown while trying to modify an extension key with bad user account - but with wrong faultcode! (faultcode: '.$exception->faultcode.' - '.$exception->faultString.')');
			}
		}

			// --- MODIFY UPLOAD PASSWORD OF THE CREATED KEY WITH VALID ACCOUNT DATA -----------------------------
		$accountData = array(
			'username' => 't3unit',
			'password' => 't3unitpassword'
		);			
		
		try {
			$resultArr = $soapClientObj->modifyExtensionKey($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['resultCode'] == 10000), 'Modifying extension key did not return the expected result (result: '.$resultArr['resultCode'].')');

			// --- MODIFY OWNER OF THE CREATED KEY TO NON EXISTING USER ------------------------------------------

		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'ownerUsername' => 't3unit-not-existing-user'
		);

		try {
			$resultArr = $soapClientObj->modifyExtensionKey($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}

		self::assertTrue($resultArr['resultCode'] == 102,'Result while trying to set a bad extension key owner was not as expected! (resultCode: '.$resultArr['resultCode'].')');

			// --- MODIFY OWNER OF THE CREATED KEY TO VALID USER ------------------------------------------------

		$extensionKeyData = array (
			'extensionKey' => 'ter_testcase_testkey',
			'ownerUsername' => 't3unit-2'
		);

		try {
			$resultArr = $soapClientObj->modifyExtensionKey($accountData, $extensionKeyData);
		} catch (SoapFault $exception) {
			self::fail ('SoapFault Exception (#'.$exception->faultcode.'): '.$exception->faultstring);
		}
		
		self::assertTrue (is_array ($resultArr) && ($resultArr['resultCode'] == 10000), 'Setting extension key owner did not return the expected result (result: '.$resultArr['resultCode'].')');


			// --- CHECK THE DATABASE DIRECTLY IF EXTENSION KEY WAS REALLY MODIFIED -----------------------------
		$res = $TYPO3_DB->exec_SELECTquery (
			'extensionkey,uploadpassword,ownerusername',
			'tx_ter_extensionkeys',
			'extensionkey="ter_testcase_testkey"'
		);
		if (!$res) self::fail ('No MySQL result while checking if extension key was correctly inserted into the DB');
		
		$extensionKeysRow = $TYPO3_DB->sql_fetch_assoc ($res);
		$extensionKeysRowCheck = (
			$extensionKeysRow['ownerusername'] == 't3unit-2' &&
			$extensionKeysRow['uploadpassword'] == 't3unitchangedpassword'
		);

		$TYPO3_DB->exec_DELETEquery (
			'tx_ter_extensionkeys',
			'extensionkey="ter_testcase_testkey"'
		);
		
		self::assertTrue ($extensionKeysRowCheck, 'Row of table "tx_ter_extensionkeys" did not contain the modified extension key data!');
	}





	/*********************************************************
	 *
	 * HELPER FUNCTIONS
	 *
	 *********************************************************/

	/**
	 * Creates an extension fixture based on a dump of the extension "nothing". Some fields
	 * are modified / added, some fields contain some special characters to
	 * exploit possible problems with utf-8 in the TER classes.
	 */

	protected function createFixture_uploadExtension (&$accountData, &$extensionData, &$filesData) {
		$dataArr = unserialize (file_get_contents(t3lib_extMgm::extPath('ter').'tests/fixtures/fixture_extuploaddataarray_zipped.dat'));
		$dataBlobArr = unserialize (gzuncompress($dataArr['datablob']));

		$accountData = array (
			'username' => 't3unit',
			'password' => 't3unitpassword',
			'uploadPassword' => 't3unituploadpassword',
		);

		$extensionData = array (
			'extensionKey' => $dataArr['extension_key'],
			'version' => $dataArr['version'],
			'metaData' => array(
				'title' => 'Nothing: (äöüæøåñè<&) <em>xss</em>',
				'description' => 'This is the nothing extension with some more special characters: (äöüæøåñè<&) <em>xss</em>',
				'category' => $dataArr['emconf_category'],
				'state' => $dataArr['emconf_state'],
				'authorName' => $dataArr['emconf_author'].'(äöüæøåñè<&) <em>xss</em>',
				'authorEmail' => 'nothing@typo3.org',
				'authorCompany' => 'my company (äöüæøåñè<&) <em>xss</em>',
			),
			'technicalData' => array (
				'dataSize' => 56789,
				'dataSizeCompressed' => 1234,
				'dependencies' => array (
					array (
						'kind' => 'depends',
						'extensionKey' => 'typo3',
						'versionRange' => '3.7.0-',
					),
					array (
						'kind' => 'depends',
						'extensionKey' => 'php',
						'versionRange' => '5.0.0-',
					)
				),
				'loadOrder' => 'top',
				'internal' => 0,
				'uploadFolder' => 1,
				'createDirs' => '',
				'shy' => 1,
				'modules' => '',
				'modifyTables' => 'tt_content',
				'priority' => 0,				
				'clearCacheOnLoad' => 1,
				'lockType' => 'L',
				'isManualIncluded' => 1,
			),
			'infoData' => array(
				'codeLines' => 1234,
				'codeBytes' => 56789,
				'codingGuidelinesCompliance' => 'CGL370',
				'codingGuidelinesComplianceNote' => 'some CGL compliance notes (äöüæøåñè<&) <em>xss</em>',
				'uploadComment' => 'Uploaded by T3Unit (äöüæøåñè<&) <em>xss</em>',
				'techInfo' => $dataArr['techinfo'],
			),

		);

		$filesData = array();
		foreach ($dataBlobArr as $filename => $infoArr) {
			$filesData['fileData'][] = array (
				'name' => $infoArr['name'],
				'size' => $infoArr['size'],
				'modificationTime' => $infoArr['mtime'],
				'isExecutable' => $infoArr['is_executable'],
				'content' => base64_encode($infoArr['content']),
				'contentMD5' => $infoArr['content_md5'],
			);
		}

	}

}

?>