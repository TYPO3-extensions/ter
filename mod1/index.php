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
 * Module for the 'ter' extension.
 *
 * $Id$
 *
 * @author    Robert Lemke <robert@typo3.org>
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

unset($MCONF);
require('conf.php');
require($BACK_PATH . 'init.php');
require($BACK_PATH . 'template.php');
include('locallang.php');

$BE_USER->modAccess(
    $MCONF, 1
);                                // This checks permissions and exits if the users has no permission for entry.

class tx_ter_module1 extends \TYPO3\CMS\Backend\Module\BaseScriptClass
{

    var $pageinfo;

    /**
     * @return    [type]        ...
     */
    function init()
    {
        global $AB, $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $HTTP_GET_VARS, $HTTP_POST_VARS, $CLIENT, $TYPO3_CONF_VARS;

        parent::init();
    }

    /**
     * Adds items to the ->MOD_MENU array. Used for the function menu selector.
     *
     * @return    [type]        ...
     */
    function menuConfig()
    {
        global $LANG;
        $this->MOD_MENU = [
            'function' => [
                '1' => $LANG->getLL('function1'),
            ]
        ];
        parent::menuConfig();
    }

    /**
     * [Describe function...]
     *
     * @return    [type]        ...
     */
    function main()
    {
        global $AB, $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $HTTP_GET_VARS, $HTTP_POST_VARS, $CLIENT, $TYPO3_CONF_VARS;

        // Access check!
        // The page will show only if there is a valid page and if this page may be viewed by the user
        $this->pageinfo = BackendUtility::readPageAccess($this->id, $this->perms_clause);
        $access = is_array($this->pageinfo) ? 1 : 0;

        if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id)) {

            // Draw the header.
            $this->doc = GeneralUtility::makeInstance('mediumDoc');
            $this->doc->backPath = $BACK_PATH;
            $this->doc->form = '<form action="" method="POST">';

            // JavaScript
            $this->doc->JScode = '
                <script language="javascript">
                    script_ended = 0;
                    function jumpToUrl(URL)    {
                        document.location = URL;
                    }
                </script>
            ';
            $this->doc->postCode = '
                <script language="javascript">
                    script_ended = 1;
                    if (top.theMenu) top.theMenu.recentuid = ' . intval($this->id) . ';
                </script>
            ';

            $headerSection = $this->doc->getHeader(
                    'pages', $this->pageinfo, $this->pageinfo['_thePath']
                ) . '<br>' . $LANG->php3Lang['labels']['path'] . ': ' . GeneralUtility::fixed_lgd_cs(
                    $this->pageinfo['_thePath'], 50
                );

            $this->content .= $this->doc->startPage($LANG->getLL('title'));
            $this->content .= $this->doc->header($LANG->getLL('title'));
            $this->content .= $this->doc->spacer(5);
            $this->content .= $this->doc->section(
                "", $this->doc->funcMenu(
                $headerSection, BackendUtility::getFuncMenu(
                $this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function']
            )
            )
            );
            $this->content .= $this->doc->divider(5);


            // Render content:
            $this->moduleContent();


            // ShortCut
            if ($BE_USER->mayMakeShortcut()) {
                $this->content .= $this->doc->spacer(20) . $this->doc->section(
                        '', $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name'])
                    );
            }

            $this->content .= $this->doc->spacer(10);
        } else {
            // If no access or if ID == zero

            $this->doc = GeneralUtility::makeInstance('mediumDoc');
            $this->doc->backPath = $BACK_PATH;

            $this->content .= $this->doc->startPage($LANG->getLL('title'));
            $this->content .= $this->doc->header($LANG->getLL('title'));
            $this->content .= $this->doc->spacer(5);
            $this->content .= $this->doc->spacer(10);
        }
    }

    /**
     * Prints out the module HTML
     *
     * @return    [type]        ...
     */
    function printContent()
    {
        global $SOBE;

        $this->content .= $this->doc->middle();
        $this->content .= $this->doc->endPage();
        echo $this->content;
    }

    /**
     * Generates the module content
     *
     * @return    [type]        ...
     */
    function moduleContent()
    {

    }

}

// Make instance:
$SOBE = GeneralUtility::makeInstance('tx_ter_module1');
$SOBE->init();

// 	Include files?
reset($SOBE->include_once);
while (list(, $INC_FILE) = each($SOBE->include_once)) {
    include_once($INC_FILE);
}

$SOBE->main();
$SOBE->printContent();

?>