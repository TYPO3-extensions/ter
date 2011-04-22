<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');

	// Add tables to TCA
$TCA['tx_ter_extensionkeys'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionkey',
		'tstamp'            => 'tstamp',
		'crdate'            => 'crdate',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'tx_ter_extensionkeys.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensionmembers'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionkey',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'tx_ter_extensionmembers.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensions'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionkey',
		'tstamp'            => 'tstamp',
		'crdate'            => 'crdate',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensions',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'tx_ter_extensions.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensiondetails'] = array (
	'ctrl' => array (
		'label'             => 'extensionuid',
		'label_userFunc'    => 'EXT:ter/class.tx_ter_tcaLabel.php:tx_ter_tcaLabel->getExtensionKey',
		'default_sortby'    => 'ORDER BY extensionuid',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'tx_ter_extensiondetails.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

	// Remove the old "CODE", "Layout" and the "recursive" fields
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages,recursive';

	// Add plugin and datasets
t3lib_extMgm::addPlugin(array('TER SOAP Server', $_EXTKEY . '_pi1'));
t3lib_extMgm::allowTableOnStandardPages('tx_ter_extensionkeys');
t3lib_extMgm::allowTableOnStandardPages('tx_ter_extensionmembers');
t3lib_extMgm::allowTableOnStandardPages('tx_ter_extensions');
t3lib_extMgm::allowTableOnStandardPages('tx_ter_extensiondetails');

	// Add static configuration files
t3lib_extMgm::addStaticFile($_EXTKEY, 'resources/static/', 'TER Server');

?>