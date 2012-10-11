<?php

########################################################################
# Extension Manager/Repository config file for ext "xflextemplate".
#
# Auto generated 08-11-2011 15:12
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'XFlexTemplate',
	'description' => 'general template extension to extend tt_content object in flexible way',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '2.1.0',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Federico Bernardin',
	'author_email' => 'federico@bernardin.it',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '4.2.2-5.3.99',
			'typo3' => '4.4.0-4.4.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:94:{s:9:"ChangeLog";s:4:"2af4";s:10:"README.txt";s:4:"ee2d";s:39:"class.tx_xflextemplate_importexport.php";s:4:"9f52";s:21:"ext_conf_template.txt";s:4:"2a98";s:12:"ext_icon.gif";s:4:"0cbf";s:17:"ext_localconf.php";s:4:"8517";s:14:"ext_tables.php";s:4:"11a0";s:14:"ext_tables.sql";s:4:"f9e5";s:24:"ext_typoscript_setup.txt";s:4:"425f";s:16:"locallang_db.xml";s:4:"22a8";s:13:"mychanges.txt";s:4:"42f5";s:38:"configuration/elementConfiguration.php";s:4:"d00d";s:29:"configuration/subelement.tmpl";s:4:"8348";s:14:"doc/manual.sxw";s:4:"3f93";s:19:"doc/wizard_form.dat";s:4:"e028";s:20:"doc/wizard_form.html";s:4:"54dc";s:69:"hooks/class.tx_xflextemplate_pi1_newContentElementWizardItemsHook.php";s:4:"586e";s:41:"hooks/class.tx_xflextemplate_tceforms.php";s:4:"6c4a";s:40:"hooks/class.tx_xflextemplate_tcemain.php";s:4:"7a74";s:25:"javascript/backEndBLTP.js";s:4:"bf1d";s:25:"javascript/backEndBSTP.js";s:4:"77cc";s:20:"javascript/import.js";s:4:"5f3d";s:38:"javascript/jquery/jquery-1.2.6.pack.js";s:4:"225d";s:40:"javascript/jquery/jquery-ui-1.5.3.min.js";s:4:"f1a1";s:36:"javascript/jquery/jquery.bgiframe.js";s:4:"a5bc";s:35:"javascript/jquery/jquery.blockUI.js";s:4:"a1d1";s:32:"javascript/jquery/jquery.form.js";s:4:"0fe0";s:39:"javascript/jquery/jquery.gritter.min.js";s:4:"bda6";s:39:"javascript/jquery/jquery.selectboxes.js";s:4:"ae79";s:32:"javascript/library/class.ajax.js";s:4:"0d7c";s:35:"javascript/library/class.element.js";s:4:"f7bf";s:35:"javascript/library/class.general.js";s:4:"6d48";s:40:"javascript/library/class.templateList.js";s:4:"24f9";s:48:"javascript/library/editor/css/t3editor_inner.css";s:4:"27f9";s:43:"javascript/library/editor/css/xmlcolors.css";s:4:"847a";s:42:"javascript/library/editor/js/codemirror.js";s:4:"c649";s:38:"javascript/library/editor/js/editor.js";s:4:"814b";s:43:"javascript/library/editor/js/mirrorframe.js";s:4:"9944";s:40:"javascript/library/editor/js/parsecss.js";s:4:"0e37";s:46:"javascript/library/editor/js/parsehtmlmixed.js";s:4:"ce7e";s:47:"javascript/library/editor/js/parsejavascript.js";s:4:"f18a";s:43:"javascript/library/editor/js/parsesparql.js";s:4:"f30b";s:47:"javascript/library/editor/js/parsetyposcript.js";s:4:"4808";s:40:"javascript/library/editor/js/parsexml.js";s:4:"a90f";s:38:"javascript/library/editor/js/select.js";s:4:"0530";s:44:"javascript/library/editor/js/stringstream.js";s:4:"c2a6";s:40:"javascript/library/editor/js/tokenize.js";s:4:"c008";s:50:"javascript/library/editor/js/tokenizejavascript.js";s:4:"448d";s:50:"javascript/library/editor/js/tokenizetyposcript.js";s:4:"43b8";s:36:"javascript/library/editor/js/undo.js";s:4:"12f2";s:36:"javascript/library/editor/js/util.js";s:4:"52ee";s:31:"language/locallang_template.xml";s:4:"ef91";s:33:"library/class.elementTemplate.php";s:4:"9533";s:30:"library/class.listTemplate.php";s:4:"41cb";s:35:"library/class.tcaTransformation.php";s:4:"ac1b";s:49:"library/class.tx_xflextemplate_handletemplate.php";s:4:"3dd5";s:27:"library/class.xftObject.php";s:4:"68f9";s:35:"library/class.xmlTransformation.php";s:4:"bcba";s:13:"mod1/conf.php";s:4:"1129";s:23:"mod1/help_locallang.xml";s:4:"7873";s:14:"mod1/index.php";s:4:"9ece";s:18:"mod1/locallang.xml";s:4:"8978";s:22:"mod1/locallang_mod.php";s:4:"84c8";s:19:"mod1/moduleicon.gif";s:4:"0cbf";s:22:"mod1/_notes/dwsync.xml";s:4:"37b7";s:34:"pi1/class.tx_xflextemplate_pi1.php";s:4:"f1b6";s:42:"pi1/class.tx_xflextemplate_pi1_wizicon.php";s:4:"50ea";s:17:"pi1/locallang.xml";s:4:"45e4";s:15:"pi1/xft_wiz.gif";s:4:"c305";s:19:"pi1/xft_wiz_old.gif";s:4:"7709";s:19:"res/group_clear.gif";s:4:"3881";s:17:"res/listmanage.js";s:4:"4d53";s:26:"res/css/jquery.gritter.css";s:4:"222e";s:20:"res/css/template.css";s:4:"4d6a";s:19:"res/css/ui.tabs.css";s:4:"80af";s:21:"res/css/xmlcolors.css";s:4:"9e47";s:24:"res/css/images/_tab1.png";s:4:"095d";s:27:"res/css/images/collapse.png";s:4:"0125";s:34:"res/css/images/expand-collapse.png";s:4:"dce7";s:34:"res/css/images/expand-collapse.psd";s:4:"681d";s:25:"res/css/images/expand.png";s:4:"79e7";s:36:"res/css/images/gritter-close-ie6.gif";s:4:"6ce7";s:31:"res/css/images/gritter-long.png";s:4:"73ab";s:26:"res/css/images/gritter.png";s:4:"0d28";s:25:"res/css/images/header.jpg";s:4:"e817";s:29:"res/css/images/loading_24.gif";s:4:"2139";s:28:"res/css/images/plusminus.jpg";s:4:"39d8";s:28:"res/css/images/plusminus.png";s:4:"b5da";s:32:"res/css/images/plusminus_old.png";s:4:"c8c3";s:28:"res/css/images/tab copia.png";s:4:"83a7";s:22:"res/css/images/tab.png";s:4:"83a7";s:23:"res/css/images/tab1.png";s:4:"095d";s:25:"res/css/images/tabxft.png";s:4:"3a9b";s:24:"res/css/images/trees.jpg";s:4:"f532";}',
	'suggests' => array(
	),
);

?>