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
 * Ajax class for managing post calls
 * 
 * @author Federico Bernardin <federico@bernardin.it>
 * @version 2.0
 * @package TYPO3
 * @subpackage xfletemplate
 */

ajaxClass=function(parameters){
	//private variables
	this._parameters = {
			type: "POST",
			url: "",
			data: "",
			async: false
	};
	//retrieve parameter from caller
	if (parameters.type) this._parameters.type = parameters.type;
	if (parameters.url) this._parameters.url = parameters.url;
	if (parameters.data) this._parameters.data = parameters.data;
	if (parameters.async) this._parameters.async = parameters.async;
	if (parameters.success) this._parameters.success = parameters.success;
}

ajaxClass.prototype=
{
	//execute function, this calls post or get
	exec : function(){
		oThis = this;
		$.ajax({
			type: this._parameters.type,
			url: this._parameters.url,
			data: this._parameters.data,
			async: this._parameters.async,
			success: function(message){
				oThis._message = message;
				if (typeof(oThis._parameters.success) != "undefined")
					oThis._parameters.success(message);
			}
		});
		return oThis._message;
	}
}