<?php


require_once(PATH_typo3 .'interfaces/interface.cms_newcontentelementwizarditemshook.php');
class tx_xflextemplate_pi1_newContentElementWizardItemsHook implements cms_newContentElementWizardsHook{

    public function manipulateWizardItems(&$wizardItems, &$object){
        global $LANG;
        $language = $LANG;
        $language->includeLLFile('EXT:xflextemplate/locallang_db.xml');

        $xflextemplateWizardArray= array(
      'icon' => t3lib_extMgm::extRelPath("xflextemplate") . 'pi1/xft_wiz.gif',
        'title' => 'Xflextemplate',
        'description' => $language->getLL('wizardText'),
      'params' => '&defVals[tt_content][CType]=xflextemplate_pi1',
        'tt_content_defValues' =>
        array (
          'CType' => 'xflextemplate_pi1',
        )
        );
        $tempArray = $wizardItems;
        $wizardItems = array();

        $xflextemplateConfiguration=unserialize($GLOBALS['TYPO3_CONF_VARS']["EXT"]["extConf"]['xflextemplate']);

        $templateItems=array();
        // Add templates directly to the content element wizard
        //todo adds user access
        $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('title,description','tx_xflextemplate_template','showinwizard=1 AND deleted=0 AND hidden=0');
        $counter=0;
        while ($dbrow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
            $title=$dbrow['title'];
            $counter++;
            $templateItems[]= array(
        'icon' => t3lib_extMgm::extRelPath("xflextemplate") . 'pi1/xft_wiz.gif',
        'title' => $dbrow['title'],
        'description' => $dbrow['description'],
        'params' => '&defVals[tt_content][CType]=xflextemplate_pi1&defVals[tt_content][xtemplate]='.$title,
        'tt_content_defValues' =>
            array (
          'CType' => 'xflextemplate_pi1',
          'xtemplate' => $title
            )
            );
        }

        foreach($tempArray as $key=>$item){
            $wizardItems[$key] = $item;
            if($key == 'common'){
                if ($xflextemplateConfiguration['xflextemplateIsShownInWizard']){
                    $wizardItems['common_xflextemplate_pi1'] = $xflextemplateWizardArray;
                }
                for($a=0;$a<$counter;$a++){
                    $wizardItems['xflextemplate_pi1_'.$a]=$templateItems[$a];
                }
            }
        }
    }
}