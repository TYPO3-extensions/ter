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
 * SOAP server for the TYPO3 Extension Repository
 *
 * $Id$
 * 
 * @author	Robert Lemke <robert@typo3.org>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */

require_once(t3lib_extMgm::extPath('ter').'class.tx_ter_helper.php');

/**
 * TYPO3 Extension Repository, SOAP Server 
 *
 * @author	Robert Lemke <robert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter_soapserver
 */
class tx_ter_api {
	
	protected	$helperObj;
	protected	$parentObj;
	protected	$cObj;

	protected	$extensionMaxUploadSize = 5000000;					// 5MB Maximum upload size for extensions
	
	/**
	 * Constructor
	 * 
	 * @param	object	$parentObj: Reference to parent object
	 * @return	void
	 * @access	public 
	 */
	public function __construct($parentObj) {
		$this->helperObj = new tx_ter_helper ($parentObj);
		$this->parentObj = $parentObj;
		$this->cObj = $parentObj->cObj;
	}





	/*********************************************************
	 *
	 * PUBLIC API
	 *
	 *********************************************************/

	/**
	 * Test method which just returns the given valued prepended with "pong"
	 * 
	 * @param	string	$value
	 * @return	string	$value prepended with "pong"
	 * @access	public 
	 * @since	2.0.0
	 */
	public function ping ($value) {
		return 'pong'.$value;	
	}
		
