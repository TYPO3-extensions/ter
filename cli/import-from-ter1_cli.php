#! /usr/bin/php -q
<?php

die ('Access denied');

	// Defining circumstances for CLI mode:
define('TYPO3_cliMode', TRUE);

	// Defining PATH_thisScript here: Must be the ABSOLUTE path of this script in the right context:
	// This will work as long as the script is called by it's absolute path!
define('PATH_thisScript',$_ENV['_']?$_ENV['_']:$_SERVER['_']);

require(dirname(PATH_thisScript).'/conf.php');
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');

require_once(dirname(PATH_thisScript).'/../class.tx_ter_helper.php');


// =========================================================================

$repositoryDir = dirname(PATH_thisScript).'/../../../../fileadmin/ter/';
$extensionsPID = 1320;

// =========================================================================

 writeExtensionIndexfile ();
 die('Only regenerated extensions.xml file'.chr(10).chr(10));

$res = $TYPO3_DB->exec_SELECTquery (
	'*',
	'tx_extrep_keytable',
	'hidden=0 AND deleted=0 AND members_only=0',
	'',
	'extension_key ASC'
);

$startTime = time();
$extensionKeyCounter = 0;
$extensionKeysWithProblems = array();
while ($extensionKeyRow = $TYPO3_DB->sql_fetch_assoc ($res)) {
	$extensionKeyCounter ++;
	
	$res2 = $TYPO3_DB->exec_SELECTquery (
		'username',
		'fe_users',
		'uid = '.$extensionKeyRow['owner_fe_user']
	);
	$feUsersRow = $TYPO3_DB->sql_fetch_assoc ($res2);
	$accountData['username'] = $feUsersRow['username'];

	echo ('importing '.str_pad ($extensionKeyRow['extension_key'].' ('.$feUsersRow['username'].')', 40, ' '));

	$res2 = $TYPO3_DB->exec_SELECTquery (
		'*',
		'tx_extrep_repository',
		'extension_uid = '.$extensionKeyRow['uid']
	);

	$versionOfExtension = 1;
	while ($extensionVersionRow = $TYPO3_DB->sql_fetch_assoc ($res2)) {
		$extensionVersionCounter ++;

		echo (str_pad ($extensionVersionRow['version'], 7, ' '));

		$filesData = unserialize (gzuncompress($extensionVersionRow['datablob']));			
		$extensionData = getExtensionDataFromRepositoryRow($extensionKeyRow, $extensionVersionRow);
				
		if (is_array ($filesData)) {
			writeExtensionAndIconFile ($extensionData, $filesData);
			writeExtensionInfoToDB ($accountData, $extensionData, $filesData, $extensionVersionRow, $versionOfExtension);		
			$versionOfExtension ++;
		} else {
			$extensionKeysWithProblems [] = $extensionKeyRow['extension_key'];	
		}
	}
	echo chr(10);
}

writeExtensionIndexfile ();

