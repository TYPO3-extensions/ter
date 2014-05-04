<?php
	$extensionPath = t3lib_extMgm::extPath('ter');

	return array(
		'tx_ter_api' => $extensionPath . 'class.tx_ter_api.php',
		'tx_ter_helper' => $extensionPath . 'class.tx_ter_helper.php',
		'tx_ter_buildextensionindex' => $extensionPath . 'cli/build-extension-index.php',
		'tx_ter_module1' => $extensionPath . 'mod1/index.php',
		'tx_ter_pi1' => $extensionPath . 'pi1/class.tx_ter_pi1.php',
		'tx_ter_updatecurrentversionlisttask' => $extensionPath . 'task/class.tx_ter_updateCurrentVersionListTask.php',
		'tx_ter_updateextensionindextask' => $extensionPath . 'task/class.tx_ter_updateExtensionIndexTask.php',
		'tx_ter_updateextensionindextask_additionalfieldprovider' => $extensionPath . 'task/class.tx_ter_updateExtensionIndexTask_additionalFieldProvider.php',
		'tx_ter_exception' => $extensionPath . 'class.tx_ter_exception.php',
		'tx_ter_exception_unauthorized' => $extensionPath . 'exception/class.tx_ter_exception_unauthorized.php',
		'tx_ter_exception_faileddependency' => $extensionPath . 'exception/class.tx_ter_exception_failedDependency.php',
		'tx_ter_exception_versionexists' => $extensionPath . 'exception/class.tx_ter_exception_versionExists.php',
		'tx_ter_exception_internalservererror' => $extensionPath . 'exception/class.tx_ter_exception_internalServerError.php',
		'tx_ter_exception_notfound' => $extensionPath . 'exception/class.tx_ter_exception_notFound.php',
		'tx_ter_exception_invalidextensionkey' => $extensionPath . 'exception/class.tx_ter_exception_invalidExtensionKey.php',
	);
?>
