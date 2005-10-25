<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005 Robert Lemke (robert@typo3.org)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Module for the 'ter' extension.
 *
 * $Id$
 *
 * @author    Robert Lemke <robert@typo3.org>
 */

unset($MCONF);    
require ("conf.php");
require ($BACK_PATH."init.php");
require ($BACK_PATH."template.php");
include ("locallang.php");
require_once (PATH_t3lib."class.t3lib_scbase.php");

$BE_USER->modAccess($MCONF,1);    							// This checks permissions and exits if the users has no permission for entry.

class tx_ter_module1 extends t3lib_SCbase {

    var $pageinfo;

    /**
     * 
     */
    function init()    {
        global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$HTTP_GET_VARS,$HTTP_POST_VARS,$CLIENT,$TYPO3_CONF_VARS;

        parent::init();
    }

    /**
     * Adds items to the ->MOD_MENU array. Used for the function menu selector.
     */
    function menuConfig()    {
        global $LANG;
        $this->MOD_MENU = Array (
            "function" => Array (
                "1" => $LANG->getLL("function1"),
            )
        );
        parent::menuConfig();
    }


    function main()    {
        global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$HTTP_GET_VARS,$HTTP_POST_VARS,$CLIENT,$TYPO3_CONF_VARS;
        
        // Access check!
        // The page will show only if there is a valid page and if this page may be viewed by the user
        $this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
        $access = is_array($this->pageinfo) ? 1 : 0;
        
        if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))    {
    
                // Draw the header.
            $this->doc = t3lib_div::makeInstance("mediumDoc");
            $this->doc->backPath = $BACK_PATH;
            $this->doc->form='<form action="" method="POST">';

                // JavaScript
            $this->doc->JScode = '
                <script language="javascript">
                    script_ended = 0;
                    function jumpToUrl(URL)    {
                        document.location = URL;
                    }
                </script>
            ';
            $this->doc->postCode='
                <script language="javascript">
                    script_ended = 1;
                    if (top.theMenu) top.theMenu.recentuid = '.intval($this->id).';
                </script>
            ';

            $headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->php3Lang["labels"]["path"].": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

            $this->content.=$this->doc->startPage($LANG->getLL("title"));
            $this->content.=$this->doc->header($LANG->getLL("title"));
            $this->content.=$this->doc->spacer(5);
            $this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
            $this->content.=$this->doc->divider(5);

            
            // Render content:
            $this->moduleContent();

            
            // ShortCut
            if ($BE_USER->mayMakeShortcut())    {
                $this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
            }
        
            $this->content.=$this->doc->spacer(10);
        } else {
                // If no access or if ID == zero
        
            $this->doc = t3lib_div::makeInstance("mediumDoc");
            $this->doc->backPath = $BACK_PATH;
        
            $this->content.=$this->doc->startPage($LANG->getLL("title"));
            $this->content.=$this->doc->header($LANG->getLL("title"));
            $this->content.=$this->doc->spacer(5);
            $this->content.=$this->doc->spacer(10);
        }
    }

    /**
     * Prints out the module HTML
     */
    function printContent()    {
        global $SOBE;

        $this->content.=$this->doc->middle();
        $this->content.=$this->doc->endPage();
        echo $this->content;
    }
    
    /**
     * Generates the module content
     */
    function moduleContent()    {

    }
	
}

	// Make instance:
$SOBE = t3lib_div::makeInstance("tx_ter_module1");
$SOBE->init();

// 	Include files?
reset($SOBE->include_once);    
while(list(,$INC_FILE)=each($SOBE->include_once))    {include_once($INC_FILE);}

$SOBE->main();
$SOBE->printContent();

?>