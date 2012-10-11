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
 *   52: class tcaTransformation
 *   61:     function getFormTCA(&$TCA,$xmlArray)
 *  121:     function getFlexFieldTCA(&$TCA,$xmlArray)
 *  176:     function getTCApalettes(&$TCA,$palettes)
 *  200:     function setSelectItems($field)
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once (t3lib_extMgm::extPath('xflextemplate')."library/class.xmlTransformation.php");

/**
 * Library for TCA management.
 *
 * @package typo3
 * @subpackage xflextemplate
 * @author	Federico Bernardin <federico@bernardin.it>
 * @version 1.1.0
 */
class tcaTransformation	{
  var $_EXTKEY='xflextemplate';

  var $ts;

  /**
   *
   * @var array contains list of palettes field (field inside palettes not field containing palettes)
   */
  public $palettesFieldsList = array();


  function init($extKey, $tsParser){
    $this->_EXTKEY = $extKey;
    $this->ts = $tsParser;
  }

/**
 * This function creates a fake TCA['tt_content'] for adding the field from flexible template.
 * It returns void but changes TCA array, so it contains the new columns as they were true.
 *
 * @param	array		TCA tree of tt_content, so you have to pass as TCA['tt_content']
 * @param	array		xml field from template
 * @return	void		none
 */
  function getFormTCA(&$TCA,$xmlArray,$excludeFieldsinPalettes = array()) {
    $fieldArray=xmlTransformation::getArrayFromXML($xmlArray); // create array of field from xml template
    $fieldArray = t3lib_div::xml2array($xmlArray);
    //var_export($fieldArray);
    if(is_array($fieldArray)){ // if array is correct
      foreach($fieldArray as $object){
        $palettes='';
        $name = $object['name'];
        foreach($object as $key=>$item){ //create TCA array from fields
          switch ($key){
            case 'name':
              $name=$item; //name of column
            break;
            case 'defaultExtras':
              $xflexTceForms[$name][$key]=$item; //valid only for rte
            break;
            case 'items':
              $xflexTceForms[$name]['config'][$key]=$this->setSelectItems($item); // items for select and radio buttons
            break;
            case 'palettes': //list of palettes
              $palettes=$item;
              $paletteArray[$item][] = $name;
            break;
            case 'palette':
            break;
            case 'xtype': //list of palettes
            break;
            case 'link':
                $xflexTceForms[$name]['config']['wizards'] = array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'script' => 'browse_links.php?mode=wizard', 'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    )
                );
                break;
            case 'maxval':
                $xflexTceForms[$name]['config']['range']['upper']=$item;
                break;
            case 'minval':
                $xflexTceForms[$name]['config']['range']['lower']=$item;
                break;
            default:
              $xflexTceForms[$name]['config'][$key]=$item; // standard config fields
            break;
          }
        }
        //defines personalization in label of field, it can be fetch from dynamicfieldtranslation
        if (is_array($this->ts->setup['language.'][$name.'.']['beLabel.']))
          $xflexTceForms[$name]['label']=($this->ts->setup['language.'][$name.'.']['beLabel.'][$GLOBALS['BE_USER']->uc['lang']])?$this->ts->setup['language.'][$name.'.']['beLabel.'][$GLOBALS['BE_USER']->uc['lang']]:$this->ts->setup['language.'][$name.'.']['beLabel.']['default'];
        //exclude field is always set to zero
        $xflexTceForms[$name]['exclude']='0';
        $globalConf=unserialize($GLOBALS['TYPO3_CONF_VARS']["EXT"]["extConf"]['xflextemplate']);
        //this fields is for RTE and other implementation of particular field (documentation in TYPO3 core api)
        if(!$xflexTceForms[$name]['defaultExtras'] && $xflexTceForms[$name]['config']['type'] == 'text') //defaultExtras is defined as follow
          $xflexTceForms[$name]['defaultExtras']=$globalConf['defaultExtra'];
        if($xflexTceForms[$name]['config']['internal_type']=='file'){
          $xflexTceForms[$name]['config']['uploadfolder']=($globalConf['uploadFolder'])?$globalConf['uploadFolder']:'uploads/pics';
          $xflexTceForms[$name]['config']['autoSizeMax'] = ($xflexTceForms[$name]['config']['autoSizeMax']) ? $xflexTceForms[$name]['config']['autoSizeMax'] : (($globalConf['autoSizeMax'])?$globalConf['autoSizeMax']:40);
        }
        //create types fields for palettes
        if (!in_array($name,$excludeFieldsinPalettes)) {
          $paletteValue=($this->translatePalettesArray[$name])?$this->translatePalettesArray[$name]:'';
          $showfields[]=$name.';;'.$paletteValue.';;';
        }

      }
    }
    $showfields=(is_array($showfields) && count($showfields)>0)?implode(',',$showfields) . ',':'';
    //Update TCA!! It's very important pass TCA for reference!!!
    $TCA['columns']=(is_array($xflexTceForms))?array_merge_recursive($TCA['columns'],$xflexTceForms):$TCA['columns'];//if template is hidden not merge array but use original TCA
    $TCA['types'][$this->_EXTKEY.'_pi1']['showitem']=$TCA['types'][$this->_EXTKEY.'_pi1']['showitem'].','. $showfields . '--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime, fe_group';
  }

