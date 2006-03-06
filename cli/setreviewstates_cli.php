#! /usr/local/bin/php -q
<?php

/**
 * This script evaluates all review rating records (RRR...) of the review plugin and
 * sets the review state in the tx_ter_extensions and tx_terfe_extensions tables
 * accordingly.
 * 
 * Use this if - why ever - all reviewstate information in the above mentioned tables
 * is lost and you need to rebuild it.
 */

die ('Inactive');

	// Defining circumstances for CLI mode:
define('TYPO3_cliMode', TRUE);

	// Defining PATH_thisScript here: Must be the ABSOLUTE path of this script in the right context:
	// This will work as long as the script is called by it's absolute path!
#define('PATH_thisScript', (is_array($_ENV) && isset($_ENV['_'])) ?$_ENV['_']:$_SERVER['_']);
define('PATH_thisScript', $_SERVER['PHP_SELF']);

require(dirname(PATH_thisScript).'/conf.php');
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');


$res = $TYPO3_DB->exec_SELECTquery (
	'extensionkey,version',
	'tx_ter_extensions',
	'1'
);

while ($extensionRow = $TYPO3_DB->sql_fetch_assoc ($res)) {

	$res2 = $TYPO3_DB->exec_SELECTquery (
		'rating',
		'tx_terfe_reviewratings',
		'extensionkey="'.$extensionRow['extensionkey'].'" AND version="'.$extensionRow['version'].'"'
	);

	$positiveRatings = 0;
	$ratingResult = 0;

	while ($ratingRow = $TYPO3_DB->sql_fetch_assoc($res2)) {
		if ($ratingRow['rating'] == 1) {
			$positiveRatings++;
			if ($positiveRatings > 1) {
				$ratingResult = 1;
				break;	
			}
		}
		if ($ratingRow['rating'] == -1) {
			$ratingResult = -1;
			break;	
		}
	}
	
	if ($ratingResult != 0) {
		echo ($extensionRow['extensionkey'].' ('.$extensionRow['version'].') - state: '.$ratingResult.chr(10));
		$TYPO3_DB->exec_UPDATEquery (
			'tx_ter_extensions',
			'extensionkey="'.$extensionRow['extensionkey'].'" AND version="'.$extensionRow['version'].'"',
			array ('reviewstate' => $ratingResult)
		);	

		$TYPO3_DB->exec_UPDATEquery (
			'tx_terfe_extensions',
			'extensionkey="'.$extensionRow['extensionkey'].'" AND version="'.$extensionRow['version'].'"',
			array ('reviewstate' => $ratingResult)
		);	
	}
}

?>