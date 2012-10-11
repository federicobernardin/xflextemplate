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
 *   59: class tx_xflextemplate_pi1 extends tslib_pibase
 *   78:     function main($content,$conf)
 *  107:     function init()
 *  143:     function getTemplateString($templateName)
 *  158:     function putContent($templateString)
 *  357:     function getPhotogallery($conf,$field)
 *  425:     function pageBrowse($suffix,$totalPage,$conf)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_t3lib.'class.t3lib_tsparser.php');
require_once (PATH_site."/typo3conf/ext/xflextemplate/library/class.xmlTransformation.php");

/**
 * Plugin 'xtemplate' for the 'xtemplate' extension.
 *
 * @package typo3
 * @subpackage xflextemplate
 * @author	Federico Bernardin <federico@bernardin.it>
 * @version 1.0.0
 */
class tx_xflextemplate_pi1 extends tslib_pibase {

  /**
   * @var string prefix for some place in code
   */
  var $prefixId = 'tx_xflextemplate_pi1';		// Same as class name

  /**
   * @var string realtive path to thi script
   */
  var $scriptRelPath = 'pi1/class.tx_xtemplate_pi1.php';	// Path to this script relative to the extension dir.

  /**
   * @var string extension key
   */
  var $extKey = 'xflextemplate';	// The extension key.

  /**
   * @var boolean define if extension uses cHash
   */
  var $pi_checkCHash = TRUE;

  //define stabdard variable used inside the code

  /**
   * @var array configuration array
   */
  var $conf=array();

  /**
   * @var array flexdata element for xflextemplate column
   */
  var $xflexData=array();

  /**
   * boolean variable for debugging system
   * @var boolean
   */
  var $debug = 0;

  /**
   * Configuration array from extension Manager
   * @var array
   */
  var $mgmConfiguration;

  /**
   * The main method of the PlugIn
   *
   * @param	string		The PlugIn content
   * @param	array		The PlugIn configuration
   * @return	string		The	content that is displayed on the website
   */
  function main($content,$conf)	{
    //define standard global variables
    $this->conf=$conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();

    //extract configuration
        $this->mgmConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['xflextemplate']);
        if (isset($this->mgmConfiguration['debug']))
          $this->debug = $this->mgmConfiguration['debug'];

    if ($this->debug)
      debug($this->conf,'configuration');

