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
 * element template class
 * Manage single element with all features
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @version 2.0
 * @package TYPO3
 * @subpackage xfletemplate
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */

require_once($BACK_PATH . 'sysext/cms/tslib/class.tslib_content.php');
require_once('../configuration/elementConfiguration.php');

class elementTemplate {

  /**
   * @var object cObject (tslib_content.php) for using template functions
   */
  var $cObj;

  /**
   * @var string template string
   */
  var $template;

  var $language;

  /**
   * This function defines template and content object
   * @param string name of file
   *
   * @return  void
   */
  function init($fileName=''){
      global $LANG;
      $this->language = $LANG;
    $this->cObj = t3lib_div::makeInstance('tslib_cObj');
    $this->template = ($fileName) ? file_get_contents($fileName) : '';
  }

  /**
   * This function retrieves subelement string
   *
   * @param string subelement marker string
   * @param string filename
   *
   * @return string subelement template string
   */
  function getSubElementObject($elementName,$fileName=''){
    if(!$this->template)
      $this->init($fileName);
    return ($this->template && $elementName)?$this->cObj->getSubpart($this->template,strtoupper($elementName)):'errore';
  }

  /**
   * This function creates the subelement and return the subelement part
   *
   * @param string name of subelement type
   * @param array configuration array
   *
   * @return string subelement code
   */
  function setSubElementType($elementName = 'inputType',$elementArray = array()){
    global $LANG;
    //retrieves subelement template
    $content = $this->getSubElementObject(strtoupper($elementName) . '_SUBELEMENT');

    //retrieve the type of element for prepending element array value
    $preLabelElementArray = substr($elementName,0,strlen($elementName)-4);
    $markerArray = array();
    foreach($GLOBALS['configuration']['subElement'][$elementName] as $item){
      $markerArray[$item . '_label'] = $LANG->sL('LLL:EXT:xflextemplate/language/locallang_template.xml:' . $item . 'label');
    //setting delete icon
    $markerArray['HELPICON' . $GLOBALS['configuration']['subElementHelpCode'][$elementName][$item]] = $this->setIcon('help', array('helperCode' => $GLOBALS['configuration']['subElementHelpCode'][$elementName][$item]));
      //checkbox
      if(t3lib_div::inList('wrap,show_thumbs,link,',$item)){
        $markerArray[$item . 'checked'] = ($elementArray[$preLabelElementArray . '_' . $item]) ? 'checked' : '';
      }
      //select
      if(t3lib_div::inList('internal_type',$item)){
        if ($elementArray[$preLabelElementArray . '_' . $item] == 'file'){
          $markerArray['fileselected'] = 'selected';
          $markerArray['databaseselected'] = '';
        }
        elseif($elementArray[$preLabelElementArray . '_' . $item] == 'db'){
          $markerArray['fileselected'] = '';
          $markerArray['databaseselected'] = 'selected';
        }
        else{
          $markerArray['fileselected'] = '';
          $markerArray['databaseselected'] = '';
        }
        $markerArray[$item . 'selected'] = ($elementArray[$preLabelElementArray . '_' . $item] == 'file') ? 'checked' : '';
      }
    }
    //retrieves value from configuration array
    $markerValueArray = $this->getSubElementValueArray($elementName, $elementArray);
    //merge array
    $markerArray = t3lib_div::array_merge_recursive_overrule($elementArray,$markerArray);
    $markerArray = t3lib_div::array_merge_recursive_overrule($markerValueArray,$markerArray);
    $content = $this->cObj->substituteMarkerArray($content,$markerArray,'###|###',1);
    //removes markers not used
    $content = preg_replace('/###[a-zA-Z0-9]*###/','',$content);
    return $content;
  }

