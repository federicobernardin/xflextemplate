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
 */

require_once($BACK_PATH . 'sysext/cms/tslib/class.tslib_content.php');
require_once('../configuration/elementConfiguration.php');
require_once('../library/class.xftObject.php');

/**
 * List of template class.
 * This class create a table based list of template form DB
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @version 2.0
 * @package TYPO3
 * @subpackage xfletemplate
 */
class listTemplate {

  /**
   * @var object xftObject
   */
  var $xft;

  /**
   * @var string template content string
   */
  var $template;

  /**
   * @var object content object (tslib_content.php)
   */
  var $cObj;

  /**
   * @var object language TYPO3 object
   */
  var $language;

  /**
   * @var array unserialize array containing EXTCONF
   */
  var $globalConf;

  /**
   * This function bind element from parameters to element of class
   *
   * @param object language TYPO3 object
   * @param string filename string
   * @param array unserialize array containing EXTCONF
   *
   * @return void
   */
  function init($langObj, $fileName='', $globalConf){
    $this->xft = t3lib_div::makeInstance('xftObject');
    $this->cObj = t3lib_div::makeInstance('tslib_cObj');
    $this->template = ($fileName) ? file_get_contents($fileName) : '';
    $this->language = $langObj;
    $this->globalConf = $globalConf;
  }

  private function setIcon($icon, $options = array()) {
      $baseArray = array();
      switch ($icon) {
          case 'edit':
              $baseArray['title'] = $this->language->getLL('editColumnTips');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-document-open';
              break;
          case 'hide':
              $baseArray['title'] = $this->language->getLL('hideColumnTips');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-edit-hide';
              break;
          case 'unhide':
              $baseArray['title'] = $this->language->getLL('showColumnTips');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-edit-unhide';
              break;
          case 'delete':
              $baseArray['title'] = $this->language->getLL('deleteColumnTips');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-edit-delete';
              break;
          case 'export':
              $baseArray['title'] = $this->language->getLL('exportTemplateTip');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-document-export-t3d';
              break;
          case 'new':
              $baseArray['title'] = $this->language->getLL('newColumnTips');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-document-new';
              break;
          case 'import':
              $baseArray['title'] = $this->language->getLL('importTemplateTip');
              $baseArray['class'] = 'tableOperationIcon pointer-icon';
              $iconSprite = 'actions-document-import-t3d';
              break;
      }
      return t3lib_iconWorks::getSpriteIcon($iconSprite ,t3lib_div::array_merge($options,$baseArray));
  }

  /**
   * This function creates the HTML code for template list
   *
   * @return string HTML code
   */
    function getTemplateList(){
    global $BACK_PATH;
    $templateList = $this->xft->getTemplateList();
     //retrieve template subparts
    $tableContent = $this->cObj->getSubpart($this->template,'###TEMPLATELIST###');
    $rowTemplate = $this->cObj->getSubpart($tableContent,'###TEMPLATELISTCOLUMN###');
    //if there is some database rows
    if(count($templateList)){
      $columnContent = '';
      //builds column marker array
      foreach($templateList as $item){
        $markerColumnArray = array();
        $markerColumnArray['titlecolumn'] = $item['title'];
        $markerColumnArray['crdatecolumn'] = date($this->globalConf['date'],$item['crdate']);
        $markerColumnArray['tstampcolumn'] = date($this->globalConf['date'],$item['tstamp']);
        $markerColumnArray['descriptioncolumn'] = $item['description'];
        $markerColumnArray['uidRowID'] = $item['uid'];
        $hiddenIcon = ($item['hidden'])?'unhide':'hide';
        $hiddenColumnTips = ($item['hidden'])?$this->language->getLL('showColumnTips'):$this->language->getLL('hiddenColumnTips');
        $markerColumnArray['iconsColumn'] = $this->setIcon('edit', array('id' =>'edit-' . $item['uid'])) .
                                            $this->setIcon($hiddenIcon, array('id' =>'hide-' . $item['uid'])) .
                                            $this->setIcon('delete', array('id' =>'dele-' . $item['uid'])) .
                                            $this->setIcon('export', array('id' =>'export-' . $item['uid']));

        $columnContent .= $this->cObj->substituteMarkerArray($rowTemplate,$markerColumnArray,'###|###',1);
      }
    }
    //builds header marker array
    $markerTableArray['titleHeader'] = $this->language->getLL("titleHeader");
    $markerTableArray['descriptionHeader'] = $this->language->getLL("descriptionHeader");
    $markerTableArray['crdateHeader'] = $this->language->getLL("crdateHeader");
    $markerTableArray['tstampHeader'] = $this->language->getLL("tstampHeader");
    $markerTableArray['iconsHeader'] = $this->setIcon('new', array('id' =>'new-NEW')) .
                                        $this->setIcon('import', array('id' =>'import-NEW'));
    /*$markerTableArray['iconsHeader'] = '<img id="new-NEW" class="tableOperationIcon pointer-icon xftNew" ' . t3lib_iconWorks::skinImg($BACK_PATH,'gfx/new_el.gif','') . ' title="' . $this->language->getLL('newColumnTips') . '"/>
                      <img id="import-NEW" class="tableOperationIcon pointer-icon xftImport" ' . t3lib_iconWorks::skinImg($BACK_PATH,'gfx/import.gif','') . ' title="' . $this->language->getLL('newColumnTips') . '"/>';*/
    $markerTableArray['deleteelementtitle'] = $this->language->getLL('deleteelementtitle');
    $markerTableArray['deleteelementmessage'] = $this->language->getLL('deleteelementmessage');
    $content = $this->cObj->substituteSubpart($tableContent, '###TEMPLATELISTCOLUMN###', $columnContent);
    $content = $this->cObj->substituteMarkerArray($content,$markerTableArray,'###|###',1);
    return $content;
  }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.listTemplate.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/xflextemplate/library/class.listTemplate.php']);
}
?>