	/**
	 * Method for uploading an extension to the repository
	 * 
	 * @param	object	$accountData: Username and passwords for upload the extension
	 * @param	object	$extensionInfoData: The general extension information as received by the SOAP server
	 * @param	array	$filesData: The array of file data objects as received by the SOAP server
	 * @return	object	uploadExtensionResult object if upload was successful, otherwise a SoapFault exception is thrown.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function uploadExtension ($accountData, $extensionInfoData, $filesData) {
		global $TSFE, $TYPO3_DB;
		
		$uploadUserRecordArr = $this->helperObj->getValidUser ($accountData);
		$extensionKeyRecordArr = $this->helperObj->getExtensionKeyRecord ($extensionInfoData->extensionKey);				
		if (!strlen($accountData->uploadPassword)) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_NOUPLOADPASSWORD, 'No upload password submitted.');
		if ($extensionKeyRecordArr == FALSE) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_EXTENSIONDOESNTEXIST, 'Extension does not exist.');
		if ($accountData->uploadPassword != $extensionKeyRecordArr['uploadpassword']) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_WRONGUPLOADPASSWORD, 'Wrong upload password.');
		
		$this->uploadExtension_calculateAndSetNewVersionNumber ($extensionInfoData);
		$this->uploadExtension_writeExtensionAndIconFile ($extensionInfoData, $filesData);
		$this->uploadExtension_writeExtensionInfoToDB ($accountData, $extensionInfoData, $filesData);
		$this->helperObj->writeExtensionIndexFile ();

		return array (
			'resultCode' => TX_TER_RESULT_EXTENSIONSUCCESSFULLYUPLOADED,
			'resultMessages' => array(),
			'version' => $extensionInfoData->version,
		);
	}

	/**
	 * Checks if an extension key already exists
	 * 
	 * @param	object	$accountData: A valid username and password
	 * @param	string	$extensionKey: The extension key to check
	 * @return	object	simpleResult object if key could be checked, otherwise a SoapFault exception is thrown.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function checkExtensionKey ($accountData, $extensionKey) {
		global $TSFE, $TYPO3_DB;
		$userRecordArr = $this->helperObj->getValidUser ($accountData);

		if ($this->checkExtensionKey_extensionKeyIsFormallyValid ($extensionKey)) {
			$extensionKeyRecordArr = $this->helperObj->getExtensionKeyRecord ($extensionKey);
			$resultCode = is_array ($extensionKeyRecordArr) ? TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS : TX_TER_RESULT_EXTENSIONKEYDOESNOTEXIST;
		} else {
			$resultCode = TX_TER_RESULT_EXTENSIONKEYNOTVALID;	
		}

		return array (
			'resultCode' => $resultCode,
			'resultMessages' => array(),
		);
	}

	/**
	 * Registers an extension key
	 * 
	 * @param	object	$accountData: A valid username and password
	 * @param	object	$extensionKeyData: The extension key and other information
	 * @return	object	simpleResult object if key was registered, otherwise a SoapFault exception is thrown.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function registerExtensionKey ($accountData, $registerExtensionKyData) {
		global $TSFE, $TYPO3_DB;

		$userRecordArr = $this->helperObj->getValidUser ($accountData);
		if ($this->checkExtensionKey_extensionKeyIsFormallyValid ($registerExtensionKyData->extensionKey)) {
			$existingExtensionKeyRecordArr = $this->helperObj->getExtensionKeyRecord ($registerExtensionKyData->extensionKey);
			if (!is_array ($existingExtensionKeyRecordArr)) {
				$this->registerExtensionKey_writeExtensionKeyInfoToDB ($accountData, $registerExtensionKyData);				
				$resultCode = TX_TER_RESULT_EXTENSIONKEYSUCCESSFULLYREGISTERED;
			} else {
				$resultCode = TX_TER_RESULT_EXTENSIONKEYALREADYEXISTS;
			}
		} else {
			$resultCode = TX_TER_RESULT_EXTENSIONKEYNOTVALID;	
		}

		return array (
			'resultCode' => $resultCode,
			'resultMessages' => array(),
		);
	}

	/**
	 * Returns a list of extension key records filtered by certain 
	 * criteria.
	 * 
	 * @param	object	$accountData: A valid username and password
	 * @param	object	$extensionKeyFilterOptions: Result will be filtered by fields set in these filter options
	 * @return	object	getExtensionKeyRepsonse-object if key(s) could be fetched (might also be an empty result). A SoapFault exception is thrown if an error ocurred.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function getExtensionKeys ($accountData, $extensionKeyFilterOptions) {
		global $TSFE, $TYPO3_DB;
		
		$userRecordArr = $this->helperObj->getValidUser ($accountData);
		$extensionKeyDataArr = array();

		$whereClause = 'pid='.intval($this->parentObj->extensionsPID);
		if (isset ($extensionKeyFilterOptions->username)) $whereClause .= ' AND ownerusername LIKE "'.$TYPO3_DB->quoteStr ($extensionKeyFilterOptions->username, 'tx_ter_extensionkeys').'"';
		if (isset ($extensionKeyFilterOptions->title)) $whereClause .= ' AND title LIKE "'.$TYPO3_DB->quoteStr ($extensionKeyFilterOptions->title, 'tx_ter_extensionkeys').'"';
		if (isset ($extensionKeyFilterOptions->description)) $whereClause .= ' AND description LIKE "'.$TYPO3_DB->quoteStr ($extensionKeyFilterOptions->description, 'tx_ter_extensionkeys').'"';
		
		$res = $TYPO3_DB->exec_SELECTquery (
			'extensionkey,title,description,ownerusername',
			'tx_ter_extensionkeys',
			$whereClause
		);

		if ($res) {
			while ($row = $TYPO3_DB->sql_fetch_assoc ($res)) {
				$extensionKeyDataArr[] = $row; 	
			}
			$resultCode = TX_TER_RESULT_GENERAL_OK;
		} else {
			throw new SoapFault (TX_TER_ERROR_GENERAL_DATABASEERROR, 'Database error while fetching extension keys.');			
		}

		return array (
			'simpleResult' => array (
				'resultCode' => $resultCode,
				'resultMessages' => array()
			),
			'extensionKeyData' => $extensionKeyDataArr			
		);
	}

	/**
	 * Deletes an extension key. Only possible if no uploaded versions exist.
	 * 
	 * @param	object	$accountData: A valid username and password
	 * @param	string	$extensionKey: The extension key to delete
	 * @return	object	simpleResponse-object. A SoapFault exception is thrown if a fatal error ocurred.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function deleteExtensionKey ($accountData, $extensionKey) {
		global $TSFE, $TYPO3_DB;
		
		$userRecordArr = $this->helperObj->getValidUser ($accountData);
		$extensionKeyRecordArr = $this->helperObj->getExtensionKeyRecord ($extensionKey);

		if (is_array ($extensionKeyRecordArr)) {
			
			if ($extensionKeyRecordArr['ownerusername'] != $accountData->username) throw new SoapFault (TX_TER_ERROR_DELETEEXTENSIONKEY_ACCESSDENIED, 'Access denied.'); 
			
			$res = $TYPO3_DB->exec_SELECTquery (
				'extensionkey',
				'tx_ter_extensions',
				'extensionkey="'.$TYPO3_DB->quoteStr($extensionKey, 'tx_ter_extensions').'" AND pid='.intval($this->parentObj->extensionsPID)
			);
	
			if ($res) {
				if ($TYPO3_DB->sql_num_rows($res) > 0) {
					$resultCode = TX_TER_ERROR_DELETEEXTENSIONKEY_CANTDELETEBECAUSEVERSIONSEXIST;
				} else {
					$res = $TYPO3_DB->exec_DELETEquery (
						'tx_ter_extensionkeys',
						'extensionkey="'.$TYPO3_DB->quoteStr($extensionKey, 'tx_ter_extensions').'" AND pid='.intval($this->parentObj->extensionsPID)
					);
					if (!$res) throw new SoapFault (TX_TER_ERROR_GENERAL_DATABASEERROR, 'Database error while deleting extension key.');
					 
					$resultCode = TX_TER_RESULT_GENERAL_OK;
				}
			} else {
				throw new SoapFault (TX_TER_ERROR_GENERAL_DATABASEERROR, 'Database error while fetching versions.');			
			}
		} else {
			$resultCode = TX_TER_ERROR_DELETEEXTENSIONKEY_KEYDOESNOTEXIST;
		}

		return array (
			'resultCode' => $resultCode,
			'resultMessages' => array()
		);
	}

	/**
	 * Modifies an extension key.
	 * 
	 * @param	object	$accountData: A valid username and password
	 * @param	object	$modifyExtensionKeyData: Fields which should be changed, "extensionKey" is mandatory. 
	 * @return	object	simpleResponse-object. A SoapFault exception is thrown if a fatal error ocurred.
	 * @access	public 
	 * @since	2.0.0
	 */
	public function modifyExtensionKey ($accountData, $modifyExtensionKeyData) {
		global $TSFE, $TYPO3_DB;
		
		$userRecordArr = $this->helperObj->getValidUser ($accountData);
		$extensionKeyRecordArr = $this->helperObj->getExtensionKeyRecord ($modifyExtensionKeyData->extensionKey);

		if (is_array ($extensionKeyRecordArr)) {			
			if ($extensionKeyRecordArr['ownerusername'] != $accountData->username) throw new SoapFault (TX_TER_ERROR_MODIFYEXTENSIONKEY_ACCESSDENIED, 'Access denied.'); 
			$resultCode = $this->modifyExtensionKey_writeModifiedKeyRecordIntoDB ($accountData, $modifyExtensionKeyData);			
			$this->helperObj->writeExtensionIndexFile ();
		} else {
			$resultCode = TX_TER_ERROR_MODIFYEXTENSIONKEY_KEYDOESNOTEXIST;
		}

		return array (
			'resultCode' => $resultCode,
			'resultMessages' => array()
		);
	}