/**
 * Function to create the flexform for definition of flexform field in TCA column.
 * Fields are fetched from xml data from templates table.
 *
 * @param	array		TCA tree of tt_content, so you have to pass as TCA['tt_content']
 * @param	array		xml field from template
 * @return	void		none
 */
   function getFlexFieldTCA(&$TCA,$xmlArray) {
     $fieldArray=xmlTransformation::getArrayFromXML($xmlArray); // create array of field from xml template
     $globalConf=unserialize($GLOBALS['TYPO3_CONF_VARS']["EXT"]["extConf"]['xflextemplate']);
    $fieldArray = t3lib_div::xml2array($xmlArray);
    if(is_array($fieldArray)){ // if array is correct
      foreach($fieldArray as $object){ // each object is a content subitem
        $temp=array();
        $name=$tempConfig='';
        $tempRange = array();
        foreach($object as $key=>$item){ //create TCA array from fields
          switch ($key){
            case 'name':
              $name=$item; //name of column
            break;
            case 'xtype':
            break;
            case 'defaultExtras':
              $defaultExtras='<'.$key.'>'.$item.'</'.$key.'>'; //valid only for rte
            break;
            case 'items':
              $tempConfig.= $this->setSelectItemsXML($item); // items for select and radio buttons
            break;
            case 'palettes': //list of palettes
              $palettes=$item;
            break;
            case 'maxval':
                $tempRange['upper']='<upper>' . $item . '</upper>';
                break;
            case 'minval':
                $tempRange['lower']='<lower>' . $item . '</lower>';
                break;
            case 'internal_type':
              if($item == 'file')
                $tempConfig.='<uploadfolder>'.$globalConf['uploadFolder'].'</uploadfolder>'."\n";
              $tempConfig.='<'.$key.'>'.$item.'</'.$key.'>'."\n";
            break;
            default:
              $tempConfig.='<'.$key.'>'.$item.'</'.$key.'>'."\n";
            break;
          }
        }
        if(!$defaultExtras && $object['type'] == 'text'){ //defaultExtras is defined as follow
          $defaultExtras = '<defaultExtras>' . $globalConf['defaultExtra'] . '</defaultExtras>';
        }
        if (count($tempRange)>0) {
            $tempConfig.='<range>'.implode('',$tempRange).'</range>'."\n";
        }
        //flextstring contains the xml block for each TCA column
        $flexString.='<'.$name.'><TCEforms>'.$label.'<config>'.$tempConfig.'</config>'.$defaultExtras.'</TCEforms></'.$name.'>'."\n";
      }
    }
    $flexString='<T3DataStructure><meta><langDisable>1</langDisable></meta><ROOT><type>array</type><el>'.$flexString.'</el></ROOT></T3DataStructure>';
    $xflexTceForms[$this->_EXTKEY]=array(
      'exclude' => 1,
      'config'=>array(
        "type" => "flex",
        "ds" => array (
          "default" => $flexString
        )
      )
    );
    //update TCA
    $TCA['columns'][$this->_EXTKEY]=$xflexTceForms[$this->_EXTKEY];
  }





  /**
   * This function changes the palettes part of TCA.
   * The $palettes parameter is a serilized array containing palette data
   *
   * @param	array		TCA tree of tt_content, so you have to pass as TCA['tt_content']
   * @param	array		palettes serialized array
   * @return	void		none
   */
  function getTCApalettes(&$TCA,$palettes) {
    //unserialize the value palettes
    $palettesArray=unserialize($palettes);
    // Order array by means of key
    ksort($TCA['palettes']);
    // fetch last key (greatest)
    end($TCA['palettes']);
     $last=key($TCA['palettes'])+1;
     //in this way $last is the last index +1 (gratest index) in the array and function uses a grater value
    if(is_array($palettesArray)){
      foreach($palettesArray as $key=>$value){
        $TCA['palettes'][$last]=array('showitem'=>$value);
        $this->palettesFieldsList = $this->attachPalettesValueToArray($this->palettesFieldsList, $value);
        $this->translatePalettesArray[$key]=$last;
        $last++;
      }
    }
  }

    /**
     * This function manages the merging mechnics of palettes item
     * @param $palettesFinalArray array contains merged pallettes
     * @param $palettesList array contains comma separated values palettes field
     * @return array merged
     */
    function attachPalettesValueToArray($palettesFinalArray, $palettesList) {
        $temporaryArray = explode(',',$palettesList);
        if (is_array($temporaryArray) && is_array($palettesFinalArray)){
             return array_merge($palettesFinalArray,$temporaryArray);
        }
        else{
            return $palettesFinalArray;
        }
    }

  /**
   * This function creates the array from items will be passed to TCA constructor for creating select or radio items.
   *
   * @param	string		in this parameter there are all items for select or radio separated from carriage return "/n" and each item is comma separated
   * @return	void		an array with row and column for each item
   */
  function setSelectItems($field){
    $rowArray=explode("\n",$field);
    foreach($rowArray as $value){
      $tmpArray[]=explode(',',$value);
    }
    return $tmpArray;
  }

  /**
   * This function creates the xml for flexdata from items will be passed to TCA constructor for creating select or radio items.
   *
   * @param	string		in this parameter there are all items for select or radio separated from carriage return "/n" and each item is comma separated
   * @return	void		an xml string with row and column for each item
   */
  function setSelectItemsXML($field){
    $rowArray=explode("\n",$field);
    foreach($rowArray as $value){
      if (strstr(',',$value))
        $tmpArray[]=explode(',',$value);
      else{
        $tmpArray[]=array($value,$value);
      }
    }
    $XMLArray = t3lib_div::array2xml($tmpArray);
    $XMLArray = str_replace('phparray', 'items', $XMLArray);
    $XMLArray = str_replace('type="array"', '', $XMLArray);
    return $XMLArray;
  }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.tcaTransformation.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.tcaTransformation.php']);
}
?>