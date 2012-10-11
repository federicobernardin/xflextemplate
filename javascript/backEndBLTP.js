/***************************************************************
*  Copyright notice
*
*  (c) 2005 Federico Bernardin (federico@bernardin.it)
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
 * Main JS File for Backend Listing Templating Programming (BSTP)
 * 
 * @author Federico Bernardin <federico@bernardin.it>
 * @version 2.0
 * @package TYPO3
 * @subpackage xfletemplate
 */

//javascript global variables
var ajaxUrl;
var languageArray;

ajaxUrl = URL_xft + 'index.php';

languageArray = new Array;

//Main document ready function
$(document).ready(function(){
	
	//Array for possible dialog button label name
	var languageKeyArray = new Array('dialogYes', 'dialogNo', 'dialogOK', 'dialogCancel','showColumnTips','hiddenColumnTips');
	
	//elements array containing tanslated label objects	
	var label = new Array();
	
	//retrieve labels for translation by ajax calls
	parameters = {
		url: ajaxUrl
	}
	
	counterLabel = 0;
	$(languageKeyArray).each(function(i,j){
		label[counterLabel] = j;
		counterLabel++;
	});
	labelString = label.join(',');
	parameters.data = 'ajax=1&action=getLL&key=' + labelString;
	ajaxObj = new ajaxClass(parameters);
	ret = ajaxObj.exec();
	languageReturned = ret.split(',');
	for (j = 0; j < languageReturned.length; j++) {
		languageArray[label[j]] = languageReturned[j];
	}
	
	//create List object
	var list = new templateList;
	//enable list template object
	list.addOperationHandler();
});