	/*********************************************************
	 *
	 * uploadExtension helper functions
	 *
	 *********************************************************/

	/**
	 * Checks the version number of the uploaded extension and modifies it if
	 * neccessary. The result will be written directly into $extensionInfoData->version
	 *
	 * @param	object		$extensionInfoData: The general extension information as received by the SOAP server
	 * @result	void
	 * @access	protected
	 */
	protected function uploadExtension_calculateAndSetNewVersionNumber (&$extensionInfoData) { 
		$latestVersion = $this->helperObj->getLatestVersionNumberOfExtension ($extensionInfoData->extensionKey);
		if (version_compare ($extensionInfoData->version, $latestVersion, '>=')) {
			$newVersion = $extensionInfoData->version;
		} else {
			list ($majorVersion, $minorVersion, $devVersion) = t3lib_div::intExplode ('.', $latestVersion);			
			$newVersion = $majorVersion.'.'.$minorVersion.'.'.($devVersion+1);
		}
		$extensionInfoData->version = $newVersion;
	}

	/**
	 * Creates a T3X file by using the extension info data and the files data and 
	 * writes the file to the repository's directory. By default this function creates
	 * gzip compressed T3X files.
	 * 
	 * After writing the extension, some files specififc data (like the filesize) is 
	 * added to $extensionInfoData for later informational use.
	 *
	 * @param	object		$extensionInfoData: The general extension information as received by the SOAP server
	 * @param	array		$filesData: The array of file data objects as received by the SOAP server
	 * @access	protected
	 */
	protected function uploadExtension_writeExtensionAndIconFile (&$extensionInfoData, $filesData) { 

		if (!@is_dir ($this->parentObj->repositoryDir)) throw new SoapFault (TX_TER_ERROR_GENERAL_EXTREPDIRDOESNTEXIST, 'Extension repository directory does not exist.');
		if (!is_array ($filesData->fileData)) throw new SoapFault (TX_TER_ERROR_GENERAL_EXTENSIONCONTAINSNOFILES, 'Extension contains no files.');
		
			// Prepare Files Data Array:
		$preparedFilesDataArr = array();
		foreach ($filesData->fileData as $fileData) {			
			$decodedContent = base64_decode ($fileData->content);
			$preparedFilesDataArr[$fileData->name] = array (
				'name' => $fileData->name,
				'size' => $fileData->size,
				'mtime' => $fileData->modificationTime,
				'is_executable' => $fileData->isExecutable,
				'content' => $decodedContent,
				'content_md5' => md5 ($decodedContent)
			);
		}

			// Create an old-style list of dependencies, conflicts etc. which is understood by older
			// versions of the Extension Manager:
		$typo3Version = '';
		$phpVersion = '';
		$dependenciesArr = array ();
		$conflictsArr = array ();

		if (is_array ($extensionInfoData->technicalData->dependencies)) {
			foreach ($extensionInfoData->technicalData->dependencies as $dependencyArr) {
				switch ($dependencyArr->extensionKey) {
					case 'typo3' :
						$typo3Version = $dependencyArr->versionRange;
					break;
					case 'php':
						$phpVersion = $dependencyArr->versionRange;
					break;
					default:
						if ($dependencyArr->kind == 'requires') {
							$dependenciesArr[] = $dependencyArr->extensionKey; 	
						} elseif ($dependencyArr->kind == 'requires') {
							$conflictsArr[] = $dependencyArr->extensionKey; 	
						}				
				}			
			}
		}

			// Prepare EM_CONF Array:
		$preparedEMConfArr = array(
			'title' => $extensionInfoData->metaData->title,
			'description' => $extensionInfoData->metaData->description,
			'category' => $extensionInfoData->metaData->category,
			'shy' => $extensionInfoData->technicalData->shy,
			'version' => $extensionInfoData->version,
			'dependencies' => implode (',', $dependenciesArr),
			'conflicts' => implode (',', $conflictsArr),
			'priority' => $extensionInfoData->technicalData->priority,
			'loadOrder' => $extensionInfoData->technicalData->loadOrder,
			'TYPO3_version' => $typo3Version,
			'PHP_version' => $phpVersion,
			'module' => $extensionInfoData->technicalData->modules,
			'state' => $extensionInfoData->metaData->state,
			'uploadfolder' => $extensionInfoData->technicalData->uploadFolder,
			'createDirs' => $extensionInfoData->technicalData->createDirs,
			'modify_tables' => $extensionInfoData->technicalData->modifyTables,
			'clearCacheOnLoad' => $extensionInfoData->technicalData->clearCacheOnLoad,
			'lockType' => $extensionInfoData->technicalData->lockType,
			'author' => $extensionInfoData->metaData->authorName,
			'author_email' => $extensionInfoData->metaData->authorEmail,
			'author_company' => $extensionInfoData->metaData->authorCompany,
			'CGLcompliance' => $extensionInfoData->infoData->codingGuidelineCompliance,
			'CGLcompliance_note' => $extensionInfoData->infoData->codeingGuidelineComplianceNote,
			'version' => $extensionInfoData->version,
		);

			// Compile T3X Data Array:
		$dataArr = array (
			'extKey' => $extensionInfoData->extensionKey,
			'EM_CONF' => $preparedEMConfArr,
			'misc' => array (),
			'techInfo' => array (),
			'FILES' => $preparedFilesDataArr
		);
		
		$t3xFileUncompressedData = serialize ($dataArr);
		$t3xFileData = md5 ($t3xFileUncompressedData) . ':gzcompress:'.gzcompress ($t3xFileUncompressedData);
		$gifFileData = $preparedFilesDataArr['ext_icon.gif']['content'];

			// Check if size of t3x file is too big:
		if (strlen ($t3xFileData) > $this->extensionMaxUploadSize) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_EXTENSIONTOOBIG, 'The extension size exceeded '.$this->extensionMaxUploadSize.' bytes which is the maximum for extension uploads.');			

