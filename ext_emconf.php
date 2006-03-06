<?php

########################################################################
# Extension Manager/Repository config file for ext: "ter"
#
# Auto generated 17-02-2006 15:51
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'TYPO3 Extension Repository',
	'description' => 'SOAP-based server module for the TYPO3 Extension Repository (TER).',
	'category' => 'misc',
	'author' => 'Robert Lemke',
	'author_email' => 'robert@typo3.org',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'TYPO3 Association',
	'version' => '2.0.0',
	'_md5_values_when_last_written' => 'a:51:{s:8:".project";s:4:"edf3";s:9:"ChangeLog";s:4:"b83c";s:20:"class.tx_ter_api.php";s:4:"92e7";s:23:"class.tx_ter_helper.php";s:4:"8e0e";s:12:"ext_icon.gif";s:4:"fa7d";s:17:"ext_localconf.php";s:4:"dfba";s:14:"ext_tables.php";s:4:"9b0e";s:14:"ext_tables.sql";s:4:"2b38";s:24:"ext_typoscript_setup.txt";s:4:"2e0f";s:17:"locallang_tca.php";s:4:"252a";s:7:"tca.php";s:4:"da49";s:11:"tx_ter.wsdl";s:4:"6c16";s:24:"tx_ter_extensionkeys.gif";s:4:"de2f";s:15:"tx_ter_wsdl.php";s:4:"4104";s:13:"doc/NOTES.txt";s:4:"3c68";s:14:"doc/manual.sxw";s:4:"a026";s:15:"doc/CVS/Entries";s:4:"244f";s:18:"doc/CVS/Repository";s:4:"be5b";s:12:"doc/CVS/Root";s:4:"a7f0";s:24:"pi1/class.tx_ter_pi1.php";s:4:"74cb";s:17:"pi1/locallang.php";s:4:"5c6a";s:15:"pi1/CVS/Entries";s:4:"5dff";s:18:"pi1/CVS/Repository";s:4:"1164";s:12:"pi1/CVS/Root";s:4:"a7f0";s:25:"tests/tx_ter_testcase.php";s:4:"2722";s:52:"tests/fixtures/fixture_extuploaddataarray_zipped.dat";s:4:"e6c2";s:26:"tests/fixtures/CVS/Entries";s:4:"c4a6";s:29:"tests/fixtures/CVS/Repository";s:4:"2a10";s:23:"tests/fixtures/CVS/Root";s:4:"a7f0";s:17:"tests/CVS/Entries";s:4:"70ce";s:20:"tests/CVS/Repository";s:4:"c842";s:14:"tests/CVS/Root";s:4:"a7f0";s:12:"cli/conf.php";s:4:"4dcb";s:26:"cli/fix-uploadcomments.php";s:4:"737e";s:28:"cli/import-from-ter1_cli.php";s:4:"0a98";s:27:"cli/setreviewstates_cli.php";s:4:"1f59";s:15:"cli/CVS/Entries";s:4:"5909";s:18:"cli/CVS/Repository";s:4:"0155";s:12:"cli/CVS/Root";s:4:"a7f0";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"402f";s:14:"mod1/index.php";s:4:"8253";s:18:"mod1/locallang.php";s:4:"03d0";s:22:"mod1/locallang_mod.php";s:4:"78fb";s:19:"mod1/moduleicon.gif";s:4:"8074";s:16:"mod1/CVS/Entries";s:4:"6a65";s:19:"mod1/CVS/Repository";s:4:"1927";s:13:"mod1/CVS/Root";s:4:"a7f0";s:11:"CVS/Entries";s:4:"95f2";s:14:"CVS/Repository";s:4:"38f4";s:8:"CVS/Root";s:4:"a7f0";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.0.0-',
			'typo3' => '3.8.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
		'recommends' => array (
			'gabriel' => '0.0.0-' 		
		),
	),
);

?>