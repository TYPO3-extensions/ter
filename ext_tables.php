<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_div::loadTCA('tt_content');

$TCA['tx_ter_extensionkeys'] = Array (
	'ctrl' => Array (
		'label' => 'extension_key',
		'default_sortby' => 'ORDER BY extensionkey',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'title' => 'LLL:EXT:ter/locallang_tca.php:tx_ter_extensionkeys',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'tx_ter_extensionkeys.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'dividers2tabs' => TRUE,
	)
);

	// Remove the old "CODE", "Layout" and the "recursive" field and add a field which displays the flexform:
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';

	// Add our plugin and define the flexform data structure:
	
$TCA['tt_content']['types']['list']['subtypes_addlist'][9]='pi_flexform';
t3lib_extMgm::addPlugin(Array('TER SOAP Server', $_EXTKEY.'_pi1'));
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:ter/flexform_ds_pluginmode.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_ter_extensionkeys');

?>