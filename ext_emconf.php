<?php

########################################################################
# Extension Manager/Repository config file for ext: "ter"
#
# Auto generated 14-12-2006 14:05
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
	'dependencies' => 't3sec_saltedpw',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'TYPO3 Association',
	'version' => '2.0.5',
	'_md5_values_when_last_written' => 'a:203:{s:9:"ChangeLog";s:4:"04de";s:20:"class.tx_ter_api.php";s:4:"a56a";s:23:"class.tx_ter_helper.php";s:4:"072e";s:21:"ext_conf_template.txt";s:4:"0af8";s:12:"ext_icon.gif";s:4:"fa7d";s:17:"ext_localconf.php";s:4:"648c";s:14:"ext_tables.php";s:4:"9d3e";s:14:"ext_tables.sql";s:4:"2b38";s:24:"ext_typoscript_setup.txt";s:4:"cf02";s:17:"locallang_tca.php";s:4:"c8fa";s:7:"tca.php";s:4:"5d5b";s:11:"tx_ter.wsdl";s:4:"3774";s:24:"tx_ter_extensionkeys.gif";s:4:"de2f";s:15:"tx_ter_wsdl.php";s:4:"a8b2";s:24:"pi1/class.tx_ter_pi1.php";s:4:"b02d";s:17:"pi1/locallang.php";s:4:"33ab";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"51ad";s:14:"mod1/index.php";s:4:"cc1d";s:18:"mod1/locallang.php";s:4:"4ed5";s:22:"mod1/locallang_mod.php";s:4:"6bb1";s:19:"mod1/moduleicon.gif";s:4:"8074";s:36:"tests/tx_ter_api_direct_testcase.php";s:4:"f6f7";s:32:"tests/tx_ter_helper_testcase.php";s:4:"0cd2";s:30:"tests/tx_ter_soap_testcase.php";s:4:"279f";s:52:"tests/fixtures/fixture_extuploaddataarray_zipped.dat";s:4:"e6c2";s:42:"tests/fixtures/special_characters_utf8.txt";s:4:"14cc";s:13:"doc/NOTES.txt";s:4:"553e";s:14:"doc/manual.sxw";s:4:"a026";s:29:"cli/build-extension-index.php";s:4:"7150";s:12:"cli/conf.php";s:4:"e1b6";s:26:"cli/fix-uploadcomments.php";s:4:"f9bb";s:28:"cli/import-from-ter1_cli.php";s:4:"d8e3";s:43:"cli/process-extension-download-logs_cli.php";s:4:"4f14";s:27:"cli/setreviewstates_cli.php";s:4:"8f4c";s:35:"resources/ezcomponents/Base/CREDITS";s:4:"be81";s:37:"resources/ezcomponents/Base/ChangeLog";s:4:"344a";s:39:"resources/ezcomponents/Base/DESCRIPTION";s:4:"5d5e";s:40:"resources/ezcomponents/Base/src/base.php";s:4:"bfce";s:49:"resources/ezcomponents/Base/src/base_autoload.php";s:4:"1e2a";s:43:"resources/ezcomponents/Base/src/options.php";s:4:"857e";s:42:"resources/ezcomponents/Base/src/struct.php";s:4:"4e88";s:56:"resources/ezcomponents/Base/src/exceptions/exception.php";s:4:"ea20";s:61:"resources/ezcomponents/Base/src/exceptions/file_exception.php";s:4:"1c71";s:54:"resources/ezcomponents/Base/src/exceptions/file_io.php";s:4:"3429";s:61:"resources/ezcomponents/Base/src/exceptions/file_not_found.php";s:4:"6618";s:62:"resources/ezcomponents/Base/src/exceptions/file_permission.php";s:4:"b6e7";s:65:"resources/ezcomponents/Base/src/exceptions/property_not_found.php";s:4:"a11f";s:66:"resources/ezcomponents/Base/src/exceptions/property_permission.php";s:4:"7891";s:64:"resources/ezcomponents/Base/src/exceptions/setting_not_found.php";s:4:"e711";s:60:"resources/ezcomponents/Base/src/exceptions/setting_value.php";s:4:"7781";s:52:"resources/ezcomponents/Base/src/exceptions/value.php";s:4:"6c0e";s:55:"resources/ezcomponents/Base/src/exceptions/whatever.php";s:4:"b1f8";s:45:"resources/ezcomponents/Base/docs/tutorial.txt";s:4:"66ae";s:54:"resources/ezcomponents/Base/docs/tutorial_autoload.php";s:4:"18f9";s:56:"resources/ezcomponents/Base/docs/tutorial_example_01.php";s:4:"d368";s:54:"resources/ezcomponents/Base/docs/repos/Me/myclass1.php";s:4:"0890";s:54:"resources/ezcomponents/Base/docs/repos/Me/myclass2.php";s:4:"f035";s:64:"resources/ezcomponents/Base/docs/repos/autoloads/my_autoload.php";s:4:"d0af";s:66:"resources/ezcomponents/Base/docs/repos/autoloads/your_autoload.php";s:4:"5a9c";s:57:"resources/ezcomponents/Base/docs/repos/You/yourclass1.php";s:4:"048a";s:57:"resources/ezcomponents/Base/docs/repos/You/yourclass2.php";s:4:"74d5";s:47:"resources/ezcomponents/Base/tests/base_test.php";s:4:"f377";s:43:"resources/ezcomponents/Base/tests/suite.php";s:4:"0533";s:86:"resources/ezcomponents/Base/tests/test_repository/autoload_files/basetest_autoload.php";s:4:"aef9";s:84:"resources/ezcomponents/Base/tests/test_repository/autoload_files/object_autoload.php";s:4:"f681";s:81:"resources/ezcomponents/Base/tests/test_repository/TestClasses/base_test_class.php";s:4:"84cd";s:92:"resources/ezcomponents/Base/tests/test_repository/TestClasses/base_test_class_number_two.php";s:4:"b233";s:67:"resources/ezcomponents/Base/tests/test_repository/object/object.php";s:4:"dfda";s:43:"resources/ezcomponents/ConsoleTools/CREDITS";s:4:"be81";s:45:"resources/ezcomponents/ConsoleTools/ChangeLog";s:4:"a8de";s:47:"resources/ezcomponents/ConsoleTools/DESCRIPTION";s:4:"d49e";s:40:"resources/ezcomponents/ConsoleTools/TODO";s:4:"f0fa";s:58:"resources/ezcomponents/ConsoleTools/docs/example_input.php";s:4:"0b08";s:59:"resources/ezcomponents/ConsoleTools/docs/example_output.php";s:4:"25ae";s:64:"resources/ezcomponents/ConsoleTools/docs/example_progressbar.php";s:4:"9507";s:68:"resources/ezcomponents/ConsoleTools/docs/example_progressmonitor.php";s:4:"bb43";s:62:"resources/ezcomponents/ConsoleTools/docs/example_statusbar.php";s:4:"b1d7";s:58:"resources/ezcomponents/ConsoleTools/docs/example_table.php";s:4:"d358";s:60:"resources/ezcomponents/ConsoleTools/docs/example_table_2.php";s:4:"2f7b";s:53:"resources/ezcomponents/ConsoleTools/docs/tutorial.txt";s:4:"6dce";s:62:"resources/ezcomponents/ConsoleTools/docs/tutorial_autoload.php";s:4:"18f9";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_01.php";s:4:"fb9e";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_02.php";s:4:"6a12";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_03.php";s:4:"847a";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_04.php";s:4:"b069";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_05.php";s:4:"b1c4";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_06.php";s:4:"4cbc";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_07.php";s:4:"af78";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_08.php";s:4:"fb8a";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_09.php";s:4:"b7bb";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_10.php";s:4:"8b27";s:64:"resources/ezcomponents/ConsoleTools/docs/tutorial_example_11.php";s:4:"c2b5";s:81:"resources/ezcomponents/ConsoleTools/docs/img/consoletools_tutorial_example_06.png";s:4:"e3f4";s:81:"resources/ezcomponents/ConsoleTools/docs/img/consoletools_tutorial_example_07.png";s:4:"9ef8";s:81:"resources/ezcomponents/ConsoleTools/docs/img/consoletools_tutorial_example_08.png";s:4:"3be4";s:81:"resources/ezcomponents/ConsoleTools/docs/img/consoletools_tutorial_example_09.png";s:4:"37c3";s:81:"resources/ezcomponents/ConsoleTools/docs/img/consoletools_tutorial_example_10.png";s:4:"4375";s:56:"resources/ezcomponents/ConsoleTools/tests/input_test.php";s:4:"5b40";s:62:"resources/ezcomponents/ConsoleTools/tests/option_rule_test.php";s:4:"1211";s:57:"resources/ezcomponents/ConsoleTools/tests/option_test.php";s:4:"f17f";s:64:"resources/ezcomponents/ConsoleTools/tests/output_format_test.php";s:4:"7fa9";s:65:"resources/ezcomponents/ConsoleTools/tests/output_formats_test.php";s:4:"aa04";s:65:"resources/ezcomponents/ConsoleTools/tests/output_options_test.php";s:4:"2a16";s:57:"resources/ezcomponents/ConsoleTools/tests/output_test.php";s:4:"ff39";s:70:"resources/ezcomponents/ConsoleTools/tests/progressbar_options_test.php";s:4:"7b73";s:62:"resources/ezcomponents/ConsoleTools/tests/progressbar_test.php";s:4:"dc66";s:74:"resources/ezcomponents/ConsoleTools/tests/progressmonitor_options_test.php";s:4:"97cf";s:66:"resources/ezcomponents/ConsoleTools/tests/progressmonitor_test.php";s:4:"218b";s:68:"resources/ezcomponents/ConsoleTools/tests/statusbar_options_test.php";s:4:"9b97";s:60:"resources/ezcomponents/ConsoleTools/tests/statusbar_test.php";s:4:"87ac";s:51:"resources/ezcomponents/ConsoleTools/tests/suite.php";s:4:"880b";s:61:"resources/ezcomponents/ConsoleTools/tests/table_cell_test.php";s:4:"b2b7";s:64:"resources/ezcomponents/ConsoleTools/tests/table_options_test.php";s:4:"fecf";s:60:"resources/ezcomponents/ConsoleTools/tests/table_row_test.php";s:4:"0534";s:56:"resources/ezcomponents/ConsoleTools/tests/table_test.php";s:4:"eea5";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress1.dat";s:4:"9165";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testProgress10.dat";s:4:"5603";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testProgress11.dat";s:4:"7127";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testProgress12.dat";s:4:"461a";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testProgress13.dat";s:4:"1255";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress2.dat";s:4:"f3d2";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress3.dat";s:4:"4668";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress4.dat";s:4:"b5d5";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress5.dat";s:4:"1bd7";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress6.dat";s:4:"8c5b";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress7.dat";s:4:"a4ea";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress8.dat";s:4:"7ad3";s:64:"resources/ezcomponents/ConsoleTools/tests/data/testProgress9.dat";s:4:"dd57";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testProgressMonitor1.dat";s:4:"01d7";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testProgressMonitor2.dat";s:4:"f23f";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testProgressMonitor3.dat";s:4:"1757";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testProgressMonitor4.dat";s:4:"fb52";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testStatusbar1.dat";s:4:"11ef";s:65:"resources/ezcomponents/ConsoleTools/tests/data/testStatusbar2.dat";s:4:"7fc6";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable1a.dat";s:4:"8baa";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable1b.dat";s:4:"fb66";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable2a.dat";s:4:"e443";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable2b.dat";s:4:"1621";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable3a.dat";s:4:"79c3";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable3b.dat";s:4:"d73c";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable4a.dat";s:4:"7bb9";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable4b.dat";s:4:"36ac";s:62:"resources/ezcomponents/ConsoleTools/tests/data/testTable4c.dat";s:4:"7bb9";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testTableColPadding1.dat";s:4:"fa54";s:71:"resources/ezcomponents/ConsoleTools/tests/data/testTableColPadding2.dat";s:4:"dac6";s:60:"resources/ezcomponents/ConsoleTools/src/console_autoload.php";s:4:"4ae0";s:49:"resources/ezcomponents/ConsoleTools/src/input.php";s:4:"903b";s:50:"resources/ezcomponents/ConsoleTools/src/output.php";s:4:"2afb";s:55:"resources/ezcomponents/ConsoleTools/src/progressbar.php";s:4:"07c3";s:59:"resources/ezcomponents/ConsoleTools/src/progressmonitor.php";s:4:"5311";s:53:"resources/ezcomponents/ConsoleTools/src/statusbar.php";s:4:"2088";s:49:"resources/ezcomponents/ConsoleTools/src/table.php";s:4:"de32";s:58:"resources/ezcomponents/ConsoleTools/src/options/output.php";s:4:"c224";s:63:"resources/ezcomponents/ConsoleTools/src/options/progressbar.php";s:4:"fecd";s:67:"resources/ezcomponents/ConsoleTools/src/options/progressmonitor.php";s:4:"ac4f";s:61:"resources/ezcomponents/ConsoleTools/src/options/statusbar.php";s:4:"a4ee";s:57:"resources/ezcomponents/ConsoleTools/src/options/table.php";s:4:"4dc4";s:54:"resources/ezcomponents/ConsoleTools/src/table/cell.php";s:4:"3225";s:53:"resources/ezcomponents/ConsoleTools/src/table/row.php";s:4:"1ef7";s:63:"resources/ezcomponents/ConsoleTools/src/structs/option_rule.php";s:4:"db5d";s:65:"resources/ezcomponents/ConsoleTools/src/structs/output_format.php";s:4:"73d3";s:66:"resources/ezcomponents/ConsoleTools/src/structs/output_formats.php";s:4:"b11d";s:64:"resources/ezcomponents/ConsoleTools/src/exceptions/exception.php";s:4:"0791";s:74:"resources/ezcomponents/ConsoleTools/src/exceptions/invalid_option_name.php";s:4:"0d7e";s:73:"resources/ezcomponents/ConsoleTools/src/exceptions/no_position_stored.php";s:4:"4a18";s:61:"resources/ezcomponents/ConsoleTools/src/exceptions/option.php";s:4:"a846";s:80:"resources/ezcomponents/ConsoleTools/src/exceptions/option_already_registered.php";s:4:"150a";s:81:"resources/ezcomponents/ConsoleTools/src/exceptions/option_arguments_violation.php";s:4:"fc9b";s:82:"resources/ezcomponents/ConsoleTools/src/exceptions/option_dependency_violation.php";s:4:"7cbd";s:81:"resources/ezcomponents/ConsoleTools/src/exceptions/option_exclusion_violation.php";s:4:"a7d6";s:81:"resources/ezcomponents/ConsoleTools/src/exceptions/option_mandatory_violation.php";s:4:"7f1f";s:75:"resources/ezcomponents/ConsoleTools/src/exceptions/option_missing_value.php";s:4:"9889";s:70:"resources/ezcomponents/ConsoleTools/src/exceptions/option_no_alias.php";s:4:"7f94";s:72:"resources/ezcomponents/ConsoleTools/src/exceptions/option_not_exists.php";s:4:"85ad";s:83:"resources/ezcomponents/ConsoleTools/src/exceptions/option_string_not_wellformed.php";s:4:"05c1";s:77:"resources/ezcomponents/ConsoleTools/src/exceptions/option_too_many_values.php";s:4:"90a2";s:76:"resources/ezcomponents/ConsoleTools/src/exceptions/option_type_violation.php";s:4:"77c3";s:56:"resources/ezcomponents/ConsoleTools/src/input/option.php";s:4:"de4d";s:52:"resources/ezcomponents/autoload/archive_autoload.php";s:4:"f642";s:49:"resources/ezcomponents/autoload/base_autoload.php";s:4:"1e2a";s:50:"resources/ezcomponents/autoload/cache_autoload.php";s:4:"dfb0";s:58:"resources/ezcomponents/autoload/configuration_autoload.php";s:4:"72da";s:52:"resources/ezcomponents/autoload/console_autoload.php";s:4:"4ae0";s:47:"resources/ezcomponents/autoload/db_autoload.php";s:4:"078b";s:54:"resources/ezcomponents/autoload/db_schema_autoload.php";s:4:"03c5";s:50:"resources/ezcomponents/autoload/debug_autoload.php";s:4:"9f39";s:54:"resources/ezcomponents/autoload/execution_autoload.php";s:4:"478d";s:49:"resources/ezcomponents/autoload/file_autoload.php";s:4:"c067";s:59:"resources/ezcomponents/autoload/image_analyzer_autoload.php";s:4:"e19e";s:50:"resources/ezcomponents/autoload/image_autoload.php";s:4:"81c8";s:50:"resources/ezcomponents/autoload/input_autoload.php";s:4:"cc92";s:48:"resources/ezcomponents/autoload/log_autoload.php";s:4:"f8cb";s:57:"resources/ezcomponents/autoload/log_database_autoload.php";s:4:"bc4f";s:49:"resources/ezcomponents/autoload/mail_autoload.php";s:4:"64b2";s:55:"resources/ezcomponents/autoload/persistent_autoload.php";s:4:"952d";s:62:"resources/ezcomponents/autoload/persistent_object_autoload.php";s:4:"937f";s:58:"resources/ezcomponents/autoload/php_generator_autoload.php";s:4:"f41e";s:50:"resources/ezcomponents/autoload/query_autoload.php";s:4:"b931";s:51:"resources/ezcomponents/autoload/system_autoload.php";s:4:"976e";s:53:"resources/ezcomponents/autoload/template_autoload.php";s:4:"a82f";s:56:"resources/ezcomponents/autoload/translation_autoload.php";s:4:"d56e";s:62:"resources/ezcomponents/autoload/translation_cache_autoload.php";s:4:"4ed0";}',
	'constraints' => array(
		'depends' => array(
			't3sec_saltedpw' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>