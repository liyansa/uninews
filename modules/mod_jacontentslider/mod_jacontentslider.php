<?php
/**
 * ------------------------------------------------------------------------
 * JA Contenslider Module
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted accessd');
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
$mainframe = JFactory::getApplication();
JHTML::_('behavior.framework', true);
// Include the syndicate functions only once
require_once (dirname(__FILE__) . DS . 'helper.php');
require_once (dirname(__FILE__) . DS . 'jaimage.php');


// using setting params
$source 		= 	$params->get( 'source', 'content' );
$xheight 		= 	$params->get( 'xheight', 400 );
$xwidth 		= 	$params->get( 'xwidth', 400 );
$iheight 		= 	$params->get( 'iheight', 80 );
$iwidth 		= 	$params->get( 'iwidth', 80 );
$numElem 		= 	$params->get( 'numElem', 4 );

$showtitle	 	= 	$params->get( 'showtitle', 0 );
$showimages 	= 	$params->get( 'showimages', 0 );
$showreadmore 	= 	$params->get( 'showreadmore', 0 );
$showintrotext 	= 	$params->get( 'showintrotext', 0 );
$link_titles 	= 	$params->get( 'link_titles', 0 );
$numChar 		= 	$params->get( 'numchar', 0 );

$auto 			= 	$params->get( 'auto', 0 );
$direction 		=	$params->get( 'direction', 'left' );
$delaytime 		= 	$params->get( 'delaytime', 5000 );
$animationtime 	= 	$params->get( 'animationtime', 1000 );
$maxitems 		=    $params->get( 'maxitems', 10 );
$numberjump 	= 	1;
$useajax 		= 	0;
$mode 			= 	$params->get( 'mode','horizontal' );
$showTab 		= 	(int) $params->get('showTab', 1);
$text_heading	=	$params->get( 'text_heading', '');

switch ($source) {
    case 'k2':
        $catid = $params->get('k2catsid', 0);
        break;
    case 'content':
    default:
        $catid = $params->get('catid', 0);
        break;
}
if (!is_array($catid)) {
    $catid = (array) $catid;
}

if ($params->get('mode') == 'vertical') {
    $mode = 'vertical';
}
$source = $params->get('source', 'content');
if($source=='folder'){
	$params->set('showtitle', 0);
}
$contents = modJacontentsliderHelper::getListItems( $catid, $params,$source );

if ($numElem==0) {
	return '';
}

/**
 * id, title, decscription,link,
 */
$total = count($contents);
if (!defined('JACONTENTSLIDER2_ASSETS')) {
    define('JACONTENTSLIDER2_ASSETS', 1);
    /* load javascript. */
    modJacontentsliderHelper::javascript($params);
    /* load css. */
    modJacontentsliderHelper::css($params);
}
require (JModuleHelper::getLayoutPath('mod_jacontentslider'));

?>