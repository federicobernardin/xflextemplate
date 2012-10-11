<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$tempColumns = Array (
  "xtemplate" => Array (
    "exclude" => 1,
    "label" => "LLL:EXT:xflextemplate/locallang_db.xml:tt_content.xtemplate",
    "config" => Array (
      "type" => "select",
      "itemsProcFunc"=>"tx_xflextemplate_handletemplate->main",
    )
  ),

);

$xflextemplateConfiguration=unserialize($GLOBALS['TYPO3_CONF_VARS']["EXT"]["extConf"]['xflextemplate']);

t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;;1-1-1,hidden;;;;1-1-1,header;LLL:EXT:lang/locallang_general.php:LGL.name;;;2-2-2,xtemplate,--div--;LLL:EXT:xflextemplate/locallang_db.xml:xftDiv';
$TCA['tt_content']['ctrl']['requestUpdate'] .= (trim($TCA['tt_content']['ctrl']['requestUpdate']))?',xtemplate':'xtemplate';




t3lib_extMgm::addPlugin(Array('LLL:EXT:xflextemplate/locallang_db.xml:tt_content.CType_pi1', $_EXTKEY.'_pi1',t3lib_extMgm::extRelPath("xflextemplate") . 'ext_icon.gif'),'CType');

//Is set the constant into ExtManager xflextemplate will be the default content element in place of text
if ($xflextemplateConfiguration['xflextemplateIsStandardContentElement']){
  $TCA['tt_content']['columns']['CType']['config']['default'] = 'xflextemplate_pi1';
}

include_once(t3lib_extMgm::extPath('xflextemplate').'library/class.tx_xflextemplate_handletemplate.php');
include_once(t3lib_extMgm::extPath('xflextemplate').'hooks/class.tx_xflextemplate_tceforms.php');
include_once(t3lib_extMgm::extPath('xflextemplate').'hooks/class.tx_xflextemplate_tcemain.php');
include_once(t3lib_extMgm::extPath('xflextemplate').'hooks/class.tx_xflextemplate_pi1_newContentElementWizardItemsHook.php');

if (TYPO3_MODE=='BE')	{
  t3lib_extMgm::addModule('tools','txxflextemplateM1','',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
  //include wizard for displaying xft as common element
  $TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_xflextemplate_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY)."pi1/class.tx_xflextemplate_pi1_wizicon.php";
 // $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_xflextemplate_pi1_newContentElementWizardItemsHook'] = t3lib_extMgm::extPath($_EXTKEY) . 'hooks/class.tx_xflextemplate_pi1_newContentElementWizardItemsHook.php';
}


?>