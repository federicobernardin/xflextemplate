<?php
$GLOBALS['configuration'] = array(
  'subElement' => array(
    'main' => array('titleLabel', 'typeLabel', 'xtypeLabel', 'paletteLabel'),
    'type' => array('inputType', 'textType', 'radioType', 'checkType', 'groupType', 'cObjectType'),
    'xtype' => array('noneXtype', 'textXtype', 'imageXtype', 'multimediaXtype', 'cObjectXtype'),
    'inputType' => array('link', 'max', 'size', 'default', 'is_in', 'eval', 'checkbox', 'maxval', 'minval'),
    'textType' => array('cols', 'rows', 'default', 'wrap', 'defaultExtras'),
    'checkType' => array('items','cols', 'default', 'itemprocfunc'),
    'radioType' => array('items', 'default', 'itemprocfunc'),
    'groupType' => array('internal_type', 'allowed', 'disallowed', 'MM', 'max_size', 'show_thumbs', 'maxitems', 'minitems', 'size', 'autoSizeMax', 'multiple','disable_controls'),
    'cObjectType' => array(),
  ),

    'subElementHelpCode' => array(
        'default' => array('title' => 1, 'groups' => 2, 'description' => 3, 'typoscript' => 4, 'html' => 5, 'elements' => 6),
        'main' => array('titleLabel' => 20, 'typeLabel' => 21, 'xtypeLabel' => 22, 'paletteLabel' => 23),
        'inputType' => array('link' => 38, 'max' => 30, 'size' => 31, 'default' => 32, 'is_in' => 33, 'eval' => 34, 'checkbox' => 35, 'maxval' => 36, 'minval' => 37),
        'textType' => array('cols' => 40, 'rows' => 41, 'default' => 42, 'wrap' => 43, 'defaultExtras' => 44),
        'checkType' => array('items' => 50,'cols' => 51, 'default' => 52, 'itemprocfunc' => 53),
        'radioType' => array('items' => 60, 'default' => 61, 'itemprocfunc' => 62),
        'groupType' => array('internal_type' => 70, 'allowed' => 71, 'disallowed' => 72, 'MM' => 73, 'max_size' => 74, 'show_thumbs' => 75, 'maxitems' => 76, 'minitems' => 77, 'size' => 78, 'autoSizeMax' => 79, 'multiple' => 80, 'disable_controls' => 81)
    ),
);
?>