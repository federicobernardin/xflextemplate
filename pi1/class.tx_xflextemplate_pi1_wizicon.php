<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Federico Bernardin (federico@bernardin.it)
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Class that adds the wizard icon.
 *
 * @author	Federico Bernardin <federico@bernardin.it>
 */



class tx_xflextemplate_pi1_wizicon {
	function proc($wizardItems)	{
		global $LANG;

		$LANG->includeLLFile("EXT:xflextemplate/mod1/locallang.xml");
		$wizArray=array(); //create array for new wizard items
		foreach($wizardItems as $key=>$item){
			if ($key=='common'){ //if it's position of first common element insert xft wizard
				$wizArray[$key]=$item;
				$wizArray["common_xft"] = array(
					"icon"=>t3lib_extMgm::extRelPath("xflextemplate")."pi1/xft_wiz.gif",
					"title"=>$LANG->getLL("pi1_title"),
					"description"=>$LANG->getLL("pi1_plus_wiz_description"),
						'tt_content_defValues' => array(
							'CType' => 'xflextemplate_pi1'
						)
				);
			}
			else
				$wizArray[$key]=$item;
		}

		return $wizArray;
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/xflextemplate/pi1/class.tx_xflextemplate_pi1_wizicon.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/xflextemplate/pi1/class.tx_xflextemplate_pi1_wizicon.php"]);
}

?>