echo ('

--------------------------------------------------------------------------
Summary
--------------------------------------------------------------------------
Number of processed extension keys:                    '.$extensionKeyCounter.'
Number of processed extension versions:                '.$extensionVersionCounter.'
Total processing time:                                 '.(time() - $startTime).' seconds

Extension keys with problems:
'.implode (', ', $extensionKeysWithProblems).'
');



function getExtensionDataFromRepositoryRow ($extensionKeyRow, $extensionVersionRow) {

		// *** DEPENDENCIES
	
	$typo3VersionMax = $extensionVersionRow['emconf_TYPO3_version_max'] > 0 ? versionConv ($extensionVersionRow['emconf_TYPO3_version_max'], 1) : '';
	$typo3VersionMin = $extensionVersionRow['emconf_TYPO3_version_min'] > 0 ? versionConv ($extensionVersionRow['emconf_TYPO3_version_min'], 1) : '';

	$phpVersionMax = $extensionVersionRow['emconf_PHP_version_max'] > 0 ? versionConv ($extensionVersionRow['emconf_PHP_version_max'], 1) : '';
	$phpVersionMin = $extensionVersionRow['emconf_PHP_version_min'] > 0 ? versionConv ($extensionVersionRow['emconf_PHP_version_min'], 1) : '';
	
	$typo3VersionRange = (strlen ($typo3VersionMin) && strlen ($typo3VersionMax)) ? $typo3VersionMin .'-'.$typo3VersionMax : ''; 
	$phpVersionRange = (strlen ($phpVersionMin) && strlen ($phpVersionMax)) ? $phpVersionMin .'-'.$phpVersionMax : ''; 

	$dependenciesArr = array (
		array (
			'kind' => 'depends',
			'extensionKey' => 'typo3',
			'versionRange' => $typo3VersionRange,
		),
		array (
			'kind' => 'depends',
			'extensionKey' => 'php',
			'versionRange' => $phpVersionRange,
		)
	);
	
	$otherDependenciesArr = t3lib_div::trimExplode (',',$extensionVersionRow['emconf_dependencies']);
	if (is_array ($otherDependenciesArr) && count ($otherDependenciesArr)) {
		foreach ($otherDependenciesArr as $dependencyExtKey) {
			$dependenciesArr [] = array (
				'kind' => 'depends',
				'extensionKey' => utf8_encode($dependencyExtKey),
				'versionRange' => '',
			);	
		}
	}
	$otherDependenciesArr = t3lib_div::trimExplode (',',$extensionVersionRow['emconf_conflicts']);
	if (is_array ($otherDependenciesArr) && count ($otherDependenciesArr)) {
		foreach ($otherDependenciesArr as $dependencyExtKey) {
			$dependenciesArr [] = array (
				'kind' => 'conflicts',
				'extensionKey' => utf8_encode($dependencyExtKey),
				'versionRange' => '',				
			);	
		}
	}
								
		// ** COMPILE EVERYTHING
				
	$extensionData = array (
		'extensionKey' => $extensionKeyRow['extension_key'],
		'version' => $extensionVersionRow['version'],
		'metaData' => array(
			'title' => utf8_encode ($extensionVersionRow['emconf_title']),
			'description' => utf8_encode ($extensionVersionRow['emconf_description']),
			'category' => utf8_encode($extensionVersionRow['emconf_category']),
			'state' => $extensionVersionRow['emconf_state'],
			'authorName' => utf8_encode($extensionVersionRow['emconf_author']),
			'authorEmail' => utf8_encode($extensionVersionRow['emconf_author_email']),
			'authorCompany' => utf8_encode ($extensionVersionRow['emconf_author_company']),
		),
		'technicalData' => array (
			'dataSize' => $extensionVersionRow['datasize'],
			'dataSizeCompressed' => $extensionVersionRow['datasize_gz'],
			'dependencies' => $dependenciesArr,	
			'loadOrder' => $extensionVersionRow['emconf_loadOrder'],
			'uploadFolder' => $extensionVersionRow['emconf_uploadfolder'],
			'createDirs' => $extensionVersionRow['emconf_createDirs'],
			'shy' => $extensionVersionRow['emconf_shy'],
			'modules' => $extensionVersionRow['emconf_module'],
			'modifyTables' => $extensionVersionRow['emconf_modify_tables'],
			'priority' => $extensionVersionRow['emconf_priority'],				
			'clearCacheOnLoad' => $extensionVersionRow['emconf_clearCacheOnLoad'],
			'lockType' => $extensionVersionRow['emconf_lockType'],
			'isManualIncluded' => $extensionVersionRow['is_manual_included'] ? 1 : 0,
		),
		'infoData' => array(
			'codeLines' => intval($extensionVersionRow['codelines']),
			'codeBytes' => intval($extensionVersionRow['codebytes']),
			'codingGuidelinesCompliance' => utf8_encode($extensionVersionRow['emconf_CGLcompliance']),
			'codingGuidelinesComplianceNotes' => utf8_encode($extensionVersionRow['emconf_CGLcompliance_note']),
			'uploadComment' => utf8_encode($extensionVersionRow['upload_comment']),
			'techInfo' => unserialize (utf8_encode($extensionVersionRow['techinfo'])),
		),
	);
	return $extensionData;	
}

function writeExtensionAndIconFile (&$extensionData, $filesData) { 

			// Create an old-style list of dependencies, conflicts etc. which is understood by older
			// versions of the Extension Manager:
		$typo3Version = '';
		$phpVersion = '';
		$dependenciesArr = array ();
		$conflictsArr = array ();

		foreach ($extensionData['technicalData']['dependencies'] as $dependencyArr) {
			switch ($dependencyArr['extensionKey']) {
				case 'typo3' :
					$typo3Version = $dependencyArr['versionRange'];
				break;
				case 'php':
					$phpVersion = $dependencyArr['versionRange'];
				break;
				default:
					if ($dependencyArr['kind'] == 'requires') {
						$dependenciesArr[] = $dependencyArr['extensionKey']; 	
					} elseif ($dependencyArr['kind'] == 'requires') {
						$conflictsArr[] = $dependencyArr['extensionKey']; 	
					}				
			}			
		}

			// Prepare Files Data Array:
		$preparedFilesDataArr = array();
		foreach ($filesData as $fileData) {			
			$preparedFilesDataArr[$fileData['name']] = array (
				'name' => $fileData['name'],
				'size' => $fileData['size'],
				'mtime' => $fileData['mtime'],
				'is_executable' => $fileData['is_executable'],
				'content' => $fileData['content'],
				'content_md5' => md5 ($fileData['content'])
			);
		}
		
			// Prepare EM_CONF Array:
		$preparedEMConfArr = array(
			'title' => $extensionData['metaData']['title'],
			'description' => $extensionData['metaData']['description'],
			'category' => $extensionData['metaData']['category'],
			'shy' => $extensionData['technicalData']['shy'],
			'dependencies' => implode (',', $dependenciesArr),
			'conflicts' => implode (',', $conflictsArr),
			'priority' => $extensionData['technicalData']['priority'],
			'loadOrder' => $extensionData['technicalData']['loadOrder'],
			'TYPO3_version' => $typo3Version,
			'PHP_version' => $phpVersion,
			'module' => $extensionData['technicalData']['modules'],
			'state' => $extensionData['metaData']['state'],
			'uploadfolder' => $extensionData['technicalData']['uploadFolder'],
			'createDirs' => $extensionData['technicalData']['createDirs'],
			'modify_tables' => $extensionData['technicalData']['modifyTables'],
			'clearCacheOnLoad' => $extensionData['technicalData']['clearCacheOnLoad'],
			'lockType' => $extensionData['technicalData']['lockType'],
			'author' => $extensionData['metaData']['authorName'],
			'author_email' => $extensionData['metaData']['authorEmail'],
			'author_company' => $extensionData['metaData']['authorCompany'],
			'CGLcompliance' => $extensionData['infoData']['codingGuidelineCompliance'],
			'CGLcompliance_note' => $extensionData['infoData']['codeingGuidelineComplianceNote'],
			'version' => $extensionData['version'],
		);

			// Compile T3X Data Array:
		$dataArr = array (		
			'extKey' => $extensionData['extensionKey'],
			'EM_CONF' => $preparedEMConfArr,
			'misc' => array (),
			'techInfo' => array (),
			'FILES' => $preparedFilesDataArr
		);
		
		$t3xFileUncompressedData = serialize ($dataArr);
		$t3xFileData = md5 ($t3xFileUncompressedData) . ':gzcompress:'.gzcompress ($t3xFileUncompressedData);
		$gifFileData = $preparedFilesDataArr['ext_icon.gif']['content'];


			// Create directories and build filenames:
		$firstLetter = strtolower (substr ($extensionData['extensionKey'], 0, 1));
		$secondLetter = strtolower (substr ($extensionData['extensionKey'], 1, 1));
		$fullPath = $GLOBALS['repositoryDir'].$firstLetter.'/'.$secondLetter.'/';
		
		if (@!is_dir ($GLOBALS['repositoryDir'] . $firstLetter)) mkdir ($GLOBALS['repositoryDir'] . $firstLetter);
		if (@!is_dir ($GLOBALS['repositoryDir'] . $firstLetter . '/'. $secondLetter)) mkdir ($GLOBALS['repositoryDir'] . $firstLetter . '/' .$secondLetter);

		list ($majorVersion, $minorVersion, $devVersion) = t3lib_div::intExplode ('.', $extensionData['version']);
		$t3xFileName = strtolower ($extensionData['extensionKey']).'_'.$majorVersion.'.'.$minorVersion.'.'.$devVersion.'.t3x';
		$gifFileName = strtolower ($extensionData['extensionKey']).'_'.$majorVersion.'.'.$minorVersion.'.'.$devVersion.'.gif';

			// Write the files		
		$fh = @fopen ($fullPath.$t3xFileName, 'wb');
		if (!$fh) {}
		fwrite ($fh, $t3xFileData);
		fclose ($fh);	

		if (strlen ($gifFileData)) {
			$fh = @fopen ($fullPath.$gifFileName, 'wb');
			if (!$fh) {}
			fwrite ($fh, $gifFileData);
			fclose ($fh);
		}
		
			// Write some data back to $extensionInfoData:
		$extensionData['t3xFileMD5'] = md5 ($t3xFileData);
		$extensionData['infoData']['dataSize'] = strlen ($t3xFileUncompressedData);
		$extensionData['infoData']['dataSizeCompressed'] = strlen ($t3xFileData);		
}

function writeExtensionInfoToDB ($accountData, $extensionData, $filesData, $extensionVersionRow, $versionOfExtension) {
	global $TYPO3_DB;

		// Add extension key to key table if we are processing the first version of the extension:
	if ($versionOfExtension == 1) {

		$res = $TYPO3_DB->exec_SELECTquery (
			'*',
			'tx_extrep_keytable',
			'extension_key = "'.$extensionData['extensionKey'].'"'
		);
		$oldExtensionKeyRow = $TYPO3_DB->sql_fetch_assoc ($res);

		$extensionKeyRow = array (
			'tstamp' => $extensionVersionRow['tstamp'],
			'crdate' => $extensionVersionRow['crdate'],
			'pid' => $GLOBALS['extensionsPID'],
			'extensionkey' => $extensionData['extensionKey'],
			'title' => $extensionData['metaData']['title'],
			'description' => $extensionData['metaData']['description'],
			'ownerusername' => $accountData['username'],
			'uploadpassword' => $oldExtensionKeyRow['upload_password'],
			'maxstoresize' => $oldExtensionKeyRow['maxStoreSize'],
	  		'downloadcounter' => $oldExtensionKeyRow['download_counter']			
		);		

		$TYPO3_DB->exec_INSERTquery (
			'tx_ter_extensionkeys', 
			$extensionKeyRow 
		);
	}

		// Prepare files information:
	foreach ($filesData as $fileData) {			
		$extensionData['infoData']['files'][$fileData['name']] = array (
			'name' => $fileData['name'],
			'size' => $fileData['size'],
			'mtime' => $fileData['mtime'],
			'is_executable' => $fileData['is_executable'],
		);			
		if ($fileData['name'] == 'doc/manual.sxw')	{
			$extensionInfoData['technicalData']['isManualIncluded'] = 1;
		}
	}

		// Prepare the new records:
	$extensionsRow = array (
		'tstamp' => $extensionVersionRow['tstamp'],
		'crdate' => $extensionVersionRow['crdate'],
		'pid' => $GLOBALS['extensionsPID'],
		'extensionkey' => $extensionData['extensionKey'],
		'version' => $extensionData['version'],
		'title' => $extensionData['metaData']['title'],
		'description' => $extensionData['metaData']['description'],
		'category' => $extensionData['metaData']['category'],
		'state' => $extensionData['metaData']['state'],
		'ismanualincluded' => $extensionData['technicalData']['isManualIncluded'],
		'downloadcounter' => $extensionVersionRow['download_counter'],
		't3xfilemd5' => $extensionData['t3xFileMD5'],			
	);

	$result = $TYPO3_DB->exec_INSERTquery ('tx_ter_extensions', $extensionsRow);
	$extensionUid = $TYPO3_DB->sql_insert_id();
	$extensionDetailsRow = array (
		'pid' => $GLOBALS['extensionsPID'],
		'extensionuid' => $extensionUid,
		'uploadcomment' => $extensionData['infoData']['uploadComment'],
		'lastuploadbyusername' => 'ter2-conversion',
		'lastuploaddate' => $extensionVersionRow['tstamp'],
		'datasize' => $extensionData['infoData']['dataSize'],
		'datasizecompressed' => $extensionData['infoData']['dataSizeCompressed'],
		'files' => serialize ($extensionData['infoData']['files']),
		'codelines' => $extensionData['infoData']['codeLines'],
		'codebytes' => $extensionData['infoData']['codeBytes'],
		'techinfo' => serialize ($extensionData['infoData']['techInfo']),
		'shy' => $extensionData['technicalData']['shy'],
		'dependencies' => serialize ($extensionData['technicalData']['dependencies']),
		'createdirs' => $extensionData['technicalData']['createDirs'],
		'priority' => $extensionData['technicalData']['priority'],
		'modules' => $extensionData['technicalData']['modules'],
		'uploadfolder' => $extensionData['technicalData']['uploadFolder'],
		'modifytables' => $extensionData['technicalData']['modifyTables'],
		'clearcacheonload' => $extensionData['technicalData']['clearCacheOnLoad'],
		'locktype' => $extensionData['technicalData']['lockType'],
		'authorname' => $extensionData['metaData']['authorName'],
		'authoremail' => $extensionData['metaData']['authorEmail'],
		'authorcompany' => $extensionData['metaData']['authorCompany'],
		'codingguidelinescompliance' => $extensionData['infoData']['codingGuidelinesComliance'],
		'codingguidelinescompliancenote' =>$extensionData['infoData']['codingGuidelinesComlianceNote'],
		'loadorder' => $extensionData['technicalData']['loadOrder'],
	);

	$TYPO3_DB->exec_INSERTquery ('tx_ter_extensiondetails',	$extensionDetailsRow);
  
}

	/**
	 * Conversion of version numbers to/from doubles
	 *
	 * @param	mixed		Input string, then a version number like "3.6.0" or "3.4.5rc2". If double it will be converted back to string. Version numbers after suffix is not supported higher than "9".
	 * @param	boolean		If set, the conversion is from double to string, otherwise from string to double.
	 * @return	mixed		String or double depending on input.
	 */
	function versionConv($input,$rev=FALSE)	{

			// Initializing translation table:
		$subDecIndex = array(
			'dev' => 1,
			'a' => 4,
			'b' => 5,
			'rc' => 8
		);

			// Direction of conversion:
		if ($rev)	{	// From double to string
			if (!$input)	{
				$result = '';
			} else {
				list($int,$dev) = explode('.',$input);

					// Looking for decimals:
				$suffix = '';
				if (strlen($dev))	{
					$int++;	// Increase integer since that would have been decreased last time.
					$subDecIndex = array_flip($subDecIndex);
					$suffix = $subDecIndex[substr($dev,0,1)];
					if ($suffix)	{
						$suffix.=intval(substr($dev,1));
					}
				}

					// Base part:
				$result = intval(substr($int,0,-6)).'.'.intval(substr($int,-6,-3)).'.'.intval(substr($int,-3)).$suffix;
			}
		} else {	// From string to double
			$result = t3lib_div::int_from_ver($input);
			if (ereg('(dev|a|b|rc)([0-9]*)$',strtolower($input),$reg))	{
				$dec = intval($subDecIndex[$reg[1]]).$reg[2];
				$result = (double)(($result-1).'.'.$dec);
			}
		}

		return $result;
	}

	/**
	 * Updates the "extensions.xml" file which contains an index of all uploaded
	 * extensions in the TER.
	 *
	 * @return	void
	 * @access	public 
	 */
	function writeExtensionIndexfile ()	{
		global $TYPO3_DB;

		if (!@is_dir ($GLOBALS['repositoryDir'])) throw new SoapFault (TX_TER_ERROR_GENERAL_EXTREPDIRDOESNTEXIST, 'Extension repository directory does not exist.');

		$trackTime = microtime();

		$res = $TYPO3_DB->exec_SELECTquery(
			'uid,tstamp,extensionkey,version,title,description,state,category,t3xfilemd5',
			'tx_ter_extensions',
			'1'
		);
		
			// Read the extension records from the DB:
		$extensionsAndVersionsArr = array();
		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$res2 = $TYPO3_DB->exec_SELECTquery(
				'ownerusername',
				'tx_ter_extensionkeys',
				'extensionkey="'.$row['extensionkey'].'"'
			);
			$extensionKeyRow = $TYPO3_DB->sql_fetch_assoc($res2);
			$row['ownerusername'] = $extensionKeyRow['ownerusername'];

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
			$extensionObj->appendChild (new DOMAttr ('extensionKey', $extensionKey));
			
			foreach ($extensionVersionsArr as $versionNumber => $extensionVersionArr) {
				$versionObj = $extensionObj->appendChild (new DOMElement('version'));
				$versionObj->appendChild (new DOMAttr ('version', $versionNumber));
				
				$versionObj->appendChild (new DOMElement('title', xmlentities ($extensionVersionArr['title'])));
				$versionObj->appendChild (new DOMElement('description', xmlentities ($extensionVersionArr['description'])));
				$versionObj->appendChild (new DOMElement('state', xmlentities ($extensionVersionArr['state'])));
				$versionObj->appendChild (new DOMElement('category', xmlentities ($extensionVersionArr['category'])));
				$versionObj->appendChild (new DOMElement('lastuploaddate', $extensionVersionArr['lastuploaddate']));
				$versionObj->appendChild (new DOMElement('uploadcomment', xmlentities ($extensionVersionArr['uploadcomment'])));
				$versionObj->appendChild (new DOMElement('dependencies', $extensionVersionArr['dependencies']));
				$versionObj->appendChild (new DOMElement('authorname', xmlentities ($extensionVersionArr['authorname'])));
				$versionObj->appendChild (new DOMElement('authoremail', xmlentities ($extensionVersionArr['authoremail'])));
				$versionObj->appendChild (new DOMElement('authorcompany', xmlentities ($extensionVersionArr['authorcompany'])));
				$versionObj->appendChild (new DOMElement('ownerusername', xmlentities ($extensionVersionArr['ownerusername'])));
				$versionObj->appendChild (new DOMElement('t3xfilemd5', $extensionVersionArr['t3xfilemd5']));
			}
		}

		$extensionsObj->appendChild (new DOMComment('Index created at '.date("D M j G:i:s T Y")));
		$extensionsObj->appendChild (new DOMComment('Index created in '.(microtime()-$trackTime).' ms'));
		
			// Write XML data to disc:
		$fh = @fopen ($GLOBALS['repositoryDir'].'extensions.xml.gz', 'wb');
		if (!$fh) throw new SoapFault (TX_TER_ERROR_UPLOADEXTENSION_WRITEERRORWHILEWRITINGEXTENSIONSINDEX, 'Write error while writing extensions index file: '.$GLOBALS['repositoryDir'].'extensions.xml');
		fwrite ($fh, gzencode ($dom->saveXML(), 9));
		fclose ($fh);
			
#t3lib_div::devLog('extensions','ter',0,$extensionsArr);		


	}

	/**
	 * Equivalent to htmlentities but for XML content
	 *
	 * @param	string		$string: String to encode
	 * @return	string		&,",',< and > replaced by entities
	 * @access	public 
	 */
	function xmlentities ($string) {
		return str_replace ( array ( '&', '"', "'", '<', '>' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
	}

?>