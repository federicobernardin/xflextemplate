<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006  Federico Bernardin <federico@bernardin.it>
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
* This class defines procedures for managing of import/export template saved from another typo3 project
* global class variable "$lastAllowedVersion" contains the last version of xflextemplate the template can import from.
*/


/**
* Structure of xft file is:
* standard string defined if $this->tagIni
* version of xft application in the momento of template creation
* MD5 block og tag Ini
* MD5 block og serialize field
* serialization of xft object
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_xflextemplate_importexport
 *   70:     function main($content='')
 *  137:     function init()
 *  152:     function checkVersion($ver2check,$ver)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_xflextemplate_importexport{

	var $tagIni='bfxft'; //first characters in exported file
	var $tagMD5Ini;
	var $tagMD5Array;
	var $tagArray;
	var $_EXTKEY; // set from caller
	var $EMCONF;
	var $lastAllowedVersion='1.0.0'; //last version template can be imported from.

	/**
	 * This function retrieve xml content from parameter " $content" control what operation is to be done
	 *
	 * @param	string		$content
	 * @return	int		-1 version is not correctly ,0 md5 is not verified or tag ini is different, otherwise return xft object unserialized
	 */
	function main($content=''){
		//initialize class parameters
		$this->init();

		//check operation required
		switch (t3lib_div::_GP('op')){
			case 'import':  //import operation: choose a file and system checks for validate fields
				$tempArray=explode('.',$content); //
				if(is_array($tempArray)){
					foreach($tempArray as $key=>$item)
						$tempArray[$key]=base64_decode($item);
					$tagIni=$tempArray[0];
					$tagMD5Ini=$tempArray[2];
					if ($tagMD5Ini==md5($tagIni)){ //checksum control
						if($tagIni==$this->tagIni){ //initial string control
							if($this->checkVersion($this->EMCONF['version'],$tempArray[1])){ //check version control
								if($tempArray[3]==md5($tempArray[4]))//checksum control of xft content
									return unserialize($tempArray[4]);
								else // content is corrupted
									return 0;
							}
							else{ // version is not correctly
								return -1;
							}
						}
						else{ // content is corrupted
							return 0;
						}
					}
					else{ // content is corrupted
						return 0;
					}
				}
				else{ // content is corrupted
					return 0;
				}
			break;
			case 'export': // export xft in a file
				if (t3lib_div::_GP('uid')){
					//fetch template data from database
					$res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_xflextemplate_template',' uid='.t3lib_div::_GP('uid'));
					if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0){ //if any is found
						$row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
						// filename is: xft name + xft major version number + _ + xft middle version number + xft minor version number + hour + minute + seconds + day + month + year
						$filename=str_replace(' ','_',$row['title'].str_replace('.','_',$this->EMCONF['version']).date('hisdmY').'.xft');
						header('Content-type: application/xft');
						header('Content-Disposition: attachment; filename='.$filename);
						$this->tagArray=serialize($row);
						$tempArray[0]=base64_encode($this->tagIni);
						$tempArray[1]=base64_encode($this->lastAllowedVersion);
						$tempArray[2]=base64_encode($this->tagMD5Ini);
						$tempArray[3]=base64_encode(md5($this->tagArray));
						$tempArray[4]=base64_encode($this->tagArray);
						$content=implode('.',$tempArray);
						echo $content;
						exit();
					}
				}
			break;
		}
	}

	/**
	 * This function initialize some variables
	 *
	 * @return	void
	 */
	function init(){
		$_EXTKEY=$this->_EXTKEY; // defines variable $_EXTKEY used in ext_emconf file
		// include exmconf file
		require_once(t3lib_extMgm::extPath($this->_EXTKEY) . 'ext_emconf.php');
		$this->EMCONF=$EM_CONF[$_EXTKEY]; // saves in global class EMCONF the content of EMCONF file
		$this->tagMD5Ini=md5($this->tagIni); // creates MD5 of Tag Ini
	}

	/**
	 * This function chek is version is correct
	 *
	 * @param	string		$ver2check: version to verify to be minor
	 * @param	string		$ver: version to verify to be major
	 * @return	string		true if $ver2check is major than $ver, otherwise false
	 */
	function checkVersion($ver2check,$ver){
		if (t3lib_div::int_from_ver($ver2check) < t3lib_div::int_from_ver($ver))  {
			return false;
		} else {
			return true;
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/class.tx_xflextemplate_importexport.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/class.tx_xflextemplate_importexport.php']);
}

?>