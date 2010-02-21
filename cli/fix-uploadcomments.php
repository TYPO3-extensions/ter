#! /usr/bin/php -q
<?php

die ('Access denied');

	// Defining circumstances for CLI mode:
define('TYPO3_cliMode', TRUE);

define('PATH_thisScript', $_SERVER['PHP_SELF']);
require(dirname(PATH_thisScript).'/conf.php');
require(dirname(PATH_thisScript).'/'.$BACK_PATH.'init.php');


$res = $TYPO3_DB->exec_SELECTquery (
	'uid, uploadcomment',
	'tx_ter_extensiondetails',
	'1'
);

$counter = 0;
while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
	$converted = (preg_replace('/[^\w\s"%&\[\]\(\)\.\,\;\:\/\?}!\$\/]/','',$row['uploadcomment']));
	
	if (strpos($converted,'defaultRenderMethod')) echo ($converted.chr(10));	
}

?>