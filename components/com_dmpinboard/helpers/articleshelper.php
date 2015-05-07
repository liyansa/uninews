<?php

	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');

class PBHArticles {
	
	public static function getArticles($arg) {
		
		if (file_exists(JPATH_COMPONENT.DS.'helpers'.DS.'source_'.$arg['source'].'.php')) {
			require_once(JPATH_COMPONENT.DS.'helpers'.DS.'source_'.$arg['source'].'.php');
			
			$helperName = 'PBHSource' . $arg['source'];
			$myHelper = new $helperName();
			$articles = array();
			$myHelper->arg = $arg;
			$itemList = $myHelper->loadFromDB();
			foreach ($itemList as $item) {
				$articles[] = self::itemToArticle($myHelper,$item);
			}
			return $articles;
			
		} else {
			return '';
		}
	}
	
	public static function itemToArticle(&$myHelper, $item) {
		
		$article = new stdClass();
		$article->id = $myHelper->getItemId($item);
		$article->link = $myHelper->getItemUrl($item);
		$article->title = $myHelper->getItemTitle($item);
		$article->image = $myHelper->getItemImage($item);
		$article->intro = $myHelper->getItemIntro($item);
		$article->share = $myHelper->getShareInfo($item);
		return $article;
	}
	
	public static function getPreview($itemId, $arg) {
		
		if (file_exists(JPATH_COMPONENT.DS.'helpers'.DS.'source_'.$arg['source'].'.php')) {
			require_once(JPATH_COMPONENT.DS.'helpers'.DS.'source_'.$arg['source'].'.php');
			
			$helperName = 'PBHSource' . $arg['source'];
			$myHelper = new $helperName();
			$articles = array();
			$myHelper->arg = $arg;
			$myItem = $myHelper->loadFromDB($itemId);
			return self::itemToPreview($myHelper,$myItem);
			
		} else {
			return '';
		}
	}
	
	public static function itemToPreview(&$myHelper,$item) {
		
		$preview = new stdClass();
		$preview->image = $myHelper->getPreviewImage($item);
		$preview->title = $myHelper->getPreviewTitle($item);
		$preview->content = $myHelper->getPreviewContent($item);
		$preview->readmore = $myHelper->getPreviewReadmore($item);
		$preview->share = $myHelper->getShareInfo($item);
		return $preview;
	}
	
}

?>