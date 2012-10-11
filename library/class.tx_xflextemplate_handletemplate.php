<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2006 Federico Bernardin <federico@bernardin.it>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_xtemplate_handletemplate
 *   60:     function checkLeastInList($list2Check,$list)
 *   79:     function main(&$params,&$pObj)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

 /**
  * Class to manipulate element from TCA to extract list of xtemplate
  *
  * @package typo3
  * @subpackage xflextemplate
  * @author	Federico Bernardin <federico@bernardin.it>
  * @version 0.7.5
  */
class tx_xflextemplate_handletemplate {
	var $prefix = 'Static: ';

	/**
	 * Check if any elements (at least one) of $list2Check is present in $list
	 * Both variable will be a comma separated list
	 *
	 * @param	string		list of values to check
	 * @param	string		list of values to compare (master values)
	 * @return	boolean		true if at least one value is present, otherwise false
	 */
	function checkLeastInList($list2Check,$list){
		if (strlen($list2Check) && strlen($list)){
			$sourceArray=explode(',',$list);
			foreach($sourceArray as $value){
				if (t3lib_div::inList($list2Check,$value))
					return true;
			}
		}
		return false;
	}

	/**
	 * Adds static data structures to selector box items arrays.
	 * Adds ALL available structures
	 *
	 * @param	array		Array of items passed by reference.
	 * @param	class		Class caller (t3lib_TCEforms / t3lib_transferData depending on context)
	 * @return	void		void
	 */
	function main(&$params,&$pObj)    {
			global $LANG,$BE_USER;
			//fetch data element object
			foreach($pObj->cachedTSconfig as $key => $item){
				$ContentElementArray = $item['_THIS_ROW'];
			}
			//load language file
			$language = $LANG;
			$language->includeLLFile('EXT:xflextemplate/mod1/locallang.xml');

			$res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('title,uid,enablegroup','tx_xflextemplate_template','deleted=0 AND hidden=0');
			$this->globalConf=unserialize($GLOBALS['TYPO3_CONF_VARS']["EXT"]["extConf"]['xflextemplate']);
			//if no template is chosen 'select template' label will be shown
			if(!$ContentElementArray['xtemplate']){
				$params['items'][]=array($language->getLL('chooseTemplate'),'notemplate');
			}
			while($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){

				if ($this->checkLeastInList($BE_USER->groupList,$row['enablegroup']) || $BE_USER->user['admin'])
					$params['items'][]=Array($row['title'],$row['title']);
				else
					if(!$this->globalConf['emptyGroupnoControl'])
						$params['items'][]=Array($row['title'],$row['title']);
			}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.tx_xflextemplate_handletemplate.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.tx_xflextemplate_handletemplate.php']);
}
?>
