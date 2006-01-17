<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2006 Robert Lemke (robert@typo3.org)
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
 * Helper functions used in the TER API
 *
 * $Id$
 *
 * @author	Robert Lemke <robert@typo3.org>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */

	// Error codes: 
define (TX_TER_ERROR_GENERAL_EXTREPDIRDOESNTEXIST, '100');
define (TX_TER_ERROR_GENERAL_NOUSERORPASSWORD, '101');
define (TX_TER_ERROR_GENERAL_USERNOTFOUND, '102');
define (TX_TER_ERROR_GENERAL_WRONGPASSWORD, '103');
define (TX_TER_ERROR_GENERAL_DATABASEERROR, '104');

define (TX_TER_ERROR_UPLOADEXTENSION_NOUPLOADPASSWORD, '200');
define (TX_TER_ERROR_UPLOADEXTENSION_WRONGUPLOADPASSWORD, '201');
define (TX_TER_ERROR_UPLOADEXTENSION_EXTENSIONDOESNTEXIST, '202');
define (TX_TER_ERROR_UPLOADEXTENSION_EXTENSIONCONTAINSNOFILES, '203');
define (TX_TER_ERROR_UPLOADEXTENSION_WRITEERRORWHILEWRITINGFILES, '204');
define (TX_TER_ERROR_UPLOADEXTENSION_EXTENSIONTOOBIG, '205');
define (TX_TER_ERROR_UPLOADEXTENSION_EXISTINGEXTENSIONRECORDNOTFOUND, '206');

define (TX_TER_ERROR_REGISTEREXTENSIONKEY_DBERRORWHILEINSERTINGKEY, '300');

define (TX_TER_ERROR_DELETEEXTENSIONKEY_ACCESSDENIED, '500');
define (TX_TER_ERROR_DELETEEXTENSIONKEY_KEYDOESNOTEXIST, '501');
define (TX_TER_ERROR_DELETEEXTENSIONKEY_CANTDELETEBECAUSEVERSIONSEXIST, '502');

define (TX_TER_ERROR_MODIFYEXTENSIONKEY_ACCESSDENIED, '600');
define (TX_TER_ERROR_MODIFYEXTENSIONKEY_SETTINGTOTHISOWNERISNOTPOSSIBLE, '601');
define (TX_TER_ERROR_MODIFYEXTENSIONKEY_KEYDOESNOTEXIST, '602');

define (TX_TER_ERROR_SETREVIEWSTATE_NOUSERGROUPDEFINED, '700');
define (TX_TER_ERROR_SETREVIEWSTATE_ACCESSDENIED, '701');
define (TX_TER_ERROR_SETREVIEWSTATE_EXTENSIONVERSIONDOESNOTEXIST, '702');

define (TX_TER_ERROR_INCREASEEXTENSIONDOWNLOADCOUNTER_NOUSERGROUPDEFINED, '800');
define (TX_TER_ERROR_INCREASEEXTENSIONDOWNLOADCOUNTER_ACCESSDENIED, '801');
define (TX_TER_ERROR_INCREASEEXTENSIONDOWNLOADCOUNTER_EXTENSIONVERSIONDOESNOTEXIST, '802');
define (TX_TER_ERROR_INCREASEEXTENSIONDOWNLOADCOUNTER_INCREMENTORNOTPOSITIVEINTEGER, '803');
define (TX_TER_ERROR_INCREASEEXTENSIONDOWNLOADCOUNTER_EXTENSIONKEYDOESNOTEXIST, '804');

	// Result codes: 
define (TX_TER_RESULT_GENERAL_OK, '10000');
	
define (TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS, '10500');
define (TX_TER_RESULT_EXTENSIONKEYDOESNOTEXIST, '10501');
define (TX_TER_RESULT_EXTENSIONKEYNOTVALID, '10502');
define (TX_TER_RESULT_EXTENSIONKEYSUCCESSFULLYREGISTERED, '10503');
define (TX_TER_RESULT_EXTENSIONSUCCESSFULLYUPLOADED, '10504');


/**
 * TYPO3 Extension Repository, helper functions 
 *
 * @author	Robert Lemke <robert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter_helper
 */
class tx_ter_helper {
	
	protected $pluginObj;

	/**
	 * Constructor
	 * 
	 * @param	object	$pluginObj: Reference to parent object
	 * @return	void
	 * @access	public 
	 */
	public function __construct($pluginObj) {
		$this->pluginObj = $pluginObj;
	}


