<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

	// Add tables to TCA
$TCA['tx_ter_extensionkeys'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionkey',
		'tstamp'            => 'tstamp',
		'crdate'            => 'crdate',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionkeys',
		'iconfile'          => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'tx_ter_extensionkeys.gif',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensionmembers'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionkey',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionmembers',
		'iconfile'          => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'tx_ter_extensionmembers.gif',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'tca.php',
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
		'iconfile'          => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'tx_ter_extensions.gif',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensiondetails'] = array (
	'ctrl' => array (
		'label'             => 'extensionuid',
		'label_userFunc'    => 'EXT:ter/class.tx_ter_tcaLabel.php:tx_ter_tcaLabel->getExtensionKey',
		'default_sortby'    => 'ORDER BY extensionuid',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensiondetails',
		'iconfile'          => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'tx_ter_extensiondetails.gif',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

$TCA['tx_ter_extensionqueue'] = array (
	'ctrl' => array (
		'label'             => 'extensionkey',
		'default_sortby'    => 'ORDER BY extensionuid',
		'crdate'            => 'crdate',
		'tstamp'            => 'tstamp',
		'delete'            => 'deleted',
		'title'             => 'LLL:EXT:ter/locallang_tca.xml:tx_ter_extensionqueue',
		'iconfile'          => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'tx_ter_extensionqueue.gif',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'tca.php',
		'dividers2tabs'     => TRUE,
	)
);

	// Remove the old "CODE", "Layout" and the "recursive" fields
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages,recursive';

	// Add plugin and datasets
ExtensionManagementUtility::addPlugin(array('TER SOAP Server', $_EXTKEY . '_pi1'));
ExtensionManagementUtility::allowTableOnStandardPages('tx_ter_extensionkeys');
ExtensionManagementUtility::allowTableOnStandardPages('tx_ter_extensionmembers');
ExtensionManagementUtility::allowTableOnStandardPages('tx_ter_extensions');
ExtensionManagementUtility::allowTableOnStandardPages('tx_ter_extensiondetails');
ExtensionManagementUtility::allowTableOnStandardPages('tx_ter_extensionqueue');

	// Add static configuration files
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'resources/static/', 'TER Server');

?>