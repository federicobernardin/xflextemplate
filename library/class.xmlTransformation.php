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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   44: class tx_xft_div
 *   55:     function getArrayFromXML($xml)
 *   80:     function getArrayFromXMLData($xml)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Library for XML transformation management.
 *
 * @package typo3
 * @subpackage xflextemplate
 * @author	Federico Bernardin <federico@bernardin.it>
 * @version 1.1.0
 */
class xmlTransformation {


	/**
	 * This function creates the array from an xml passed as parameter to an array in output
	 * The use of this function is restricted to xml in xft format (like defined in table tx_xflextemplate_template)
	 *
	 * @param	string		$xml: xml from template
	 * @return	array		an array with data from xml
	 */
	function getArrayFromXML($xml){
		$XMLArray=array(); //set value, this value will be send to array merge overrule, and if it'isn't an array an error will raise
		$tmpArray=t3lib_div::xml2array($xml); // tranform xml into array typo3 format
		if(is_array($tmpArray)){
			$index = 0;
				foreach($tmpArray as $object){ // cycle from element inside array
					foreach($object as $key=>$item){
						if(strstr($key,$object['type'] . '_'))
							$XMLArray[$index][substr($key,strlen($object['type'])+1)]=$item; // save value into XMLArray
						else
							if($key == 'type')
								$XMLArray[$index][$key]=$object['type'];
							else									
								$XMLArray[$index][$key]=$item;
					}
					$index++; // increment index of final array
				}
		}
		return $XMLArray;
	}

	/**
	 * This function creates the array from an xml passed as parameter to an array in output
	 * The use of this function is restricted to xml in xft format (like defined in table tx_xflextemplate_template)
	 * The different from getArrayFromXML is the format of xml, this is the xml of Typo3 FlexData
	 *
	 * @param	string		$xml: xml from template
	 * @return	array		an array with data from xml
	 */
	function getArrayFromXMLData($xml){
		$XMLArray=array(); //set value, this value will be send to array merge overrule, and if it'isn't an array an error will raise
		$tmpArray=t3lib_div::xml2array($xml); // tranform xml into array typo3 format
		if(is_array($tmpArray))	{
			$tmpArray=$tmpArray['data']['sDEF']['lDEF']; // define start level inside array
			$index=0; // reset index of final array result
			if(is_array($tmpArray)){
				foreach($tmpArray as $key=>$item){ // cycle from element inside array
					$XMLArray[$key]=$item['vDEF']; // save value into XMLArray
				}
			}
		}
		return $XMLArray;
	}	
	
/**
	 * This function creates the array of Flex Form in TYPO3 format from the array passed as value
	 *
	 * @param	array		$dataArray: array to transform
	 * @param array			$rteArray: the text type element are set to insert rte transformation flag
	 * @return	array		an array with data from input in TYPO3 flex form format
	 */
	function getXMLDataFromArray($dataArray, $rteArray){
		if(is_array($dataArray)){
			foreach($dataArray as $key=>$item){
				$resultArray[$key]['vDEF']=$item; //create single item in form: [$key]['vDEF']=item
				//if element is text type add _TRANSFORM_vDEF for rte transformation
				if (isset($rteArray[$key]))
					$resultArray[$key]['_TRANSFORM_vDEF'] = 'RTE';
			}
		}
		$XMLDataArray['data']['sDEF']['lDEF']=$resultArray; // link array created above with ['data']['sDEF']['lDEF']
		return $XMLDataArray;
	}

}





if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.xmlTransformation.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.xmlTransformation.php']);
}

?>