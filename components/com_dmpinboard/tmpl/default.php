<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	
	$optMasonry = '';
	if ($arg['animatelist'] == 'yes') {
		$optMasonry .= ', isAnimated: !Modernizr.csstransitions';
	}
	
	$optDMPin = '';
	if ($arg['show_morebutton'] == 'yes') {
		$optDMPin .= 'DMPinboard.enableButton = true;';
	}
	if ($arg['previewpopup'] == 'enabled') {
		$optDMPin .= 'DMPinboard.enablePopup = true;';
	}
	
?>

<div id="pbgrid_cont" class="<?php echo $arg['preset_style']; ?>"></div>
<div id="pbgrid_notice"></div>

<script>
	
	jDM(document).ready(function(){
		
		DMPinboard.root = "<?php echo JUri::base(); ?>";
		DMPinboard.comurl = "<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid')); ?>";
		DMPinboard.source = "<?php echo $arg['source']; ?>";
		DMPinboard.itemId = <?php echo JRequest::getInt('Itemid'); ?>;
		DMPinboard.presetStyle = "<?php echo $arg['preset_style']; ?>";
		DMPinboard.container = jDM('#pbgrid_cont');
		<?php echo $optDMPin; ?>
		DMPinboard.lang.LOADMORE = "<?php echo JText::_('COM_DMPINBOARD_FRONTEND_LOADMORE'); ?>";
		DMPinboard.lang.LOADING = "<?php echo JText::_('COM_DMPINBOARD_FRONTEND_LOADING'); ?>";
		
		DMPinboard.container.masonry({
			itemSelector: '.pbitem_cont',
			isFitWidth: true<?php echo $optMasonry; ?>
		});
		
		<?php if ($arg['infinitescroll'] == 'enabled') { ?>
		jDM(window).scroll(function(){
		    DMPinboard.scrollCheck();
		});
		<?php } ?>
		
		DMPinboard.getItems();
		
	});
	
</script>