  /**
   * This function defines the element object (this function calls setSubElementType)
   *
   * @param string subelememnt type
   * @param array array of subelement
   *
   * @return string element code
   * @see setSubElemntType
   */
  function setSubElement($elementName,$elementArray = array()){
    global $LANG, $BACK_PATH;
    $content = $this->getSubElementObject('MAIN_ELEMENT');
    //setting element label
    if (count($GLOBALS['configuration']['subElement']['main'])){
      foreach($GLOBALS['configuration']['subElement']['main'] as $item){
        $markerArray[$item] = $LANG->sL('LLL:EXT:xflextemplate/language/locallang_template.xml:' . $item);
      }
    }

    //setting type select
    $optionType = array();
    if (count($GLOBALS['configuration']['subElement']['type'])){
      foreach($GLOBALS['configuration']['subElement']['type'] as $item){
        $selected = ($elementArray['type'] == $item) ? ' selected ' : '';
        $optionType[] = '<option value="' . $item . '"' . $selected . '>' . $LANG->sL('LLL:EXT:xflextemplate/language/locallang_template.xml:' . $item) . '</option>';
      }
    }
    $markerArray['TYPESELECT'] = implode(chr(10),$optionType);

    //setting rendering type select
    $optionType = array();
    if (count($GLOBALS['configuration']['subElement']['xtype'])){
      foreach($GLOBALS['configuration']['subElement']['xtype'] as $item){
        $selected = ($elementArray['xtype'] == $item) ? ' selected ' : '';
        $optionType[] = '<option value="' . $item . '"' . $selected . '>' . $LANG->sL('LLL:EXT:xflextemplate/language/locallang_template.xml:' . $item) . '</option>';
      }
    }
    $markerArray['XTYPESELECT'] = implode(chr(10),$optionType);

    //setting palettes select
    $optionType = array();
    $optionType[] = '<option value="none">' . $LANG->sL('LLL:EXT:xflextemplate/language/locallang_template.xml:palettenone') . '</option>';
    if (is_array($elementArray['paletteArray']) && count($elementArray['paletteArray'])){
      foreach($elementArray['paletteArray'] as $item){
        $paletteSubItem = explode('_',$item);
        $selected = ($elementArray['palette'] == 'element_' . $paletteSubItem[1]) ? ' selected ' : '';
        if($paletteSubItem[0] != $elementArray['title']){
          $optionType[] = '<option value="element_' . $paletteSubItem[1] . '" ' . $selected . '>' . $paletteSubItem[0] . '</option>';
        }
      }
    }
    $markerArray['PALETTESELECT'] = implode(chr(10),$optionType);

    //setting delete icon
    $markerArray['DELETEICON'] = '<img ' . t3lib_iconWorks::skinImg($BACK_PATH,'gfx/garbage.gif','') . '/>';

    foreach($GLOBALS['configuration']['subElementHelpCode']['main'] as $value){
        //setting delete icon
        $markerArray['HELPICON'.$value] = $this->setIcon('help',array('helperCode'=>$value));
    }

    //setting titlebar
    $markerArray['TITLEVALUE'] = htmlentities($elementArray['title']);

    //setting subelement
    $markerArray['SUBELEMENT'] = $this->setSubElementType($elementName,$elementArray);

    //setting and merge markerarray and elementarray
    $markerArray = t3lib_div::array_merge_recursive_overrule($elementArray,$markerArray);


    $content = $this->cObj->substituteMarkerArray($content,$markerArray,'###|###',1);
    $content = preg_replace('/###[a-zA-Z0-9_]*###/','',$content);

    //return element
    return $content;
  }

  /**
   * This function return an array with value for every field extract from element array
   * @param string name of element type
   * @param array array of element value
   *
   * @return array markerArray with values
   */
  function getSubElementValueArray($elementName,$elementArray = array()){
    $markerArray = array();
    if (count($elementArray)){
      foreach($GLOBALS['configuration']['subElement'][$elementName] as $item){
        $markerArray[$item . 'value'] = $elementArray[substr($elementName,0,strlen($elementName)-4) . '_' . $item];
      }
    }
    return $markerArray;
  }


    /**
     * This function return icon in the sprite format
     * @param $icon string icon name (not sprite name, see below)
     * @param $options array this array contains the attribute of icon: title, class, id etc...
     * @return string sprite icon
     */
  private function setIcon($icon, $options = array()) {
      $baseArray = array();
      switch ($icon) {
          case 'help':
              $baseArray['title'] = $this->language->sL('LLL:EXT:xflextemplate/mod1/locallang.xml: helpTip');
              $baseArray['class'] = 'pointer-icon xftHelp';
              $baseArray['style'] = 'margin: 10px 10px 10px 5px;';
              $iconSprite = 'actions-system-help-open';
              break;
          case 'newElement':
             // $baseArray['title'] = $this->language->getLL('xftNewElementTitle');
              $baseArray['class'] = 'pointer-icon xftNewElement';
              $baseArray['style'] = 'margin: 0 5px';
              $iconSprite = 'actions-document-new';
              break;
          case 'save':
              //$baseArray['title'] = $this->language->getLL('xftSaveDokTitle');
              $baseArray['class'] = 'pointer-icon xftSaveDok';
              $iconSprite = 'actions-document-save';
              break;
          case 'close':
              //$baseArray['title'] = $this->language->getLL('xftCloseDokTitle');
              $baseArray['class'] = 'tableOperationIcon pointer-icon xftCloseDok';
              $iconSprite = 'actions-document-close';
              break;
      }
      return t3lib_iconWorks::getSpriteIcon($iconSprite ,t3lib_div::array_merge($options,$baseArray));
  }

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.elementTemplate.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.elementTemplate.php']);
}
?>