    //call init function to extract and recollect data
    $this->init();
    //if not static plugin is loaded generate an error, in the static template is defined many standard variable, the plugin could generate error without it
    if ($this->conf['installed']){
      if(strlen($this->template)>0){
        $content=$this->putContent($this->template);
      }
      else{
        $content=$this->pi_getLL('emptyTemplate','Template is not found or it\'s empty, control your xft template for HTML tabs or typoscript');
      }
    }
    else
      $content=$this->pi_getLL('noStaticTyposcriptLoaded','no static template is loaded!, insert inside your typoscript template the xflextemplate template');
    return $content;
  }




  /**
   * initializes the flexform and all config options
   *
   * @return	void		void
   */
  function init(){
    $this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
    //merge lConf with standard tt_content column, so in $this->cObj->data there are all data from xflextemplate
    $this->cObj->data=array_merge($this->cObj->data,xmlTransformation::getArrayFromXMLData($this->cObj->data['xflextemplate']));
    //fetch all other data from template
    $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('xml,typoscript,html','tx_xflextemplate_template','title="'.$this->cObj->data['xtemplate'].'" AND deleted=0 AND hidden=0');
    $databaseRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
    $this->typoscript=$databaseRow['typoscript'];
    //debug();
    //This code updates typoscript of template with template from page
    $GLOBALS['TSFE']->tmpl->config[count($GLOBALS['TSFE']->tmpl->config)] = $this->typoscript;
    $typoscriptParser = $GLOBALS['TSFE']->tmpl;
    $typoscriptParser->generateConfig();

    //$ts contains all typoscript from xflextemplate
    $xml=str_replace("''","'",$databaseRow['xml']);
    //create correct element data from xml in the xflextemplate
    $xmlArray=xmlTransformation::getArrayFromXML($xml);
    if(is_array($xmlArray)){
      foreach($xmlArray as $xElemet){
        $this->xflexData[$xElemet['name']]=$xElemet;
      }
    }
    //assign typoscript
    $this->typoscript=$typoscriptParser->setup;
    //$this->template=($databaseRow['html']) ? $databaseRow['html'] : $this->cObj->TEMPLATE($this->typoscript['templateFile.']);
    if(!$databaseRow['html']){
      $this->template=$this->cObj->getSubpart($this->cObj->TEMPLATE($this->typoscript['templateFile.']),  '###'.strtoupper(str_replace(' ','_',$this->cObj->data['xtemplate'])).'###');
    }
    else{
      $this->template = $databaseRow['html'];
    }
    //only for back compatibility
    $this->template=($this->template)?$this->template:$this->getTemplateString($dbrow['file']);//retrieve file data, only if is not defined in template
    //Updating conf array with typoscript
    $this->conf=t3lib_div::array_merge_recursive_overrule($this->conf,$typoscriptParser->setup);
  }

  /**
   * Retrieve the content of template, the name of template block will be retrieved from $this->cObj->data['xtemplate'] (xtemplate tt_content column)
   *
   * @param	string		name of xml part of the file
   * @return	string		The content of the template otherwise if template doesn't exist return empty string
   */
  function getTemplateString($templateName) {
    if ($templateName) { //fetch template string !DOCUMENT!
      $templateString=$this->cObj->fileResource($templateName);
      $template=$this->cObj->getSubpart($templateString, '###'.strtoupper($this->cObj->data['xtemplate']).'###');
      return $template;
    }
    return '';
  }

  /**
   * Creation of content based on type of element
   *
   * @param	string		template content string
   * @return	string		The	content to put on page
   */
  function putContent($templateString) {
    //cycle from any content element of the content data
    $hookObjectsArr = array();
    if (is_array ($TYPO3_CONF_VARS['SC_OPTIONS']['typo3conf/ext/xflextemplate/class.tx_xflextemplate_pi1.php']['processContent']))	{
      foreach ($TYPO3_CONF_VARS['SC_OPTIONS']['typo3conf/ext/xflextemplate/class.tx_xflextemplate_pi1.php']['processContent'] as $classRef)	{
        $hookObjectsArr[] = &t3lib_div::getUserObj($classRef);
      }
    }
    //analyze single object and define data
    foreach ($this->xflexData as $key=>$xftitem) {
      $conf = array();
      $confSingle = array();
      $confType = array();
      $conf = $this->conf; //cannot modify original array
      if(!$conf[$key]){
        $conf[$key] = $xftitem['xtype'];
      }
      if(is_array($conf[$conf[$key] . '.']))
        $this->substiteValueInArrayRecursive('###XFTELEMENTFIELD###', $key ,$conf[$conf[$key] . '.']);
      $confType = ($conf[$conf[$key] . '.']) ? $conf[$conf[$key] . '.'] : array();
      $conf[$key . '.'] = ($conf[$key . '.']) ? $conf[$key . '.'] : array();
      switch ($conf[$key]){
        case 'text':
        break;
        case 'image':
        case 'multimedia':
          if($conf[$key . '.']['file']){
            unset($confType['file.']['import']);
            unset($confType['file.']['import.']);
          }
        break;
        case 'cObject':
          $conf[$key] = 'COA';
        break;
      }
      $confSingle['10.'] = t3lib_div::array_merge_recursive_overrule($confType, $conf[$key . '.']);
      $confSingle['10'] = strtoupper($conf[$key]);
      if ($this->debug)
        debug($confSingle,$key);
      if($conf[$key]!='NONE')
        $this->markerArray['###' . strtoupper($key) . '###']=$this->cObj->cObjGet($confSingle);
      if ($this->debug)
        debug($this->cObj->cObjGet($confSingle),$key);

    }
    $this->markerArray['###CONTENTUID###']=$this->cObj->data['uid'];
    //merge all marker in the output content object
    $content=$this->cObj->substituteMarkerArray($templateString,$this->markerArray,'',1);
    $this->markerArray=array();
    $content = preg_replace('/###.*###/i', '', $content);
    return $content;
  }

  /**
   * Function for substitute recursively some string inside array
   * @param string $search string to search
   * @param string $replace replacement string
   * @param array $array array to navigate
   * @return none
   */
  function substiteValueInArrayRecursive($search,$replace,&$array){
    foreach($array as $key=>$item){
      if (!is_array($item)){
        if($item == $search){
          $array[$key] = str_replace($search, $replace, $item);
        }
      }
      else
        $this->substiteValueInArrayRecursive($search, $replace, $array[$key]);
    }
  }

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/pi1/class.tx_xflextemplate_pi1.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/pi1/class.tx_xflextemplate_pi1.php']);
}

?>