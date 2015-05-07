<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');

?>
<div class="pbpopup_cont">
	<?php
		if (!empty($preview->title)) {
			echo '<div class="pbpopup_title">'.$preview->title.'</div>';
		}
		if (!empty($preview->image)) {
			echo '<div class="pbpopup_image"><img src="'.JUri::base().$preview->image.'" /></div>';
		}
		if (!empty($preview->content)) {
			echo '<div class="pbpopup_content">'.$preview->content.'</div>';
		}
		if (!empty($preview->readmore)) {
			echo '<div class="pbpopup_readmore">'.$preview->readmore.'</div>';
		}
	?>
</div>

<?php if ($arg['socialstuff'] == 'yes' || $arg['socialstuff'] == 'onlypopup') { ?>
<div class="pbpopup_social">
   <div class="addthis_toolbox addthis_counter_style">
    	<a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
    	<a class="addthis_button_tweet" tw:count="vertical"></a>
    	<a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
    	<a class="addthis_counter"></a>
    </div>
    
    <script type="text/javascript">
    	var addthis_share = {
			<?php
				if (!empty($preview->share['url']) && !empty($preview->share['title'])) {
					echo 'url : "'.$preview->share['url'].'",';
					echo 'title : "'.$preview->share['title'].'"';
				}
			?>
		}
    	var script = 'http://s7.addthis.com/js/250/addthis_widget.js#domready=1';
		if (window.addthis) {
		    window.addthis = null;
		    window._adr = null;
		    window._atc = null;
		    window._atd = null;
		    window._ate = null;
		    window._atr = null;
		    window._atw = null;
		}
		jQuery.getScript(script);
	</script>
</div>
<?php } ?>