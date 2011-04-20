<?php
	$extensionPath = t3lib_extMgm::extPath('ter');

	return array(
		'tx_ter_api' => $extensionPath . 'class.tx_ter_api.php',
		'tx_ter_helper' => $extensionPath . 'class.tx_ter_helper.php',
		'tx_ter_buildextensionindex' => $extensionPath . 'cli/build-extension-index.php',
		'tx_ter_module1' => $extensionPath . 'mod1/index.php',
		'tx_ter_pi1' => $extensionPath . 'pi1/class.tx_ter_pi1.php',
		'tx_ter_updateextensionindextask' => $extensionPath . 'task/class.tx_ter_updateExtensionIndexTask.php',
	);
?>