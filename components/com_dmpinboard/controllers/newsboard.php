<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	jimport('joomla.application.component.controller');

class PBControllerNewsboard extends DMController {
	
	function display($cachable = false, $urlparams = array()) {
		
		parent::display();
	}
	
	function getItems() {
		
		$source = JRequest::getVar('source','');
		$Itemid = JRequest::getVar('Itemid',-1);
		$arg = PBHHtml::getViewParams($source);
		$articles = PBHArticles::getArticles($arg);
		
		if (count($articles) > 0) {
			PBHHtml::reqOtput('OK','JSON',json_encode($articles));
		} else {
			PBHHtml::reqOtput('WARNING','JS','DMPinboard.removeButton();DMPinboard.loading("stop");DMPinboard.contentFinished=true;');
		}
		exit;
	}
	
	function getItemPreview() {
		
		$source = JRequest::getVar('source','');
		$Itemid = JRequest::getVar('Itemid',-1);
		$articleId = JRequest::getVar('articleId',-1);

		$arg = PBHHtml::getViewParams($source);
		$preview = PBHArticles::getPreview($articleId,$arg);
		if (!empty($preview)) {
			$output = PBHHtml::getPreviewHtml($preview,$arg);
			PBHHtml::reqOtput('OK','HTML',$output);
		} else {
			PBHHtml::reqOtput('ERROR','','','Error retrieving articles info');
		}
		exit;
	}
	
}

?>