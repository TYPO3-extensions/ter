#!/usr/local/php5/bin/php
<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Francois Suter (fsuter@cobweb.ch)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * This PHP CLI script parses a log file from a TER server and counts
 * how many times each version of an extension has been downloaded.
 * The results are then sent to the TER via a SOAP call.
 *
 * Example for testing this script:
 *
 * /usr/bin/php5 {fullpath...}/process-extension-download-logs_cli.php -u terdevtestmirror -p terdevemptypassword -l /var/log/access_log -w http://ter.dev.robertlemke.de/wsdl/tx_ter_wsdl.php
 *
 * Don't forget to add the parameter --dry-run if you're just testing!
 *
 * $Id$
 *
 * @author	Francois Suter <fsuter@cobweb.ch>
 * @author	Robert Lemke <robert@typo3.org>
 * @package TYPO3
 * @subpackage tx_ter
 */

define('TER_CLI_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
require_once (TER_CLI_PATH . '../resources/ezcomponents/Base/src/base.php');

/**
 * Autoload ezc classes
 *
 * @param string $className
 */
function __autoload( $className ) {
    ezcBase::autoload( $className );
}

$input = new ezcConsoleInput();
$output = new ezcConsoleOutput();

$output->outputText(chr(10) . 'TER extension download logfile analyzer $Id$'.chr(10));

$options = array(
	'pathToTER' => $input->registerOption (
		new ezcConsoleOption(
			't',
			'terdirectory',
			ezcConsoleInput::TYPE_STRING,
			'/fileadmin/ter/',
			FALSE,
			'Relative path to the TER directory.',
			'Relative path to the TER directory. This directory must appear directly after the "GET" keyword in the access log files!',
			array(),
			array(),
			TRUE,
			FALSE,
			FALSE
		)
	),
	'username' => $input->registerOption (
		new ezcConsoleOption(
			'u',
			'username',
			ezcConsoleInput::TYPE_STRING,
			NULL,
			FALSE,
			'TER mirror username.',
			'A username of the TYPO3.org account which is member of the usergroup TER Mirrors.',
			array(),
			array(),
			TRUE,
			TRUE,
			FALSE
		)
	),
	'password' => $input->registerOption (
		new ezcConsoleOption(
			'p',
			'password',
			ezcConsoleInput::TYPE_STRING,
			NULL,
			FALSE,
			'TER mirror password.',
			'The password of the TYPO3.org account which is member of the usergroup TER Mirrors.',
			array(),
			array(),
			TRUE,
			TRUE,
			FALSE
		)
	),
	'logfile' => $input->registerOption (
		new ezcConsoleOption(
			'l',
			'logfile',
			ezcConsoleInput::TYPE_STRING,
			NULL,
			FALSE,
			'Path and filename of the logfile.',
			'Full path and filename of the Apache access logfile tracking extension downloads.',
			array(),
			array(),
			TRUE,
			TRUE,
			FALSE
		)
	),
	'WSDLURI' => $input->registerOption (
		new ezcConsoleOption(
			'w',
			'wsdl',
			ezcConsoleInput::TYPE_STRING,
			'http://repositories.typo3.org/wsdl/tx_ter_wsdl.php',
			FALSE,
			'WSDL URI',
			'The URI of the WSDL definition of the TER SOAP service. Use http://ter.dev.robertlemke.de/wsdl/tx_ter_wsdl.php for testing.',
			array(),
			array(),
			TRUE,
			FALSE,
			FALSE
		)
	),
	'dryrun' => $input->registerOption (
		new ezcConsoleOption(
			'd',
			'dry-run',
			ezcConsoleInput::TYPE_NONE,
			NULL,
			FALSE,
			'Dry run',
			'Dry run: The log file will be analyzed but the results are not uploaded to the TER. Instead the SOAP connection will be tested.',
			array(),
			array(),
			FALSE,
			FALSE,
			FALSE
		)
	),
	'help' => $input->registerOption (
		new ezcConsoleOption(
			'h',
			'help',
			ezcConsoleInput::TYPE_NONE,
			NULL,
			FALSE,
			'Help',
			'Display a little help.',
			array(),
			array(),
			FALSE,
			FALSE,
			TRUE
		)
	)
);

try {
    $input->process();
} catch ( ezcConsoleOptionException $e ) {
	$output->outputText($e->getMessage() . chr(10) . chr(10), 'failure');
    die();
}

if ($options['help']->value !== FALSE) {
	$output->outputText($input->getHelpText('TER extension download logfile analyzer') . chr(10) . chr(10));
	exit(0);
}

if (substr($options['pathToTER']->value,-1) != '/') $options['pathToTER']->value .= '/';

// Try to open the file corresponding to the argument

$fp = fopen($options['logfile']->value,'r');

// If the file can't be opened, issue error message and exit with status 2

if ($fp === false) {
	$output->outputText('Could not open file ' . $options['logfile']->value . ' for reading'."\n", 'failure');
	exit(2);
}

// Assemble regular expression for t3x files path
// Path is of the form $options['pathToTER']->value/x/x/xx_yy_1.1.1.t3x
// Only the xx_yy_1.1.1 part and the log data coming after .t3x are captured by this regexp

$t3xRegExp = '/GET '.addcslashes($options['pathToTER']->value,'/').'\w\/\w\/(.+?)\.t3x (.+)/';

// Initialise download counter and start parsing file line by line

$downloadCounter = array();
while (!feof($fp)) {
	$line = fgets($fp);

// Consider only non-empty lines that contain a t3x entry

	if (!empty($line) && strpos($line,'t3x') !== false) {

// Extract the actual extension key and version number

		$matches = array();
		$result = preg_match($t3xRegExp,$line,$matches);

// Extract HTTP return code
// (number of bytes transferred might also be extracted at a later point
// to compare with actual file size to check whether download was completed or not)
// A hit will be counted only on a succesful return code (200)

		$trailingLogData = explode(' ',substr($matches[2],strpos($matches[2],'"')));
		$returnCode = $trailingLogData[1];
		if ($returnCode == '200') {

// Split string on last dash to get extension key and version number

			$extensionKey = substr($matches[1],0,strrpos($matches[1],'_'));
			$extensionVersion = substr($matches[1],strrpos($matches[1],'_') + 1);

// Increment counter for each extension and each version of extension
// (so called "increase counter per version" :-)

			if (!isset($downloadCounter[$extensionKey])) $downloadCounter[$extensionKey] = array();
			if (!isset($downloadCounter[$extensionKey][$extensionVersion])) $downloadCounter[$extensionKey][$extensionVersion] = 0;
			$downloadCounter[$extensionKey][$extensionVersion]++;
		}
	}
}

	// Format download counter array for SOAP call to TER
$extensionVersionsAndIncrementors = array();
foreach ($downloadCounter as $extensionKey => $versionCounters) {
	foreach ($versionCounters as $extensionVersion => $count) {
		$extensionVersionsAndIncrementors[] = array(
			'extensionKey' => $extensionKey,
			'version' => $extensionVersion,
			'downloadCountIncrementor' => $count
		);
	}
}

	// SOAP call to TER
$wsdlData = file_get_contents($options['WSDLURI']->value);
file_put_contents('/tmp/typo3org_wsdl.xml', $wsdlData);
$soapClient = new SoapClient('/tmp/typo3org_wsdl.xml');
if ($options['dryrun']->value !== FALSE) {
	$output->outputText('Dry run - checking the SOAP connection: ');
	try {
		$result = $soapClient->ping('soapcheck');
	}
	catch (SoapFault $e) {
		$output->outputText('failed' . chr(10), 'failure');
		$output->outputText($e->getMessage() . chr(10) . chr(10), 'failure');
		exit(3);
	}
	$output->outputText('success' . chr(10), 'success');
} else {
	$output->outputText('Uploading results via SOAP: ');
	$accountData = array('username' => $options['username']->value, 'password' => $options['password']->value);
	try {
		$result = $soapClient->increaseExtensionDownloadCounters($accountData, $extensionVersionsAndIncrementors);
	}
	catch (SoapFault $e) {
		$output->outputText('failed' . chr(10), 'failure');
		$output->outputText($e->getMessage() . chr(10) . chr(10), 'failure');
		exit(3);
	}
	$output->outputText('success' . chr(10), 'success');
}

// Output a formatted view of the results
// This can be saved to a log file or just ignored

$table = new ezcConsoleTable($output, 60 );

$table[0][0]->content = 'Extension key';
$table[0][1]->content = 'Extension version';
$table[0][2]->content = 'Count';

$tableRowCounter = 1;
ksort($downloadCounter);
foreach ($downloadCounter as $extensionKey => $versionCounters) {
	ksort($versionCounters);
	foreach ($versionCounters as $extensionVersion => $count) {
		$table[$tableRowCounter][]->content = $extensionKey;
		$table[$tableRowCounter][]->content = $extensionVersion;
		$table[$tableRowCounter][]->content = $count;
		$tableRowCounter ++;
	}
}
$table->outputTable();

	// Close the file pointer
fclose($fp);

echo ("\n");
?>
