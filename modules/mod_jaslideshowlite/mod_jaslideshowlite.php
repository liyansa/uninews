<?php
/**
 * ------------------------------------------------------------------------
 * JA Slideshow Lite Module
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__) .  '/helpers/'  . 'helper.php');
require_once (dirname(__FILE__) . '/helpers/' . 'jaimage.php');

// load mootools
JHTML::_('behavior.framework', true);

$t3Exists = false;
if (!class_exists('T3Common')) {
    if (file_exists(JPATH_SITE . '/plugins/system/jat3/jat3/core/common.php')) {
        require_once (JPATH_SITE . '/plugins/system/jat3/jat3/core/common.php');
        $t3Exists = true;    
	}
} else {
    $t3Exists = true;
}

$jdoc = JFactory::getDocument();
$mainframe = JFactory::getApplication();
$helper = ModJASlideshowLite::getInstance();

$type = $params->get('type', 'fade');
$autoPlay = $params->get('autoplay', 1);
$showNavigation = $params->get('navigation', 0);
$showThumbnail = $params->get('thumbnail', 0);
$direction = 'ltr';

//compactible
$animationType = $type;

//refine parameter
//at least 1 pixel image
if(($params->get('mainWidth') == 'auto' && $params->get('mainHeight') == 'auto')){
	$params->set('main_mode', 'none');
} else {
	$params->set('main_mode', 'crop');
	$params->set('mainWidth', max(1, intval($params->get('mainWidth'))));
	$params->set('mainHeight', max(1, intval($params->get('mainHeight'))));
}

$params->set('thumbWidth', max(1, intval($params->get('thumbWidth'))));
$params->set('thumbHeight', max(1, intval($params->get('thumbHeight'))));
$params->set('main_mode', ($params->get('mainWidth') == 'auto' && $params->get('mainHeight') == 'auto') ? 'none' : 'crop');
$params->set('source-articles-images-main_mode', $params->get('main_mode', 'crop'));
if(substr($params->get('folder'), 0, 1) == '/'){
	$params->set('folder', substr($params->get('folder'), 1));
}

if ($t3Exists) {
    $direction = T3Common::isRTL() ? 'rtl' : 'ltr';
} else {
    $direction = $jdoc->getDirection();
}

if (!defined('_MODE_JASLIDESHOWLITE_ASSETS_')) {
    define('_MODE_JASLIDESHOWLITE_ASSETS_', 1);
	
	JHTML::stylesheet('modules/' . $module->module . '/assets/animate.css');
	if (is_file(JPATH_SITE .  '/templates/'. $mainframe->getTemplate() .  '/css/'  . $module->module . '.css')) {
        JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/css/' . $module->module . '.css');
	} else {
		JHTML::stylesheet('modules/' . $module->module . '/assets/mod_jaslideshowlite.css');
	}
	
	if($direction == 'rtl'){
		JHTML::stylesheet('modules/' . $module->module . '/assets/mod_jaslideshowlite_rtl.css');
	}
	JHtml::_('behavior.framework', true);
	JHTML::script('modules/' . $module->module . '/assets/script.js');
}

if (!defined('_MODE_JASLIDESHOWLITE_ASSETS_' . strtoupper($type))) {
	define('_MODE_JASLIDESHOWLITE_ASSETS_' . strtoupper($type), 1);
	
	// add extra css for selected type
	$fname = $module->module . '-' . $type . '.css';
	if (is_file(JPATH_SITE .'/templates/' . $mainframe->getTemplate() .  '/css/' . $fname)) {
        JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/css/' . $fname);
	} else if (is_file(JPATH_SITE .  '/modules/' . $module->module . '/assets/' . $fname)) {
		JHTML::stylesheet('modules/' . $module->module . '/assets/' . $fname);
	}
}

//get the image list
$list = $helper->callMethod('getListImages', $params);

if( !empty($list) ) :
	$images		   = $list['mainImageArray'];
	$thumbArray	   = $list['thumbArray'];
	$captionsArray = $list['captionsArray'];
	$urls		   = $list['urls'];
	$targets 	   = $list['targets'];
	$classes	   = $list['classes'];
	
	//remove all unwanted images
	$timages = array();
	$tthumbArray = array();
	$tcaptionsArray = array();
	$turls = array();
	$ttargets = array();
	
	for($i = 0, $il = count($images); $i < $il; $i++){
		$iname = basename($images[$i]);
		if(strpos($iname, '-first') === false && strpos($iname, '-second') === false){
			$timages[] = $images[$i];
			$tthumbArray[] = $thumbArray[$i];
			$tcaptionsArray[] = $captionsArray[$i];
			$turls[] = $urls[$i];
			$ttargets[] = $targets[$i];
		} 
	}
	
	if($type != 'custom'){			
		$images = $timages;
		$thumbArray = $tthumbArray;
		$captionsArray = $tcaptionsArray;
	}
	
	$urls = $turls;
	$targets = $ttargets;
	
	// get layout
	$layout = JModuleHelper::getLayoutPath( $module->module, $type );
	
	//if file not exist or layout is default.php in module folder, we must recheck again. This is getLayoutPath issue
	if (!file_exists($layout) || basename($layout) == 'default.php') {
		// use default layout
		$layout = JModuleHelper::getLayoutPath( $module->module );
	}
	
	require( $layout );
?>
<script type="text/javascript">
	window.addEvent('domready', function(){
		window.jassliteInst = window.jassliteInst || [];
		window.jassliteInst.push(new JASliderCSS('ja-ss-<?php echo $module->id;?>', {
			interval: 5000,
			duration: <?php echo ($type != 'custom' ? '1000' : '2200'); ?>,
			
			repeat: true,
			autoplay: <?php echo $autoPlay;?>,
			
			navigation: <?php echo $showNavigation;?>,
			thumbnail: <?php echo $showThumbnail;?>,
			
			urls:['<?php echo implode('\',\'', $urls); ?>'],
			targets:['<?php echo implode('\',\'', $targets); ?>']
		}));
	});
</script>
<!-- Fix animation in IE -->
<!--[if IE]>
	<script src="<?php echo JURI::base(true).'/modules/'.$module->module ?>/assets/iefix.js" type="text/javascript"></script>
<![endif]-->

<?php
	unset($list);
	unset($images);
	unset($thumbArray);
	unset($captionsArray);
	unset($urls);
	unset($targets);
	
endif;
?>