			// Create directories and build filenames:
		$firstLetter = strtolower (substr ($extensionInfoData->extensionKey, 0, 1));
		$secondLetter = strtolower (substr ($extensionInfoData->extensionKey, 1, 1));
		$fullPath = $this->parentObj->repositoryDir.$firstLetter.'/'.$secondLetter.'/';
		
		if (@!is_dir ($this->parentObj->repositoryDir . $firstLetter)) mkdir ($this->parentObj->repositoryDir . $firstLetter);
		if (@!is_dir ($this->parentObj->repositoryDir . $firstLetter . '/'. $secondLetter)) mkdir ($this->parentObj->repositoryDir . $firstLetter . '/' .$secondLetter);

		list ($majorVersion, $minorVersion, $devVersion) = t3lib_div::intExplode ('.', $extensionInfoData->version);
		$t3xFileName = strtolower ($extensionInfoData->extensionKey).'_'.$majorVersion.'.'.$minorVersion.'.'.$devVersion.'.t3x';
		$gifFileName = strtolower ($extensionInfoData->extensionKey).'_'.$majorVersion.'.'.$minorVersion.'.'.$devVersion.'.gif';

			// Write the files		
		$fh = @fopen ($fullPath.$t3xFileName, 'wb');
		if (!$fh) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_WRITEERRORWHILEWRITINGFILES, 'Write error while writing .t3x file: '.$fullPath.$t3xFileName);
		fwrite ($fh, $t3xFileData);
		fclose ($fh);	

		if (strlen ($gifFileData)) {
			$fh = @fopen ($fullPath.$gifFileName, 'wb');
			if (!$fh) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_WRITEERRORWHILEWRITINGFILES, 'Write error while writing .gif file: '.$fullPath.$gifFileName);
			fwrite ($fh, $gifFileData);
			fclose ($fh);
		}
		
			// Write some data back to $extensionInfoData:
		$extensionInfoData->t3xFileMD5 = md5 ($t3xFileData);
		$extensionInfoData->infoData->dataSize = strlen ($t3xFileUncompressedData);
		$extensionInfoData->infoData->dataSizeCompressed = strlen ($t3xFileData);		
	}

	/**
	 * Extracts information from the extension information and stores it as a record in the database
	 * tables. These tables are used for quickly getting information about extensions for display
	 * in the TER frontend plugin.
	 * 
	 * Note: All data written to the database (like everywhere else in the TER handling) is assumed to be 
	 * utf-8 encoded!
	 *
	 * @param	object		$accountData: Username and passwords
	 * @param	object		$extensionInfoData: The general extension information as received by the SOAP server
	 * @param	array		$filesData: The array of file data objects as received by the SOAP server
	 * @return	void
	 * @access	protected 
	 */
	protected function uploadExtension_writeExtensionInfoToDB ($accountData, $extensionInfoData, $filesData) {
		global $TYPO3_DB;

			// Prepare information about files:
		$extensionInfoData->technicalData->isManualIncluded = 0;
		foreach ($filesData->fileData as $fileData) {			
			$extensionInfoData->infoData->files [$fileData->name] = array (
				'filename' => $fileData->name,
				'size' => $fileData->size,
				'mtime' => $fileData->modificationTime,
				'is_executable' => $fileData->isExecutable,
			);			
			if ($fileData->name == 'doc/manual.sxw')	{
				$extensionInfoData->technicalData->isManualIncluded = 1;
			}
		}

			// Prepare the new records:
		$extensionsRow = array (
			'tstamp' => time(),
			'crdate' => time(),
			'pid' => $this->parentObj->extensionsPID,
			'extensionkey' => $extensionInfoData->extensionKey,
			'version' => $extensionInfoData->version,
			'title' => $extensionInfoData->metaData->title,
			'description' => $extensionInfoData->metaData->description,
			'state' => $extensionInfoData->metaData->state,
			'category' => $extensionInfoData->metaData->category,
			'ismanualincluded' => $extensionInfoData->technicalData->isManualIncluded,
			't3xfilemd5' => $extensionInfoData->t3xFileMD5,			
		);

			// Either update an existing or insert a new extension record:
		$latestVersion = $this->helperObj->getLatestVersionNumberOfExtension ($extensionInfoData->extensionKey);
		if (version_compare ($extensionInfoData->version, $latestVersion, '==')) {

			$result = $TYPO3_DB->exec_SELECTquery (
				'uid',
				'tx_ter_extensions', 
				'extensionkey ="'.$extensionInfoData->extensionKey.'" AND version = "'.$extensionInfoData->version.'"'
			);
			$existingRow = $TYPO3_DB->sql_fetch_assoc ($result);
			if (!is_array ($existingRow)) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_EXISTINGEXTENSIONRECORDNOTFOUND, 'Extension database record not found while updating existing extension version.');			
			$extensionUid = $existingRow['uid']; 

			$result = $TYPO3_DB->exec_UPDATEquery (
				'tx_ter_extensions', 
				'extensionkey ="'.$extensionInfoData->extensionKey.'" 
				 AND version = "'.$extensionInfoData->version.'"',
				$extensionsRow
			);

		} else {
			$result = $TYPO3_DB->exec_INSERTquery ('tx_ter_extensions', $extensionsRow);
			$extensionUid = $TYPO3_DB->sql_insert_id();
		}
		
		$dependenciesArr = array();
		if (is_array ($extensionInfoData->technicalData->dependencies)) {
			foreach ($extensionInfoData->technicalData->dependencies as $dependency) {
				$dependenciesArr[] = array (
					'kind' => $dependency->kind,
					'extensionKey' => $dependency->extensionKey,
					'versionRange' => $dependency->versionRange,
				);	
			}
		}

		$extensionDetailsRow = array (
			'extensionuid' => $extensionUid,
			'uploadcomment' => $extensionInfoData->infoData->uploadComment,
			'lastuploadbyusername' => $accountData->username,
			'lastuploaddate' => time(),
			'datasize' => $extensionInfoData->infoData->dataSize,
			'datasizecompressed' => $extensionInfoData->infoData->dataSizeCompressed,
			'files' => serialize ($extensionInfoData->infoData->files),
			'codelines' => $extensionInfoData->infoData->codeLines,
			'codebytes' => $extensionInfoData->infoData->codeBytes,
			'techinfo' => serialize ($extensionInfoData->infoData->techInfo),
			'shy' => (boolean) $extensionInfoData->technicalData->shy ? 1: 0,
			'dependencies' => serialize ($dependenciesArr),
			'createdirs' => $extensionInfoData->technicalData->createDirs,
			'priority' => $extensionInfoData->technicalData->priority,
			'modules' => $extensionInfoData->technicalData->modules,
			'uploadfolder' => (boolean)$extensionInfoData->technicalData->uploadFolder ? 1 : 0,
			'modifytables' => $extensionInfoData->technicalData->modifyTables,
			'clearcacheonload' => (boolean)$extensionInfoData->technicalData->clearCacheOnLoad ? 1 : 0,
			'locktype' => $extensionInfoData->technicalData->lockType,
			'authorname' => $extensionInfoData->metaData->authorName,
			'authoremail' => $extensionInfoData->metaData->authorEmail,
			'authorcompany' => $extensionInfoData->metaData->authorCompany,
			'codingguidelinescompliance' => $extensionInfoData->infoData->codingGuidelinesCompliance,
			'codingguidelinescompliancenote' =>$extensionInfoData->infoData->codingGuidelinesComplianceNote,
			'loadorder' => $extensionInfoData->technicalData->loadOrder,
		);

		if (version_compare ($extensionInfoData->version, $latestVersion, '==')) {
			$result = $TYPO3_DB->exec_SELECTquery (
				'uid',
				'tx_ter_extensiondetails', 
				'extensionuid = '.$extensionUid.''
			);
			$existingRow = $TYPO3_DB->sql_fetch_assoc ($result);
			$this->cObj->DBgetUpdate('tx_ter_extensiondetails', $existingRow['uid'], $extensionDetailsRow, implode (',',array_keys($extensionDetailsRow)), TRUE);			
		} else {
			$this->cObj->DBgetInsert('tx_ter_extensiondetails', $this->parentObj->extensionsPID, $extensionDetailsRow, implode (',',array_keys($extensionDetailsRow)), TRUE);			
		}  
	}





	/*********************************************************
	 *
	 * checkExtensionKey helper functions
	 *
	 *********************************************************/

	/**
	 * Checks if an extension key is formally valid
	 *
	 * @param	string		$extensionKey: The extension key to check
	 * @result	boolean		TRUE if the extension key is valid
	 * @access	protected
	 */
	protected function checkExtensionKey_extensionKeyIsFormallyValid ($extensionKey) {
		$ok = TRUE;
		
			// Check characters used:
		if (ereg('[^a-z0-9_]',$extensionKey))	{
			$ok = FALSE;
		}

			// Check characters used:
		if (ereg('^[0-9_]',$extensionKey) || ereg("[_]$", $extensionKey))	{
			$ok = FALSE;
		}

			// Length
		$extensionKeyModule = str_replace('_','',$extensionKey);
		if (strlen($extensionKey)>30 || strlen($extensionKey)<3 || strlen($extensionKeyModule)<3)	{
			$ok = FALSE;
		}

			// Bad prefixes:
		$badPrefixesArr = array ('tx','u','user_','pages','tt_','sys_','ts_language_','csh_');
		foreach ($badPrefixesArr as $prefix) {
			if (t3lib_div::isFirstPartOfStr($extensionKey, $prefix))	{
				$ok = FALSE;
			}
		}

		return $ok; 
	}	





	/*********************************************************
	 *
	 * registerExtensionKey helper functions
	 *
	 *********************************************************/

	/**
	 * Writes extension key information into the database
	 *
	 * @param	object	$accountData: A valid username and password
	 * @param	object	$extensionKeyData: The extension key and other information
	 * @result	void
	 * @access	protected
	 */
	protected function registerExtensionKey_writeExtensionKeyInfoToDB ($accountData, $extensionKeyData) {
		global $TYPO3_DB, $TSFE;

		$extensionKeysRow = array(
			'pid' => $this->parentObj->extensionsPID,
			'tstamp' => time(),
			'crdate' => time(),
			'extensionkey' => $extensionKeyData->extensionKey,
			'title' => $TSFE->csConvObj->strtrunc('utf-8', $extensionKeyData->title, 50),
			'description' => $TSFE->csConvObj->strtrunc('utf-8', $extensionKeyData->description, 255),
			'uploadpassword' => $TSFE->csConvObj->strtrunc('utf-8', $extensionKeyData->uploadPassword, 30),
			'ownerusername' => $accountData->username,
			'maxstoresize' => 0			
		);
		$result = $TYPO3_DB->exec_INSERTquery ('tx_ter_extensionkeys', $extensionKeysRow);
		if (!$result) {
			throw new SoapFault (TX_TER_ERROR_REGISTEREXTENSIONKEY_DBERRORWHILEINSERTINGKEY, 'Database error while inserting extension key.');
		}
	}





	/*********************************************************
	 *
	 * modifyExtensionKey helper functions
	 *
	 *********************************************************/

	/**
	 * Writes modified extension key information into the database
	 *
	 * @param	object	$accountData: A valid username and password
	 * @param	object	$modifyExtensionKeyData: The extension key field which shall be updated
	 * @result	void
	 * @access	protected
	 */
	protected function modifyExtensionKey_writeModifiedKeyRecordIntoDB ($accountData, $modifyExtensionKeyData) {
		global $TYPO3_DB, $TSFE;

		$updateValues = array();
		
		if (isset ($modifyExtensionKeyData->ownerUsername)) {
			$modifyExtensionKeyData->ownerUsername;
			$res = $TYPO3_DB->exec_SELECTquery(
				'*', 
				'fe_users', 
				'username="'.$TYPO3_DB->quoteStr($modifyExtensionKeyData->ownerUsername, 'fe_users').'"'.$TSFE->sys_page->enableFields('fe_users')
			);						
			if ($newOwnerUserRecordArr = $TYPO3_DB->sql_fetch_assoc($res)) {
				$updateValues['ownerusername'] = $newOwnerUserRecordArr['username'];
			} else return TX_TER_ERROR_GENERAL_USERNOTFOUND;
		}

		if (isset ($modifyExtensionKeyData->uploadPassword)) {
			$updateValues['uploadpassword'] = $modifyExtensionKeyData->uploadPassword;
		}

		$res = $TYPO3_DB->exec_UPDATEquery (
			'tx_ter_extensionkeys', 
			'extensionkey ="'.$TYPO3_DB->quoteStr($modifyExtensionKeyData->extensionKey, 'tx_ter_extensionkeys').'"',
			$updateValues
		);
		if (!$res) throw new SoapFault (TX_TER_ERROR_GENERAL_DATABASEERROR, 'Database error while updating extension key.');
		
		return TX_TER_RESULT_GENERAL_OK;
	}

}

?>