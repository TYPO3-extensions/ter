<?php
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Additional BE fields for ter extension publish task
 *
 * Creates an input field to define UIDs of pages to clear their
 * cache after successfully running the scheduler task
 *
 * @author Kai Vogel <kai.vogel@speedprogs.de>
 * @package TYPO3
 * @subpackage tx_ter
 */
class tx_ter_updateExtensionIndexTask_additionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * @var string
	 */
	protected $fieldName = 'scheduler_updateExtensionIndexTask_clearCachePages';

	/**
	 * @var string
	 */
	protected $fieldId = 'task_updateExtensionIndexTask_clearCachePages';


	/**
	 * Add an input field to define UIDs of pages to clear their
	 * cache after successfully running the scheduler task
	 *
	 * @param array Reference to the array containing the info used in the add/edit form
	 * @param object When editing, reference to the current task object. Null when adding.
	 * @param tx_scheduler_Module Reference to the calling object (Scheduler's BE module)
	 * @return array Array containg all the information pertaining to the additional fields
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
			// Initialize selected fields
		if (empty($taskInfo[$this->fieldName])) {
			$taskInfo[$this->fieldName] = '';
			if ($parentObject->CMD === 'edit') {
				$taskInfo[$this->fieldName] = $task->clearCachePages;
			}
		}

		$fieldName    = 'tx_scheduler[' . $this->fieldName . ']';
		$fieldValue   = htmlspecialchars($taskInfo[$this->fieldName]);
		$fieldHtml    = '<input type="text" name="' . $fieldName . '" id="' . $this->fieldId . '" value="' . $fieldValue . '" size="30" />';

		$additionalFields[$fieldId] = array(
			'code'     => $fieldHtml,
			'label'    => 'LLL:EXT:ter/locallang.xml:tx_ter_updateExtensionIndexTask.clearCachePages',
			'cshKey'   => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId,
		);

		return $additionalFields;
	}


	/**
	 * Checks if the given value is valid
	 *
	 * @param array Reference to the array containing the data submitted by the user
	 * @param tx_scheduler_Module Reference to the calling object (Scheduler's BE module)
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$value = str_replace(array(',', ' '), '', $submittedData[$this->fieldName]);
		return (empty($value) || ctype_digit($value));
	}


	/**
	 * Saves given integer value in task object
	 *
	 * @param array Contains data submitted by the user
	 * @param tx_scheduler_Task Reference to the current task object
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->clearCachePages = $submittedData[$this->fieldName];
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler/tasks/class.tx_scheduler_recyclergarbagecollection_additionalfieldprovider.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler/tasks/class.tx_scheduler_recyclergarbagecollection_additionalfieldprovider.php']);
}