	/**
	 * This verifies the given fe_users username/password and upload password. 
	 * Either the fe_user row is returned or an exception is thrown.
	 *
	 * @param	object		$accountData: Account data information with username, password and upload password
	 * @return	mixed		If success, returns array of fe_users, otherwise error string.
	 * @access	public 
	 */
	public function getValidUser ($accountData)	{
		global $TYPO3_DB, $TSFE;
		
		if (!strlen($accountData->username) || (!strlen($accountData->password))) {
			throw new SoapFault (TX_TER_ERROR_GENERAL_NOUSERORPASSWORD, 'No user or no password submitted.');
		}

		$res = $TYPO3_DB->exec_SELECTquery(
			'*', 
			'fe_users', 
			'username="'.$TYPO3_DB->quoteStr($accountData->username, 'fe_users').'"'.$TSFE->sys_page->enableFields('fe_users')
		);		
		
		if ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			if ($row['password'] != $accountData->password) {
				throw new SoapFault (TX_TER_ERROR_GENERAL_WRONGPASSWORD, 'Wrong password.');
			}
		} else {
			throw new SoapFault (TX_TER_ERROR_GENERAL_USERNOTFOUND, 'The specified user does not exist.');
		}
		
		return $row;
	}

	/**
	 * Based on $extKey this returns the extension-key record.
	 *
	 * @param	string		$extKey: Extension key
	 * @return	mixed		The extension key row or FALSE
	 * @access	public 
	 */
	public function getExtensionKeyRecord ($extKey)	{
		global $TYPO3_DB, $TSFE;

		$res = $TYPO3_DB->exec_SELECTquery(
			'*',
			'tx_ter_extensionkeys',
			'extensionkey="'.$TYPO3_DB->quoteStr($extKey, 'tx_ter_extensionkeys').'"
				AND pid='.intval($this->pluginObj->extensionsPID).
				$TSFE->sys_page->enableFields('tx_ter_extensionkeys')
		);
		
		if ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			return $row;
		}
		return FALSE;
	}

	/**
	 * Searches the repository for the highest version number of an upload of the
	 * extension specified by $extensionKey. If no upload was found at all, FALSE
	 * will be returned. If at least one upload was found, the highest version number
	 * following the format major.minor.dev (eg. 4.2.1) will be returned.
	 *
	 * @param	string		$extKey: Extension key
	 * @return	mixed		The version number as a string or FALSE
	 * @access	public 
	 */
	public function getLatestVersionNumberOfExtension ($extensionKey) {
		global $TYPO3_DB, $TSFE;
		
		$res = $TYPO3_DB->exec_SELECTquery (
			'version',
			'tx_ter_extensions',
			'extensionkey="'.$TYPO3_DB->quoteStr($extensionKey, 'tx_ter_extensions').'"
				AND pid='.intval($this->pluginObj->extensionsPID)
		);
		$latestVersion = FALSE;
		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			if (version_compare($row['version'], $latestVersion, '>')) {
				$latestVersion = $row['version'];	
			}
		}
		
		return $latestVersion;	
	}

	/**
	 * Updates the "extensions.xml" file which contains an index of all uploaded
	 * extensions in the TER.
	 *
	 * @return	void
	 * @access	public 
	 */
	public function writeExtensionIndexfile ()	{
		global $TYPO3_DB;

		if (!@is_dir ($this->pluginObj->repositoryDir)) throw new SoapFault (TX_TER_ERROR_GENERAL_EXTREPDIRDOESNTEXIST, 'Extension repository directory does not exist.');

		$trackTime = microtime();

		$res = $TYPO3_DB->exec_SELECTquery(
			'uid,tstamp,extensionkey,version,title,description,state,reviewstate,category,downloadcounter,t3xfilemd5',
			'tx_ter_extensions',
			'1'
		);
		
			// Read the extension records from the DB:
		$extensionsAndVersionsArr = array();
		$extensionsTotalDownloadsArr = array();
		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$res2 = $TYPO3_DB->exec_SELECTquery(
				'ownerusername,downloadcounter',
				'tx_ter_extensionkeys',
				'extensionkey="'.$row['extensionkey'].'"'
			);
			$extensionKeyRow = $TYPO3_DB->sql_fetch_assoc($res2);
			$row['ownerusername'] = $extensionKeyRow['ownerusername'];
			$extensionsTotalDownloadsArr[$row['extensionkey']] = $extensionKeyRow['downloadcounter'];

			$res2 = $TYPO3_DB->exec_SELECTquery(
				'lastuploaddate,uploadcomment,dependencies,authorname,authoremail,authorcompany',
				'tx_ter_extensiondetails',
				'extensionuid='.$row['uid']
			);
			$detailsRow = $TYPO3_DB->sql_fetch_assoc($res2);
			if (is_array ($detailsRow)) {
				$row = $row + $detailsRow;	
			}
			$extensionsAndVersionsArr [$row['extensionkey']][$row['version']] = $row;
		}

			// Prepare the DOM object:
		$dom = new DOMDocument ('1.0', 'utf-8');
		$dom->formatOutput = TRUE;
		$extensionsObj = $dom->appendChild (new DOMElement('extensions'));

			// Create the nested XML structure:
		foreach ($extensionsAndVersionsArr as $extensionKey => $extensionVersionsArr) {
			$extensionObj = $extensionsObj->appendChild (new DOMElement('extension'));
			$extensionObj->appendChild (new DOMAttr ('extensionkey', $extensionKey));
			$extensionObj->appendChild (new DOMElement ('downloadcounter', $this->xmlentities ($extensionsTotalDownloadsArr[$extensionKey])));
			
			foreach ($extensionVersionsArr as $versionNumber => $extensionVersionArr) {
				$versionObj = $extensionObj->appendChild (new DOMElement('version'));
				$versionObj->appendChild (new DOMAttr ('version', $versionNumber));
				
				$versionObj->appendChild (new DOMElement('title', $this->xmlentities ($extensionVersionArr['title'])));
				$versionObj->appendChild (new DOMElement('description', $this->xmlentities ($extensionVersionArr['description'])));
				$versionObj->appendChild (new DOMElement('state', $this->xmlentities ($extensionVersionArr['state'])));
				$versionObj->appendChild (new DOMElement('reviewstate', $this->xmlentities ($extensionVersionArr['reviewstate'])));
				$versionObj->appendChild (new DOMElement('category', $this->xmlentities ($extensionVersionArr['category'])));
				$versionObj->appendChild (new DOMElement('downloadcounter', $this->xmlentities ($extensionVersionArr['downloadcounter'])));
				$versionObj->appendChild (new DOMElement('lastuploaddate', $extensionVersionArr['lastuploaddate']));
				$versionObj->appendChild (new DOMElement('uploadcomment', $this->xmlentities ($extensionVersionArr['uploadcomment'])));
				$versionObj->appendChild (new DOMElement('dependencies', $extensionVersionArr['dependencies']));
				$versionObj->appendChild (new DOMElement('authorname', $this->xmlentities ($extensionVersionArr['authorname'])));
				$versionObj->appendChild (new DOMElement('authoremail', $this->xmlentities ($extensionVersionArr['authoremail'])));
				$versionObj->appendChild (new DOMElement('authorcompany', $this->xmlentities ($extensionVersionArr['authorcompany'])));
				$versionObj->appendChild (new DOMElement('ownerusername', $this->xmlentities ($extensionVersionArr['ownerusername'])));
				$versionObj->appendChild (new DOMElement('t3xfilemd5', $extensionVersionArr['t3xfilemd5']));
			}
		}

		$extensionsObj->appendChild (new DOMComment('Index created at '.date("D M j G:i:s T Y")));
		$extensionsObj->appendChild (new DOMComment('Index created in '.(microtime()-$trackTime).' ms'));
		
			// Write XML data to disc:
		$fh = @fopen ($this->pluginObj->repositoryDir.'extensions.xml.gz', 'wb');
		if (!$fh) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_WRITEERRORWHILEWRITINGEXTENSIONSINDEX, 'Write error while writing extensions index file: '.$this->pluginObj->repositoryDir.'extensions.xml');
		fwrite ($fh, gzencode ($dom->saveXML(), 9));
		fclose ($fh);
	}
	
	/**
	 * Equivalent to htmlentities but for XML content
	 *
	 * @param	string		$string: String to encode
	 * @return	string		&,",',< and > replaced by entities
	 * @access	public 
	 */
	public function xmlentities ($string) {
		return str_replace ( array ( '&', '"', "'", '<', '>' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
	}
}

?>