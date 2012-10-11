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
 * element object class for managing single object (content element)
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @version 2.0
 * @package TYPO3
 * @subpackage xfletemplate
 */

element = function(initializeParameters){
   var id;
  var name;
  var type;
  var xtype;
  var renderType;
  var palettes;
  this.configuration = {
    portletClass: 'portlet',
    portletHeaderClass: 'portlet-header',
    columnClass: 'column',
    iconDeleteClass: 'ui-icon-delete',
    uiIconClass: 'ui-icon',
    elementPreId: 'element',
    subElementPreId: 'subelement',
    titleClass: 'xftTitle',
    typeClass: 'typeHandler',
    palettesClass: 'xft-palette',
    language: {
      dialogYes: 'Yes',
      dialogCancel: 'Cancel'
    },
    defaultElementName: 'inputType',
    create: 1,
    open: 1
  };

  //merge data from caller with private one
  $.extend(this.configuration, initializeParameters);
 }

 //method definition for element class
 element.prototype=
 {
   //add function for creation of element
   add: function(id){
    //define class in function variable
    var oThis = this;
    this.id = id;
    //(the object is not already present in HTML code) the code will be inserted in HTML code
    if (oThis.configuration.create) {
      palette = Array();
      //create palette array
      $(' .' + oThis.configuration.titleClass).each(function(){
        if ($(this).val())
          palette.push($(this).val() + '_' + id);
      });
      //palette is built join single item with pipe
      palette = palette.join('|');
      parameters = {
        url: ajaxUrl,
        data: 'ajax=1&action=newElement&subElement=' + oThis.configuration.defaultElementName + '&elementID=' + id + '&palette=' + palette
      }
      ajaxObj = new ajaxClass(parameters);
      ret = ajaxObj.exec();
      //add HTML code to column
      $('.' + oThis.configuration.columnClass).append(ret);
    }
    //add all handlers to element
    this.addTitleHandler();
    this.addTypeHandler();
        this.addSortProperties();
        this.addDeleteHandler();
  },

  /**
   * This function add Title update event to system
   * When user change focus from title title input change all palette from other element
   * When user press key on title input, title of element change
   */
  addTitleHandler: function(){
    var oThis = this;
    $('#' + oThis.configuration.elementPreId + '_'+oThis.id+'.' + oThis.configuration.portletClass + ' .' + oThis.configuration.titleClass).bind('keyup',function(){
      dataArray = $(this).attr('id').split('_');
      $('#' + oThis.configuration.elementPreId + '_'+oThis.id+'.' + oThis.configuration.portletClass + ' .title').html(htmlentities($(this).val()));
    });
    $('#' + oThis.configuration.elementPreId + '_'+oThis.id+'.' + oThis.configuration.portletClass + ' .' + oThis.configuration.titleClass).bind('blur',function(){
      oThis.changeAllPaletteByChangerID($(this).val());
    });
  },

  /**
   * This function binds change on type select of element
   * When user changes type of element, application calls (via ajax) the modificatio of subelement
   */
  addTypeHandler: function(){
    var oThis = this;
    $('#' + oThis.configuration.elementPreId + '_'+oThis.id+'.' + oThis.configuration.portletClass + ' .' + oThis.configuration.typeClass).bind('change',function(){
      var parameters = {
        url: ajaxUrl,
        data: 'ajax=1&action=changeSubElement&subElementType=' + $(this).val() + '&elementID=' + oThis.id
      }
      var ajaxObj = new ajaxClass(parameters);
      var ret = ajaxObj.exec();
      //set subelemet code
      $('#' + oThis.configuration.subElementPreId + '_' + oThis.id).html(ret);

      setHelperHandler();

    });
  },

  /**
   * This function changes title in palette of all other element with title of this element
   * @param string title of element
   */
  changeAllPaletteByChangerID: function(title){
    var oThis = this;
    $('.' + oThis.configuration.palettesClass).each(function(){
      dataArray = $(this).attr('id').split('_');
      elementId = dataArray[1];
      selectedItem = this.selectedIndex;
      if (elementId!=oThis.id){
        found = 0;
        $('option', $(this)).each(function(){
          if ($(this).val() == ('element'+oThis.id)){
            $(this).html(title);
            found = 1;
          }
        })
        if(!found){
          $(this).addOption('element_' + oThis.id, title);
        }
        this.selectedIndex = selectedItem;
      }
    })
  },

  /**
   * This function removes title from all other palettes
   */
  removePaletteOptionByElementDelete: function(){
    var oThis = this;
    $('.' + oThis.configuration.palettesClass).each(function(){
      selectedIndex = this.selectedIndex;
      selectedItem = this.options[this.selectedIndex].value;
      actualElementID = 'element_' + oThis.id;
      $(this).removeOption(actualElementID);
      if(selectedItem == actualElementID)
        this.selectedIndex = 0
      else
        this.selectedIndex = selectedIndex;
    });
  },

  /**
   * This function add sorting feature to the element
   */
  addSortProperties: function(){
    var oThis = this;
    //defining icon for close and open element
    if (oThis.configuration.open == 1) {
      className = 'ui-icon-minusthick';
    }
    else {
      className = 'ui-icon-plusthick';
      $('#' + oThis.configuration.elementPreId + '_' + oThis.id + '.portlet').find('.portlet-content').toggle();
    }

    //add sort draggable function
    $('#' + oThis.configuration.elementPreId + '_' + oThis.id + '.portlet').addClass('ui-widget ui-widget-content ui-helper-clearfix ui-corner-all')
    .find('.portlet-header')
      .addClass('ui-widget-header ui-corner-all')
      .prepend('<span class="ui-icon ' + className + '"></span>')
      .end()
    .find('.portlet-content');

    //enable open and close event
    $('#' + oThis.configuration.elementPreId + '_' + oThis.id + ' .' + oThis.configuration.portletHeaderClass + ' .' + oThis.configuration.uiIconClass).click(function() {
      $(this).toggleClass('ui-icon-minusthick');
      $(this).toggleClass('ui-icon-plusthick');
      $(this).parents('.portlet:first').find('.portlet-content').toggle();
      dataArray = $(this).parent().parent().attr('id').split('_');
      elementId = dataArray[1];
    });
  },

  /**
   * This function adds delete event, opens dialog for confirmation of deleting element
   */
  addDeleteHandler: function(){
    var oThis = this;
    $('#' + oThis.configuration.elementPreId + '_'+ this.id +' .portlet-header .ui-icon-delete').bind('click',function() {
      //open dialog
         /*   Ext.MessageBox.show({
               title:'Save Changes?',
               msg: 'You are closing a tab that has unsaved changes. <br />Would you like to save your changes?',
               buttons: Ext.MessageBox.YESNOCANCEL,
               //fn: showResult,
               animEl: 'mb4',
               icon: Ext.MessageBox.QUESTION
           });*/
      $('#dialog').dialog({
        bgiframe: true,
        resizable: false,
        height:140,
        modal: true,
        overlay: {
          backgroundColor: '#000',
          opacity: 0.5
        },
        buttons: {
          'dialogYes': function() {
            oThis.removePaletteOptionByElementDelete();
            $('#' + oThis.configuration.elementPreId + '_' + oThis.id).remove();
            $(this).dialog('close');
          },
          'dialogCancel': function() {
            $(this).dialog('close');
          }
        }
      });
      //update dialog button with label from configuration array
      $('.ui-dialog-buttonpane button').each(function(){
        $(this).html(oThis.configuration.language[$(this).html()]);
      });
    });
  }
 }
