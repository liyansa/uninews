<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	jimport('joomla.application.component.view');
	
class PBViewJcontent extends DMView {
	
	function display($tpl = null) {
		
		$arg = PBHHtml::getViewParams('jcontent');
		PBHHtml::outputView($arg);
	}
	
}
	
?>