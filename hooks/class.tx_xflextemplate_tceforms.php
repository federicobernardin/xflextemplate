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
 *   57: class tx_xflextemplate_tceforms
 *   76:     function getMainFields_preProcess($table, &$row, $pObj)
 *  113:     function translatepages($list)
 *  138:     function getTCA(&$TCA,$xmlArray)
 *  203:     function getTCApalettes(&$TCA,$palettes)
 *  228:     function setSelectItems($field)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



require_once(PATH_t3lib.'class.t3lib_tsparser.php');
require_once (t3lib_extMgm::extPath('xflextemplate')."library/class.tcaTransformation.php");
require_once (t3lib_extMgm::extPath('xflextemplate')."library/class.xmlTransformation.php");

 /**
  * Hook 'tx_xflextemplate_tceforms' for the 't3lib_tceforms.getMainFieldsClass'
  * php class.
  *
  * @package typo3
  * @subpackage xflextemplate
  * @author	Federico Bernardin <federico@bernardin.it>
  * @version 2.0.0
  */
class tx_xflextemplate_tceforms	{
  /*
  * Name of plugin
  *
  * @var  string $_EXTKEY
  */
  var $_EXTKEY='xflextemplate';

  /**
   * This function translate the xml data from xft template into array for TCE Forms creation
   *
   * @param	string		E' il nome della tabella TCA di cui vengono effettuate le operazioni
   * @param	array		E' l'array  contenente i campi da inserire nelle form (e con cui creare le form)
   * @param	object		E' il puntatore alla classe con cui viene effetuato lo hook (tceforms)
   * @return	void		none
   */
  function getMainFields_preProcess($table, &$row, $pObj)	{
    if($row['xtemplate'] && $row['xtemplate']!='notemplate' && $table = 'tt_content'){ //if xtemplate is not set none to do
    //fetch from db the xml which will create the forms
      $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('typoscript,xml,palettes','tx_xflextemplate_template','title="'.$row['xtemplate'].'" AND deleted=0 AND hidden=0');
      $dbrow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
      $this->ts=t3lib_div::makeInstance('t3lib_TSparser');
      $this->ts->parse($dbrow['typoscript']);
      $xml=str_replace("''","'",$dbrow['xml']);
      //update the TCA with newer one
      $tcaTransformation = t3lib_div::makeInstance('tcaTransformation');
      $tcaTransformation->init($this->_EXTKEY, $this->ts);
      $tcaTransformation->getTCApalettes($GLOBALS['TCA']['tt_content'],$dbrow['palettes']);
      //send lis of fields to exclude to show (those are in palettes) to getFormTCA
      $palettesArray = @unserialize($dbrow['palettes']);
      /*debug($palettesArray);
      if (is_array($palettesArray) && count($palettesArray)>0) {
          $excludePalettesField= array();
          foreach ($palettesArray as $value){
              $excludePalettesField = array_merge($excludePalettesField,$value);
          }
          //$excludePalettesField = $palettesArray;
          debug($excludePalettesField);
      }
      else{
          $excludePalettesField = array();
      }*/
      $excludePalettesField = $tcaTransformation->palettesFieldsList;
      //call getFormTCA with field to exclude (changed in version 2.1)
      $tcaTransformation->getFormTCA($GLOBALS['TCA']['tt_content'],$xml,$excludePalettesField);
      $flexFields=xmlTransformation::getArrayFromXMLData($row[$this->_EXTKEY]);
     // debug($GLOBALS['TCA']['tt_content']);
      if(is_array($flexFields)){ //if flexdata is an array data will be put into flexdata array
        //put any field from flexfields
        foreach($flexFields as $key=>$obj){
          $row[$key]=($GLOBALS['TCA']['tt_content']['columns'][$key]['config']['default'] && ($obj == '')) ? $GLOBALS['TCA']['tt_content']['columns'][$key]['config']['default'] : $obj;
        }
      }
    }
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/hooks/class.tx_xflextemplate_tceforms.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/hooks/class.tx_xflextemplate_tceforms.php']);
}
?>
