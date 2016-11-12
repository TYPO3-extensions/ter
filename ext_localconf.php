<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_ter_pi1.php', '_pi1', 'list_type', 0);

// Register extension list update task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_ter_updateExtensionIndexTask'] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:tx_ter_updateExtensionIndexTask.name',
	'description' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:tx_ter_updateExtensionIndexTask.description',
	'additionalFields' => 'tx_ter_updateExtensionIndexTask_additionalFieldProvider',
];
// Register core version update task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_ter_updateCurrentVersionListTask'] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:tx_ter_updateCurrentVersionListTask.name',
	'description' => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:tx_ter_updateCurrentVersionListTask.description',
	'additionalFields' => '',
];
